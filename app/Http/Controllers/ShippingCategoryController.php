<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ShippingCategory;
use App\Utility\CategoryUtility;
use Illuminate\Support\Str;
use Cache;

class ShippingCategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        
        // Start the query and order by 'order_level' first
        $categories = ShippingCategory::orderBy('parent_id', 'desc')->orderBy('name', 'asc');

        // Check if there is a search request
        if ($request->has('search')) {
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%' . $sort_search . '%');
        }

        // Order by name alphabetically after filtering
        $categories = $categories->paginate(15); // Change to 'desc' for descending order

        return view('backend.shipping_categories.index', compact('categories', 'sort_search'));
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ShippingCategory::where('parent_id', 0)->get();
       // dd( $categories);
        return view('backend.shipping_categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'air_cost' => 'required|numeric',
            'air_delivery_time' => 'required|string',
            'ship_cost' => 'required|numeric',
            'ship_delivery_time' => 'required|string',
            'origin' => 'required',
        ]);

        // Create a new category instance and save the data
        $category = new ShippingCategory();
        $category->name = $request->name;
        if ($request->parent_id != 0) {
            $category->parent_id = $request->parent_id;
        }
        $category->air_cost = $request->air_cost;
        $category->air_delivery_time = $request->air_delivery_time;
        $category->ship_cost = $request->ship_cost;
        $category->ship_delivery_time = $request->ship_delivery_time;
        $category->origin = $request->origin;
        $category->save();

        flash(translate('Category has been inserted successfully'))->success();
        return redirect()->route('shipping_categories.index');
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
        $categories = ShippingCategory::where('parent_id', 0)->get();
        $category = ShippingCategory::findOrFail($id);
        
        return view('backend.shipping_categories.edit', compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $category = ShippingCategory::findOrFail($id);
    //     if($request->lang == env("DEFAULT_LANGUAGE")){
    //         $category->name = $request->name;
    //     }
    //     if($request->order_level != null) {
    //         $category->order_level = $request->order_level;
    //     }
    //     $category->digital = $request->digital;
    //     $category->banner = $request->banner;
    //     $category->icon = $request->icon;
    //     $category->cover_image = $request->cover_image;
    //     $category->meta_title = $request->meta_title;
    //     $category->meta_description = $request->meta_description;

    //     $previous_level = $category->level;

    //     if ($request->parent_id != "0") {
    //         $category->parent_id = $request->parent_id;

    //         $parent = ShippingCategory::find($request->parent_id);
    //         $category->level = $parent->level + 1 ;
    //     }
    //     else{
    //         $category->parent_id = 0;
    //         $category->level = 0;
    //     }

    //     if($category->level > $previous_level){
    //         CategoryUtility::move_level_down($category->id);
    //     }
    //     elseif ($category->level < $previous_level) {
    //         CategoryUtility::move_level_up($category->id);
    //     }

    //     if ($request->slug != null) {
    //         $category->slug = strtolower($request->slug);
    //     }
    //     else {
    //         $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
    //     }


    //     if ($request->commision_rate != null) {
    //         $category->commision_rate = $request->commision_rate;
    //     }

    //     $category->save();

    //     $category->attributes()->sync($request->filtering_attributes);

    //     $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);
    //     $category_translation->name = $request->name;
    //     $category_translation->save();

    //     Cache::forget('featured_categories');
    //     flash(translate('Category has been updated successfully'))->success();
    //     return back();
    // }

    public function update(Request $request, $id)
    {
        //dd("ok");
        $request->validate([
            'name' => 'required|string|max:255',
            'air_cost' => 'required|numeric',
            'air_delivery_time' => 'required|string',
            'ship_cost' => 'required|numeric',
            'ship_delivery_time' => 'required|string',
            'origin' => 'required',
        ]);

        // Find the existing category instance by ID
        $category = ShippingCategory::findOrFail($id);

        // Update the category fields
        $category->name = $request->name;
        if ($request->parent_id != 0) {
            $category->parent_id = $request->parent_id;
        } else {
            $category->parent_id = null; // Or any default value you wish to assign
        }
        $category->air_cost = $request->air_cost;
        $category->air_delivery_time = $request->air_delivery_time;
        $category->ship_cost = $request->ship_cost;
        $category->ship_delivery_time = $request->ship_delivery_time;
        $category->origin = $request->origin;
        
        // Save the updated category
        $category->save();

        $page = $request->input('page', 1);

    flash(translate('Category has been updated successfully'))->success();
    
    // Redirect to the index page with the correct pagination page
    return redirect()->route('shipping_categories.index', ['page' => $page]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        // Find and delete the category
        ShippingCategory::findOrFail($id)->delete();
    
    
        // Success message and redirect
        flash(translate('Category has been deleted successfully'))->success();
        return redirect()->route('shipping_categories.index');
    }


    public function updateFeatured(Request $request)
    {
        $category = ShippingCategory::findOrFail($request->id);
        $category->featured = $request->status;
        $category->save();
        Cache::forget('featured_categories');
        return 1;
    }

    public function categoriesByType(Request $request)
    {
        $categories = ShippingCategory::where('parent_id', 0)
            ->where('digital', $request->digital)
            ->with('childrenCategories')
            ->get();

        return view('backend.shipping_categories.categories_option', compact('categories'));
    }
}
