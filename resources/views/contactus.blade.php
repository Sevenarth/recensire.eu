<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
     <script>
       function onSubmit(token) {
         document.getElementById("contactus").submit();
       }
     </script>
</head>
<body class="bg-white">
  <div class="container">
    <img src="/images/logo.svg" alt="logo" class="mx-4 mt-4 mb-2 img-fluid">
      <a href="{{ url('/') }}" class="btn btn-info float-right mx-4 mt-4 mb-2">Homepage</a>
      <div class="clearfix"></div>
      <div class="mx-4 mt-4 mb-2">
        <div class="h3 mb-1">
          Contact us
        </div><br>
        <div class="h5 mb-4">Fill the form below to contact us!</div>

        @if(session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
        @endif

        <form id="contactus" action="{{ route('postContactus') }}" method="post">
          @csrf
          <fieldset class="form-group">
            <label for="name">Your name</label>
            <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" required>
            @if($errors->has('name'))
            <div class="invalid-feedback">
            @foreach($errors->get('name') as $err)
              {{ $err }}<br>
            @endforeach
            </div>
            @endif
          </fieldset>
          <fieldset class="form-group">
            <label for="email">Your email address</label>
            <input type="email" name="email" placeholder="john.doe@example.com" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : ''}}" required>
            @if($errors->has('email'))
            <div class="invalid-feedback">
            @foreach($errors->get('email') as $err)
              {{ $err }}<br>
            @endforeach
            </div>
            @endif
          </fieldset>
          <fieldset class="form-group">
            <label for="content">Your question</label>
            <textarea name="content" style="min-height: 130px" class="form-control{{ $errors->has('content') ? ' is-invalid' : ''}}" required>{{ old('content') }}</textarea>
            @if($errors->has('content'))
            <div class="invalid-feedback">
            @foreach($errors->get('content') as $err)
              {{ $err }}<br>
            @endforeach
            </div>
            @endif
          </fieldset>

          <button type="submit" data-callback='onSubmit' data-sitekey="{{ config('app.recaptcha_public_key') }}" class="g-recaptcha my-2 btn btn-primary">Send question</button>
          @if($errors->has('g-recaptcha-response'))
          <div class="text-danger"><small>
          @foreach($errors->get('g-recaptcha-response') as $err)
            {{ $err }}<br>
          @endforeach</small>
          </div>
          @endif
        </form>
      </div>
  </body>
  </html>
