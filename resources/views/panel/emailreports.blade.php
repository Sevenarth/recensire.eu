@extends('layouts.panel')

@section('title') Reports email @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('emailreports') }}
@endsection

@section('content')
<div class="px-4 py-3 h3 border-bottom">
        Reports email
      </div>
      <div class="px-4 py-3">
          @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
          @endif
      </div>
    </div>
@endsection