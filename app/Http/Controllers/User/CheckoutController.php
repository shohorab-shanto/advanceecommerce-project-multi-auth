<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function DistrictGetAjax($division_id){
        $ship =ShipDistrict::where('division_id',$division_id)->orderBy('district_name','ASC')->get();
        return json_encode($ship);
    }//end method

    public function StateGetAjax($district_id){
        $shipState =ShipState::where('district_id',$district_id)->orderBy('state_name','ASC')->get();
        return json_encode($shipState);
    }//end method

    public function CheckoutStore(Request $request){
        //dd($request->all());
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['post_code'] = $request->post_code;
        $data['division_id'] = $request->division_id;
        $data['district_id'] = $request->district_id;
        $data['state_id'] = $request->state_id;
        $data['notes'] = $request->notes;

        $cartTotal = Cart::total();
        $carts = Cart::content();
        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount'];
        }else{
            $total_amount = round(Cart::total());
        }

        if($request->payment_method == 'stripe'){
            return view('frontend.payment.stripe',compact('data','cartTotal'));
        }elseif($request->payment_method == 'sslHost'){
            return view('frontend.payment.hosted',compact('data','total_amount','carts'));
        }elseif($request->payment_method == 'sslEasy'){
            return view('frontend.payment.easy',compact('data','total_amount','carts'));
        }
        else{
            // return view('frontend.payment.cash',compact('data'));
        }
    }



}//end method
