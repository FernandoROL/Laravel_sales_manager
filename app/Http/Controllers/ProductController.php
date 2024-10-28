<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestProduct;
use App\Models\Components;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ProductController extends Controller 
{
    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index(Request $request) {
        $search = $request->search;
        $findProduct = $this->product->getProductSearchIndex(search: $search ?? '');

        return view('pages/products/paging', compact('findProduct'));
    }

    public function delete(Request $request) {
        $id = $request->id;
        $SearchRegistry = Product::find($id);
        $SearchRegistry->delete();
        return response()->json(['success' => true]);    
    }
    
    public function registerProduct(FormRequestProduct $request) {
        if ($request->method() == 'POST') {
            $data = $request->all();
            $components = new Components();
            $data['price'] = $components->formatacaoMascaraDinheiroDecimal($data['price']);
            Product::create($data);
            
            // Toastr::success('Product successfully added', 'Success!');

            return redirect()->route('products.index');
        }

        return view('pages.products.create');
    }

    public function updateProduct(FormRequestProduct $request, $id) {
        if ($request->method() == 'PUT') {
            $data = $request->all();
            $components = new Components();
            $data['price'] = $components->formatacaoMascaraDinheiroDecimal($data['price']);

            $searchRegistry = Product::find($id);
            $searchRegistry->update($data);

            return redirect()->route('products.index');
        }

        $findProduct = Product::where('id', '=', $id)->first();

        return view('pages.products.update', compact('findProduct'));
    }
}