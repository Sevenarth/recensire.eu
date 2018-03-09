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
    @openForm('panel.testUnits.update', 'patch', arg="testUnit->id")
  @elseif(!empty($testUnit->mass))
    <form action="{{ route('panel.testUnits.massPut', $testUnit->testOrder->id) }}" method="post">
      @method('put')
      @csrf
    @else
    <form action="{{ route('panel.testUnits.put', $testUnit->testOrder->id) }}" method="post">
      @method('put')
      @csrf
    @endif

    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="tester_id">Tester <small class="text-muted">(opzionale)</small></label>
          @if(!($testUnit->status > 0))
          <div class="input-group">
            <div class="input-group-prepend">
              <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#select-tester" type="button">Cerca</button>
              <button type="button" class="btn btn-outline-danger" onclick="$('#tester-id').val('');$('#tester-name').val('');"title="Disassocia"><i class="fa fa-fw fa-unlink"></i></button>
            </div>
          @endif
            <input type="hidden" value="{{ !empty($testUnit->tester) ? $testUnit->tester->id : '' }}" id="tester-id" name="tester_id">
            <input class="form-control{{ $errors->has('tester_id') ? ' is-invalid' : '' }}" id="tester-name" type="text" value="{{ !empty($testUnit->tester) ? ($testUnit->tester->name . " - " . $testUnit->tester->email) : '' }}" placeholder="Nessun tester selezionato" readonly>
            @if($errors->has('seller_id'))
            <div class="invalid-feedback">
              @php foreach($errors->get('seller_id') as $error) echo $error . "<br>"; @endphp
            </div>
            @endif
          @if(!($testUnit->status > 0))</div>@endif
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="starts_on">Data inizio <small class="text-muted">(ora se lasciato in bianco)</small></label>
          <div class="input-group">
             @if($testUnit->status > 0)
               <input type="date" class="form-control" name="expires_on_date" value="{{ $testUnit->startsDate() }}" readonly>
               <input type="time" class="form-control" name="expires_on_time" value="{{ $testUnit->startsTime() }}" readonly>
             @else
            <input class="form-control{{ $errors->has('starts_on_date') ? ' is-invalid' : ''}}" type="date" name="starts_on_date" value="{{ old('starts_on_date', !empty($testUnit->starts_on) ? $testUnit->startsDate() : '') }}">
            <input class="form-control{{ $errors->has('starts_on_time') ? ' is-invalid' : ''}}" type="time" name="starts_on_time" value="{{ old('starts_on_time', !empty($testUnit->starts_on) ? $testUnit->startsTime() : '') }}">
            @endif
            @if($errors->has('starts_on_date')||$errors->has('starts_on_time'))
            <div class="invalid-feedback">
              @foreach($errors->get('starts_on_date') as $err) {{$err}}<br> @endforeach
              @foreach($errors->get('starts_on_time') as $err) {{$err}}<br> @endforeach
            </div>
          @endif
          </div>
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label>Scadenza in</label>
          @if($testUnit->status > 0) <input type="hidden" name="expires_on_space" value="{{ $testUnit->expires_on_space }}"> @endif
          <div class="input-group">
             @if($testUnit->status > 0)
               <input type="text" class="form-control" name="expires_on_time" value="{{ $testUnit->expires_on_time }}" readonly>
               <input type="text" class="form-control" value="{{ config('testUnit.timeSpaces')[$testUnit->expires_on_space] }}" readonly>
             @else
               <input type="number" min="1" step="1" class="form-control{{ $errors->has('expires_on_time') ? ' is-invalid' : '' }}" name="expires_on_time" value="{{ old('expires_on_time', !empty($testUnit->id) ? $testUnit->expires_on_time : '') }}" required>
            <select class="custom-select{{ $errors->has('expires_on_space') ? ' is-invalid' : '' }}" name="expires_on_space" required>
              @foreach(config('testUnit.timeSpaces') as $id => $timeSpace)
                <option value="{{ $id }}" {{ $id == old('expires_on_space', !empty($testUnit->id) ? $testUnit->expires_on_space : null) ? 'selected' : '' }}>{{ $timeSpace }}</option>
              @endforeach
            </select>
          @endif
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
    @if($testUnit->status > 0)
      <fieldset class="form-group">
        <label for="reference_url">Link base di ricerca</label>
        <input type="text" readonly class="form-control" value="{{ $testUnit->reference_url}}" name="reference_url">
      </fieldset>
  @else
  @formTextfield('reference_url', 'Link base di ricerca', editMode="testUnit", placeholder="http://")
