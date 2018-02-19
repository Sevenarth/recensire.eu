@extends('layouts.panel')

@section('title')
Nuovo prodotto
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('products.create') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    Nuovo prodotto
  </div>
  <div class="px-4 py-3">

  </div>
@endsection
