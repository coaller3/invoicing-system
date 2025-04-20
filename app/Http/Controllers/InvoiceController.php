<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProject;
use App\Models\Project;
use App\Models\Client;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Traits\ClientTrait;

class InvoiceController extends Controller
{
    use ClientTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $invoices = $this->invoice_list();

        return view('invoice.listing', [
            'datas' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clients = $this->client_list();

        return view('invoice.create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return DB::transaction(function() use ($request) {
            $validatedData = $request->validate([
                'client_id' => 'required|integer',
                'project_id' => 'required|array',
            ]);

            $invoice_num = $this->invoice_num_generate();

            $invoice_data = [
                'client_id' => $request->client_id,
                'invoice_number' => $invoice_num,
                'total' => 0,
                'paid_date' => null,
                'status' => 'PENDING',
            ];

            $invoice = Invoice::create($invoice_data);

            $total = 0;

            foreach ($request->project_id as $project_id) {

                $project = Project::findOrFail($project_id);

                $this->invoice_project_create($project, $invoice);

                $total += $project->rate * $project->duration;

            }

            $this->update_total($total, $invoice);

            return response()->json(['status' => 'success'], 200);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
        $invoice = Invoice::with(['client'])->where('id', $invoice->id)->first();

        $invoice_projects = InvoiceProject::with(['project'])->where('invoice_id', $invoice->id)->get();

        $invoice_projects_ids = (clone $invoice_projects)->pluck('project_id')->toArray();

        $projects = Project::where('client_id', $invoice->client_id)->whereNotIn('id', $invoice_projects_ids)->orderByDesc('created_at')->get();

        return view('invoice.edit', [
            'datas' => $invoice,
            'projects' => $projects,
            'invoice_projects' => $invoice_projects,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
        $validatedData = $request->validate([
            'paid_date' => 'required|date',
        ]);

        $invoice->fill([
            'paid_date' => date('Y-m-d', strtotime($request->paid_date)),
            'status' => 'PAID',
        ]);
        $invoice->save();

        return response()->json(['status' => 'success'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
        return DB::transaction(function() use ($invoice) {
            $invoice->delete();
            $invoice->invoiceProject()->delete();

            return response()->json(['status' => 'success'], 200);
        });
    }

    public function index_api()
    {
        //
        $invoices = $this->invoice_list();

        return response()->json(['status' => 'success', 'datas' => $invoices], 200);
    }

    public function invoicePDF(Invoice $invoice)
    {
        //
        $pdf = $this->invoice_pdf_generate($invoice);

        return $pdf->stream($invoice->invoice_number.'.pdf');

    }

    public function email_invoice(Invoice $invoice)
    {
        $pdf = $this->invoice_pdf_generate($invoice);
        $pdfContent = $pdf->output();

        $clientName = $invoice->client?->name ?? '';
        $invoiceNumber = $invoice->invoice_number;
        $email = $invoice->client?->email ?? '';

        if ($email == '') {
            return response()->json(['status' => 'error', 'message' => 'Client email not found'], 404);
        }

        $pdfPath = 'Invoice/' . $invoiceNumber . '.pdf';
        Storage::put($pdfPath, $pdfContent);

        Mail::to("damiensim96@gmail.com")->queue(
        // Mail::to($email)->queue(
            new InvoiceMail($clientName, $invoiceNumber, $pdfPath));

        return response()->json(['status' => 'sent']);
    }

    private function invoice_list()
    {
        if(Auth::user()->role == 'ADMIN') {
            $invoices = Invoice::with(['client', 'invoiceProject'])->orderByDesc('invoice_number')->get();
        }
        else {
            $client_ids = Client::where('user_id', Auth::user()->id)->pluck('id')->toArray();

            $invoices = Invoice::with(['client', 'invoiceProject'])->whereIn('client_id', $client_ids)->orderByDesc('invoice_number')->get();
        }

        return $invoices;
    }

    private function invoice_pdf_generate($invoice)
    {
        $invoice = Invoice::with(['client'])->where('id', $invoice->id)->first();

        $invoice_projects = InvoiceProject::with(['project'])->where('invoice_id', $invoice->id)->get();

        $pdf = PDF::loadView('invoice.invoice_pdf', [
            'invoice' => $invoice,
            'invoice_projects' => $invoice_projects,
        ])->setPaper('a4', 'potrait');

        return $pdf;
    }

    public function get_project(Client $client)
    {
        //
        $projects = Project::where('client_id', $client->id)->orderByDesc('created_at')->get();

        return response()->json([
            'status' => 'success',
            'projects' => $projects
        ], 200);
    }

    public function add_project(Request $request, Invoice $invoice)
    {
        //
        return DB::transaction(function() use ($request, $invoice) {
            $validatedData = $request->validate([
                'project_id' => 'required|integer',
            ]);

            $project = Project::findOrFail($request->project_id);

            $this->invoice_project_create($project, $invoice);

            $total = $invoice->total;

            $total += $project->rate * $project->duration;

            $this->update_total($total, $invoice);

            return response()->json(['status' => 'success'], 200);
        });

    }

    public function delete_project(InvoiceProject $invoiceProject)
    {
        //
        return DB::transaction(function() use ($invoiceProject) {

            if ($invoiceProject) {
                $invoiceProject->delete();
            }
            else {
                return response()->json(['status' => 'error', 'message' => 'Project in Invoice not found'], 404);
            }

            $invoice = Invoice::findOrFail($invoiceProject->invoice_id);

            $project = Project::findOrFail($invoiceProject->project_id);

            $total = $invoice->total;

            $total -= $project->rate * $project->duration;

            $this->update_total($total, $invoice);

            return response()->json(['status' => 'success'], 200);
        });
    }

    private function invoice_num_generate()
    {
        $date = date('Ymd');
        $invoice = Invoice::where('invoice_number', 'like', '%' . $date . '%')->orderByDesc('invoice_number')->first();

        if ($invoice) {
            $invoice_split = explode('-', $invoice->invoice_number);
            $latest_num = (int)end($invoice_split) + 1;
            $invoice_number = 'INV-' . $date . '-' . str_pad($latest_num, 4, '0', STR_PAD_LEFT);
        }
        else {
            $invoice_number = 'INV-' . $date . '-0001';
        }

        return $invoice_number;
    }

    private function invoice_project_create($project, $invoice)
    {
        $invoice_project = [
            'invoice_id' => $invoice->id,
            'project_id' => $project->id,
            'rate' => $project->rate,
            'duration' => $project->duration,
        ];

        InvoiceProject::create($invoice_project);
    }

    private function update_total($total, $invoice)
    {
        $invoice->fill([
            'total' => $total,
        ]);
        $invoice->save();

    }

}
