<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\FollowSellerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecaptchaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RequestBuyShipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OTAPIController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Payment\AamarpayController;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\NagadController;
use App\Http\Controllers\Payment\NgeniusController;
use App\Http\Controllers\Payment\PayhereController;
use App\Http\Controllers\Payment\PaykuController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\PhonepeController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\SslcommerzController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Payment\VoguepayController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\RequestShipController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SizeChartController;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


Route::get('/clear-all', [HomeController::class, 'clearAll'])->name('clear_all');
Route::post('/recaptcha-v3-validate', [RecaptchaController::class, 'validateV3Token'])->name('recaptcha.v3.validate');
Route::get('/blocked', function() {
    return view('blocked'); // Return a "Blocked" page view
});


Route::get('/cache', function() {
    Artisan::call('config:cache');
    Artisan::call('event:cache');
    return "Artisan commands executed successfully!";
});

Route::get('/insert-categories', function () {
    
    DB::table('categories')->truncate();
    $jsonData = file_get_contents(public_path('json/category_info.json'));
    $data = json_decode($jsonData, true);
    
    if (isset($data['CategoryInfoList']['Content']) && is_array($data['CategoryInfoList']['Content'])) {
        foreach ($data['CategoryInfoList']['Content'] as $categoryData) {
            
            Category::create([
                'CategoryId' => $categoryData['Id'],
                'ProviderType' => $categoryData['ProviderType'],
                'IsHidden' => $categoryData['IsHidden'],
                'IsVirtual' => $categoryData['IsVirtual'],
                'ExternalId' => $categoryData['ExternalId'],
                'Name' => $categoryData['Name'] ?? null,
                'CustomName' => $categoryData['Name'] ?? null,
                'IsParent' => $categoryData['IsParent'],
                'ParentId' => $categoryData['ParentId'] ?? null,
                'IsInternal' => $categoryData['IsInternal'],
                'IsShow' => $categoryData['IsShow'] ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return 'Categories Inserted Successfully..!!';
});


Route::get('/refresh-csrf', function () {
    return csrf_token();
});

// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
    Route::post('/aiz-uploader', 'show_uploader');
    Route::post('/aiz-uploader/upload', 'upload');
    Route::get('/aiz-uploader/get-uploaded-files', 'get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
    Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Route::group(['middleware' => ['prevent-back-history']], function () {
    Auth::routes(['verify' => true]);
});

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout');
    Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
    //Apple Callback
    Route::post('/apple-callback', 'handleAppleCallback');
    Route::get('/account-deletion', 'account_deletion')->name('account_delete');
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/email-change/callback', 'email_change_callback')->name('email_change.callback');
    Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');

    Route::get('/users/login', 'login')->name('user.login');
    Route::get('/users/registration', 'registration')->name('user.registration');
    Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit');
    // Route::get('/new-page', 'new_page')->name('new_page');


    //Home Page
    Route::get('/', 'index')->name('home');

    Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
    // Route::post('/home/section/best-selling', 'load_best_selling_section')->name('home.section.best_selling');
    // Route::post('/home/section/newest-products', 'load_newest_product_section')->name('home.section.newest_products');
    // Route::post('/home/section/home-categories', 'load_home_categories_section')->name('home.section.home_categories');
    // Route::post('/home/section/best-sellers', 'load_best_sellers_section')->name('home.section.best_sellers');

    //category dropdown menu ajax call
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');
    Route::post('/category/nav-element-list-for-mobile', 'getSubCategoryItems')->name('category.subcategory_elements');

    //Flash Deal Details Page
    Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
    Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');

    //Route::get('/products/{categoryId}', 'categoryWiseProducts')->name('category_wise_products');
   // Route::match(['get', 'post'], '/products/{categoryId}', 'categoryWiseProducts')->name('category_wise_products');
    Route::match(['get', 'post'], '/products/{category}', 'categoryWiseProducts')->name('category_wise_products');

    Route::get('/product/{productId}', 'product')->name('product');
    Route::post('/product/variant-price', 'variant_price')->name('products.variant_price');
    Route::get('/seller/{vendorId}/{vendorName}', 'shop')->name('shop.visit');

    Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');

    Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');

    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/sellers', 'all_seller')->name('sellers');
    Route::get('/coupons', 'all_coupons')->name('coupons.all');
    Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');


    // Policies
    Route::get('/seller-policy', 'sellerpolicy')->name('sellerpolicy');
    Route::get('/return-policy', 'returnpolicy')->name('returnpolicy');
    Route::get('/support-policy', 'supportpolicy')->name('supportpolicy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/privacy-policy', 'privacypolicy')->name('privacypolicy');

    Route::get('/track-your-order', 'trackOrder')->name('orders.track');
    Route::get('/buy-and-ship', 'buyAndShip')->name('buy_and_ship');
    Route::get('/ship-for-me', 'shipForMe')->name('ship_for_me');
    Route::get('/cost-calculator', 'costCalculator')->name('cost_calculator');

    Route::get('/category-api', 'getCategoryApi')->name('category_api');
    Route::get('/calculate-shipping-charge-api', 'getShippingDetails')->name('get_shipping_details_api');
});

// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');


// Size Chart Show
Route::get('/size-charts-show/{id}', [SizeChartController::class, 'show'])->name('size-charts-show');

Route::get('/sitemap.xml', function () {
    return base_path('sitemap.xml');
});


// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search');
    Route::match(['get', 'post'], '/search/image', 'imageSearch')->name('search.image'); 
    Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
    Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});

// Cart
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
    
    Route::post('/cart/store-selected-items','storeSelectedItems')->name('cart.storeSelectedItems');
});

