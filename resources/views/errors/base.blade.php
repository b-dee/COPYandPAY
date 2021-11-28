@extends('page')
@section('title', $exception->getStatusCode())

@section('content')
  <h1 class="pb-4">{{ $exception->getStatusCode() }}</h1>
  <p class="lead">{{ $exception->getMessage() }}</p>
@endsection
