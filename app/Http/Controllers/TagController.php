<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if request is AJAX
        if (request()->ajax()) {
            return $this->getTagsData();
        }

        return view('dashboard.tags.index');
    }

    /**
     * Get tags data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagsData()
    {
        $query = Tag::withCount('products');

        // Apply filters
        if (request()->has('type') && request()->type !== '') {
            $query->where('type', request()->type);
        }

        if (request()->has('color') && request()->color !== '') {
            $query->where('color', request()->color);
        }

        if (request()->has('search') && request()->search !== '') {
            $search = request()->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = request()->input('sort', 'name');
        $direction = request()->input('direction', 'asc');
        
        if ($sort === 'products_count') {
            $query->orderBy('products_count', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // Calculate statistics
        $stats = [
            'totalTags' => Tag::count(),
            'mostUsedTag' => null,
            'mostUsedTagCount' => 0,
            'tagTypeCount' => Tag::distinct('type')->count('type'),
            'tagGrowth' => 0
        ];

        // Get most used tag
        $mostUsedTag = Tag::withCount('products')
            ->orderBy('products_count', 'desc')
            ->first();

        if ($mostUsedTag) {
            $stats['mostUsedTag'] = $mostUsedTag->name;
            $stats['mostUsedTagCount'] = $mostUsedTag->products_count;
        }

        // Calculate growth (comparing to last month)
        $lastMonthCount = Tag::where('created_at', '<', now()->subMonth())->count();
        $currentCount = $stats['totalTags'];
        
        if ($lastMonthCount > 0) {
            $stats['tagGrowth'] = round((($currentCount - $lastMonthCount) / $lastMonthCount) * 100);
        }

        // Paginate results
        $perPage = request()->input('per_page', 12);
        $tags = $query->paginate($perPage);

        return response()->json([
            'tags' => $tags,
            'stats' => $stats
        ]);
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:30',
            'type' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag = Tag::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully',
                'tag' => $tag
            ]);
        }

        return redirect()->route('admin.tags')
            ->with('success', 'Tag created successfully');
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tags')->ignore($tag->id),
            ],
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:30',
            'type' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully',
                'tag' => $tag
            ]);
        }

        return redirect()->route('admin.tags')
            ->with('success', 'Tag updated successfully');
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        // Detach all products from this tag
        $tag->products()->detach();

        $tag->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag deleted successfully'
            ]);
        }

        return redirect()->route('admin.tags')
            ->with('success', 'Tag deleted successfully');
    }

    /**
     * Export tags to CSV.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tags-'.date('Y-m-d').'.csv"',
        ];

        $tags = Tag::withCount('products')->get();

        $callback = function() use ($tags) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Slug', 'Description', 'Type', 'Color', 
                'Products Count', 'Created At', 'Updated At'
            ]);
            
            foreach ($tags as $tag) {
                fputcsv($file, [
                    $tag->id,
                    $tag->name,
                    $tag->slug,
                    $tag->description,
                    $tag->type,
                    $tag->color,
                    $tag->products_count,
                    $tag->created_at->format('Y-m-d H:i:s'),
                    $tag->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}