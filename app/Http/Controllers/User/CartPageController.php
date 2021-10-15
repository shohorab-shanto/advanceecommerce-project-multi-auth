<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CartPageController extends Controller
{
    public function MyCart(){
        return view('frontend.wishlist.view_mycart');
    }

    public function GetCartProduct(){
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

    public function RemoveCartProduct($rowId){
        Cart::remove($rowId);
        if(Session::has('coupon')){  //if session has data previous data wil be forget
            Session::forget('coupon');
        }

        return response()->json(['success' => 'Successfully cart Remove']);

    }// end method

    public function CartIncrement($rowId){
        $row = Cart::get($rowId); //rowId dhore oi id er cart ene row te rakhlam
        Cart::update($rowId, $row->qty + 1); //oi cart er qty field e 1 add kore update korlam

        if(Session::has('coupon')){ //jodi session e kono data thke  //first we have to get coupon name and coupon
            $coupon_name = Session::get('coupon')['coupon_name']; //getting name from session
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();//getting data from coupon table
            Session::put('coupon',[ //sb value coupon e rakha hobe
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)   //total - discount = total amount

            ]);

        }

        return response()->json('increment');
    }//end method

    public function CartDecrement($rowId){
        $row = Cart::get($rowId); //rowId dhore oi id er cart ene row te rakhlam
        Cart::update($rowId, $row->qty - 1); //oi cart er qty field e 1 add kore update korlam

        if(Session::has('coupon')){ //jodi session e kono data thke  //first we have to get coupon name and coupon
            $coupon_name = Session::get('coupon')['coupon_name']; //getting name from session
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();//getting data from coupon table
            Session::put('coupon',[ //sb value coupon e rakha hobe
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)   //total - discount = total amount

            ]);

        }


        return response()->json('decrement');
    }//end method



}
