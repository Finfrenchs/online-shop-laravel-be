<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // $categories = Category::paginate(5);
        $categories = Category::when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })
        ->orderBy('created_at', 'desc')->paginate(5);

        return view('pages.category.index', compact('categories'));
    }
}
