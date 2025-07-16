<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Upload;
use App\Models\Product;
use App\Utility\CartUtility;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('orderDetails')->where('user_id', Auth::user()->id)->whereNot('status', 'delivered')->whereNotIn('order_type', ['ship_for_me'])->orderBy('code', 'desc')->paginate(10);
        return view('frontend.user.purchase_history', [
            'orders' => $orders,
            'filter' => 'all'
        ]);
    }
    public function delivered()
    {
        $orders = Order::with('orderDetails')->where('user_id', Auth::user()->id)->where('status', 'delivered')->orderBy('code', 'desc')->paginate(10);
        
        return view('frontend.user.purchase_history', [
            'orders' => $orders,
            'filter' => 'delivered'
        ]);
    }
   public function forwardParcel()
{
    $orders = Order::with('orderDetails')
        ->where('user_id', Auth::user()->id)
        ->where('payment_status', 'paid')
         ->whereNotIn('status', ['delivered', 'order_cancelled'])
        ->orderBy('code', 'desc')
        ->paginate(10);
      //dd($orders);
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
//dd($abroadCount,$bangladeshCount);
    // Return the view with necessary data
    return view('frontend.user.purchase_history', [
        'orders' => $orders,
        'filter' => 'forward-parcel',
        'abroadCount' => $abroadCount,
        'bangladeshCount' => $bangladeshCount
    ]);
}

    public function cancelled()
    {
        $orders = Order::with('orderDetails')->where('user_id', Auth::user()->id)->where('status', 'order_cancelled')->orderBy('code', 'desc')->paginate(10);
        return view('frontend.user.purchase_history', [
            'orders' => $orders,
            'filter' => 'cancelled'
        ]);
    }

    public function purchase_history_details($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->delivery_viewed = 1;
        $order->payment_status_viewed = 1;
        $order->save();

        return view('frontend.user.order_details_customer', compact('order'));
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function order_cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($order && ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')) {
            $order->delivery_status = 'cancelled';
            $order->save();

            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = 'cancelled';
                $orderDetail->save();
            }

            flash(translate('Order has been canceled successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }

        return back();
    }

    public function re_order($id)
    {
        $user_id = Auth::user()->id;

        // if Cart has auction product check
        $carts = Cart::where('user_id', $user_id)->get();
        foreach ($carts as $cartItem) {
            $cart_product = Product::where('id', $cartItem['product_id'])->first();
            if ($cart_product->auction_product == 1) {
                flash(translate('Remove auction product from cart to add products.'))->error();
                return back();
            }
        }

        $order = Order::findOrFail(decrypt($id));
        $success_msgs = [];
        $failed_msgs = [];
        $data['user_id'] = $user_id;
        foreach ($order->orderDetails as $key => $orderDetail) {
            $product = $orderDetail->product;

            if (
                !$product || $product->published == 0 ||
                $product->approved == 0 || ($product->wholesale_product && !addon_is_activated("wholesale"))
            ) {
                array_push($failed_msgs, translate('An item from this order is not available now.'));
                continue;
            }

            if ($product->auction_product == 0) {

                // If product min qty is greater then the ordered qty, then update the order qty 
                $order_qty = $orderDetail->quantity;
                if ($product->digital == 0 && $order_qty < $product->min_qty) {
                    $order_qty = $product->min_qty;
                }

                $cart = Cart::firstOrNew([
                    'variation' => $orderDetail->variation,
                    'user_id' => auth()->user()->id,
                    'product_id' => $product->id
                ]);

                $product_stock = $product->stocks->where('variant', $orderDetail->variation)->first();
                if ($product_stock) {
                    $quantity = 1;
                    if ($product->digital != 1) {
                        $quantity = $product_stock->qty;
                        if ($quantity > 0) {
                            if ($cart->exists) {
                                $order_qty = $cart->quantity + $order_qty;
                            }
                            //If order qty is greater then the product stock, set order qty = current product stock qty
                            $quantity = $quantity >= $order_qty ? $order_qty : $quantity;
                        } else {
                            array_push($failed_msgs, $product->getTranslation('name') . ' ' . translate(' is stock out.'));
                            continue;
                        }
                    }
                    $price = CartUtility::get_price($product, $product_stock, $quantity);
                    $tax = CartUtility::tax_calculation($product, $price);

                    CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);
                    array_push($success_msgs, $product->getTranslation('name') . ' ' . translate('added to cart.'));
                } else {
                    array_push($failed_msgs, $product->getTranslation('name') . ' ' . translate('is stock out.'));
                }
            } else {
                array_push($failed_msgs, translate('You can not re order an auction product.'));
                break;
            }
        }

        foreach ($failed_msgs as $msg) {
            flash($msg)->warning();
        }
        foreach ($success_msgs as $msg) {
            flash($msg)->success();
        }

        return redirect()->route('cart');
    }
}