//Paypal START
Route::controller(PaypalController::class)->group(function () {
    Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
    Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
});
//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
    Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
    Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago 

// SSLCOMMERZ Start
Route::controller(SslcommerzController::class)->group(function () {
    Route::get('/sslcommerz/pay', 'index');
    Route::POST('/sslcommerz/success', 'success');
    Route::POST('/sslcommerz/fail', 'fail');
    Route::POST('/sslcommerz/cancel', 'cancel');
    Route::POST('/sslcommerz/ipn', 'ipn');
});
//SSLCOMMERZ END

//Stipe Start
Route::controller(StripeController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
    Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
    Route::get('/stripe/success', 'success')->name('stripe.success');
    Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END


// Subscribe
Route::resource('subscribers', SubscriberController::class);

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware(['prevent-back-history']);
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/security', 'security')->name('security');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
    });

    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');
});

Route::group(['middleware' => ['customer', 'verified', 'unbanned']], function () {

    // Checkout Routs
    Route::group(['prefix' => 'checkout'], function () {
        Route::controller(CheckoutController::class)->group(function () {
            Route::get('/', 'get_shipping_info')->name('checkout.shipping_info');
            Route::any('/payment-info', 'store_shipping_info')->name('checkout.store_shipping_infostore');
            Route::post('/payment-select', 'store_delivery_info')->name('checkout.store_delivery_info');
            Route::get('/order-confirmed', 'order_confirmed')->name('order_confirmed');
            Route::post('/payment', 'checkout')->name('payment.checkout');
            Route::post('/re-payment', 're_checkout')->name('re_payment.checkout');
            Route::post('/get-pick-up-points', 'get_pick_up_points')->name('shipping_info.get_pick_up_points');
            Route::get('/payment-select', 'get_payment_info')->name('checkout.payment_info');
            Route::post('/apply-coupon-code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
            Route::post('/remove-coupon-code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
            //Club point
            Route::post('/apply-club-point', 'apply_club_point')->name('checkout.apply_club_point');
            Route::post('/remove-club-point', 'remove_club_point')->name('checkout.remove_club_point');
        });
    });

    // Purchase History
    // Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/my-order/index', 'index')->name('purchase_history.index');
        Route::get('/my-order/delivered', 'delivered')->name('purchase_history.delivered');
        Route::get('/my-order/forward-parcel', 'forwardParcel')->name('purchase_history.forward_parcel');
        Route::get('/my-order/cancelled', 'cancelled')->name('purchase_history.cancelled');
        Route::get('/my-order/details/{id}', 'purchase_history_details')->name('purchase_history.details');
        Route::get('/my-order/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');

        Route::get('/re-order/{id}', 're_order')->name('re_order');
    });

    // Wishlist
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');

    //Follow
    Route::controller(FollowSellerController::class)->group(function () {
        Route::get('/followed-seller', 'index')->name('followed_seller');
        Route::get('/followed-seller/store', 'store')->name('followed_seller.store');
        Route::get('/followed-seller/remove', 'remove')->name('followed_seller.remove');
    });

    // Wallet
    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet', 'index')->name('wallet.index');
        Route::post('/wallet-recharge', 'recharge')->name('wallet.recharge');
        Route::get('/wallet-balance', 'balance')->name('wallet.balance');
    });

    // Support Ticket
    Route::resource('support_ticket', SupportTicketController::class);
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

    // Customer Package
    Route::post('/customer-packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');


    // Product Review
    Route::post('/product-review-modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');


    //Request Product
    Route::controller(RequestShipController::class)->group(function () {
       Route::get('/ship-for-me/my-request', 'index')->name('ship_product_request.index');
       Route::get('/ship-for-me/my-request/forward-parcel', 'forwardParcel')->name('ship_product_request.forward_parcel');
       Route::get('/ship-for-me/my-request/delivered', 'delivered')->name('ship_product_request.delivered');
       Route::post('/ship-for-me/my-request/start-shipment/{id}', 'startShipment')->name('ship_product_request.start_shipment');
       Route::post('/ship-for-me/my-request/cancel-shipment/{id}', 'cancelShipment')->name('ship_product_request.cancel_shipment');
       Route::post('/ship-for-me/my-request-store', 'store')->name('ship_product_request.store');
       Route::post('/ship-for-me/my-request-cancel', 'cancel')->name('ship_product_request.cancel');
       Route::post('/ship-for-me/my-request-destroy', 'destroy')->name('ship_product_request.destroy');

       Route::get('/ship-for-me/drafted-items', 'getDraftedItems')->name('ship_product_request.drafted.items');
       Route::post('/ship-for-me/drafted-items/delete', 'draftedItemDelete')->name('ship_product_request.drafted.item.delete');
       Route::post('/ship-for-me/drafted-items/duplicate', 'draftedItemDuplicate')->name('ship_product_request.drafted.item.duplicate');

       Route::post('/ship-for-me/drafted-items/request', 'requestAllDraftedItems')->name('ship_product_request.drafted.items.request');


    });

    //Buy & Ship Request
    Route::controller(RequestBuyShipController::class)->group(function () {
      
       Route::get('/buy-ship-for-me/my-request', 'index')->name('buy_ship_product_request.index');
       Route::post('/buy-ship-for-me/my-request-store', 'store')->name('buy_ship_product_request.store');    
       Route::post('/buy-ship-for-me/my-request/start-order/{id}', 'startOrder')->name('buy_ship_product_request.start_order');
       Route::post('/buy-ship-for-me/my-request/cancel-order/{id}', 'cancelOrder')->name('buy_ship_product_request.cancel_order');
    });



});


