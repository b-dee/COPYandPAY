@extends('page')
@section('title', 'Pay')

@section('nav')
  <li class="nav-item active"><a class="nav-link" href="{{ url('/pay') }}">Make a payment</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/pay/history') }}">Payment history</a></li>
  <li class="nav-item"><a class="nav-link" href="{{ url('/logout') }}">Sign out</a></li>
@endsection

{{-- There is no particular reason I did this in one view. It could have been two separate views (like result). 
I was just playing with the new blade template extension I found for my editor! --}}

@section('content')
  <h1 class="pb-4">Make a payment</h1>
  <p class="lead">Please enter your payment details below.</p>
  @if ($isPayment)
    {{-- Payment form --}}
    <form action="{{ url("/pay/{$checkoutId}/result") }}" class="paymentWidgets" data-brands="VISA"></form>
  @else
    {{-- Preparation form --}}
    <form action="{{ url('/pay') }}" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="form-group">
            <label for="amount">Amount:</label>
            <input class="form-control mt-2" type="number" name="amount" id="amount" min="0" step="0.01" placeholder="Amount" value="{{ old('amount') }}">
            @error('amount')
              <small class="form-text text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="form-group">
            <label for="reference">Reference:</label>
            <input class="form-control mt-2" type="text" name="reference" id="reference" placeholder="Reference" value="{{ old('reference') }}">
            @error('reference')
              <small class="form-text text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
      </div>
      <br>
      <button type="submit" class="btn btn-primary">Continue</button>
    </form>
  @endif
@endsection

@section('scripts')
  @if ($isPayment)
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $checkoutId }}"></script>
  @endif
@endsection
