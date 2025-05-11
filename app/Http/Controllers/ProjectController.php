<?php

namespace App\Http\Controllers;

use App\Exports\ProjectExport;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Traits\ClientTrait;

class ProjectController extends Controller
{
    use ClientTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $datas = $this->project_list();

        if($request->has('excel')){

            $export = new ProjectExport($datas);

            return Excel::download($export, 'Project List.xlsx');

        }

        return view('project.listing', ['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clients = $this->client_list();

        return view('project.create', ['clients' => $clients]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0',
        ]);

        $project_data = [
            'client_id' => $request->client_id,
            'name' => $request->name,
            'description' => $request->description,
            'rate' => $request->rate,
            'duration' => $request->duration,
        ];

        Project::create($project_data);

        return response()->json(['status'=>"success"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        $project = Project::with(['client'])->where('id', $project->id)->first();

        $clients = $this->client_list();

        return view('project.edit', [
            'datas' => $project,
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0',
        ]);

        $project_data = [
            'name' => $request->name,
            'client_id' => $request->client_id,
            'description' => $request->description,
            'rate' => $request->rate,
            'duration' => $request->duration,
        ];

        $project->fill($project_data);
        $project->save();

        return response()->json(['status'=>"success"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        $project->delete();
        return response()->json(['status'=>"success"], 200);
    }

    public function index_api()
    {
        //
        $datas = $this->project_list();

        return response()->json(['status'=>"success", 'data' => $datas], 200);
    }

    private function project_list()
    {
        if(Auth::user()->role == 'ADMIN') {
            $datas = Project::with(['client'])->orderByDesc('created_at')->get();
        }
        else {
            $datas = Project::whereHas('client', function($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->with(['client'])
            ->orderByDesc('created_at')
            ->get();
        }

        return $datas;
    }
}
