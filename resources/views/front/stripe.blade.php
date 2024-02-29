@extends('front.layouts.app')

@section('content')
    <section class="section-9 pt-4">
        <div class="container p-3 ">

            @if (session('flash_message_success'))
                <div class="alert alert-success">
                    {{ session('flash_message_success') }}
                </div>
            @elseif(session('flash_message_error'))
                <div class="alert alert-danger">
                    {{ session('flash_message_error') }}
                </div>
            @endif

            <form action="{{ route('front.stripe') }}" method="post" id="payment-form">
                @csrf
                <div class="form-row">
                    <b>Total Amount (in Dollars)</b>
                    <input readonly type="text" name="total_amount" placeholder="Enter Total Amount" class="form-control"
                        value="{{ number_format(request('grand_total'), 2) }}">
                    <b>Name</b>
                    <input type="text" name="name" placeholder="Enter Your Name" class="form-control"
                        value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                    <b>Card Number</b>
                    <div id="card-element" class="form-control"></div>
                </div>
                <button class="btn btn-primary" style="float:right;margin-top:10px;">Submit Payment</button>
                <div id="card-errors" role="alert"></div>
            </form>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
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
        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                console.log(result.token);

                if (result.error) {
                    var errorElement = document.getElementById("card-errors");
                    errorElement.textContent = result.error.message;
                } else {
                    // Add the token to the form data
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);

                    // Submit the form
                    form.submit();
                }
            });
        });
    </script>
@endsection
