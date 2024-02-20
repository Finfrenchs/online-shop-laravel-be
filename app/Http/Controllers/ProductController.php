<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //get data products
        $products = Product::when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        //sort by created_at desc

        return view('pages.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        //$categories = DB::table('categories')->get();

        return view('pages.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = (int) $request->price;
        $product->stock = (int) $request->stock;
        $product->category_id = $request->category_id;


        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $product->image = $filename;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Product successfully created');
    }
}