Route::get('translation-check/{check}', [LanguageController::class, 'get_translation']);


Route::group(['middleware' => ['auth']], function () {

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

    // Reviews
    Route::resource('/reviews', ReviewController::class);

    // Product Conversation
    Route::resource('conversations', ConversationController::class);
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
    });


    Route::resource('messages', MessageController::class);

    //Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set-default/{id}', 'set_default')->name('addresses.set_default');
    });
});


Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);
Route::get('/paystack/new-callback', [PaystackController::class, 'paystackNewCallback']);

Route::controller(VoguepayController::class)->group(function () {
    Route::get('/vogue-pay', 'showForm');
    Route::get('/vogue-pay/success/{id}', 'paymentSuccess');
    Route::get('/vogue-pay/callback', 'handleCallback');
    Route::get('/vogue-pay/failure/{id}', 'paymentFailure');
});


//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

Route::get('/customer-products/admin', [IyzicoController::class, 'initPayment'])->name('profile.edit');

//payhere below
Route::controller(PayhereController::class)->group(function () {
    Route::get('/payhere/checkout/testing', 'checkout_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/wallet/testing', 'wallet_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/customer_package/testing', 'customer_package_testing')->name('payhere.customer_package.testing');

    Route::any('/payhere/checkout/notify', 'checkout_notify')->name('payhere.checkout.notify');
    Route::any('/payhere/checkout/return', 'checkout_return')->name('payhere.checkout.return');
    Route::any('/payhere/checkout/cancel', 'chekout_cancel')->name('payhere.checkout.cancel');

    Route::any('/payhere/wallet/notify', 'wallet_notify')->name('payhere.wallet.notify');
    Route::any('/payhere/wallet/return', 'wallet_return')->name('payhere.wallet.return');
    Route::any('/payhere/wallet/cancel', 'wallet_cancel')->name('payhere.wallet.cancel');

    Route::any('/payhere/seller_package_payment/notify', 'seller_package_notify')->name('payhere.seller_package_payment.notify');
    Route::any('/payhere/seller_package_payment/return', 'seller_package_payment_return')->name('payhere.seller_package_payment.return');
    Route::any('/payhere/seller_package_payment/cancel', 'seller_package_payment_cancel')->name('payhere.seller_package_payment.cancel');

    Route::any('/payhere/customer_package_payment/notify', 'customer_package_notify')->name('payhere.customer_package_payment.notify');
    Route::any('/payhere/customer_package_payment/return', 'customer_package_return')->name('payhere.customer_package_payment.return');
    Route::any('/payhere/customer_package_payment/cancel', 'customer_package_cancel')->name('payhere.customer_package_payment.cancel');
});


