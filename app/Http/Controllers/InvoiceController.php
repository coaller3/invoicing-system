<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProject;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $invoices = Invoice::with(['client', 'invoiceProject'])->orderByDesc('invoice_number')->get();

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

        return view('invoice.add_invoice', [
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
        $invoice = Invoice::with(['client', 'invoiceProject.project'])->where('id', $invoice->id)->first();

        return view('invoice.view_invoice', [
            'datas' => $invoice,
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

    public function delete_project(Request $request)
    {
        //
        return DB::transaction(function() use ($request) {
            $validatedData = $request->validate([
                'invoice_project_id' => 'required|integer',
            ]);

            $invoice_project = InvoiceProject::where('id', $request->invoice_project_id)->first();

            $invoice = Invoice::findOrFail($invoice_project->invoice_id);

            if ($invoice_project) {
                $invoice_project->delete();
            }
            else {
                return response()->json(['status' => 'error', 'message' => 'Project in Invoice not found'], 404);
            }

            $project = Project::findOrFail($invoice_project->project_id);

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
            'project_id' => $project->project_id,
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
