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
            'image' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        HeroSlide::create($validated);

        return redirect()->back()->with('success', 'Hero slide created successfully.');
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $heroSlide->update($validated);

        return redirect()->back()->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $heroSlide->delete();

        return redirect()->back()->with('success', 'Hero slide deleted successfully.');
    }
}
