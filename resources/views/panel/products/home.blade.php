@extends('layouts.panel')

@section('title')
Prodotti
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('products') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    <a href="{{ route('panel.products.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo prodotto
    </button></a>
    Prodotti
  </div>
  <div class="px-4 py-3">
    <a href="{{ route('panel.products.create') }}"><button type="button" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo prodotto
    </button></a>

  </div>
@endsection
