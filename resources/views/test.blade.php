@extends('layouts.public')

@section('title') Invito di test @endsection

@section('content')
  @if(\Carbon\Carbon::now() > new \Carbon\Carbon($testUnit->starts_on, config('app.timezone')))
  <div id="cont" class="container" data-open="true">
    <div class="alert alert-success p-4 h5">
      <b>Congratulazioni <b>{{ !empty($testUnit->tester) ? $testUnit->tester->name : '-' }}</b>!</b> Sei stato invitato a testare un prodotto da recensire, da uno dei nostri negozi affiliati! Segui le istruzioni e completa il modulo per accettare l'invito.
    </div>
    <div class="p-3 mb-4 variable-heading border-bottom bg-warning">
      <i class="float-left m-1 mr-3 fa-2x fas fa-fw fa-history"></i>
      <span class="countdown" data-time="{{ (new \Carbon\Carbon($testUnit->expires_on, config('app.timezone')))->toIso8601String() }}"></span> rimanenti<br>
      <small>Invito in scadenza</small>
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
              <div class="col-sm-6">
                <fieldset class="form-group">
                  <label><b>Tipo di rimborso</b></label>
                  <input type="text" class="form-control-plaintext" readonly value="{{ config('testUnit.refundingTypes')[$testUnit->refunding_type] }}">
                </fieldset>
              </div>
              <div class="col-sm-6">
                <fieldset class="form-group">
                  <label><b>Nome tester</b></label>
                  <input type="text" class="form-control-plaintext" readonly value="{{ !empty($testUnit->tester) ?$testUnit->tester->name : '-' }}">
                </fieldset>
              </div>
            </div>
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
                <div class="p-2">{{ $testUnit->testOrder->product->title }}</div>
              </fieldset>
            </div>
          </div>
          <a id="amazon-link" href="{{ route('tests.go', $testUnit->hash_code) }}" class="d-none btn btn-primary my-2" target="_blank"><i class="fa fa-fw fa-external-link-alt"></i> Vai alla ricerca di Amazon</a>

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-7 mb-3">
        <div id="content" class="p-4 bg-white border">
          <h1 class="pb-3 mb-3 border-bottom">Istruzioni</h1>
          <div id="instructions" class="markdown">{{ $testUnit->instructions }}</div>
        </div>
      </div>
      <div class="col-md-5">
          <div id="content" class="p-4 bg-white border">
            <h3 class="pb-3 mb-3 border-bottom">Accettazione</h3>
            @openForm('tests.accept', 'patch', arg='testUnit->hash_code')
              <div class="alert alert-warning">
                Assicurati di inserire i dati corretti! Non sarai in grado di applicare le modifiche dopo!
              </div>
              @formTextfield('amazon_order_id', 'Numero ordine Amazon', placeholder="XXX-XXXXXXX-XXXXXXX")
              @formTextfield('paypal_account', 'Account PayPal', placeholder="me@tester.com")
              <fieldset class="form-group">
                <label for="tester_notes">Note aggiuntive <small class="text-muted">(opzionale)</small></label>
                <textarea id="tester_notes" class="form-control{{ $errors->has('tester_notes') ? ' is-invalid' : '' }}" rows="4" name="tester_notes"></textarea>
                @if($errors->has('tester_notes'))
                <div class="invalid-feedback">
                  @foreach($errors->get('tester_notes') as $err)
                    {{$err}}<br>
                  @endforeach
                </div>
              @endif
              </fieldset>
              <button type="submit" class="mt-2 btn btn-primary">Completa modulo</button>
            @closeForm
          </div>
      </div>
    </div>
  @else
  <div id="cont" class="container">
    <div class="p-3 mb-4 variable-heading border-bottom bg-success">
      <i class="float-left m-1 mr-3 fa-2x fas fa-fw fa-history"></i>
      <span class="countdown" data-time="{{ (new \Carbon\Carbon($testUnit->starts_on, config('app.timezone')))->toIso8601String() }}"></span> rimanenti<br>
      <small>all'apertura di questo test!</small>
      <div class="clearfix"></div>
    </div>
  @endif
    <hr>
    <small class="text-muted">
      Copyright &copy; {{ date("Y") }} {{ config('app.name', 'Sowia Arts') }}. Tutti i diritti sono riservati.
    </small>
  </div>
@endsection
