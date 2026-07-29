<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->orderBy('sort_order')->latest()->paginate(10)->withQueryString();

        return Inertia::render('Dashboard/Categories', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $imagePath = '/storage/'.$path;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        $validated['image'] = $imagePath;
        unset($validated['image_url']);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = '/storage/'.$path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        unset($validated['image_url']);

        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category with associated products.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
