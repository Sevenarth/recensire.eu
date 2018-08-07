@extends('layouts.front')

@section('title') Contact us @endsection

@section('navbar')
<div>
    <a href="/" class="btn btn-info">Homepage</a>
    <a href="/posts" class="btn btn-info">Apply</a>
</div>
@endsection

@section('content')
  <div class="container">
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
@endsection

@section('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
  function onSubmit(token) {
    document.getElementById("contactus").submit();
  }
</script>
@endsection
