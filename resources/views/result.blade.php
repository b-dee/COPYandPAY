@extends('page')
@section('title', $result)

@section('nav')
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/logout') }}">Sign out</a></li>
@endsection

@section('content')
  <h1 class="pb-4">{{ $result }}</h1>
@endsection

@section('scripts')
  @if ($isPayment)
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $checkoutId }}"></script>
  @endif
@endsection
