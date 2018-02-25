@extends('layouts.panel')

@section('title')
@php if(!empty($testUnit->id)) echo 'Modifica unità di test #'.$testUnit->id; else echo 'Nuova unità di test'; @endphp
@endsection

@section('breadcrumb')
@php echo !empty($testUnit->id) ? Breadcrumbs::render('testUnits.edit', $testUnit) : Breadcrumbs::render('testUnits.create', $testUnit) @endphp
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @php echo !empty($testUnit->id) ? 'Modifica unità di test #'.$testUnit->hash_code : 'Nuova unità di test' @endphp
  </div>
  <div class="px-4 py-3">
    @if(!empty($testUnit->id))
    @openForm('panel.testOrders.testUnits.update', 'patch', arg="testUnit->id")
    @else
    <form action="{{ route('panel.testOrders.testUnits.put', $testUnit->testOrder->id) }}" method="post">
      @method('put')
      @csrf
    @endif
@foreach($errors->all() as $err) {{ $err}} <br> @endforeach
    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="tester_id">Tester</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#select-tester" type="button">Cerca</button>
            </div>
            <input type="hidden" value="{{ !empty($testUnit->id) ? $testUnit->tester->id : '' }}" id="tester-id" name="tester_id">
            <input class="form-control{{ $errors->has('tester_id') ? ' is-invalid' : '' }}" id="tester-name" type="text" value="{{ !empty($testUnit->id) ? ($testUnit->tester->name . " - " . $testUnit->tester->email) : '' }}" placeholder="Nessun tester selezionato" required readonly>
            @if($errors->has('seller_id'))
            <div class="invalid-feedback">
              @php foreach($errors->get('seller_id') as $error) echo $error . "<br>"; @endphp
            </div>
            @endif
          </div>
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label>Scadenza in <span class="text-muted">(dalla creazione)</span></label>
          <div class="input-group">
            <input type="number" min="1" step="1" class="form-control{{ $errors->has('expires_on_time') ? ' is-invalid' : '' }}" name="expires_on_time" value="{{ old('expires_on_time', !empty($testUnit->id) ? $testUnit->expires_on_time : '') }}" required>
            <select class="form-control{{ $errors->has('expires_on_space') ? ' is-invalid' : '' }}" name="expires_on_space" required>
              @foreach(config('testUnit.timeSpaces') as $id => $timeSpace)
                <option value="{{ $id }}" {{ $id == old('expires_on_space', !empty($testUnit->id) ? $testUnit->expires_on_space : null) ? 'selected' : '' }}>{{ $timeSpace }}</option>
              @endforeach
            </select>
            @foreach($errors->get('expires_on_time') as $err)
              {{ $err}}<br>
            @endforeach
            @foreach($errors->get('expires_on_space') as $err)
              {{ $err}}<br>
            @endforeach
          </div>
        </fieldset>
      </div>
    </div>
    @formTextfield('reference_url', 'Link base di ricerca', editMode="testUnit", placeholder="http://")
    @formTextfield('review_url', 'Collegamento alla recensione', editMode="testUnit", required="false", placeholder="http://")

    <div class="row">
      <div class="col-sm-6">
        @formTextfield('amazon_order_id', 'Numero di ordine Amazon', editMode="testUnit", required="false")
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="refunded_amount">Importo da rimborsare</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">&euro;</span>
            </div>
            <input class="form-control{{ $errors->has("fee") ? ' is-invalid' : '' }}" type="number" min="0.01" step="0.01" id="refunded_amount" name="refunded_amount" placeholder="25.00" value="{{ old('refunded_amount', !empty($testUnit->refunded_amount) ? $testUnit->refunded_amount : '') }}" required>
            @if($errors->has("refunded_amount"))
            <div class="invalid-feedback">
              @foreach($errors->get("refunded_amount") as $err)
                {{$err}}<br>
              @endforeach
            </div>
            @endif
          </div>
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        @formTextfield('paypal_account', 'Account PayPal', editMode="testUnit", placeholder="me@testers.com", required="false")
      </div>
      <div class="col-sm-6">
        <label for="status">Stato corrente</label>
        <select class="custom-select{{ $errors->has("status") ? ' is-invalid' : '' }}" name="status">
          @foreach(config('testUnit.statuses') as $id => $name)
            <option value="{{$id}}"{{ $id == old('status', $testUnit->status) ? ' selected' : ''}}>{{$name}}</option>
          @endforeach
        </select>
        @if($errors->has('status'))
        <div class="invalid-feedback">
          @foreach($errors->get('status') as $err)
            {{$err}}<br>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="refunded" name="refunded" {{ old('refunded', $testUnit->refunded) ? 'checked' : ''}}>
          <label class="custom-control-label" for="refunded">Rimborsato</label>
        </div>
      </div>
      <div class="col-sm-6">
        <label for="refunding_type">Metodo di rimborso</label>
        <select class="custom-select{{ $errors->has("status") ? ' is-invalid' : '' }}" name="refunding_type">
          @foreach(config('testUnit.refundingTypes') as $id => $name)
            <option value="{{$id}}"{{ $id == old('refunding_type', $testUnit->refunding_type) ? ' selected' : ''}}>{{$name}}</option>
          @endforeach
        </select>
        @if($errors->has('refunding_type'))
        <div class="invalid-feedback">
          @foreach($errors->get('refunding_type') as $err)
            {{$err}}<br>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    <fieldset class="form-group">
      <label for="instructions">Istruzioni</label>
      <textarea id="instructions" name="instructions">{{ old('instructions', $testUnit->instructions) }}</textarea>
    </fieldset>

      <button type="submit" class="mt-3 mb-2 btn btn-primary">@php echo !empty($testUnit->id) ? 'Modifica unità di test' : 'Crea nuova unità di test' @endphp</button>
    @closeForm
  </div>
  <div id="select-tester" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 mb-3">
            <form class="json-request" data-event="select-tester" data-action="{{ route('panel.testers.fetch' )}}" data-result="#tester-search-results">
              @csrf
              <fieldset class="form-group mb-0">
                <div class="input-group input-group-lg">
                  <input type="text" class="form-control" id="tester-search" name="s" placeholder="Ricerca tester...">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></button>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        <div class="modal-content border-0">
            <ul class="list-group" id="tester-search-results">
            </ul>
        </div>
      </div>
  </div>
@endsection

@section('scripts')
<script>
var simplemde = new SimpleMDE({
  element: $("#instructions")[0],
  spellChecker: false,
  status: false
});
</script>
@endsection
