<?php

namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.tags.index');
    }

    /**
     * Return tags data for the index page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Tag::query();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate
        $tags = $query->withCount('products')->paginate(12);

        // Count total and active tags
        $totalTags = Tag::count();
        $activeTags = Tag::where('is_active', true)->count();
        $activeTagsPercent = $totalTags > 0 ? round(($activeTags / $totalTags) * 100) : 0;

        // Get statistics for cards
        $stats = [
            'totalTags' => $totalTags,
            'tagGrowth' => $this->calculateTagGrowth(),
            'tagTypeCount' => Tag::distinct('type')->count('type'),
            'activeTags' => $activeTags,
            'activeTagsPercent' => $activeTagsPercent
        ];

        // Find most used tag
        $mostUsedTag = Tag::withCount('products')
            ->orderBy('products_count', 'desc')
            ->first();

        if ($mostUsedTag) {
            $stats['mostUsedTag'] = $mostUsedTag->name;
            $stats['mostUsedTagCount'] = $mostUsedTag->products_count;
        } else {
            $stats['mostUsedTag'] = null;
            $stats['mostUsedTagCount'] = 0;
        }

        return response()->json([
            'tags' => $tags,
            'stats' => $stats,
            'error' => false
        ]);
    }

    /**
     * Show the form for creating a new tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        $slug = $request->slug;
        if (empty($slug)) {
            $slug = Str::slug($request->name);
            
            // Ensure slug is unique
            $count = 1;
            $originalSlug = $slug;
            while (Tag::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
            'color' => $request->color,
            'position' => $request->position ?? 0,
            'is_active' => $request->filled('is_active'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully',
                'tag' => $tag
            ]);
        }

        return redirect()->route('admin.tags')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Show the form for editing the specified tag.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('dashboard.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'color' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        $slug = $request->slug;
        if (empty($slug)) {
            $slug = Str::slug($request->name);
            
            // Ensure slug is unique
            $count = 1;
            $originalSlug = $slug;
            while (Tag::where('slug', $slug)->where('id', '!=', $tag->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $tag->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
            'color' => $request->color,
            'position' => $request->position ?? $tag->position,
            'is_active' => $request->filled('is_active'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully',
                'tag' => $tag
            ]);
        }

        return redirect()->route('admin.tags')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags')
            ->with('success', 'Tag deleted successfully.');
    }

    /**
     * Export tags data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = Tag::query();

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('color')) {
            $query->where('color', $request->color);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $tags = $query->withCount('products')->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=tags-export-' . date('Y-m-d') . '.csv',
        ];

        $columns = ['ID', 'Name', 'Slug', 'Description', 'Type', 'Color', 'Position', 'Products Count', 'Active', 'Created At'];

        $callback = function () use ($tags, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tags as $tag) {
                fputcsv($file, [
                    $tag->id,
                    $tag->name,
                    $tag->slug,
                    $tag->description,
                    $tag->type,
                    $tag->color,
                    $tag->position,
                    $tag->products_count,
                    $tag->is_active ? 'Yes' : 'No',
                    $tag->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate the growth percentage of tags from last month.
     *
     * @return int
     */
    private function calculateTagGrowth()
    {
        $now = now();
        $currentMonthCount = Tag::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $lastMonth = $now->subMonth();
        $lastMonthCount = Tag::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        if ($lastMonthCount === 0) {
            return $currentMonthCount > 0 ? 100 : 0;
        }

        $growth = (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100;
        return round($growth);
    }
}