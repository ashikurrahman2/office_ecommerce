<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Get the current user
        $userId = auth()->user()->id ?? null;;
        $carts = Cart::where('user_id', $userId)->get();
        return view('frontend.view_cart', compact('carts'));
    }
    public function storeSelectedItems(Request $request)
    {
        $selectedItems = $request->input('selectedItems', []);
    
        if (empty($selectedItems)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }
    
        // Store in session
        session(['selected_cart_items' => $selectedItems]);
    
        return response()->json(['success' => true]);
    }


    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.'.get_setting('homepage_select').'.partials.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    // public function addToCart(Request $request)
    // {
    //     $carts = Cart::where('user_id', auth()->user()->id)->get();
       
    //     $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
    //     $product = Product::find($request->id);
        
    //     $carts = array();
        
    //     if($check_auction_in_cart && $product->auction_product == 0) {
    //         return array(
    //             'status' => 0,
    //             'cart_count' => count($carts),
    //             'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.removeAuctionProductFromCart')->render(),
    //             'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //         );
    //     }
        
    //     $quantity = $request['quantity'];

    //     if ($quantity < $product->min_qty) {
    //         return array(
    //             'status' => 0,
    //             'cart_count' => count($carts),
    //             'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
    //             'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //         );
    //     }

    //     //check the color enabled or disabled for the product
    //     $str = CartUtility::create_cart_variant($product, $request->all());
    //     $product_stock = $product->stocks->where('variant', $str)->first();

    //     $cart = Cart::firstOrNew([
    //         'variation' => $str,
    //         'user_id' => auth()->user()->id,
    //         'product_id' => $request['id']
    //     ]);

    //     if ($cart->exists && $product->digital == 0) {
    //         if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
    //             return array(
    //                 'status' => 0,
    //                 'cart_count' => count($carts),
    //                 'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.auctionProductAlredayAddedCart')->render(),
    //                 'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //             );
    //         }
    //         if ($product_stock->qty < $cart->quantity + $request['quantity']) {
    //             return array(
    //                 'status' => 0,
    //                 'cart_count' => count($carts),
    //                 'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.outOfStockCart')->render(),
    //                 'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //             );
    //         }
    //         $quantity = $cart->quantity + $request['quantity'];
    //     }

    //     $price = CartUtility::get_price($product, $product_stock, $request->quantity);
    //     $tax = CartUtility::tax_calculation($product, $price);
        
    //     CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);
        
    //     $carts = Cart::where('user_id', auth()->user()->id)->get();
    //     return array(
    //         'status' => 1,
    //         'cart_count' => count($carts),
    //         'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.addedToCart', compact('product', 'cart'))->render(),
    //         'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    //     );
    // }

   
    public function addToCart(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.itemId' => 'required|string',
            'items.*.itemTitle' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1', // Quantity must be a positive integer
            'items.*.price' => 'required|numeric|min:0', // Price must be a positive number
            'items.*.vid' => 'nullable|string',
            'items.*.pid' => 'nullable|string',
            'items.*.attributes' => 'nullable|array',
            'items.*.image' => 'nullable|url', // Validate image URL
            'items.*.externalitemurl' => 'nullable|url',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
 
        // Get the current user
        $userId = auth()->user()->id;

        foreach ($request->items as $item) {
            // Extract relevant data
            $productId = $item['itemId'];
            $attributes = $item['attributes']; // Incoming attributes
            $vid = $item['vid'];
            $pid = $item['pid'];
            $quantity = $item['quantity'];
            $price = $item['price'];
        
            // Get all matching cart items for the user and product
            $existingCartItems = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('vid', $vid)
                ->where('pid', $pid)
                ->get();
        
            // Flag to check if a match was found
            $matched = false;
        
            foreach ($existingCartItems as $existingCartItem) {
                // Decode the JSON attributes from the database
                $cartAttributes = json_decode($existingCartItem->attributes, true);
        
                // Ensure both attribute sets are sorted by name for comparison
                $cartAttributes = array_column($cartAttributes, 'value', 'name');
                $incomingAttributes = array_column($attributes, 'value', 'name');
        
                // Compare the two attributes arrays
                if ($cartAttributes === $incomingAttributes) {
                    // Attributes matched
                    $matched = true;
        
                    // Update quantity and total price
                    $existingCartItem->quantity += $quantity;
                    $existingCartItem->price = $price;
                    $existingCartItem->save();

                    $message = 'Item updated successfully!';

                    Wishlist::where('user_id', $userId)->where('product_id', $productId)->delete();
                    break; // Exit loop after updating
                }
            }
        //dd($request->items);
            // If no match was found, create a new cart item
            if (!$matched) {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'product_title' => $item['itemTitle'],
                    'externalitemurl' => $item['ExternalItemUrl'],
                    'quantity' => $quantity,
                    'min_quantity' => $item['min_quantity'],
                    'price' => $price,
                    'product_weight' => $item['itemWeight'],
                    'vid' => $vid,
                    'pid' => $pid,
                    'attributes' => json_encode($attributes), // Store attributes as JSON
                    'image' => $item['image'],
                    'stock' => $item['stock'],
                ]);
                
                // Set success message for adding
                $message = 'Item added to cart successfully!';
                Wishlist::where('user_id', $userId)->where('product_id', $productId)->delete();
            }
        }
        

        // Get updated cart count
        $carts = Cart::where('user_id', $userId)->get();
       // dd($carts);
        return response()->json([
            // 'status' => 1,
            'message' => $message,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.addedToCart', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.' . get_setting('homepage_select') . '.partials.cart')->render(),
        ]);
    }


    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);
        
         // Check if the requested quantity meets the minimum quantity requirement
         if ($request->quantity >= $cartItem->min_quantity) {
            // Update the cart item quantity and price
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Quantity updated successfully.',
                'cartItem' => $cartItem
            ], 200);
        } else {
            // Return an error response if quantity is less than the minimum required
            return response()->json([
                'status' => 'error',
                'message' => 'Quantity must be at least ' . $cartItem->min_quantity . '.'
            ], 400);
        }
        
    }
}
