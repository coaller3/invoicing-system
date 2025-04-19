<?php

namespace App\Traits;
use App\Models\Client;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


trait ClientTrait
{

    public function client_list()
    {
        if(Auth::user()->role == 'ADMIN') {
            $clients = Client::where('status', 'ACTIVE')->orderBy('name')->get();
        }
        else {
            $clients = Client::where('user_id', Auth::user()->id)->where('status', 'ACTIVE')->orderBy('name')->get();
        }

        return $clients;
    }

}
