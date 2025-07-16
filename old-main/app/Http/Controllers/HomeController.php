<?php
namespace App\Http\Controllers;

use App\Mail\SecondEmailVerifyMailManager;
use App\Models\AffiliateConfig;
use App\Models\Cart;
use App\Models\CategoryShippingCost;
use App\Models\Coupon;
use App\Models\CustomerPackage;
use App\Models\Order;
use App\Models\Page;
use App\Models\PickupPoint;
use App\Models\ShippingCategory;
use App\Models\User;
use App\Services\OtApiService;
use Cookie;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mail;

class HomeController extends Controller
{
    protected $otApiService;

    // Inject OTapiService via the constructor
    public function __construct(OTapiService $otApiService)
    {
        $this->otApiService = $otApiService;
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allowedCategoryIds     = ['otc-10'];
        $categoriesWithProducts = [];

        foreach ($allowedCategoryIds as $categoryId) {
            $subCategoryId = $this->getSubCategoryId($categoryId);

            // Shared cache for all users
            $cacheKey = 'shared_category_products_' . $categoryId;

            $products = Cache::remember($cacheKey, now()->addDays(7), function () use ($subCategoryId) {
                return $this->otApiService->batchSearchItemsFrame($subCategoryId, 'en', 0, 20, '');
            });

            $categoriesWithProducts[] = [
                'id'            => $categoryId,
                'subcategoryId' => $subCategoryId,
                'items'         => $products['Result']['Items']['Items']['Content'] ?? [],
            ];
        }

        $featuredProducts = Cache::remember('shared_featured_products', now()->addDays(3), function () {
            return $this->otApiService->getTrendingProducts(0, 'Popular', 0, 20);
        });

        $allowedCategoryIdsSection2     = ['otc-23', 'otc-19'];
        $categoriesWithProductsSection2 = [];

        foreach ($allowedCategoryIdsSection2 as $categoryId) {
            $subCategoryId = $this->getSubCategoryId($categoryId);

            $cacheKey = 'shared_category_products_' . $categoryId;

            $products = Cache::remember($cacheKey, now()->addDays(7), function () use ($subCategoryId) {
                return $this->otApiService->batchSearchItemsFrame($subCategoryId, 'en', 0, 20, '');
            });

            $categoriesWithProductsSection2[] = [
                'id'            => $categoryId,
                'subcategoryId' => $subCategoryId,
                'items'         => $products['Result']['Items']['Items']['Content'] ?? [],
            ];
        }
        // $coupon = null;

        //     $coupon = Coupon::where('type', 'cart_base')
        //         ->where('status', 1)
        //         ->first();

        //     if ($coupon) {
        //         $validationDateCheckCondition = strtotime(date('d-m-Y')) >= strtotime($coupon->start_date)
        //             && strtotime(date('d-m-Y')) <= strtotime($coupon->end_date);
        //     }

        //dd($coupon);
        return view('frontend.' . get_setting('homepage_select') . '.index', compact(
            'categoriesWithProducts',
            'featuredProducts',
            'categoriesWithProductsSection2'
        ));
    }

    private function getSubCategoryId($categoryId)
    {
        $subCategoryMap = [
            'otc-10' => 'abb-201967404',
            'otc-18' => 'abb-126442003',
            'otc-13' => 'abb-1031919',
            'otc-23' => 'abb-122322004',
            'otc-19' => 'abb-1042634',
        ];

        return $subCategoryMap[$categoryId] ?? null;
    }

    public function load_featured_section()
    {
        return view('frontend.' . get_setting('homepage_select') . '.partials.featured_products_section');
    }

