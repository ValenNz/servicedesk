<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->withCount('tickets')->latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:categories,name',
            'description' => 'required|string|min:10',
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'ticket_count' => 0
        ]);

        return redirect()->route('categories.index')->with('success', 'Category has been created successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255|unique:categories,name,' . $category->id,
            'description' => 'required|string|min:10',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category has been updated successfully!');
    }

    public function destroy(Category $category)
    {
        try {
            $ticketCount = $category->tickets()->count();
            
            if ($ticketCount > 0) {
                return redirect()->route('categories.index')->with('error', 
                    "Cannot delete '{$category->name}'. It still has {$ticketCount} assigned ticket(s). Please reassign or delete those tickets first."
                );
            }

            $category->delete();
            
            return redirect()->route('categories.index')->with('success', 'Category has been permanently deleted.');
            
        } catch (\Exception $e) {
            \Log::error('Category deletion failed: ' . $e->getMessage());
            
            return redirect()->route('categories.index')->with('error', 
                'An unexpected error occurred while deleting the category. Please check the logs.'
            );
        }
    }
}