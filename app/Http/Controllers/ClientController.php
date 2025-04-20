<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Traits\ClientTrait;

class ClientController extends Controller
{
    use ClientTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $datas = $this->client_list();

        return view('client.listing', ['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('client.add_client');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'phone' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'address' => 'required|text',
        ]);

        $client_data = [
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
            'status' => 'ACTIVE',
        ];

        Client::create($client_data);

        return response()->json(['status'=>"success"], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
        $datas = Client::where('id', $client->id)->first();

        return view('client.edit_client', ['datas' => $datas]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,'.$client->id,
            'phone' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'address' => 'required|text',
            'status' => 'required|string|max:255',
        ]);

        $client_data = [
            'name'=> $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'company'=> $request->company,
            'address'=> $request->address,
            'status'=> $request->status,
        ];

        $client->fill($client_data);
        $client->save();

        return response()->json(['status'=>"success"], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
        $client->delete();
        return response()->json(['status'=>"success"], 200);
    }

    public function index_api()
    {
        //
        $datas = $this->client_list();

        return response()->json(['status'=>"success", 'datas'=>$datas], 200);
    }
}
