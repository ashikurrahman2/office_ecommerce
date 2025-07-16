<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use Illuminate\Support\Str;
use App\Services\OtApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    protected $otApiService;

    public function __construct(OtApiService $otApiService)
    {
        $this->otApiService = $otApiService;
    }

   
  public function index(Request $request)
{//dd('ok');
    // Validate the input to ensure the required field is provided
    $request->validate([
        'keyword' => 'required|string',
    ]);

    $keyword = $request->keyword;

     if (Str::startsWith($keyword, ['http://', 'https://'])) {
            // Directly redirect URLs from 'https://cplexpressbd.com/'
            if (Str::contains($keyword, 'cplexpressbd.com')) {
                return redirect($keyword);
            }
        
            // Extract productId for known patterns
            if (preg_match('/offer\/(\d+)|product\/([a-zA-Z0-9-]+)/', $keyword, $matches)) {
                $productId = $matches[1] ?? $matches[2] ?? null;
        
                if ($productId) {
                    // Prepend 'abb-' if the productId is purely numeric
                    if (is_numeric($productId)) {
                        $productId = 'abb-' . $productId;
                    }
                    return redirect()->route('product', ['productId' => $productId]);
                }
            }
        
            // If no productId is found, show the manual request page
            return view('frontend.buy_ship_manual_request', compact('keyword'));
        }



    // Get the min and max price from the request
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    // Get the current page from the request (default is 1)
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 20;
    $offset = ($currentPage - 1) * $perPage;

    // Generate a unique cache key based on keyword, min price, max price, and current page
    $cacheKey = 'search_products_' . md5($keyword . '_' . $minPrice . '_' . $maxPrice) . '_page_' . $currentPage;

    // Retrieve products from cache or fetch from OtApiService if not cached
    $productsQuery = Cache::remember($cacheKey,  now()->addDays(7), function () use ($keyword, $offset, $perPage, $minPrice, $maxPrice) {
        $searchParams = [
            'ItemTitle' => $keyword,
            'CurrencyCode' => 'BDT',
        ];

        // Add min and max price to search parameters if they exist
        if ($minPrice) {
            $searchParams['MinPrice'] = $minPrice;
        }
        if ($maxPrice) {
            $searchParams['MaxPrice'] = $maxPrice;
        }

        return $this->otApiService->searchItems($searchParams, '', $offset, $perPage, '');
    });

    $allProducts = $productsQuery['Result']['Items']['Items']['Content'] ?? [];
    $totalCount = $productsQuery['Result']['Items']['Items']['TotalCount'] ?? 0;

    $paginatedProducts = new LengthAwarePaginator(
        $allProducts,
        $totalCount,
        $perPage,
        $currentPage,
        ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );

    $paginatedProducts->appends(['keyword' => $keyword, 'min_price' => $minPrice, 'max_price' => $maxPrice]);

    return view('frontend.search_results', ['products' => $paginatedProducts, 'keyword' => $keyword]);
}

    
    public function imageSearch(Request $request)
    { //return redirect()->route('home');
        // Validate the image file and generate the image URL on the first request
        if ($request->isMethod('post')) {
            // Validate and process the uploaded image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);
    
            $fileName = Str::random(40);
            $extension = $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->move(public_path('assets/search'), $fileName . '.' . $extension);
            $imageUrl = static_asset('assets/search/' . $fileName . '.' . $extension);
    
        } else {
            $imageUrl = $request->query('imageUrl');
        }
    
        // If imageUrl is not set, return an error or redirect as needed
        if (!$imageUrl) {
            return redirect()->back()->withErrors('Image is required for the search.');
        }
    
        // Define pagination variables
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $offset = ($currentPage - 1) * $perPage;
    
        // Create a cache key based on the image URL and current page
        $cacheKey = 'search_image_' . md5($imageUrl) . '_page_' . $currentPage;
    
        // Fetch products based on the image URL
        $productsQuery = Cache::remember($cacheKey,  now()->addDays(7), function () use ($imageUrl, $offset, $perPage) {
            $searchParams = [
                'ImageUrl' => $imageUrl,
                'Provider'=>'Alibaba1688',
            ];
    
            return $this->otApiService->searchItems($searchParams, '', $offset, $perPage, '');
        });
    
        // Extract all products and get the total count
        $allProducts = $productsQuery['Result']['Items']['Items']['Content'] ?? [];
        $totalCount = $productsQuery['Result']['Items']['Items']['TotalCount'] ?? 0;
    
        // Create a LengthAwarePaginator instance for pagination
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts,
            $totalCount,
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    
        // Append imageUrl to pagination links
        $paginatedProducts->appends(['imageUrl' => $imageUrl]);
        
    
        // Return the view with paginated products and "image search" as keyword
        return view('frontend.search_results_img', [
            'products' => $paginatedProducts,
            'keyword2' => 'uploaded image',
            'imageUrl' => $imageUrl,
        ]);
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products_query = filter_products(Product::query());

        $products_query = $products_query->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        $case1 = $query . '%';
        $case2 = '%' . $query . '%';

        $products_query->orderByRaw("CASE 
                WHEN name LIKE '$case1' THEN 1 
                WHEN name LIKE '$case2' THEN 2 
                ELSE 3 
                END");
        $products = $products_query->limit(3)->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.'.get_setting('homepage_select').'.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}
