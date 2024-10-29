<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestSalle;
use App\Mail\EmailSaleRecipt;
use App\Models\Client;
use App\Models\Product;
use App\Models\Salles;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function SendEmailRecipt($id)
    {
        $searchSalle = Salles::where('id', '=', $id)->first();
        $productName = $searchSalle->product->name;
        $clientMail = $searchSalle->client->email;
        $clientName = $searchSalle->client->name;

        $sendMailData = [
            'productName' => $productName,
            'clientName' => $clientName,
        ];

        Mail::to($clientMail)->send(new EmailSaleRecipt($sendMailData));

        Toastr::success('Email sent successfully!');
        return redirect()->route(route: 'salles.index');
    }
}
