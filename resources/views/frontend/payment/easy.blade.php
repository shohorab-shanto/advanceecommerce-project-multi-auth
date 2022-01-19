@extends('frontend.main_master')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('title')
   SSL Easy
@endsection

        <div class="breadcrumb">
            <div class="container">
                <div class="breadcrumb-inner">
                    <ul class="list-inline list-unstyled">
                        <li><a href="home.html">Home</a></li>
                        <li class='active'>Easy Payment</li>
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
                            <form method="POST" class="needs-validation" novalidate>

                                <input type="hidden" value="{{ $total_amount }}" name="amount" id="total_amount" required/>
                                <input type="hidden" name="post_code" value="{{ $data['post_code'] }}" id="post_code">
                                <input type="hidden" name="division_id" value="{{ $data['division_id'] }}" id="division_id">
                                <input type="hidden" name="district_id" value="{{ $data['district_id'] }}" id="district_id">
                                <input type="hidden" name="state_id" value="{{ $data['state_id'] }}" id="state_id">
                                <input type="hidden" name="notes" value="{{ $data['notes'] }}" id="notes">

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="firstName">Full name</label>
                                        <input type="text" name="customer_name" disabled value="{{ $data['shipping_name'] }}" class="form-control" id="customer_name" placeholder=""
                                               value="John Doe" required>
                                        <div class="invalid-feedback">
                                            Valid customer name is required.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="mobile">Mobile</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+88</span>
                                        </div>
                                        <input type="text" name="customer_mobile" disabled value="{{ $data['shipping_phone'] }}" class="form-control" id="mobile" placeholder="Mobile"
                                              required>
                                        <div class="invalid-feedback" style="width: 100%;">
                                            Your Mobile number is required.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email">Email <span class="text-muted">(Optional)</span></label>
                                    <input type="email" name="customer_email" disabled value="{{ $data['shipping_email'] }}" class="form-control" id="email"
                                           placeholder="you@example.com"  required>
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
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
                                <button class="btn btn-primary btn-lg btn-block" id="sslczPayBtn"
                                        token="if you have any token validation"
                                        postdata="your javascript arrays or objects which requires in backend"
                                        order="If you already have the transaction generated for current order"
                                        endpoint="{{ url('/pay-via-ajax') }}"> Pay Now
                                </button>
                            </form>
                        </div>
                    </div><!-- /.row -->

                </div>
            </div>
        </div>

<script>
    var obj = {};
    obj.cus_name = $('#customer_name').val();
    obj.cus_phone = $('#mobile').val();
    obj.cus_email = $('#email').val();
    obj.amount = $('#total_amount').val();
    obj.post_code = $('#post_code').val();
    obj.division_id = $('#division_id').val();
    obj.district_id = $('#district_id').val();
    obj.state_id = $('#state_id').val();
    obj.notes = $('#notes').val();

    $('#sslczPayBtn').prop('postdata', obj);

    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            // script.src = "https://seamless-epay.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7); // USE THIS FOR LIVE
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7); // USE THIS FOR SANDBOX
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>

<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>


@endsection
