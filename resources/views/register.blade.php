@extends('page')
@section('title', 'Sign up')

@section('content')
  <form action="{{ url('/register') }}" method="POST">
    @csrf
    <label for="name">Name:</label>
    <br>
    <input type="text" name="name" id="name">
    <br>
    <label for="email">Email:</label>
    <br>
    <input type="text" name="email" id="email">
    <br>
    <label for="password">Password:</label>
    <br>
    <input type="password" name="password" id="password">
    <br>
    <br>
    <input type="submit" value="Sign up">
  </form>
@endsection
