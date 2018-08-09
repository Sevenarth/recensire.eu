@extends('layouts.panel')

@section('title') Reportistica @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('report') }}
@endsection

@section('content')
    <div class="px-4 py-3 h3 border-bottom">
      Reportistica
    </div>
    <div class="px-4 py-3">
      @if (session('status'))
      <div class="alert alert-success">
          {!! session('status') !!}
      </div>
      @endif

      @openForm('panel.postReport', 'post')
      <h4 class="mb-3">Criteri di ricerca</h4>
      <div class="row">
        <div class="col-sm-6">
          <fieldset class="form-group">
            <label for="start_date">Data di inizio</label>
            <input type="date" class="form-control" required name="start_date" value="{{ old('start_date', \Carbon\Carbon::now(config('app.timezone'))->toDateString()) }}">
          </fieldset>
        </div>
        <div class="col-sm-6">
          <fieldset class="form-group">
            <label for="start_date">Data di fine</label>
            <input type="date" class="form-control" required name="end_date" value="{{ old('end_date', \Carbon\Carbon::now(config('app.timezone'))->toDateString()) }}">
          </fieldset>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <fieldset class="form-group">
              <label for="sellers">Venditori <small class="text-muted">(lascia vuoto per tutti)</small></label>
        
              <select id="sellers" name="sellers[]" data-role="tagsinput" multiple></select>
              @if($errors->has('sellers'))
              <div class="invalid-feedback">
                @foreach ($errors->get('sellers') as $err)
                  {{ $err }}<br>
                @endforeach
              </div>
              @endif
              <small class="text-muted">Digita il nome del venditore, e separa multipli da una virgola.</small>
            </fieldset>
        </div>
        <div class="col-sm-6">
          <fieldset class="form-group">
              <label for="stores">Negozi <small class="text-muted">(lascia vuoto per tutti)</small></label>
        
              <select id="stores" name="stores[]" data-role="tagsinput" multiple></select>
              @if($errors->has('stores'))
              <div class="invalid-feedback">
                @foreach ($errors->get('stores') as $err)
                  {{ $err }}<br>
                @endforeach
              </div>
              @endif
              <small class="text-muted">Digita il nome del negozio, e separa multipli da una virgola.</small>
            </fieldset>
        </div>
      </div>
      <fieldset class="form-group">
          <label>Stato</label>
        <div class="row">
          <div class="col-sm-3">
            <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" name="status" value="all" id="status_all"{{ old('status', "all") == "all" ? ' checked' : '' }}>
              <label class="custom-control-label" for="status_all">Tutti</label>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" name="status" value="expiring" id="status_expiring"{{ old('status') == "expiring" ? ' checked' : '' }}>
              <label class="custom-control-label" for="status_expiring">In scadenza/Scaduti</label>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" name="status" value="others" id="status_others"{{ old('status') == "others" ? ' checked' : '' }}>
                <label class="custom-control-label w-100" for="status_others">
                  <select size="{{ count(config('testUnit.statuses')) }}" onClick="$('#status_others').click()" class="custom-select" multiple name="statuses[]">
                      @foreach(config('testUnit.statuses') as $id => $status)
                      <option value="{{ $id }}"{{ in_array($id, old('statuses', [])) ? ' selected' : '' }}>{{ $status }}</option>
                      @endforeach
                    </select>
                </label>
              </div>
          </div>
        </div>
      </fieldset>
      <div class="row">
        <div class="col-sm-6">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="current_state" id="current_state"{{ old('current_state') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="current_state">Solo stato corrente</label>
          </div>
        </div>
        <div class="col-sm-6">
        </div>
      </div>
      <hr>
      <h4 class="mb-4">Campi da mostrare</h4>
      <div class="row">
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="hash_code" id="hash_code"{{ old('hash_code') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="hash_code">Codice test</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="amazon_order_id" id="amazon_order_id"{{ old('amazon_order_id') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="amazon_order_id">Numero ordine Amazon</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="paypal_account" id="paypal_account"{{ old('paypal_account') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="paypal_account">Account PayPal</label>
          </div>
        </div>
      </div>
      <div class="row my-3">
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="review_url" id="review_url"{{ old('review_url') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="review_url">Link recensione</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="amazon_profile" id="amazon_profile"{{ old('amazon_profile') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="amazon_profile">Profilo Amazon</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="tester_name" id="tester_name"{{ old('tester_name') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="tester_name">Nome tester</label>
          </div>
        </div>
      </div>
      <div class="row my-3">
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="facebook_id" id="facebook_id"{{ old('facebook_id') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="facebook_id">Facebook ID</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="refunded" id="refunded"{{ old('refunded') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="refunded">Rimborsato</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="status_check" id="status_check"{{ old('status_check') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="status_check">Stato</label>
          </div>
        </div>
      </div>
      <div class="row my-3">
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="asin" id="asin"{{ old('asin') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="asin">ASIN</label>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="refunded_amount" id="refunded_amount"{{ old('refunded_amount') == "on" ? ' checked' : '' }}>
            <label class="custom-control-label" for="refunded_amount">Importo rimborsato</label>
          </div>
        </div>
      </div>
      <button type="submit" class="mb-2 btn btn-primary">Genera report</button> <button type="button" onclick="window.location.reload()" class="mb-2 btn btn-outline-primary">Reset</button>
      @closeForm
      @if(!empty(old('report', null)))
        <hr>
        <h4 class="mb-4">Report</h4>
        <pre class="form-control" style="max-height: 350px; overflow-y: auto">{!! old('report') !!}</pre>
      @endif
    </div>
    <div id="select-store" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content border-0 mb-3">
              <form class="json-request" data-event="select-store" data-action="{{ route('panel.stores.fetch' )}}" data-result="#store-search-results">
                @csrf
                <fieldset class="form-group mb-0">
                  <div class="input-group input-group-lg">
                    <input type="text" class="form-control" id="store-search" name="s" placeholder="Ricerca negozio...">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></button>
                    </div>
                  </div>
                </fieldset>
              </form>
          </div>
          <div class="modal-content border-0">
              <ul class="list-group" id="store-search-results">
              </ul>
          </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
var sellers = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  identify: function(obj) { return obj.id; },
  remote: {
    url: '{{ route('panel.sellers.fetch') }}',
    prepare: function (query, settings) {
        settings.type = "POST";
        settings.contentType = "application/json; charset=utf-8"
        settings.headers = {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        };
        settings.data = JSON.stringify({
          s: query,
          except: $("#sellers").val(),
        });
        return settings;
    },
    transform: function (data) {
      return $.map(data, function (store) {
          return {
            id: store.id,
            name: store.nickname
          };
      });
    }
  }
});

sellers.initialize();

$('#sellers').tagsinput({
  tagClass: 'badge',
  itemValue: 'id',
  itemText: 'name',
  typeaheadjs: [{
    minLength: 1,
    highlight: true
  },{
    name: 'sellers',
    displayKey: 'name',
    source: sellers.ttAdapter(),
    limit: Infinity,
    hint: true
  }],
  freeInput: false
});

@foreach($sellers as $seller)
$('#sellers').tagsinput("add", {!! json_encode($seller) !!});
@endforeach

var stores = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  identify: function(obj) { return obj.id; },
  remote: {
    url: '{{ route('panel.stores.fetch') }}',
    prepare: function (query, settings) {
        settings.type = "POST";
        settings.contentType = "application/json; charset=utf-8"
        settings.headers = {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        };
        settings.data = JSON.stringify({
          s: query,
          except: $("#stores").val(),
          sellers: $("#sellers").val()
        });
        return settings;
    },
    transform: function (data) {
      return $.map(data, function (store) {
          return {
            id: store.id,
            name: store.name
          };
      });
    }
  }
});

stores.initialize();

$('#stores').tagsinput({
  tagClass: 'badge',
  itemValue: 'id',
  itemText: 'name',
  typeaheadjs: [{
    minLength: 1,
    highlight: true
  },{
    name: 'stores',
    displayKey: 'name',
    source: stores.ttAdapter(),
    limit: Infinity,
    hint: true
  }],
  freeInput: false
});

@foreach($stores as $store)
$('#stores').tagsinput("add", {!! json_encode($store) !!});
@endforeach
</script>
@endsection