    public function categoryWiseProducts(Request $request, $subcategory_Id)
    {
        // Determine subcategory name based on $subcategoryId
        $subcategoryId = $request->input('subcategoryId') ?? $subcategory_Id;

        $subCategoryName = $request->name ?? '';
        if ($subcategoryId == 'abb-201967404') {
            $subCategoryName = "Bag's";
        } elseif ($subcategoryId == 'abb-126442003') {
            $subCategoryName = "Sneakers";
        } elseif ($subcategoryId == 'abb-1031919') {
            $subCategoryName = "Shirt's";
        } elseif ($subcategoryId == 'abb-122322004') {
            $subCategoryName = "Accessories";
        } elseif ($subcategoryId == 'abb-1042634') {
            $subCategoryName = "Cosmetics";
        }

        // Get the current page from the request (default is 1)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Define how many products per page
        $perPage = 20;

        // Calculate offset for the API request
        $offset = ($currentPage - 1) * $perPage;

        // Create a cache key for the category based on $subcategoryId and current page
        $cacheKey      = 'category_wise_products_' . $subcategoryId . '_page_' . $currentPage;
        $productsQuery = Cache::remember($cacheKey, now()->addDays(7), function () use ($subcategoryId, $offset, $perPage) {
            // Adjust the API call to include offset and limit (perPage)
            return $this->otApiService->batchSearchItemsFrame($subcategoryId, 'en', $offset, $perPage, '');
        });

        // Extract all products from 'Content'
        $allProducts = $productsQuery['Result']['Items']['Items']['Content'] ?? [];

        // Get the total count from the API response
        $totalCount = $productsQuery['Result']['Items']['Items']['TotalCount'] ?? 0;

        // Create a new LengthAwarePaginator instance
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts,                                         // Items for the current page
            $totalCount,                                          // Total items from API
            $perPage,                                             // Items per page
            $currentPage,                                         // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()]// Set path for pagination links
        );

