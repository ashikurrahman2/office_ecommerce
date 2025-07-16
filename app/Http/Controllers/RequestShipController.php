<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CombinedOrder;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderDetail;
use App\Models\RequestShip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utility\NotificationUtility;
use Illuminate\Support\Facades\DB;

class RequestShipController extends Controller
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
        $statusCounts = RequestShip::select(
            DB::raw("SUM(status = 'pending') as pending_count"),
            DB::raw("SUM(status = 'approved')  as approved_count"),
            DB::raw("SUM(status = 'cancelled') as rejected_count")
        )
        ->where('user_id', $userId)
        ->first();

        $pendingRequestProducts = RequestShip::where('user_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $approvedRequestProducts = RequestShip::where('user_id', $userId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $rejectedRequestProducts = RequestShip::where('user_id', $userId)
            ->where('status', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('frontend.user.ship-my-request', [
            'pendingRequestProducts' => $pendingRequestProducts,
            'approvedRequestProducts' => $approvedRequestProducts,
            'rejectedRequestProducts' => $rejectedRequestProducts,
            'statusCounts' => $statusCounts,
        ]);
        
    }

    public function forwardParcel()
    { $orders = Order::with('orderDetails')
        ->where('user_id', Auth::user()->id)
         //->where('payment_status', 'paid')
         ->where('order_type', 'ship_for_me')
         ->whereNotIn('status', ['delivered', 'order_cancelled'])
        ->orderBy('code', 'desc')
        ->paginate(10);

    $orders->each(function ($order) {
        if ($order->status === 'goods_received_in_bangladesh' ||$order->status === 'ready_to_deliver') {
            $order->filterStatus = 'abroad';
        } else {
            $order->filterStatus = 'bangladesh';
        }
    });
//dd($orders);
    $abroadCount = $orders->filter(function ($order) {
        return $order->filterStatus === 'abroad';
    })->count();

    $bangladeshCount = $orders->filter(function ($order) {
        return $order->filterStatus === 'bangladesh';
    })->count();
    return view('frontend.user.ship-my-request-forward-parcel', [
        'orders' => $orders,
        'filter' => 'forward-parcel',
        'abroadCount' => $abroadCount,
        'bangladeshCount' => $bangladeshCount
    ]);
        // $requestProducts = RequestShip::where('user_id', Auth::id())->where('order_status', 'shipment_done')->orderBy('id', 'asc')->paginate(20);
        // dd($requestProducts);
        // return view('frontend.user.ship-my-request-forward-parcel', compact('requestProducts'));
    }

    public function delivered()
    {
        $requestProducts = Order::with('orderDetails')->where('user_id', Auth::id())->where('status', 'delivered') ->where('order_type', 'ship_for_me')->orderBy('id', 'asc')->paginate(20);
        return view('frontend.user.ship-my-request-delivered', compact('requestProducts'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmin()
    {
        $requestProducts = RequestShip::whereNotIn('status', ['draft'])->orderBy('id', 'desc')->paginate(20);
        return view('backend.request_product.ship_for_me', compact('requestProducts'));
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
    public function store(Request $request){
//dd('come');
        // Validate the form data, including images
        $request->validate([
            'product_link' => 'nullable|url',
            'product_title' => 'required|string',
            'category_name' => 'required',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'ship_to' => 'required|date',
            'valid_to' => 'required|date',
            'weight' => 'required|numeric',
            'shipping_type' => 'required|string',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'images.*' => 'required|image|max:2048'
        ]);

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads/request_ships');
                $images[] = $path;
            }
        }

        // Create a new instance of RequestProduct
        $requestProduct = new RequestShip();

        // Set the properties using $request
        $requestProduct->user_id = Auth::id();
        $requestProduct->product_link = $request->product_link;
        $requestProduct->product_title = $request->product_title;
        $requestProduct->category_name = $request->category_name;
        $requestProduct->description = $request->description;
        $requestProduct->price = $request->price;
        $requestProduct->quantity = $request->quantity;
        $requestProduct->ship_to = $request->ship_to;
        $requestProduct->valid_to = $request->valid_to;
        $requestProduct->weight = $request->weight;
        $requestProduct->shipping_type = $request->shipping_type;
        $requestProduct->length = $request->length;
        $requestProduct->width = $request->width;
        $requestProduct->height = $request->height;
        $requestProduct->images = json_encode($images);
        $requestProduct->status = 'draft';
        $requestProduct->save();

        $draftedItems = RequestShip::where('user_id', Auth::id())->where('status', 'draft')->get();

        // Return a JSON response
        return response()->json([
            'message' => 'Product added successfully!',
            'data' => $draftedItems
        ]);
    
    }
 
    public function getDraftedItems()
    {
        $draftedItems = RequestShip::where('user_id', Auth::id())->where('status', 'draft')->get();
        if ($draftedItems->isEmpty()) {
            return redirect()->route('ship_for_me');
        }

        return response()->json($draftedItems);
    }
    public function draftedItemDelete(Request $request)
    {
        $draftedItem = RequestShip::find($request->id);
        $draftedItem->delete();
        return response()->json(['success' => true, 'message' => 'Product has been deleted.']);
    }

    public function draftedItemDuplicate(Request $request)
    {
        $itemData = $request->item;
        $duplicatedItem = RequestShip::create($itemData);
        return response()->json(['success' => true, 'message' => 'Product has been duplicated.!!', 'item' => $duplicatedItem]);
    }

    
    public function requestAllDraftedItems(Request $request)
    {
        $ids = $request->ids;
        RequestShip::whereIn('id', $ids)->update(['status' => 'pending']);

        return response()->json(['success' => true, 'message' => 'Request send successfully.!!', 'redirect_url' => route('ship_product_request.index')]);
    }

public function warehouseAdd (Request $request, $id)
{
    $request->validate([
        'warehouse_details' => 'required|string',
    ]);

    $requestProduct = RequestShip::find($id);

    if (!$requestProduct) {
        return response()->json(['error' => 'Product not found'], 404);
    }
//dd($requestProduct);
    $requestProduct->warehouse_details = $request->warehouse_details;
    $requestProduct->save();

    return response()->json(['message' => 'Warehouse_details added successfully!']);
}


   public function startShipment($id)
{
    $requestProduct = RequestShip::find($id);
//dd($requestProduct);
    // Find the user associated with the request product
    $user = User::find($requestProduct->user_id);
    
    $address = Address::where('user_id', $requestProduct->user_id)->first();
//dd($address);
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

    $combined_order = new CombinedOrder();
    $combined_order->user_id = $requestProduct->user_id; // Use $requestProduct->user_id instead of Auth::user()->id
    $combined_order->shipping_address = json_encode($shippingAddress);
    $combined_order->grand_total = $requestProduct->quantity * $requestProduct->price;
    $combined_order->save();

    $order = new Order;
    $order->combined_order_id = $combined_order->id;
    $order->user_id = $requestProduct->user_id; // Use $requestProduct->user_id instead of Auth::id()
    $order->shipping_address = $combined_order->shipping_address;
    $order->additional_info = $requestProduct->additional_info ?? null;
    $order->payment_type = $requestProduct->payment_option ?? null;
    $order->status = 'goods_received_in_china_warehouse';
    $order->delivery_viewed = '0';
    $order->payment_status_viewed = '0';
    $order->code = date('Ymd-His') . rand(10, 99);
    $order->date = strtotime('now');
    $order->grand_total = $requestProduct->weight * $requestProduct->price;
    $order->order_type = 'ship_for_me';
    $order->save();

    // Order Details Storing
    $order_detail = new OrderDetail();
    $order_detail->order_id = $order->id;
    $order_detail->seller_id = null;
    $order_detail->product_id = 'ship_for_me';
    $order_detail->image = null;
    $order_detail->images = json_encode($requestProduct->images);
    $order_detail->product_title = $requestProduct->product_title;
    $order_detail->product_url = $requestProduct->product_link;
    $order_detail->variation = null;
    $order_detail->price = $requestProduct->price;
    $order_detail->weight = $requestProduct->weight;
    $order_detail->quantity = $requestProduct->quantity;
    $order_detail->save();

    // $requestProduct->order_status = 'move_to_order'; 
    // $requestProduct->order_code = $order->code; 
    // $requestProduct->save(); 
    $requestProduct->delete();

    foreach($combined_order->orders as $order){
        NotificationUtility::sendOrderPlacedNotification($order);
    }

    session()->put('combined_order_id', $combined_order->id);
    return response()->json(['success' => true, 'message' => 'The shipping process has been started.']);
}

    public function cancelShipment($id)
    {
        $requestProduct = RequestShip::find($id);

        $requestProduct->order_status = 'shipment_cancelled'; 
        $requestProduct->status = 'cancelled'; 
        $requestProduct->save();

        return response()->json(['success' => true, 'message' => 'Shipment has been cancelled.']);
    }
    public function cancel(Request $request)
    {
        $requestProduct = RequestShip::find($request->id);
        if ($requestProduct) {
            $requestProduct->status = 'cancelled'; 
            $requestProduct->save();
            return response()->json(['success' => true, 'message' => 'Request has been canceled.']);
        }
        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }

    public function destroy(Request $request)
    {
        $requestProduct = RequestShip::find($request->id);
        if ($requestProduct) {
            $requestProduct->delete();
            return response()->json(['success' => true, 'message' => 'Request has been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }

   
    public function approveAdmin($id){
       
        $requestProduct = RequestShip::find($id);
        if ($requestProduct) {
       
            $requestProduct->status = 'approved';
            $requestProduct->save();
            $this->startShipment($id);
            return response()->json(['success' => true, 'message' => 'Successfully Approved.']);
        }
        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }

    public function cancelAdmin($id){
        $requestProduct = RequestShip::find($id);
        if ($requestProduct) {
            $requestProduct->status = 'cancelled'; 
            $requestProduct->save();
            return response()->json(['success' => true, 'message' => 'Successfully Canceled.']);
        }
        return response()->json(['success' => false, 'message' => 'Request not found.'], 404);
    }
  
}
