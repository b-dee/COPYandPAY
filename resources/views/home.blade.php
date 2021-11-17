@extends('page')
@section('title', 'Home')

@section('nav')
  <li class="nav-item active"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Sign up</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Sign in</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
@endsection

@section('content')
  <h1 class="pb-4">Welcome!</h1>
  <p class="lead">You'll need an account to continue.</p>
  <p>Please <a href="{{ url('/register') }}">Sign up</a> or <a href="{{ url('/login') }}">Sign in</a>.</p>
@endsection
