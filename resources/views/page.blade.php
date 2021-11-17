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
  <header class="bg-light">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">COPYandPAY</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            @yield('nav')
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <main>
    <div class="container pt-5">
      @yield('content')
    </div>
  </main>
  <script type="text/javascript" src="{{ asset('/bootstrap.bundle.min.js') }}"></script>
  @yield('scripts')
</body>
</html>
