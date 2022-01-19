<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mail\OrderMail;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Carbon\Carbon;
use PDF;


class AllUserController extends Controller
{
    public function MyOrders(){
        $orders = Order::where('user_id',Auth::id())->orderBy('id','DESC')->get(); //login kora user er id er sathe order table er user id match kore oi er joto data ordr table e aca sob niea asbe
        return view('frontend.user.order.order_view',compact('orders'));
    }//end method

    public function OrdersDetails($order_id){
        $order = Order::where('id',$order_id)->where('user_id',Auth::id())->first(); //to get specific row data use first and order id and user id willmatch with our order table id
        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('frontend.user.order.order_details',compact('order','orderItems'));
     }

     //invoice download
     public function InvoiceDownload($order_id){
        $order = Order::where('id',$order_id)->where('user_id',Auth::id())->first(); //to get specific row data use first and order id and user id willmatch with our order table id
        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id','DESC')->get();

        $pdf = PDF::loadView('frontend.user.order.invoice',compact('order','orderItems'));
        return $pdf->download('invoice.pdf');

     }
}
