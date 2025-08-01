<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CommissionHistory;
use App\Models\Wallet;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Search;
use App\Models\Shop;
use App\Models\Order;
use Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:in_house_product_sale_report'])->only('in_house_sale_report');
        $this->middleware(['permission:seller_products_sale_report'])->only('seller_sale_report');
        $this->middleware(['permission:products_stock_report'])->only('stock_report');
        $this->middleware(['permission:product_wishlist_report'])->only('wish_report');
        $this->middleware(['permission:user_search_report'])->only('user_search_report');
        $this->middleware(['permission:commission_history_report'])->only('commission_history');
        $this->middleware(['permission:wallet_transaction_report'])->only('wallet_transaction_history');
    }


public function index(Request $request)
{
    $date            = $request->date;
    $sort_search     = $request->search;
    $delivery_status = $request->delivery_status ?? null;
    $payment_status  = $request->payment_status  ?? null;
    $order_type      = $request->order_type      ?? null;

  
    $orders = Order::query()->where('status','delivered')
        ->leftJoin('order_costs', 'order_costs.order_id', '=', 'orders.id')
        ->orderByDesc('orders.id')
        ->select([
            'orders.*',
            'order_costs.product_cost_tk   AS total_amount_cost',
            'order_costs.china_courier_charge_tk AS china_courier_charge',
            DB::raw('(order_costs.shipping_cost_per_kg * order_costs.product_weight) AS shipping_cost'),
        ]);

    
    if ($sort_search) {
        $orders->where('orders.code', 'like', "%{$sort_search}%");
    }

    if ($date) {
        [$from, $to] = array_map(
            fn ($d) => date('Y-m-d', strtotime($d)),
            explode(' to ', $date)
        );

        $orders->whereBetween('orders.created_at', ["{$from} 00:00:00", "{$to} 23:59:59"]);
    }

    if ($delivery_status) {
        $orders->where('orders.delivery_status', $delivery_status);
    }

    if ($payment_status) {
        $orders->where('orders.payment_status', $payment_status);
    }

    if ($order_type) {
        $orders->where('orders.order_type', $order_type);
    }

  
    $orders = $orders->paginate(15)->withQueryString();

   
    return view(
        'backend.reports.index',
        compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date', 'order_type')
    );
}



    public function stock_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products', 'sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products', 'sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by = null;
        // $sellers = User::where('user_type', 'seller')->orderBy('created_at', 'desc');
        $sellers = Shop::with('user')->orderBy('created_at', 'desc');
        if ($request->has('verification_status')) {
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers', 'sort_by'));
    }

    // public function wish_report(Request $request)
    // {
    //     $sort_by = null;
    //     $products = Wishlist::orderBy('created_at', 'desc');
    //     if ($request->has('category_id')) {
    //         $sort_by = $request->category_id;
    //         $products = $products->where('category_id', $sort_by);
    //     }
    //     $products = $products->paginate(10);
    //     return view('backend.reports.wish_report', compact('products', 'sort_by'));
    // }
public function wish_report(Request $request)
{
    $products = Wishlist::selectRaw('product_title, image, COUNT(*) as count')
        ->groupBy('product_title', 'image') // Include 'image' in the groupBy
        ->orderBy('count', 'desc') // Order by count
        ->paginate(10); // Paginate the results

    return view('backend.reports.wish_report', compact('products'));
}



    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request)
    {
        $seller_id = null;
        $date_range = null;

        if (Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        }
        if ($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id) {

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if (Auth::user()->user_type == 'seller') {
            return view('seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }

    public function wallet_transaction_history(Request $request)
    {
        $user_id = null;
        $date_range = null;

        if ($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function ($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id) {
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }
}
