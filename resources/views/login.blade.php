@extends('page')
@section('title', 'Sign in')

@section('nav')
  <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Sign up</a></li>
  <li class="nav-item active"><a class="nav-link" href="{{ url('/login') }}">Sign in</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
@endsection

@section('content')
  <h1 class="pb-4">Sign in</h1>
  <form action="{{ url('/login') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group">
          <label for="email">Email:</label>
          <input class="form-control mt-2" type="text" name="email" id="email" placeholder="Email">
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group">
          <label for="password">Password:</label>
          <input class="form-control mt-2" type="password" name="password" id="password" placeholder="Password">
        </div>
      </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Sign in</button>
  </form>
@endsection
