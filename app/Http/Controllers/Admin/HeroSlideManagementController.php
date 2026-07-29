<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HeroSlideManagementController extends Controller
{
    public function index(Request $request): Response
    {
        $slides = HeroSlide::orderBy('sort_order')->latest()->paginate(10);

        return Inertia::render('Dashboard/HeroSlides', [
            'slides' => $slides,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $validated['image'] ?? null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('hero-slides', 'public');
            $imagePath = '/storage/'.$path;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }

        if (! $imagePath) {
            return redirect()->back()->withErrors(['image' => 'Please provide an image file or URL.']);
        }

        HeroSlide::create([
            'image' => $imagePath,
            'link' => $validated['link'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->back()->with('success', 'Hero slide created successfully.');
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_url' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $imagePath = $heroSlide->image;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('hero-slides', 'public');
            $imagePath = '/storage/'.$path;
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        } elseif ($request->filled('image')) {
            $imagePath = $request->image;
        }

        $heroSlide->update([
            'image' => $imagePath,
            'link' => $validated['link'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->back()->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->delete();

        return redirect()->back()->with('success', 'Hero slide deleted successfully.');
    }
}
