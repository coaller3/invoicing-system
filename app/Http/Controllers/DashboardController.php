<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard(Request $request)
    {
        //shipments summary box start
        $year = $request->year != "" ? $request->year : date('Y');
        $month = $request->month != "" ? $request->month : "";

        $client = Client::query();
        $project = Project::query();
        $outstanding = Invoice::where('status', 'PENDING');
        $total = Invoice::where('status', 'PAID');

        if(Auth::user()->role !== 'ADMIN'){
            $client = $client->where('user_id', Auth::user()->id);

            $client_id = (clone $client)->pluck('id')->toArray();

            $project = $project->whereIn('client_id', $client_id);

            $outstanding = $outstanding->whereIn('client_id', $client_id);

        }

        //if year selected
        if ($year != "" && $year != "all") {

            $total = $total->whereYear('paid_date', $year);

            //if month selected
            if ($month != "") {

                $total = $total->whereMonth('paid_date', $month);

            }

        }

        $client = $client->get()->count();
        $project = $project->get()->count();
        $outstanding = $outstanding->get()->count();
        $total = $total->get()->sum('total');

        $year_list = Invoice::selectRaw('YEAR(paid_date) as year')->groupBy('year')->orderByDesc('year')->get()->pluck('year');

        $month_list = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        return view('dashboard', [
            'client' => $client,
            'project' => $project,
            'outstanding' => $outstanding,
            'total' => $total,
            'year' => $year,
            'year_list' => $year_list,
            'month' => $month,
            'month_list' => $month_list,
        ]);
    }

}
