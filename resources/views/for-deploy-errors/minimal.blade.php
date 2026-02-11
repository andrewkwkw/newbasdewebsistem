  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>@yield('title')</title>
      <link rel="stylesheet" href="{{ asset('css/errors.css') }}">
      <link rel="shortcut icon" href="{{asset('img/logo.webp')}}" type="image/x-icon">

  </head>

  <body>
      <div class="container">
        <div class="imgBox">
            <img src="{{ asset('img/icon_error.svg') }}" width="500" />
        </div>
        <span>
            <span>@yield('code')</span>
            <span>
                @yield('message')
            </span>
        </span>

      </div>
  </body>

  </html>
