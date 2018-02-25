@extends('layouts.public')

@section('title') @endsection

@section('content')
  <div class="container">
    <div class="p-3 mb-4 variable-heading border-bottom bg-warning">
      <i class="float-left m-1 mr-3 fa-2x fas fa-fw fa-history"></i>
      <span class="countdown" data-time="{{ (new \Carbon\Carbon($testUnit->expires_on, config('app.timezone')))->toIso8601String() }}"></span> rimanenti<br>
      <small>Richiesta in scadenza</small>
      <div class="clearfix"></div>
    </div>
    <div class="row mb-3">
      <div class="col-sm-4">
        <div class="slideshow">
          @foreach($testUnit->testOrder->product->images as $image)
          <div><img class="img-slideshow" src="{{ !empty($image) ? $image : '/images/package.svg' }}" alt="Immagine prodotto"></div>
          @endforeach
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="col-sm-8">
        <div id="content" class="bg-white border">
          <div class="p-4">
            <div class="row">
              <div class="col-sm-2">
                <fieldset class="form-group">
                  <label><b>Marca</b></label>
                  <input type="text" class="form-control-plaintext" readonly value="{{ $testUnit->testOrder->product->brand }}">
                </fieldset>
              </div>
              <div class="col-sm-10">
                <fieldset class="form-group">
                  <label><b>Nome prodotto</b></label>
                  <input type="text" class="form-control-plaintext" readonly value="{{ $testUnit->testOrder->product->title }}">
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <h1 class="pb-3 border-bottom">Istruzioni</h1>
    <div class="markdown">{{ $testUnit->instructions }}</div>
  </div>
@endsection
