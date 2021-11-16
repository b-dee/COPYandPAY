@extends('page')
@section('title', 'Pay')

@section('nav')
  <ul>
    <li><a href="{{ url('/pay') }}">Make a payment</a></li>
    <li><a href="{{ url('/logout') }}">Sign out</a></li>
  </ul>
@endsection

@section('content')
  <h1>Make a payment</h1>
  <p>Please enter your payment details below.</p>
  @if ($isPayment)
    {{-- Payment form --}}
    <form action="{{ url("/pay/{$checkoutId}/result") }}" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
  @else
    {{-- Preparation form --}}
    <form action="{{ url('/pay') }}" method="POST">
      @csrf
      <label for="amount">Amount:</label>
      <br>
      <input type="number" name="amount" id="amount" min="0" step="0.01">
      <br>
      <label for="reference">Reference:</label>
      <br>
      <input type="text" name="reference" id="reference">
      <br>
      <br>
      <input type="submit" value="Continue">
    </form>
  @endif
@endsection


@section('scripts')
  @if ($isPayment)
    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $checkoutId }}"></script>
  @endif
@endsection