        // Append subcategory ID and name to the paginator to maintain them in the pagination links
        $paginatedProducts->appends(['subcategoryId' => $subcategoryId, 'name' => $subCategoryName]);
        //dd($subCategoryName);
        // Return the view with subcategory name and paginated products
        return view('frontend.category_wise_products', [
            'subCategoryName' => $subCategoryName,
            'products'        => $paginatedProducts,
        ]);
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        if (Route::currentRouteName() == 'seller.login' && get_setting('vendor_system_activation') == 1) {
            return view('auth.' . get_setting('authentication_layout_select') . '.seller_login');
        } else if (Route::currentRouteName() == 'deliveryboy.login' && addon_is_activated('delivery_boy')) {
            return view('auth.' . get_setting('authentication_layout_select') . '.deliveryboy_login');
        }
        return view('auth.' . get_setting('authentication_layout_select') . '.user_login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute             = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        return view('auth.' . get_setting('authentication_layout_select') . '.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return back();
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            $users_cart = Cart::where('user_id', auth()->user()->id)->first();
            if ($users_cart) {
                flash(translate('You had placed your items in the shopping cart. Try to order before the product quantity runs out.'))->warning();
            }
            return view('frontend.user.customer.dashboard');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('seller.profile.index');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.profile');
        } else {
            return view('frontend.user.profile');
        }
    }
    public function security(Request $request)
    {
        return view('frontend.user.security');
    }
    public function userProfileUpdate(Request $request)
    {
        $user              = Auth::user();
        $user->name        = $request->name;
        $user->address     = $request->address;
        $user->country     = $request->country;
        $user->city        = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone       = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;
        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function trackOrder(Request $request)
    {
        $orderCode    = $request->order_code ?? null; // Use null coalescing operator
        $order        = null;
        $errorMessage = '';

        // Check if order_code is provided
        if ($request->has('order_code')) {
            $order = Order::where('code', $orderCode)->first();

            // Set error message if order not found
            if (! $order) {
                $errorMessage = 'Order no not found..!!';
            }
        }

        return view('frontend.track_order', compact('order', 'orderCode', 'errorMessage'));
    }

    public function product(Request $request, $productId)
    {
        if (! Auth::check()) {
            session(['link' => url()->current()]);
        }

        // Cache product info for 7 days
        $cacheKey        = 'product_info_' . $productId;
        $detailedProduct = Cache::remember($cacheKey, now()->addDays(7), function () use ($productId) {
            $query = $this->otApiService->getFullItemInfo($productId);
            return $query['Result']['Item'];
        });

        // Get the current page from the request (default is 1)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Define how many reviews per page (20 per page)
        $perPage = 20;
        $offset  = ($currentPage - 1) * $perPage;

        // Cache product reviews for 7 days
        $cacheKeyReviews = 'product_reviews_' . $productId . '_page_' . $currentPage;
        $reviewsQuery    = Cache::remember($cacheKeyReviews, now()->addDays(7), function () use ($productId, $offset, $perPage) {
            return $this->otApiService->getReviews($productId, $offset, $perPage);
        });

        // Check if reviewsQuery is valid
        if ($reviewsQuery && isset($reviewsQuery['Result']['Content'])) {
            $reviewsContent = $reviewsQuery['Result']['Content'];
        } else {
            $reviewsContent = [];
        }
        $SubcategoryId   = $detailedProduct['CategoryId'];
        $cacheKeyReviews = 'product_parent_category_' . $SubcategoryId . '_page_' . $currentPage;
        $ParentCategory  = Cache::remember($cacheKeyReviews, now()->addDays(7), function () use ($SubcategoryId) {
            return $this->otApiService->getParentCategories($SubcategoryId);
        });
        $lastCategory = end($ParentCategory);
        $lastId       = $lastCategory['Id'] ?? null;
        $shippingCost = CategoryShippingCost::with('category')
            ->whereHas('category', function ($query) use ($lastId) {
                $query->where('CategoryId', $lastId);
            })
            ->first();

        // dd($shippingCosts);
        // Filter featured values
        $featuredValues = [];
        if (isset($detailedProduct['FeaturedValues'])) {
            foreach ($detailedProduct['FeaturedValues'] as $value) {
                if (in_array($value['Name'], ['RatesCount', 'normalizedRating', 'rating', 'goodRates', 'SalesInLast30Days', 'reviews'])) {
                    $featuredValues[$value['Name']] = $value['Value'];
                }
            }
        }

        $vendorId = $detailedProduct['VendorId'];

        // Retrieve products from cache or fetch from OtApiService if not cached
        $cacheKey      = 'vendor_wise_products_' . $vendorId . '_page_' . $currentPage;
        $productsQuery = Cache::remember($cacheKey, now()->addDays(7), function () use ($vendorId, $offset, $perPage) {
            return $this->otApiService->vendorWiseProducts($vendorId, $offset, $perPage, '');
        });

        // Extract all products from 'Content'
        $SimilerProducts = $productsQuery['Result']['Items']['Items']['Content'] ?? [];

        // Extract attributes and configured items
        $attributes      = $detailedProduct['Attributes'] ?? [];
        $configuredItems = $detailedProduct['ConfiguredItems'] ?? [];

        // Initialize variables for property names and configurators check
        $propertyNames = [];
        $isMultiple    = 'no';

        foreach ($configuredItems as $item) {
            if (count($item['Configurators']) > 1) {
                $isMultiple = 'yes';
            }

            foreach ($item['Configurators'] as $index => $config) {
                if ($isMultiple === 'no') {
                    $attribute = collect($attributes)->firstWhere(function ($attr) use ($config) {
                        return $attr['Pid'] === $config['Pid'] && $attr['Vid'] === $config['Vid'];
                    });

                    if ($attribute && empty($propertyNames)) {
                        $propertyNames['first_property'] = $attribute;
                    }
                } else {
                    if ($index < 2) {
                        $matchingAttribute = collect($attributes)->firstWhere(function ($attr) use ($config) {
                            return $attr['Pid'] === $config['Pid'] && $attr['Vid'] === $config['Vid'];
                        });

                        if ($matchingAttribute) {
                            $key                 = $index === 0 ? 'first_property' : 'second_property';
                            $propertyNames[$key] = $matchingAttribute['PropertyName'];
                        }
                    }
                }
            }
        }

        // Handle grouping of attributes with similar property names
        $groupedAttributes = [];
        foreach ($attributes as $attribute) {
            $propertyName = $attribute['PropertyName'];
            if (! isset($groupedAttributes[$propertyName])) {
                $groupedAttributes[$propertyName] = [];
            }
            $groupedAttributes[$propertyName][] = $attribute['Value'];
        }

        // Ensure unique values for grouped attributes
        foreach ($groupedAttributes as $propertyName => $values) {
            $groupedAttributes[$propertyName] = array_unique($values);
        }

        $shippingCategories = ShippingCategory::orderBy('name', 'asc')->get();

        return view('frontend.product_details', compact(
            'detailedProduct',
            'featuredValues',
            'propertyNames',
            'isMultiple',
            'reviewsContent',
            'groupedAttributes',
            'shippingCategories',
            'SimilerProducts',
            'shippingCost'
        ));
    }

    public function buyAndShip(Request $request)
    {
        return view('frontend.buy_and_ship');
    }
    public function shipForMe(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You must be logged in to access this page.');
        }

        $categories = ShippingCategory::orderBy('name', 'asc')->get();
        return view('frontend.ship_for_me', compact('categories'));
    }
    public function costCalculator(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You must be logged in to access this page.');
        }

        $categories = ShippingCategory::orderBy('name', 'asc')->get();
        return view('frontend.cost_calculator', compact('categories'));
    }
    public function getShippingDetails(Request $request)
    {
        $categoryId = $request->category_id;
        // $origin = $request->origin;
        // ->where('origin', $origin)
        $category = ShippingCategory::where('id', $categoryId)
            ->select('air_cost', 'air_delivery_time', 'ship_cost', 'ship_delivery_time')
            ->first();

        if ($category) {
            return response()->json([
                'success'            => true,
                'air_cost'           => $category->air_cost,
                'air_delivery_time'  => $category->air_delivery_time,
                'ship_cost'          => $category->ship_cost,
                'ship_delivery_time' => $category->ship_delivery_time,
                'origin'             => $category->origin,
                'message'            => 'Shipping Charge Calculator has been calculated',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong..!!',
        ], 404);

    }
    public function getCategoryApi(Request $request)
    {
        $category = ShippingCategory::where('id', $request->category_id)->first();
        return response()->json($category);
    }

    public function shop($vendorId, $vendorName)
    {
        // Get the current page from the request (default is 1)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Define how many products you want per page (20 per page)
        $perPage = 20;

        // Calculate the offset for the API request based on the current page
        $offset = ($currentPage - 1) * $perPage;

        // Retrieve products from cache or fetch from OtApiService if not cached
        $cacheKey      = 'vendor_wise_products_' . $vendorId . '_page_' . $currentPage;
        $productsQuery = Cache::remember($cacheKey, now()->addDays(7), function () use ($vendorId, $offset, $perPage) {
            // Adjust the API call to include the offset and limit (perPage)
            return $this->otApiService->vendorWiseProducts($vendorId, $offset, $perPage, '');
        });

        // Extract all products from 'Content'
        $allProducts = $productsQuery['Result']['Items']['Items']['Content'] ?? [];

        // Override the 'TotalCount' with the actual total count from the API
        $totalCount = $productsQuery['Result']['Items']['Items']['TotalCount'] ?? 0;

        // Create a new LengthAwarePaginator instance
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts,                                         // Items for the current page
            $totalCount,                                          // Total items (returned from API)
            $perPage,                                             // Items per page
            $currentPage,                                         // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()]// Set the path for pagination links
        );

        // Pass both the vendor name and paginated products to the view
        return view('frontend.seller_shop', [
            'vendorName' => $vendorName,
            'products'   => $paginatedProducts,
        ]);
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function sellerpolicy()
    {
        $page = Page::where('type', 'seller_policy_page')->first();
        return view("frontend.policies.sellerpolicy", compact('page'));
    }

    public function returnpolicy()
    {
        $page = Page::where('type', 'return_policy_page')->first();
        return view("frontend.policies.returnpolicy", compact('page'));
    }

    public function supportpolicy()
    {
        $page = Page::where('type', 'support_policy_page')->first();
        return view("frontend.policies.supportpolicy", compact('page'));
    }

    public function terms()
    {
        $page = Page::where('type', 'terms_conditions_page')->first();
        return view("frontend.policies.terms", compact('page'));
    }

    public function privacypolicy()
    {
        $page = Page::where('type', 'privacy_policy_page')->first();
        return view("frontend.policies.privacypolicy", compact('page'));
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.' . get_setting('homepage_select') . '.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        $categoryId = $request->category_id;
        $categories = get_sub_categories($categoryId);

        return view('frontend.' . get_setting('homepage_select') . '.partials.category_elements', compact('categories'));
    }
    public function getSubCategoryItems(Request $request)
    {
        $categoryId = $request->category_id;
        $categories = get_sub_categories($categoryId);

        return response()->json([
            'success'       => true,
            'subcategories' => $categories,
        ]);
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status']  = 2;
            $response['message'] = translate('Email already exists!');
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }

    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status']  = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = translate('Email Verification');
        $array['from']    = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Verify your account');
        $array['link']    = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender']  = Auth::user()->name;
        $array['details'] = translate("Email Second");

        $user                               = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status']  = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status']  = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param = $request->input('new_email_verificiation_code');
            $user                           = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email                        = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                if ($user->user_type == 'seller') {
                    return redirect()->route('seller.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password          = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash(translate("Password and confirm password didn't match"))->warning();
                return view('auth.' . get_setting('authentication_layout_select') . '.reset_password');
            }
        } else {
            flash(translate("Verification code mismatch"))->error();
            return view('auth.' . get_setting('authentication_layout_select') . '.reset_password');
        }
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function clearAll()
    {
        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('event:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return response()->json(['message' => 'Artisan commands executed successfully!']);
    }
}
