<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\OrderMail;
use Illuminate\Http\Request;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class StripeController extends Controller
{
    public function StripeOrder(Request $request){

        if(Session::has('coupon')){
            $total_amount = Session::get('coupon')['total_amount'];
        }else{
            $total_amount = round(Cart::total());
        }

        //below part copy from stripe storing page
        \Stripe\Stripe::setApiKey('sk_test_51JXs2KKELHe5oxAKbZa6c393ZoyLtfDJBKw63bqBH3xfZaeFooj5M6T1LbgjlgOFLgjs2jc2PYhkZvMUPcHc5qiY00dmkn0eFY');

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
        'amount' => $total_amount*100, //comming from above dynamic
        'currency' => 'usd',
        'description' => 'Online Store charge',
        'source' => $token,
        'metadata' => ['order_id' => uniqid()], //by unqid it will generate auto a random number
        ]);
        // we have to insert this data two different table order table and order item tabel
        // dd($charge);
        //stripe page thke hidden input field er maddhome sob $request data ana hoice

        $order_id = Order::insertGetId([ //data table e insert kore id ta ei variable e rakhbe
            'user_id' => Auth::id(),
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'state_id' => $request->state_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'post_code' => $request->post_code,
            'notes' => $request->notes,

            'payment_type' => 'stripe',
            'payment_method' => 'stripe',
            'payment_type' => $charge->payment_method,
            'transaction_id' => $charge->balance_transaction,
            'currency' => $charge->currency,
            'amount' => $total_amount,
            'order_number' => $charge->metadata->order_id,

            'invoice_no' => 'EOS'.mt_rand(000000001,99999999), //default number
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'status' => 'Pending',
            'created_at' => Carbon::now(),

        ]);

        //start send mail
        $invoice = Order::findOrFail($order_id);
        $data = [
            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,

        ];

        Mail::to($request->email)->send(new OrderMail($data)); //mail jabe requested mail e and data object er maddhome OrderMail e pass korlam

        //end send mail

        $carts = Cart::content();
        foreach($carts as $cart){
            OrderItem::insert([
                'order_id' =>$order_id,
                'product_id' => $cart->id,
                'color' => $cart->options->color,
                'size' => $cart->options->size,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' => Carbon::now(),
            ]);
        }

        //when payment is done coupon session and cart will  be destroy

        if(Session::has('coupon')){
            Session::forget('coupon');
        }
        Cart::destroy();

        $notification = array(
            'message' =>  'Your Order Place Sucessfuly',
            'alert-type' => 'success'
        );
        return redirect()->route('dashboard')->with($notification);


    }//end method
}
