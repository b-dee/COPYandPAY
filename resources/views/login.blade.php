@extends('page')
@section('title', 'Sign in')

@section('nav')
  <ul>
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="{{ url('/register') }}">Sign up</a></li>
    <li><a href="{{ url('/login') }}">Sign in</a></li>
    <li><a href="{{ url('/pay') }}">Make a payment</a></li>
  </ul>
@endsection

@section('content')
  <form action="{{ url('/login') }}" method="POST">
    @csrf
    <label for="email">Email:</label>
    <br>
    <input type="text" name="email" id="email">
    <br>
    <label for="password">Password:</label>
    <br>
    <input type="password" name="password" id="password">
    <br>
    <br>
    <input type="submit" value="Sign in">
  </form>
@endsection
