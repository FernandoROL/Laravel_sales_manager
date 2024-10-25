<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestProduct;
use App\Models\Product;
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
            Product::create($data);

            return redirect()->route('products.index');
        }
        return view('pages.products.create');
    }
}