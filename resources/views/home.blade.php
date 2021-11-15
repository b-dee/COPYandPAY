@extends('page')
@section('title', 'Home')

@section('content')
  <h1>Welcome!</h1>
  <p>You'll need an account to continue.</p>
  <p>Please <a href="{{ url('/register') }}">Sign up</a> or <a href="{{ url('/login') }}">Sign in</a>.</p>
@endsection
