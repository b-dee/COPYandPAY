<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap.min.css') }}">
  <title>@yield('title') | COPYandPAY</title>
</head>
<body>
  <header>
    <h1>COPYandPAY</h1>
    <nav>
      @yield('nav')
    </nav>
    <hr>
    <br>
  </header>
  <main>
    @yield('content')
  </main>
  @yield('scripts')
</body>
</html>
