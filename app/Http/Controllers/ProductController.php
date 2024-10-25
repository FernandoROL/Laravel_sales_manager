<?php

namespace App\Http\Controllers;

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
    
    public function registerProduct(Request $request) {
        if ($request->method() == 'POST') {
            if($request->price !='' && $request->name !='') {
                if (is_numeric($request->price)) {
                    $data = $request->all();
                    Product::create($data);

                    return redirect()->route('products.index');
                } else {
                    echo '<script>alert("Price field is invalid!")</script>';
                }
            } else {
                echo '<script>alert("Fill in all fields!")</script>';
            }
        }

        return view('pages.products.create');
    }

}