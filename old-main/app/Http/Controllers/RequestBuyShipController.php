<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CombinedOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Models\RequestBuyShip;
use App\Utility\NotificationUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestBuyShipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();

        // Fetch counts for each status in a single query
        $statusCounts = RequestBuyShip::select(
            DB::raw("SUM(status = 'pending') as pending_count"),
            DB::raw("SUM(status = 'approved' AND (order_status NOT IN ('order_cancelled', 'order_placement', 'buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'goods_received_in_bangladesh', 'ready_to_deliver') OR order_status IS NULL)) as approved_count"),
            DB::raw("SUM(status = 'cancelled') as rejected_count")
        )
        ->where('user_id', $userId)
        ->first();

        // Fetch paginated data for each status
        $pendingRequestProducts = RequestBuyShip::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $approvedRequestProducts = RequestBuyShip::where('user_id', $userId)
            ->where('status', 'approved')
            ->where(function ($query) {
                $query->whereNotIn('order_status', ['order_cancelled', 'order_placement', 'buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'goods_received_in_bangladesh', 'ready_to_deliver'])
                      ->orWhereNull('order_status');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $rejectedRequestProducts = RequestBuyShip::where('user_id', $userId)
            ->where('status', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('frontend.user.buy-ship-request', [
            'pendingRequestProducts' => $pendingRequestProducts,
            'approvedRequestProducts' => $approvedRequestProducts,
            'rejectedRequestProducts' => $rejectedRequestProducts,
            'statusCounts' => $statusCounts,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
{
      $requestProducts = RequestBuyShip::join('users', 'request_buy_ships.user_id', '=', 'users.id')
        ->select('request_buy_ships.*', 'users.name', 'users.email','users.phone')  // Select the fields you need from users
        ->orderBy('request_buy_ships.id', 'desc')
        ->paginate(20);
   //dd($requestProducts);
    return view('backend.request_product.buy_ship_for_me', compact('requestProducts'));
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
        // Validate the form data
        $request->validate([
            'product_link' => 'required|string',
            'product_title' => 'nullable|string',
            'description' => 'required|string',
            'quantity' => 'required|integer',
        ]);
        
        // Create a new instance of RequestBuyShip
        $requestProduct = new RequestBuyShip();

        // Set the properties using $request
        $requestProduct->user_id = Auth::id();
        $requestProduct->product_link = $request->product_link;
        $requestProduct->product_title = $request->product_title;
        $requestProduct->description = $request->description;
        $requestProduct->quantity = $request->quantity;
        $requestProduct->status = 'pending';
        $requestProduct->save();

        // Return a JSON response for AJAX success with a redirect URL
        return response()->json([
            'success' => true,
            'message' => 'Your Product has been requested!',
            'redirect' => route('buy_ship_product_request.index')
        ]);
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function destroy($id)
{
    $requestProduct = RequestBuyShip::find($id);
    
    if (!$requestProduct) {
        return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
    }

    $requestProduct->delete();
    return response()->json(['success' => true, 'message' => 'Successfully deleted.']);
}

    public function startOrder($id)
    {
        $requestProduct = RequestBuyShip::find($id);
        //$user = User::find($requestProduct->user_id);
       $address = Address::where('user_id', Auth::user()->id)->first();

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

        $combined_order = new CombinedOrder();
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->grand_total = $requestProduct->quantity * $requestProduct->price;
        $combined_order->save();


        $order = new Order();
        $order->combined_order_id = $combined_order->id;
        $order->user_id = Auth::id();
        $order->shipping_address = $combined_order->shipping_address;
        $order->additional_info = $requestProduct->additional_info ?? null;
        $order->payment_type = $requestProduct->payment_option ?? null;
        $order->status = 'order_placement';
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-His') . rand(10, 99);
        $order->date = strtotime('now');
        $order->grand_total = $requestProduct->quantity * $requestProduct->price;
        $order->order_type = 'buy_ship';
        $order->save();
     
        //Order Details Storing
        $order_detail = new OrderDetail();
        $order_detail->order_id = $order->id;
        $order_detail->seller_id = null;
        $order_detail->product_id = 'buy_ship_for_me';
        $order_detail->image = null;
        $order_detail->images = json_encode($requestProduct->images);
        $order_detail->product_title = $requestProduct->product_title;
        $order_detail->product_url = $requestProduct->product_link;
        $order_detail->variation = null;
        $order_detail->price = $requestProduct->price;
        $order_detail->quantity = $requestProduct->quantity;
        $order_detail->save();
       
        $requestProduct->order_status = 'order_placement'; 
        $requestProduct->order_code =  $order->code; 
        $requestProduct->save();

        foreach($combined_order->orders as $order){
            NotificationUtility::sendOrderPlacedNotification($order);
        }
       
        session()->put('combined_order_id', $combined_order->id);
        return response()->json(['success' => true, 'message' => 'The shipping process has been started.']);
    }

    public function cancelOrder($id)
    {
        $requestProduct = RequestBuyShip::find($id);

        $requestProduct->order_status = 'order_cancelled'; 
        $requestProduct->status = 'cancelled'; 
        $requestProduct->save();

        return response()->json(['success' => true, 'message' => 'Order has been cancelled.']);
    }

    public function approveAdmin(Request $request, $id){
        $requestProduct = RequestBuyShip::find($id);
        if ($requestProduct) {
             

        $user = User::find($requestProduct->user_id);
        $requestProduct->price = $request->input('price');
        $requestProduct->quantity = $request->input('quantity');
        $address = Address::where('user_id', $requestProduct->user_id)->first();
       // dd( $requestProduct);
                $shippingAddress = [];
                if ($address != null) {
                    $shippingAddress['name']        = $user->name ?? ''; // Replace Auth::user()->name with $user->name
                            $shippingAddress['email']       = $user->email ?? ''; // Replace Auth::user()->email with $user->email
                            $shippingAddress['address']     = $address->address;
                            $shippingAddress['country']     = $address->country->name ?? '';
                            $shippingAddress['state']       = $address->state->name ?? '';
                            $shippingAddress['city']        = $address->city->name ?? '';
                            $shippingAddress['postal_code'] = $address->postal_code;
                            $shippingAddress['phone']       = $address->phone;
                            if ($address->latitude || $address->longitude) {
                                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
                            }
                }
         //dd( $user->id);
                $combined_order = new CombinedOrder();
                $combined_order->user_id = $requestProduct->user_id;
                $combined_order->shipping_address = json_encode($shippingAddress);
                $combined_order->grand_total = $requestProduct->quantity * $requestProduct->price;
                $combined_order->save();
        
      
                $order = new Order();
                $order->combined_order_id = $combined_order->id;
                $order->user_id = $requestProduct->user_id;
                $order->shipping_address = $combined_order->shipping_address;
                $order->additional_info = $requestProduct->additional_info ?? null;
                $order->payment_type = $requestProduct->payment_option ?? null;
                $order->status = 'order_placement';
                $order->delivery_viewed = '0';
                $order->payment_status_viewed = '0';
                $order->code = date('Ymd-His') . rand(10, 99);
                $order->date = strtotime('now');
                $order->grand_total = $requestProduct->quantity * $requestProduct->price;
                $order->order_type = 'buy_ship';
                $order->save();
             
                //Order Details Storing
                $order_detail = new OrderDetail();
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = null;
                $order_detail->product_id = 'buy_ship_for_me';
                $order_detail->image = null;
                $order_detail->images = json_encode($requestProduct->images);
                $order_detail->product_title = $requestProduct->product_title;
                $order_detail->product_url = $requestProduct->product_link;
                $order_detail->variation = null;
                $order_detail->price = $requestProduct->price;
                $order_detail->quantity = $requestProduct->quantity;
                $order_detail->save();
                
                $requestProduct->delete();
                foreach($combined_order->orders as $order){
                    NotificationUtility::sendOrderPlacedNotification($order);
                }
       
            return response()->json(['success' => true, 'message' => 'Successfully Approved.']);
        }
        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }
    
    public function cancelAdmin($id){
        $requestProduct = RequestBuyShip::find($id);
        if ($requestProduct) {
            $requestProduct->status = 'cancelled'; 
            $requestProduct->save();
            return response()->json(['success' => true, 'message' => 'Successfully Canceled.']);
        }
        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }
}
