<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestClient;
use App\Models\Components;
use App\Models\Client;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function index(Request $request) {
        $search = $request->search;
        $findClient = $this->client->getClientSearchIndex(search: $search ?? '');

        return view('pages/clients/paging', compact('findClient'));
    }

    public function delete(Request $request) {
        $id = $request->id;
        $SearchRegistry = Client::find($id);
        $SearchRegistry->delete();
        return response()->json(['success' => true]);    
    }
    
    public function registerClient(FormRequestClient $request) {
        if ($request->method() == 'POST') {
            $data = $request->all();
            Client::create($data);

            Toastr::success('Client added successfully', 'Success!');

            return redirect()->route('clients.index');
        }
        return view('pages.clients.create');
    }

    public function updateClient(FormRequestClient $request, $id) {
        if ($request->method() == 'PUT') {
            $data = $request->all();
            $searchRegistry = Client::find(id: $id);
            $searchRegistry->update($data);

            return redirect()->route('clients.index');
        }

        $findClient = Client::where('id', '=', $id)->first();

        return view('pages.clients.update', compact('findClient'));
    }
}
