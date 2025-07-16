<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderCost;
use App\Models\Address;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\SmsTemplate;
use App\Models\ShippingCost;
 use App\Models\BusinessSetting;
use Mail;
use App\Models\RequestBuyShip;
use App\Utility\NotificationUtility;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }

    // All Orders
    public function all_orders(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type='';
       
     $orders = Order::join('combined_orders', 'orders.combined_order_id', '=', 'combined_orders.id')
   
    ->orderBy('orders.id', 'desc')
    ->select(
        'orders.*',
        'combined_orders.grand_total_without as total_amount',
        'combined_orders.grand_total as paid_amount',
        \DB::raw('(combined_orders.grand_total_without - combined_orders.grand_total) as due_amount')
    );

    if (Auth::user()->can('view_all_orders') && Auth::user()->can('view_inhouse_orders') && Auth::user()->can('view_seller_orders')) {
        // User has all three permissions; show all orders without filtering
        // No additional condition needed
    }  else {
        $orderTypes = [];
        if (Auth::user()->can('view_all_orders')) {
             $orderTypes[] = 'normal';
        }
        if (Auth::user()->can('view_inhouse_orders')) {
            $orderTypes[] = 'ship_for_me';
        }
    
        if (Auth::user()->can('view_seller_orders')) {
            $orderTypes[] = 'buy_ship';
        }
    
        if (!empty($orderTypes)) {
            $orders->whereIn('order_type', $orderTypes);
        }
    }


       
        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
         if ($request->order_type != null) {
            $orders = $orders->where('order_type', $request->order_type);
            $order_type = $request->order_type;
        }
        if ($date != null) {
            [$from, $to] = array_map(
            fn ($d) => date('Y-m-d', strtotime($d)),
            explode(' to ', $date)
        );

        $orders->whereBetween('orders.created_at', ["{$from} 00:00:00", "{$to} 23:59:59"]);
        }
         $orders = $orders->paginate(15);
        return view('backend.sales.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date','order_type'));
    }

    public function purchases(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type='';
       
       
       $orders = Order::join('combined_orders', 'orders.combined_order_id', '=', 'combined_orders.id')
        ->where('orders.status', 'order_placement')
        ->where('orders.payment_status', 'paid')
        ->orderBy('orders.id', 'desc')
        ->select(
            'orders.*',
            'combined_orders.grand_total_without as total_amount',
            'combined_orders.grand_total as paid_amount',
            \DB::raw('(combined_orders.grand_total_without - combined_orders.grand_total) as due_amount')
        );


        if (Auth::user()->can('view_all_orders') && Auth::user()->can('view_inhouse_orders') && Auth::user()->can('view_seller_orders')) {
            // User has all three permissions; show all orders without filtering
            // No additional condition needed
        }  else {
            $orderTypes = [];
            if (Auth::user()->can('view_all_orders')) {
                $orderTypes[] = 'normal';
            }
            if (Auth::user()->can('view_inhouse_orders')) {
                $orderTypes[] = 'ship_for_me';
            }
        
            if (Auth::user()->can('view_seller_orders')) {
                $orderTypes[] = 'buy_ship';
            }
        
            if (!empty($orderTypes)) {
                $orders->whereIn('order_type', $orderTypes);
            }
        }


       
        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
         if ($request->order_type != null) {
            $orders = $orders->where('order_type', $request->order_type);
            $order_type = $request->order_type;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
         $orders = $orders->paginate(15);
        return view('backend.sales.purchases', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date','order_type'));
    }
    public function Tracking(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type='';
       
       
       $orders = Order::join('combined_orders', 'orders.combined_order_id', '=', 'combined_orders.id')
        ->where('orders.status', 'purchase_complete')
        ->where('orders.payment_status', 'paid')
        ->orderBy('orders.id', 'desc')
        ->select(
            'orders.*',
            'combined_orders.grand_total_without as total_amount',
            'combined_orders.grand_total as paid_amount',
            \DB::raw('(combined_orders.grand_total_without - combined_orders.grand_total) as due_amount')
        );


        if (Auth::user()->can('view_all_orders') && Auth::user()->can('view_inhouse_orders') && Auth::user()->can('view_seller_orders')) {
            // User has all three permissions; show all orders without filtering
            // No additional condition needed
        }  else {
            $orderTypes = [];
            if (Auth::user()->can('view_all_orders')) {
                $orderTypes[] = 'normal';
            }
            if (Auth::user()->can('view_inhouse_orders')) {
                $orderTypes[] = 'ship_for_me';
            }
        
            if (Auth::user()->can('view_seller_orders')) {
                $orderTypes[] = 'buy_ship';
            }
        
            if (!empty($orderTypes)) {
                $orders->whereIn('order_type', $orderTypes);
            }
        }


       
        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
         if ($request->order_type != null) {
            $orders = $orders->where('order_type', $request->order_type);
            $order_type = $request->order_type;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
         $orders = $orders->paginate(15);
        return view('backend.sales.tracking', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date','order_type'));
    }

     public function Shorting(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type='';
       
       
       $orders = Order::join('combined_orders', 'orders.combined_order_id', '=', 'combined_orders.id')
        ->whereIn('orders.status', [
    'goods_received_in_china_warehouse',
    'receive_in_china_airport',
    'receive_in_bangladesh_airport'
])
        ->where('orders.payment_status', 'paid')
        ->orderBy('orders.id', 'desc')
        ->select(
            'orders.*',
            'combined_orders.grand_total_without as total_amount',
            'combined_orders.grand_total as paid_amount',
            \DB::raw('(combined_orders.grand_total_without - combined_orders.grand_total) as due_amount')
        );


        if (Auth::user()->can('view_all_orders') && Auth::user()->can('view_inhouse_orders') && Auth::user()->can('view_seller_orders')) {
            // User has all three permissions; show all orders without filtering
            // No additional condition needed
        }  else {
            $orderTypes = [];
            if (Auth::user()->can('view_all_orders')) {
                $orderTypes[] = 'normal';
            }
            if (Auth::user()->can('view_inhouse_orders')) {
                $orderTypes[] = 'ship_for_me';
            }
        
            if (Auth::user()->can('view_seller_orders')) {
                $orderTypes[] = 'buy_ship';
            }
        
            if (!empty($orderTypes)) {
                $orders->whereIn('order_type', $orderTypes);
            }
        }


       
        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
         if ($request->order_type != null) {
            $orders = $orders->where('order_type', $request->order_type);
            $order_type = $request->order_type;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
         $orders = $orders->paginate(15);
        return view('backend.sales.shorting', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date','order_type'));
    }

     public function Delivery(Request $request)
    {
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type='';
       
       
       $orders = Order::join('combined_orders', 'orders.combined_order_id', '=', 'combined_orders.id')
        ->where('orders.status', 'shorting_complete')
        ->where('orders.payment_status', 'paid')
        ->orderBy('orders.id', 'desc')
        ->select(
            'orders.*',
            'combined_orders.grand_total_without as total_amount',
            'combined_orders.grand_total as paid_amount',
            \DB::raw('(combined_orders.grand_total_without - combined_orders.grand_total) as due_amount')
        );


        if (Auth::user()->can('view_all_orders') && Auth::user()->can('view_inhouse_orders') && Auth::user()->can('view_seller_orders')) {
            // User has all three permissions; show all orders without filtering
            // No additional condition needed
        }  else {
            $orderTypes = [];
            if (Auth::user()->can('view_all_orders')) {
                $orderTypes[] = 'normal';
            }
            if (Auth::user()->can('view_inhouse_orders')) {
                $orderTypes[] = 'ship_for_me';
            }
        
            if (Auth::user()->can('view_seller_orders')) {
                $orderTypes[] = 'buy_ship';
            }
        
            if (!empty($orderTypes)) {
                $orders->whereIn('order_type', $orderTypes);
            }
        }


       
        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
         if ($request->order_type != null) {
            $orders = $orders->where('order_type', $request->order_type);
            $order_type = $request->order_type;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
         $orders = $orders->paginate(15);
        return view('backend.sales.delivery', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date','order_type'));
    }

    public function show($id)
    {
        
        $order = Order::findOrFail(decrypt($id));
        // dd( $order);
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys ='';

        $order->viewed = 1;
        $order->save();
        return view('backend.sales.show', compact('order', 'delivery_boys'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
          $selectedItems = session('selected_cart_items', []);
        if (!empty($selectedItems)) {
            $carts = Cart::where('user_id', Auth::user()->id)
                         ->whereIn('id', $selectedItems) // Filter by selected item IDs
                         ->get();
        } else {
            // If no items are selected, you can handle it accordingly
            $carts = Cart::where('user_id', Auth::user()->id)->get();
        }

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $address = Address::where('id', $carts[0]['address_id'])->first();

        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = Auth::user()->name;
            $shippingAddress['email']       = Auth::user()->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            if (isset($products[$cartItem->user_id])) {
                $product_ids = $products[$cartItem->user_id];
            }
            array_push($product_ids, $cartItem);
            $products[$cartItem->user_id] = $product_ids;
        }
     
        
        foreach ($products as $product) {
          // Get the last order with code starting with C-
            $lastOrder = Order::where('code', 'LIKE', 'C-%')->orderByDesc('id')->first();

            if ($lastOrder && preg_match('/C-(\d+)/', $lastOrder->code, $matches)) {
                $lastNumber = (int)$matches[1];
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1000; // Starting number
            }
           

            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = Auth::user()->id;
            $order->shipping_address = $combined_order->shipping_address;
            $order->additional_info = $request->additional_info;
            $order->payment_type = $request->payment_option;
            $order->order_type = 'normal';
            $order->status = 'order_placement';
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = 'C-' . $newNumber;
            $order->date = strtotime('now');
            $order->save();

            $subtotal = 0;
            $coupon_discount = 0;
            //Order Details Storing
            foreach ($product as $cartItem) {
                $subtotal += $cartItem->price * $cartItem->quantity;

                $coupon_discount += $cartItem['discount'];
                
                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $cartItem->user_id;
                $order_detail->product_id = $cartItem->product_id;
                $order_detail->product_url = $cartItem->externalitemurl;
                $order_detail->image = $cartItem->image;
                $order_detail->product_title = $cartItem->product_title;
                $order_detail->variation = json_decode($cartItem->attributes);
                $order_detail->price = $cartItem->price;
                $order_detail->quantity = $cartItem->quantity;
                $order_detail->save();
            }

            $order->grand_total = $subtotal;
            
            if ($product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }
            $combined_order->grand_total_without = $order->grand_total;
            $combined_order->grand_total = $combined_order->grand_total_without * 0.5;

            $order->save();
        }

        $combined_order->save();

       foreach ($combined_order->orders as $order) {
            dispatch(function () use ($order) {
                NotificationUtility::sendOrderPlacedNotification($order);
            })->afterResponse(); 
        }

        $request->session()->put('combined_order_id', $combined_order->id);
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delete();
            }
            $order->delete();
            
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function changeStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'order_id' => 'required|integer',
            'status' => 'required|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the order
            $order = Order::find($request->order_id);

            if ($order) {
              if ($request->status == 'goods_received_in_china_warehouse') {
                    $orderCost = OrderCost::where('order_id', $request->order_id)->first();

                    if ($orderCost) {
                         $orderCost->cn_warehouse_msg_sent_at = now();
                         $orderCost->cn_airport_message = now()->addDays(3);
                       
                        $orderCost->save();
                    }
                }
                $order->status = $request->status; // Assuming you have a 'status' column in your orders table
                
                if ($request->status == 'delivered') {
                   $order->payment_status='fullpaid' ;
                }
                $order->save();

                // Check if the order type is 'ship_for_me' and update related RequestProduct records
                

                // Commit the transaction if everything was successful
                DB::commit();
                 if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
                    try {
                       
                        dispatch(function () use ($order) {
                            SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
                        })->afterResponse(); 
                    } catch (\Exception $e) {
                        
                        \Log::error('SMS dispatch failed: '.$e->getMessage());
                    }
                }
                return response()->json(['success' => true, 'message' => 'Order status updated successfully.']);
            }

            // If the order is not found, throw an exception to rollback
            throw new \Exception('Order not found.');

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function update_delivery_status(Request $request)
    {
        return 1;
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }
public function storeOrderWeight(Request $request)
{
    $data = $request->validate([
        'order_id' => 'required|exists:orders,id',
        'weight'   => 'required|numeric|min:0',
    ]);

    // Find the OrderCost by order_id and update only the weight
    $orderCost = OrderCost::where('order_id', $data['order_id'])->first();

    if ($orderCost) {
        $orderCost->product_weight = $data['weight'];
        $orderCost->save();
    }
    $this->changeStatus(new Request([
        'order_id' => $data['order_id'],
        'status'   => 'shorting_complete',
    ]));
    return back()->with('success', 'Weight updated successfully.');
}

   

public function storeOrderCost(Request $request)
{
   
    $data = $request->validate([
        'order_id'             => 'required|exists:orders,id',
        'product_cost'         => 'required|numeric|min:0',
        'china_courier_charge' => 'nullable|numeric|min:0',
        'shipping_cost_per_kg' => 'nullable|numeric|min:0',
        'port'                 => 'required|in:guanzhou,hongkong',
    ]);

  
    $rmbRate = (float) BusinessSetting::where('type', 'rmb_rate')->value('value') ?: 0;

   
    $data['product_cost_tk']         = round($data['product_cost'] * $rmbRate, 2);
    $data['china_courier_charge_tk'] = isset($data['china_courier_charge'])
        ? round($data['china_courier_charge'] * $rmbRate, 2)
        : null;                     

   
    OrderCost::updateOrCreate(
        ['order_id' => $data['order_id']],
        $data
    );

   
    $this->changeStatus(new Request([
        'order_id' => $data['order_id'],
        'status'   => 'purchase_complete',
    ]));

    return back()->with('success', 'Shipping cost saved successfully.');
}
   public function storeShippingCost(Request $request)
{//dd($request);
    $validated = $request->validate([
        'order_id' => 'required|exists:orders,id',
        'items' => 'required|array|min:1',
        'items.*.name' => 'required|string|max:255',
        'items.*.type' => 'required|in:variable,fixed,discount',
        'items.*.weight' => 'nullable|numeric|min:0',
        'items.*.cost_per_kg' => 'nullable|numeric|min:0',
        'items.*.total_cost' => 'required|numeric|min:0',
    ]);
//dd($validated['items']);
    // Remove old shipping cost records for the order
    ShippingCost::where('order_id', $validated['order_id'])->delete();

    // Process and save each item
    foreach ($validated['items'] as $item) {
        ShippingCost::create([
            'order_id' => $validated['order_id'],
            'name' => $item['name'],
            'type' => $item['type'],
            'weight' => $item['type'] === 'variable' ? $item['weight'] : 0,
            'cost_per_kg' => $item['type'] === 'variable' ? $item['cost_per_kg'] : 0,
            'total_shipping_cost' => $item['total_cost'],
        ]);
    }

    return redirect()->back()->with('success', 'Shipping costs saved successfully!');
}




    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();
        $request = new Request([
            'order_id' => $request->order_id,
            'status' => 'goods_received_in_china_warehouse',
        ]);

        $this->changeStatus($request);
        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        //sends Notifications to user
        // NotificationUtility::sendNotification($order, $request->status);
        // if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
        //     $request->device_token = $order->user->device_token;
        //     $request->title = "Order updated !";
        //     $status = str_replace("_", "", $order->payment_status);
        //     $request->text = " Your order {$order->code} has been {$status}";

        //     $request->type = "order";
        //     $request->id = $order->id;
        //     $request->user_id = $order->user->id;

        //     NotificationUtility::sendFirebaseNotification($request);
        // }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
           
            try {
                 dispatch(function () use ($order) {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
                 })->afterResponse(); 
            } catch (\Exception $e) {
            }
        }
        
        return 1;
    }

}