@endif
    <fieldset class="form-group">
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
    </fieldset>
    <hr>

    <div class="row">
      <div class="col-sm-6">
      @if($testUnit->status > 0)
        <fieldset class="form-group">
          <label for="amazon_order_id">Numero di ordine Amazon</label>
          <input type="text" readonly class="form-control" value="{{ $testUnit->amazon_order_id }}" name="amazon_order_id">
        </fieldset>
    @else
        @formTextfield('amazon_order_id', 'Numero di ordine Amazon', editMode="testUnit", required="false")
      @endif
      </div>
      <div class="col-sm-6">
        @formTextfield('paypal_account', 'Account PayPal', editMode="testUnit", placeholder="me@testers.com", required="false")
      </div>
    </div>

    <fieldset class="form-group">
      <label for="tester_notes">Note dal tester <small class="text-muted">(opzionale)</small></label>
      <textarea name="tester_notes" class="form-control{{ $errors->has('tester_notes') ? ' is-invalid' : ''}}" id="tester_notes" rows="4">{{ old('tester_notes', !empty($testUnit->tester_notes) ? $testUnit->tester_notes : '') }}</textarea>
      @if($errors->has('tester_notes'))
      <div class="invalid-feedback">
        @foreach($errors->get('tester_notes') as $err)
          {{$err}}<br>
        @endforeach
      </div>
      @endif
    </fieldset>

    <hr>

    <div class="row">
      <div class="col-sm-5">
        <label for="status">Stato corrente</label>
        <select class="custom-select{{ $errors->has("status") ? ' is-invalid' : '' }}" name="status">
          @foreach(config('testUnit.statuses') as $id => $name) @if($id != 4)
            <option value="{{$id}}"{{ $id == old('status', $testUnit->status) ? ' selected' : ''}}>{{$name}}</option>@endif
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
      <div class="col-sm-2">
        <div class="custom-control mt-4 mb-sm-0 mb-4 custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="refunded" name="refunded" {{ old('refunded', $testUnit->refunded) ? 'checked' : ''}}>
          <label class="custom-control-label" for="refunded">Rimborsato</label>
        </div>
      </div>
      <div class="col-sm-5">
        <fieldset class="form-group">
          <label for="refunded_amount">Importo da rimborsare <small class="text-muted">(opzionale)</small></label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">&euro;</span>
            </div>
            <input class="form-control{{ $errors->has("fee") ? ' is-invalid' : '' }}" type="number" min="0.01" step="0.01" id="refunded_amount" name="refunded_amount" placeholder="25.00" value="{{ old('refunded_amount', !empty($testUnit->refunded_amount) ? $testUnit->refunded_amount : '') }}">
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

    @formTextfield('review_url', 'Collegamento alla recensione', editMode="testUnit", required="false", placeholder="http://")


    <fieldset class="form-group">
      <label for="instructions">Istruzioni</label>
      <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
      <textarea id="instructions" name="instructions">{{ old('instructions', $testUnit->instructions) }}</textarea>
      @if($errors->has('instructions'))
        <div class="text-danger"><small>@foreach($errors->get('instructions') as $err){{ $err }}<br>@endforeach</small></div>
      @endif
      <div class="text-muted">
        <small>Per inserire il bottone-collegamento alla ricerca di Amazon inserisci <code class="border p-1 rounded">#link-amazon</code></small>
      </div>
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
$('#instructions').mde();
</script>
@endsection
