@extends('page')
@section('title', 'Sign in')

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
