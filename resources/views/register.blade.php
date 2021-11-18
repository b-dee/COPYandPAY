@extends('page')
@section('title', 'Sign up')

@section('nav')
  <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
  <li class="nav-item active"><a class="nav-link" href="{{ url('/register') }}">Sign up</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Sign in</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
@endsection

@section('content')
  <h1 class="pb-4">Sign up</h1>
  <form action="{{ url('/register') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group">
          <label for="name">Name:</label>
          <input class="form-control mt-2" type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}">
          @error('name')
            <small class="form-text text-danger">{{ $message }}</small>
          @enderror
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group">
          <label for="email">Email:</label>
          <input class="form-control mt-2" type="text" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
          @error('email')
            <small class="form-text text-danger">{{ $message }}</small>
          @enderror
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-6 col-lg-4">
        <div class="form-group">
          <label for="password">Password:</label>
          <input class="form-control mt-2" type="password" name="password" id="password" placeholder="Password">
          @error('password')
            <small class="form-text text-danger">{{ $message }}</small>
          @enderror
        </div>
      </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Sign up</button>
  </form>
@endsection
