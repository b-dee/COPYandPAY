@extends('page')
@section('title', "Payment history")

@section('nav')
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
  <li class="nav-item active"><a class="nav-link" href="{{ url('/pay/history') }}">Payment history</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/logout') }}">Sign out</a></li>
@endsection

@section('content')
  <h1 class="pb-4">Payment history</h1>
  <p class="lead">Here are your recent transactions:</p>

  @foreach ($payments as $payment)
    <div class="card mt-3 mb-3">
      <div class="card-body">
        <h4 class="card-title">{{ $payment->amount }} {{ $payment->currency }}</h4>
        <p class="card-text">#{{ $payment->merchant_tx_id }}</p>
        <p class="card-text">{{ date("F j Y, g:i A", strtotime($payment->updated_at)) }}</p>
      </div>
    </div>
  @endforeach

@endsection
