<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestSalle;
use App\Models\Client;
use App\Models\Product;
use App\Models\Salles;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function __construct(Salles $salle)
    {
        $this->salle = $salle;
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $findSalle = $this->salle->getSalleSearchIndex(search: $search ?? '');

        return view('pages.salles.paging', compact('findSalle'));
    }
    public function registerSalle(FormRequestSalle $request)
    {
        $findSalleNumber = Salles::max('salleNumber') + 1;
        $findProduct = Product::all();
        $findClient = Client::all();
        if ($request->method() == 'POST') {
            $data = $request->all();
            $data['salleNumber'] = $findSalleNumber;
            Salles::create($data);

            Toastr::success('Sale completed succefully');
            return redirect()->route(route: 'salles.index');
        }

        return view('pages.salles.create', compact('findSalleNumber', 'findProduct', 'findClient'));
    }
}
