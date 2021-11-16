@extends('page')
@section('title', 'Home')

@section('nav')
  <ul>
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="{{ url('/register') }}">Sign up</a></li>
    <li><a href="{{ url('/login') }}">Sign in</a></li>
    <li><a href="{{ url('/pay') }}">Make a payment</a></li>
  </ul>
@endsection

@section('content')
  <h1>Welcome!</h1>
  <p>You'll need an account to continue.</p>
  <p>Please <a href="{{ url('/register') }}">Sign up</a> or <a href="{{ url('/login') }}">Sign in</a>.</p>
@endsection
