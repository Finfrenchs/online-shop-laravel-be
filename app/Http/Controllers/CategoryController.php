<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        return view('pages.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'image' => 'required|image|mimes:png,jpg,jpeg',
        ]);

        //$category = Category::create($validated);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/categories', $filename);
            $category->image = $filename;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|max:100',
        'image' => 'image|mimes:png,jpg,jpeg',
    ]);

    $category = Category::findOrFail($id);
    $category->name = $request->name;
    $category->description = $request->description;

    if ($request->hasFile('image')) {
        // Jika sudah ada gambar, hapus gambar lama
        if ($category->image) {
            Storage::delete('public/categories/' . $category->image);
        }
        // Unggah gambar baru
        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/categories', $filename);
        $category->image = $filename;
    }

    $category->save();

    return redirect()->route('category.index')->with('success', 'Category updated successfully');
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.index')->with('success', 'Category deleted successfully');
    }
}
