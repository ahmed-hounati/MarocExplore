<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $category = new Category;
        $category->name = $request->name;

        $category->save();

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }
}
