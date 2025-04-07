<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création de catégorie
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Créer le slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        // S'assurer que le slug est unique
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Gérer l'image
        $imagePath = null;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // Créer la catégorie
        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'une catégorie
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
            ->where('is_active', true)
            ->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Mettre à jour une catégorie existante
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Vérifier que la catégorie parent n'est pas elle-même
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Une catégorie ne peut pas être sa propre parente.']);
        }

        // Gérer l'image
        $imagePath = $category->image;
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // Mettre à jour la catégorie
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => $request->boolean('is_active', true),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy(Category $category)
    {
        // Vérifier si la catégorie a des produits
        if ($category->products()->count() > 0) {
            return back()->withErrors(['category' => 'Cette catégorie contient des produits et ne peut pas être supprimée.']);
        }

        // Supprimer l'image si elle existe
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        // Supprimer la catégorie
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}