//N-genius
Route::controller(NgeniusController::class)->group(function () {
    Route::any('ngenius/cart_payment_callback', 'cart_payment_callback')->name('ngenius.cart_payment_callback');
    Route::any('ngenius/wallet_payment_callback', 'wallet_payment_callback')->name('ngenius.wallet_payment_callback');
    Route::any('ngenius/customer_package_payment_callback', 'customer_package_payment_callback')->name('ngenius.customer_package_payment_callback');
    Route::any('ngenius/seller_package_payment_callback', 'seller_package_payment_callback')->name('ngenius.seller_package_payment_callback');
});

Route::controller(BkashController::class)->group(function () {
    Route::get('/bkash/create-payment', 'create_payment')->name('bkash.create_payment');
    Route::get('/bkash/callback', 'callback')->name('bkash.callback');
    Route::get('/bkash/success', 'success')->name('bkash.success');
});

Route::get('/checkout-payment-detail', [StripeController::class, 'checkout_payment_detail']);

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::controller(AamarpayController::class)->group(function () {
    Route::post('/aamarpay/success', 'success')->name('aamarpay.success');
    Route::post('/aamarpay/fail', 'fail')->name('aamarpay.fail');
});

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizenetController::class, 'handleonlinepay'])->name('dopay.online');
Route::get('/authorizenet/cardtype', [AuthorizenetController::class, 'cardType'])->name('authorizenet.cardtype');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

//Blog Section
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'all_blog')->name('blog');
    Route::get('/blog/{slug}', 'blog_details')->name('blog.details');
});

Route::controller(PageController::class)->group(function () {
    //mobile app balnk page for webview
    Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');

    //Custom page
    Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});
