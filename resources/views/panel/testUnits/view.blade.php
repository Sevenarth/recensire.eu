@extends('layouts.panel')

@section('title')
Unità di test #{{ $testUnit->hash_code }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testUnits.view', $testUnit) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @if($testUnit->status > 0)
      <div class="btn-group float-right" role="group">
        <a href="{{route('panel.testUnits.renew', $testUnit->id)}}" class="btn btn-outline-info"><i class="fa fa-fw fa-sync-alt"></i> Rinnova</a><a href="{{route('panel.testUnits.duplicate', $testUnit->id)}}" class="btn btn-outline-secondary"><i class="fa fa-fw fa-clone"></i> Duplica</a><a href="{{route("panel.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      </div>
    @else
      @openForm('panel.testUnits.delete', 'delete', arg="testUnit->id")
      <div class="btn-group float-right" role="group">
        <a href="{{route('panel.testUnits.renew', $testUnit->id)}}" class="btn btn-outline-info"><i class="fa fa-fw fa-sync-alt"></i> Rinnova</a>
        <a href="{{route('panel.testUnits.duplicate', $testUnit->id)}}" class="btn btn-outline-secondary"><i class="fa fa-fw fa-clone"></i> Duplica</a>
        <a href="{{route("panel.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
        <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa unità di test?"><i class="fa fa-fw fa-times"></i> Elimina</button>
      </div>
      @closeForm
    @endif
    Unità di test #{{ $testUnit->hash_code }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
    @endif

    @if($testUnit->status > 0)
      <div class="btn-group text-center d-block d-md-none mb-4" role="group">
        <a href="{{route('panel.testUnits.renew', $testUnit->id)}}" class="btn btn-outline-info"><i class="fa fa-fw fa-sync-alt"></i> Rinnova</a><a href="{{route('panel.testUnits.duplicate', $testUnit->id)}}" class="btn btn-outline-secondary"><i class="fa fa-fw fa-clone"></i> Duplica</a><a href="{{route("panel.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      </div>
    @else
      @openForm('panel.testUnits.delete', 'delete', arg="testUnit->id")
      <div class="btn-group text-center d-block d-md-none mb-4" role="group">
        <a href="{{route('panel.testUnits.renew', $testUnit->id)}}" class="btn btn-outline-info"><i class="fa fa-fw fa-sync-alt"></i> Rinnova</a><a href="{{route('panel.testUnits.duplicate', $testUnit->id)}}" class="btn btn-outline-secondary"><i class="fa fa-fw fa-clone"></i> Duplica</a><a href="{{route("panel.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa unità di test?"><i class="fa fa-fw fa-times"></i> Elimina</button>
      </div>
      @closeForm
    @endif



    <div class="row">
      <div class="col-sm-4">
      <div class="form-group">
        <label><b>Stato corrente</b></label>
        <input class="form-control-plaintext" type="text" value="{{ config('testUnit.statuses')[$testUnit->status] }}" readonly>
      </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label><b>Rimborsato?</b></label>
          <input class="form-control-plaintext" type="text" value="{{ !empty($testUnit->refunded) ? 'Sì' : 'No' }}" readonly>
        </div>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Importo da rimborsare</b></label>
          <input type="text" class="form-control-plaintext" value="&euro; {{ !empty($testUnit->refunded_amount) ? number_format($testUnit->refunded_amount, 2) : '-' }}" readonly>
        </fieldset>
      </div>
    </div>

    <fieldset class="form-group">
      <label><b>Collegamento alla recensione</b></label>
      @if(!empty($testUnit->review_url)) <div class="input-group"> @endif
      <input type="text" class="form-control{{ !empty($testUnit->review_url) ? '' : '-plaintext' }}" value="{{ !empty($testUnit->review_url) ? $testUnit->review_url : '-' }}" readonly>
      @if(!empty($testUnit->review_url)) <div class="input-group-append">
        <a href="{{ $testUnit->review_url }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
      </div>
    </div>@endif
    </fieldset>

    <hr>

    <div class="row">
      <div class="col-sm-4">
        <fieldset class="form-group">
          <label><b>Tester</b>
            @if(!empty($testUnit->tester))
          <a title="Vai alla pagina del tester" href="{{ route('panel.testers.view', ['tester' => $testUnit->tester->id]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-external-link-alt"></i>
          </a></label>@endif
          <input type="text" readonly class="form-control-plaintext {{ !empty($testUnit->tester) ? 'tester-status-' . $testUnit->tester->status : '' }}" value="{{ !empty($testUnit->tester) ? $testUnit->tester->name : '-' }}">
        </fieldset>
      </div>
      <div class="col-sm-4">
        <fieldset class="form-group">
          <label><b>Numero di ordine Amazon</b></label>
          <input type="text" class="form-control-plaintext" value="{{ !empty($testUnit->amazon_order_id) ? $testUnit->amazon_order_id : '-' }}" readonly>
        </fieldset>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <label><b>Account PayPal</b></label>
          <input class="form-control-plaintext" type="text" value="{{ !empty($testUnit->paypal_account) ? $testUnit->paypal_account : '-' }}" readonly>
        </div>
      </div>
    </div>

    <fieldset class="form-group mb-4">
      <label><b>Note del tester</b></label>
      @if(!empty($testUnit->tester_notes))
      <div class="form-control markdown p-3" style="max-height: 300px; overflow-y: auto">{{ $testUnit->tester_notes }}</div>
    @else
      <input type="text" class="form-control-plaintext" readonly value="-">
    @endif
    </fieldset>

    <fieldset class="form-group">
      <label><b>Link per il tester</b></label>
      <div class="input-group">
      <input type="text" class="form-control" value="{{ route('tests.view', $testUnit->hash_code) }}" readonly>
      <div class="input-group-append">
        <a href="{{ route('tests.view', $testUnit->hash_code) }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
      </div>
    </div>
    </fieldset>

    <hr>

    <div class="row mb-xl-4">
      <div class="col-sm-3 mb-4 mb-sm-0 text-center">
        @if(!empty($testUnit->testOrder->product))
          <img style="max-height: 190px" id="image" src="@if(empty($testUnit->testOrder->product->images[0])) /images/package.svg @else{{ $testUnit->testOrder->product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border">
        @else
          <img style="max-height: 190px" id="image" src="/images/package.svg" class="img-fluid img-thumbnail rounded border">
        @endif
      </div>
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Marchio e nome prodotto</b> @if(!empty($testUnit->testOrder->product->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.products.view', $testUnit->testOrder->product->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{ !empty($testUnit->testOrder->product->id) ? $testUnit->testOrder->product->brand . ' '. $testUnit->testOrder->product->title : 'Prodotto assente' }}">
            </fieldset>
          </div>
          @if(!empty($testUnit->testOrder->product->id))
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>ASIN</b></label>
              <input type="text" readonly class="form-control-plaintext" value="{{  $testUnit->testOrder->product->ASIN }}">
            </fieldset>
          </div>
        @endif
        </div>
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Negozio</b> @if(!empty($testUnit->testOrder->store->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.stores.view', $testUnit->testOrder->store->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{ !empty($testUnit->testOrder->store) ? $testUnit->testOrder->store->name : 'Negozio assente' }}">
            </fieldset>
          </div>
          @if(!empty($testUnit->testOrder->store->id))
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Venditore</b> @if(!empty($testUnit->testOrder->store->seller->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.sellers.view', $testUnit->testOrder->store->seller->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{  !empty($testUnit->testOrder->store->seller->id) ? $testUnit->testOrder->store->seller->nickname : '-' }}">
            </fieldset>
          </div>
        @endif
        </div>
      </div>
    </div>

    <hr>

    <fieldset class="form-group">
      <label><b>Link base di ricerca</b></label>
      <div class="input-group">
        <input type="text" class="form-control" value="{{ $testUnit->reference_url }}" readonly>
        <div class="input-group-append">
          <a href="{{ $testUnit->reference_url }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
        </div>
      </div>
    </fieldset>

    <div class="row">
      <div class="col-sm-9">
        <fieldset class="form-group">
          <label><b>Link Amazon per il tester</b></label>
          <div class="input-group">
            <input type="text" class="form-control-plaintext" value="{{ route('tests.go', $testUnit->hash_code) }}" readonly>
            <div class="input-group-append">
              <a href="{{ $testUnit->review_url }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="col-sm-3">
        <label><b>Aperto?</b></label>
        <input class="form-control-plaintext" type="text" value="{{ !empty($testUnit->viewed) ? 'Sì' : 'No' }}" readonly>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Data inizio</b></label>
          <input type="text" class="form-control-plaintext" readonly value="{{ $testUnit->starts_on }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Scadenza</b></label>
          @php $expiration = new \Carbon\Carbon($testUnit->expires_on, config('app.timezone')); @endphp
          @if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))))
          <div class="relative-time form-control p-2">{{ $expiration->toIso8601String() }}</div>
          @else
          <div class="form-control p-2 text-danger"><b>Scaduto</b></div>
          @endif
        </fieldset>
      </div>
    </div>

      <fieldset class="form-group">
        <label><b>Metodo di rimborso</b></label>
        <input type="text" class="form-control-plaintext" value="{{ config('testUnit.refundingTypes')[$testUnit->refunding_type] }}" readonly>
      </fieldset>

    <fieldset class="form-group mb-4">
      <label><b>Istruzioni</b></label>
      <div class="form-control markdown p-3" style="max-height: 300px; overflow-y: auto">{{ $testUnit->instructions }}</div>
    </fieldset>

    <h5 class="mb-3">Cronologia stati</h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Stato</th>
        <th>Data</th>
      </thead>
      <tbody>@php $status_logs = $testUnit->statuses()->orderBy('created_at', 'desc')->paginate(8); @endphp
        @foreach($status_logs as $status)
        <tr>
          <td class="p-2">{{ config('testUnit.statuses')[$status->status] }}</td>
          <td class="p-2">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->format('d/m/Y H:i:s') }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {{ $status_logs->appends(request()->query())->links() }}
  </div>
</div>
@endsection
