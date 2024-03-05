@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form id="orderForm" name="orderForm" action="{{ route('front.processCheckout') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}
                                                            value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}
                                            </textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                                placeholder="State"
                                                value="{{ !empty($customerAddress) ? $customerAddress->state : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No."
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ $item->price * $item->qty }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Discount</strong></div>
                                    <div class="h6"><strong id="discount_value">${{ $discount }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong
                                            id="shippingAmount">${{ number_format($totalShippingCharge, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong
                                            id="grandTotal">${{ number_format($grandTotal, 2) }}</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code"
                                id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                        </div>

                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="m-2 mt-3" id="discount-response">
                                    <strong>{{ Session::get('code')->code }}</strong>
                                    <a class="btn btn-sm btn-danger m-2" id="remove-discount"><i
                                            class="fa fa-times"></i></a>
                                </div>
                            @endif
                        </div>

                        <div class="card payment-form ">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                            <div class="">
                                <input checked type="radio" name="payment_method" id="payment_method_one"
                                    value="cod">
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>

                            <div class="">
                                <input type="radio" name="payment_method" id="payment_method_two" value="stripe">
                                <label for="payment_method_two" class="form-check-label">Stripe</label>
                            </div>

                            <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                <b>Card Number</b>
                                <div id="card-element" class="form-control"></div>
                                <div id="card-errors" role="alert" class="m-2"></div>

                            </div>
                        </div>

                        <div class="pt-4">
                            <button class="btn-dark btn btn-block w-100" type="submit">Pay now</button>
                        </div>
                    </div>
                    <!-- CREDIT CARD FORM ENDS HERE -->
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#payment_method_one").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").addClass('d-none');
            }
        });

        $("#payment_method_two").click(function() {
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none');
            }
        });

        // Create a Stripe client.
        var stripe = Stripe(
            "pk_test_51OgRRpBEY16pz2drPI2JlDjK2e34M3pGcgl1g3nhJhrD9P5sq5h8jPEf9mLOxW7BRYjeaH3BcmQrvKQNElG1ScGY00ihwdSCKc"
        );

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {
            style: style
        });

        // Add an instance of the card Element into the card-element <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('orderForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            // Disable submit button to prevent double submission
            $('button[type="submit"]').prop('disabled', true);

            // Use the serializeArray function to gather form data
            var formData = $(this).serializeArray();

            // Perform Stripe token creation
            stripe.createToken(card).then(function(result) {
                console.log(result);

                if (result.error) {
                    var errorElement = document.getElementById("card-errors");
                    errorElement.textContent = result.error.message;

                    // Re-enable submit button on error
                    $('button[type="submit"]').prop('disabled', false);
                } else {
                    // Add the Stripe token to the form data
                    formData.push({
                        name: 'stripeToken',
                        value: result.token.id
                    });

                    // Make the AJAX request
                    $.ajax({
                        url: '{{ route('front.processCheckout') }}',
                        type: 'post',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            var errors = response.errors;

                            // Re-enable submit button after AJAX request
                            $('button[type="submit"]').prop('disabled', false);

                            if (response.status == false) {
                                if (errors.first_name) {
                                    $("#first_name").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.first_name);
                                } else {
                                    $("#first_name").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.last_name) {
                                    $("#last_name").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.last_name);
                                } else {
                                    $("#last_name").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.email) {
                                    $("#email").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.email);
                                } else {
                                    $("#email").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.country) {
                                    $("#country").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.country);
                                } else {
                                    $("#country").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.address) {
                                    $("#address").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.address);
                                } else {
                                    $("#address").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.city) {
                                    $("#city").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.city);
                                } else {
                                    $("#city").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.state) {
                                    $("#state").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.state);
                                } else {
                                    $("#state").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.zip) {
                                    $("#zip").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.zip);
                                } else {
                                    $("#zip").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                                if (errors.mobile) {
                                    $("#mobile").addClass('is-invalid')
                                        .siblings("p").addClass('invalid-feedback')
                                        .html(errors.mobile);
                                } else {
                                    $("#mobile").removeClass('is-invalid')
                                        .siblings("p").removeClass('invalid-feedback')
                                        .html('');
                                }

                            } else {
                                // Redirect to the 'thanks' page with the orderId
                                window.location.href = "{{ url('thanks/') }}/" + response
                                    .orderId;
                            }
                        }
                    });
                }
            });
        });

        $("#country").change(function() {
            $.ajax({
                url: '{{ route('front.getOrderSummery') }}',
                type: 'post',
                data: {
                    country_id: $(this).val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);

                    }
                }
            });
        });

        $("#apply-discount").click(function() {
            $.ajax({
                url: '{{ route('front.applyDiscount') }}',
                type: 'post',
                data: {
                    code: $("#discount_code").val(),
                    country_id: $("#country").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        $("#discount-response-wrapper").html("<span class='text-danger'>" + response
                            .message + "</span>");
                    }
                }
            });
        });

        $('body').on('click', "#remove-discount", function() {
            $.ajax({
                url: '{{ route('front.removeCoupon') }}',
                type: 'post',
                data: {
                    country_id: $("#country").val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $("#shippingAmount").html('$' + response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response").html('');
                        $("#discount_code").val('');
                    }
                }
            });
        });
    </script>
@endsection
