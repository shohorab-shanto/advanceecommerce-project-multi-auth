@extends('frontend.main_master')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('title')
   SSL Hosted
@endsection

        <div class="breadcrumb">
            <div class="container">
                <div class="breadcrumb-inner">
                    <ul class="list-inline list-unstyled">
                        <li><a href="home.html">Home</a></li>
                        <li class='active'>Hosted Payment</li>
                    </ul>
                </div><!-- /.breadcrumb-inner -->
            </div><!-- /.container -->
        </div><!-- /.breadcrumb -->


        <div class="body-content">
            <div class="container">
                <div class="checkout-box ">

                    <div class="row">
                        <div class="col-md-4 order-md-2 mb-4">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Your cart</span>
                                <span class="badge badge-secondary badge-pill">{{ Cart::count() }}</span>
                            </h4>
                            <ul class="list-group mb-3">
                                @foreach ($carts as $item)

                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">{{ $item->name }}</h6>
                                        <small class="text-muted">{{ $item->options->color }}</small><br>
                                        <small class="text-muted">{{ $item->options->size }}</small>
                                    </div>
                                    <span class="text-muted">{{ $item->price }}</span>
                                </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total (BDT)</span>
                                    <strong>{{ $total_amount }} TK</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8 order-md-1">
                            <h4 class="mb-3">Billing address</h4>
                            <form action="{{ url('/pay') }}" method="POST" class="needs-validation">
                                <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                                <input type="hidden" value="{{ $total_amount }}" name="amount" id="total_amount" required/>
                                <input type="hidden" name="shipping_name" value="{{ $data['shipping_name'] }}">
                                <input type="hidden" name="shipping_email" value="{{ $data['shipping_email'] }}">
                                <input type="hidden" name="shipping_phone" value="{{ $data['shipping_phone'] }}">
                                <input type="hidden" name="post_code" value="{{ $data['post_code'] }}">
                                <input type="hidden" name="division_id" value="{{ $data['division_id'] }}">
                                <input type="hidden" name="district_id" value="{{ $data['district_id'] }}">
                                <input type="hidden" name="state_id" value="{{ $data['state_id'] }}">
                                <input type="hidden" name="notes" value="{{ $data['notes'] }}">

                                <div class="row">

                                </div>
                                <hr class="mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="same-address">

                                    <label class="custom-control-label" for="same-address">Shipping address is the same as my billing
                                        address</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="save-info">
                                    <label class="custom-control-label" for="save-info">Save this information for next time</label>
                                </div>
                                <hr class="mb-4">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay now</button>
                            </form>
                        </div>
                    </div><!-- /.row -->

                </div>
            </div>
        </div>




@endsection
