@extends('layouts.panel')

@section('title')
Unità di test #{{ $testUnit->hash_code }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testUnits.view', $testUnit) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.testOrders.testUnits.delete', 'delete', arg="testUnit->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.testOrders.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa unità di test?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Unità di test #{{ $testUnit->hash_code }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.testOrders.testUnits.delete', 'delete', arg="testUnit->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.testOrders.testUnits.edit", $testUnit->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa unità di test?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Tester</b>
          <a title="Vai alla pagina del tester" href="{{ route('panel.testers.view', ['tester' => $testUnit->tester->id]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-external-link-alt"></i>
          </a></label>
          <input type="text" readonly class="form-control-plaintext" value="{{ $testUnit->tester->name }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Scadenza</b></label>
          <input type="text" readonly class="form-control-plaintext" value="{{ $testUnit->expires_on }}">
        </fieldset>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label><b>Stato corrente</b></label>
          <input class="form-control-plaintext" type="text" value="{{ config('testUnit.statuses')[$testUnit->status] }}" readonly>
        </div>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Importo da rimborsare</b></label>
          <input type="text" class="form-control-plaintext" value="&euro; {{ number_format($testUnit->refunded_amount, 2) }}" readonly>
        </fieldset>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label><b>Account PayPal</b></label>
          <input class="form-control-plaintext" type="text" value="{{ !empty($testUnit->paypal_account) ? $testUnit->paypal_account : '-' }}" readonly>
        </div>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Numero di ordine Amazon</b></label>
          <input type="text" class="form-control-plaintext" value="{{ !empty($testUnit->amazon_order_id) ? $testUnit->amazon_order_id : '-' }}" readonly>
        </fieldset>
      </div>
    </div>

    <fieldset class="form-group">
      <label><b>Collegamento alla recensione</b></label>
      <div class="input-group">
        <input type="text" class="form-control" value="{{ $testUnit->reference_url }}" readonly>
        <div class="input-group-append">
          <a href="{{ $testUnit->reference_url }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
        </div>
      </div>
    </fieldset>

    <fieldset class="form-group">
      <label><b>Collegamento di riferimento</b></label>
      @if(!empty($testUnit->review_url)) <div class="input-group"> @endif
      <input type="text" class="form-control{{ !empty($testUnit->review_url) ? '' : '-plaintext' }}" value="{{ !empty($testUnit->review_url) ? $testUnit->review_url : '-' }}" readonly>
      @if(!empty($testUnit->review_url)) <div class="input-group-append">
        <a href="{{ $testUnit->reference_url }}" target="_blank" class="btn btn-outline-primary"><i class="fa fa-fw fa-external-link-alt"></i></a>
      </div>
    </div>@endif
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

    <fieldset class="form-group mb-4">
      <label><b>Istruzioni</b></label>
      <div class="form-control markdown p-3" style="max-height: 300px; overflow-y: auto">{{ $testUnit->instructions }}</div>
    </fieldset>

    <h5 class="mb-3">Ultimi stati</h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Stato</th>
        <th>Data</th>
      </thead>
      <tbody>
        @foreach($testUnit->statuses()->orderBy('created_at', 'desc')->get() as $status)
        <tr>
          <td class="p-2">{{ config('testUnit.statuses')[$status->status] }}</td>
          <td class="p-2">{{ $status->created_at }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection