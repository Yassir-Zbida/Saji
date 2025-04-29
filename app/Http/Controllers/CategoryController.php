<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if request is AJAX
        if (request()->ajax()) {
            return $this->getCategoriesData();
        }

        return view('dashboard.categories.index');
    }

    /**
     * Get categories data for AJAX requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesData()
    {
        $query = Category::with(['parent', 'products'])
            ->withCount(['products', 'children']);

        // Apply filters
        if (request()->has('parent')) {
            if (request()->parent === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', request()->parent);
            }
        }

        if (request()->has('status')) {
            $status = request()->status === 'active';
            $query->where('is_active', $status);
        }

        if (request()->has('search')) {
            $search = request()->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = request()->input('sort', 'position');
        $direction = request()->input('direction', 'asc');

        if ($sort === 'products_count') {
            $query->withCount('products')
                ->orderBy('products_count', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // Get parent categories for the dropdown
        $parentCategories = Category::whereNull('parent_id')
            ->orWhere(function ($query) {
                $query->has('children');
            })
            ->orderBy('name')
            ->get();

        // Calculate statistics
        $stats = [
            'totalCategories' => Category::count(),
            'activeCategories' => Category::where('is_active', true)->count(),
            'parentCategories' => Category::whereNull('parent_id')->count(),
            'topCategory' => 'None',
            'topCategoryProductCount' => 0,
            'categoryGrowth' => 0
        ];

        // Get top category by product count
        $topCategory = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->first();

        if ($topCategory) {
            $stats['topCategory'] = $topCategory->name;
            $stats['topCategoryProductCount'] = $topCategory->products_count;
        }

        // Calculate growth (comparing to last month)
        $lastMonthCount = Category::where('created_at', '<', now()->subMonth())->count();
        $currentCount = $stats['totalCategories'];

        if ($lastMonthCount > 0) {
            $stats['categoryGrowth'] = round((($currentCount - $lastMonthCount) / $lastMonthCount) * 100);
        }

        // Paginate results
        $perPage = request()->input('per_page', 10);
        $categories = $query->paginate($perPage);

        return response()->json([
            'categories' => $categories,
            'parentCategories' => $parentCategories,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orWhere(function ($query) {
                $query->has('children');
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Set default position if not provided
        if (empty($data['position'])) {
            $data['position'] = Category::max('position') + 1;
        }

        // Set is_active to false if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = false;
        }

        $category = Category::create($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
            ->whereNotIn('id', $category->descendants()->pluck('id')->toArray())
            ->whereNull('parent_id')
            ->orWhere(function ($query) use ($category) {
                $query->has('children')
                    ->where('id', '!=', $category->id)
                    ->whereNotIn('id', $category->descendants()->pluck('id')->toArray());
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    // In your CategoryController update method
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'position' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Make sure a category can't be its own parent
        if (!empty($data['parent_id']) && $data['parent_id'] == $category->id) {
            return redirect()->back()
                ->with('error', 'A category cannot be its own parent')
                ->withInput();
        }

        // Handle slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Set is_active to false if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = false;
        }

        $category->update($data);

        return redirect()->route('admin.categories')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category because it has associated products');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // If the category has children, either delete them or move them up one level
        if ($category->children()->count() > 0) {
            foreach ($category->children as $child) {
                $child->parent_id = $category->parent_id;
                $child->save();
            }
        }

        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Category deleted successfully');
    }

    /**
     * Update category position.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function updatePosition(Request $request, Category $category)
    {
        $direction = $request->input('direction', 'up');
        $currentPosition = $category->position;

        if ($direction === 'up') {
            $targetCategory = Category::where('position', '<', $currentPosition)
                ->where('parent_id', $category->parent_id)
                ->orderBy('position', 'desc')
                ->first();
        } else {
            $targetCategory = Category::where('position', '>', $currentPosition)
                ->where('parent_id', $category->parent_id)
                ->orderBy('position', 'asc')
                ->first();
        }

        if ($targetCategory) {
            $targetPosition = $targetCategory->position;

            // Swap positions
            $category->position = $targetPosition;
            $targetCategory->position = $currentPosition;

            $category->save();
            $targetCategory->save();
        }

        return redirect()->back()->with('success', 'Category position updated');
    }

    /**
     * Export categories to CSV.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="categories-' . date('Y-m-d') . '.csv"',
        ];

        $categories = Category::with('parent')
            ->withCount('products')
            ->get();

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID',
                'Name',
                'Slug',
                'Description',
                'Parent',
                'Products Count',
                'Position',
                'Status',
                'Meta Title',
                'Meta Description',
                'Meta Keywords',
                'Created At',
                'Updated At'
            ]);

            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->name,
                    $category->slug,
                    $category->description,
                    $category->parent ? $category->parent->name : 'None',
                    $category->products_count,
                    $category->position,
                    $category->is_active ? 'Active' : 'Inactive',
                    $category->meta_title,
                    $category->meta_description,
                    $category->meta_keywords,
                    $category->created_at->format('Y-m-d H:i:s'),
                    $category->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}