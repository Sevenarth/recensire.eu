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
            <input type="date" class="form-control" required name="start_date" value="{{ old('start_date') }}">
          </fieldset>
        </div>
        <div class="col-sm-6">
          <fieldset class="form-group">
            <label for="start_date">Data di fine</label>
            <input type="date" class="form-control" required name="end_date" value="{{ old('end_date') }}">
          </fieldset>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <fieldset class="form-group">
            <label for="store_id">Negozio <small class="text-muted">(lascia vuoto per tutti)</small></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#select-store" type="button">Cerca</button>
              </div>
              <input type="hidden" value="{{ old('store_id') }}" id="store-id" name="store_id">
              <input class="form-control" id="store-name" name="store_name" type="text" value="{{ old('store_name') }}" placeholder="Nessun negozio selezionato" required readonly>
            </div>
          </fieldset>
        </div>
        <div class="col-sm-6">
          <fieldset class="form-group">
            <label for="status">Stato</label>
            <select class="custom-select" name="status">
              <option value="-2"{{ intval(old('status', -2)) === -2 ? ' selected' : '' }}>Tutti</option>
              <option value="-1"{{ intval(old('status', -2)) === -1 ? ' selected' : '' }}>In scadenza/Scaduti</option>
              @foreach(config('testUnit.statuses') as $id => $status)
              <option value="{{ $id }}"{{ intval(old('status', -1)) === $id ? ' selected' : '' }}>{{ $status }}</option>
              @endforeach
            </select>
          </fieldset>
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
      <button type="submit" class="mb-2 btn btn-primary">Genera report</button> <button type="button" onclick="window.location.reload()" class="mb-2 btn btn-outline-primary">Reset</button>
      @closeForm
      @if(!empty(old('report', null)))
        <hr>
        <h4 class="mb-4">Report</h4>
        <textarea class="form-control" rows="10">{{ old('report') }}</textarea>
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
