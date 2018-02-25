@extends('layouts.panel')

@section('title')
Ordine di lavoro #{{ $testOrder->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testOrders.view', $testOrder) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.testOrders.delete', 'delete', arg="testOrder->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo ordine di lavoro?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Ordine di lavoro #{{ $testOrder->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.testOrders.delete', 'delete', arg="testOrder->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo ordine di lavoro?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="store"><b>Negozio</b>
          <a title="Vai al negozio" href="{{ route('panel.stores.view', ['store' => $testOrder->store->id]) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-external-link-alt"></i>
          </a></label>
          <input type="text" id="store" readonly class="form-control-plaintext" value="{{ $testOrder->store->name }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="product"><b>Prodotto</b>
          <a title="Vai al prodotto" href="{{ route('panel.products.view', $testOrder->product->id) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-external-link-alt"></i>
          </a></label>
          <input type="text" id="product" readonly class="form-control-plaintext" value="{{ $testOrder->product->title }}">
        </fieldset>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label><b>Commissione</b></label>
          <input class="form-control-plaintext" type="text" value="&euro; {{ number_format($testOrder->fee, 2) }}" readonly>
        </div>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label><b>Numero di unità di test</b></label>
          <input type="text" class="form-control-plaintext" value="{{ $testOrder->quantity }}" readonly>
        </fieldset>
      </div>
    </div>

    <fieldset class="form-group mb-4">
      <label><b>Descrizione</b></label>
      <div class="form-control markdown p-3" style="max-height: 300px; overflow-y: auto">{{ $testOrder->description }}</div>
    </fieldset>

    <h5 class="mb-3">Unit&agrave; di test</h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Indice</th>
        <th>Tester</th>
        <th>Ultimo stato</th>
        <th>Scadenza in</th>
        <th></th>
      </thead>
      <tbody>@php $count = 0; @endphp
        @foreach($testOrder->testUnits as $unit)
        <tr>
          <th class="p-2" scope="row">{{ $unit->hash_code }}</th>
          <td class="p-2"><a href="{{ route('panel.testers.view', $unit->tester->id) }}">{{ $unit->tester->name }}</a></td>
          <td class="p-2">{{ config('testUnit.statuses')[$unit->status] }}</td>
          <td class="p-2">
            <div class="relative-time">{{ (new \Carbon\Carbon($unit->expires_on, config('app.timezone')))->toIso8601String() }}</div>
          </td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.view', $unit->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-external-link-alt"></i> Visualizza</a>
          </td>
        </tr>@php $count++ @endphp
        @endforeach
        @for($i = 0; $i < $testOrder->quantity-$count; $i++)
        <tr>
          <td class="p-2" colspan="4"><i>Unità di test mancante.</i></td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.create', $testOrder->id) }}" class="btn btn-success btn-sm"><i class="fas fa-plus fa-fw"></i> Crea unità di test</a>
          </td>
        </tr>
      @endfor
      </tbody>
    </table>
  </div>
</div>
@endsection
