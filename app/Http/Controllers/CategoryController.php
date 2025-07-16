<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryShippingCost;
use App\Models\Product;
use App\Models\CategoryTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_product_categories'])->only('index');
        $this->middleware(['permission:add_product_category'])->only('create');
        $this->middleware(['permission:edit_product_category'])->only('edit');
        $this->middleware(['permission:delete_product_category'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $categories = Category::with('parentCategory'); // Eager load parent category

       if ($request->has('search')) {
                $sort_search = $request->search;
                $categories = $categories->where(function($query) use ($sort_search) {
                    $query->where('name', 'like', '%' . $sort_search . '%')
                          ->orWhere('CustomName', 'like', '%' . $sort_search . '%');
                });
            }


        $categories = $categories->paginate(30);

        return view('backend.categories.index', compact('categories', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find the category by its ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->CustomName = $request->CustomName;
        $category->banner = $request->banner;
        $category->save();

        $cacheKeyRoot = 'root-categories';
        $cacheKeySub = 'sub-categories-'.$category->CategoryId;

        // Clear the cache for root and sub categories
        Cache::forget($cacheKeyRoot);
        Cache::forget($cacheKeySub);

        flash(translate('Category has been updated successfully'))->success();
        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        $cacheKeyRoot = 'root-categories';
        $cacheKeySub = 'sub-categories-'.$category->CategoryId;

        if ($category) {
            $subcategories = Category::where('ParentId', $category->CategoryId)->get();
    
            foreach ($subcategories as $subcategory) {
                $subcategory->delete();
            }

            $category->delete();
            
            // Clear the cache for root and sub categories
            Cache::forget($cacheKeyRoot);
            Cache::forget($cacheKeySub);

            flash(translate('Category and its subcategories deleted successfully'))->success();
            return redirect()->route('categories.index');
        }
        return response()->json(['success' => false, 'message' => 'Category not found.']);
    }

    

    public function statusChange(Request $request)
    {
        $category = Category::find($request->id);

        $cacheKeyRoot = 'root-categories';
        $cacheKeySub = 'sub-categories-'.$category->CategoryId;

        if ($category) {
            $category->IsShow = $request->status;
            $category->save();

            if ($request->status == 0) {
                Category::where('ParentId', $category->CategoryId)->update(['IsShow' => 0]);
            }else{
                Category::where('ParentId', $category->CategoryId)->update(['IsShow' => 1]);
            }

            // Clear the cache for root and sub categories
            Cache::forget($cacheKeyRoot);
            Cache::forget($cacheKeySub);

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found.']);
    }

    public function topCategory(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            $category->is_top = $request->status;
            $category->save();

            return response()->json(['success' => true, 'message' => 'Top Category updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found.']);
    }
    public function shippingCostForm($id)
    {
        $category = Category::findOrFail($id);
        $shippingCost = CategoryShippingCost::where('category_id', $id)->first();
       // return view('categories.shipping_cost_form', compact('category', 'shippingCost'));
    }
    public function storeShippingCost(Request $request, $categoryId)
    {
        $validated = $request->validate([
            'air_cost' => 'required|numeric',
            'air_delivery_time' => 'required|string|max:255',
            'ship_cost' => 'required|numeric',
            'ship_delivery_time' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
        ]);
    
        CategoryShippingCost::updateOrCreate(
            ['category_id' => $categoryId],
            [
                'air_cost' => $validated['air_cost'],
                'air_delivery_time' => $validated['air_delivery_time'],
                'ship_cost' => $validated['ship_cost'],
                'ship_delivery_time' => $validated['ship_delivery_time'],
                'origin' => $validated['origin'],
            ]
        );
    
        return back()->with('success', 'Shipping cost updated successfully.');
    }


}
