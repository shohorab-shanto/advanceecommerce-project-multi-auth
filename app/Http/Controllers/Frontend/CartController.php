<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\ShipDivision;
use Illuminate\Support\Facades\Session;



class CartController extends Controller
{
    public function AddToCart(Request $request, $id){
        if(Session::has('coupon')){ //if session has data previous data wil be forget
            Session::forget('coupon');
        }
        $product = Product::findOrFail($id);
        if($product->discount_price == NULL){
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,

                ],
            ]);

            return response()->json(['success' => 'Successfully Added on Your Cart']);

        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,

                ],
            ]);

            return response()->json(['success' => 'Successfully Added on Your Cart']); //response e data json akare joma hoy

        }
    }//end method

    //mini cart section
    public function AddMiniCart(){
        $carts = Cart::content();
        $cartQty = Cart::count(); //count how many cart i have
        $cartTotal = Cart::total(); //cart er price auto niea nibe ei total method er maddhome
        //all things pass by json array
        return response()->json(array( //response thke json data array hisebe pass korteci
            'carts' => $carts,
            'cartQty' => $cartQty, //how much cart added
            'cartTotal' => round($cartTotal), //for tatla amount
        ));

    }//end method

    public function RemoveMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'product Remove from Cart']);
    }//end method

    //add to wishlist method
    public function AddToWishlist(Request $request, $product_id){
        //checking user login or not
        if(Auth::check()){
            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first(); //auth user id r requested user id mille data anbe and oi data exist e rakhbe
            //if product not exist in database then insert//$exist check product is aviable or not
            if(!$exists){
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),
                ]);

                return response()->json(['success' => 'Wishlist Added Successfully']);
            }else{
                return response()->json(['error' => 'This Product has Already on Your Wishlist']);
            }


        }else{
            return response()->json(['error' => 'At First Login Your Account']);
        }

    }//end

    //////////==============coupon=================
    //by this model we wil get all data from coupon model
    public function CouponApply(Request $request){
        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first(); //database coupon will match requested coupon//
        if($coupon){
            Session::put('coupon',[ //sb value coupon e rakha hobe
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)   //total - discount = total amount

            ]);

            return response()->json(array(
                'validity' => true,
                'success' => 'coupon Applied Successfully'
            ));

        }else{
            return response()->json(['error' => 'Invalid Coupon']);
        }

    }//end method

    public function CouponCalculation(){
        if(Session::has('coupon')){
            return response()->json(array( //get and passing all data couponCalculation() function//jdi coupon take tahle ei data pass korbe
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],

            ));
        }else{

            return response()->json(array( //jdi coupon na thtke tahle ei data pass korbe
                'total' => Cart::total(),
            ));
        }

    }//end method

    //remove coupon
    public function CouponRemove(){
        Session::forget('coupon');
        return response()->json(['success' =>"Coupon Remove Successfully"]);
    }//end method

    //=======checkout=====================
    public function CheckoutCreate(){
        if(Auth::check()){ //check this user is login or not
            if(Cart::total() > 0){ //cart page has product it will take view page
                //view page e ei data gula pathano lagbe
                $carts = Cart::content();
                $cartQty = Cart::count();
                $cartTotal = Cart::total();

                $divisions = ShipDivision::orderBy('division_name','ASC')->get();
                return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','divisions'));

            }else{
                $notification = array(
                    'message' =>  'Shopping At list One Product',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification); //if cart page empty it wll return to home page
            }

        }else{

            $notification = array(
                'message' =>  'You need to login first',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }

    }//end method





}
