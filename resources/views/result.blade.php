@extends('page')
@section('title', "Payment {$resultMsg}")

@section('nav')
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay/history') }}">Payment history</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/logout') }}">Sign out</a></li>
@endsection

@section('content')
  <h1 class="pb-4">Payment {{ $resultMsg }}</h1>
  <p class="lead">Result code: {{ $resultCode }}</p>
  <p class="lead">Description: {{ $resultDesc }}</p>
  @if ($success)
    <p>Good news, you've paid! You can now <a href="{{ url('/pay') }}">Make another payment</a> or <a href="{{ url('/pay/history') }}">View payment history</a>.</p>  
  @else
    <p>Please try again later.</p>
  @endif
@endsection
