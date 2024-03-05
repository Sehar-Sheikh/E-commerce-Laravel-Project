@extends('front.layouts.app')
@section('content')
    <section class="container">
        <div class="col-md-12 text-center pt-5">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <img class="m-4" src="{{ asset('/front-assets/images/tick.png') }}" style="width:7%" />
            <h1>
                Thank you for shopping!
            </h1>
            <p class="pt-4">Your order ID is: {{ $id }}</p>
        </div>
    </section>
@endsection
