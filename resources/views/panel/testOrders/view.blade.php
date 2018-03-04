@extends('layouts.panel')

@section('title')
Ordine di lavoro #{{ $testOrder->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testOrders.view', $testOrder) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @if($testOrder->hasCompletes())
      <div class="btn-group float-right" role="group">
        <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      </div>
    @else
      @openForm('panel.testOrders.delete', 'delete', arg="testOrder->id")
      <div class="btn-group float-right" role="group">
        <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
        <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo ordine di lavoro?"><i class="fa fa-fw fa-times"></i> Elimina</button>
      </div>
      @closeForm
    @endif
    Ordine di lavoro #{{ $testOrder->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if($testOrder->hasCompletes())
      <div class="btn-group text-center d-block d-md-none mb-4" role="group">
        <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      </div>
    @else
      @openForm('panel.testOrders.delete', 'delete', arg="testOrder->id")
      <div class="btn-group text-center d-block d-md-none mb-4" role="group">
        <a href="{{route("panel.testOrders.edit", $testOrder->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo ordine di lavoro?"><i class="fa fa-fw fa-times"></i> Elimina</button>
      </div>
      @closeForm
    @endif


    <div class="row mb-xl-4">
      <div class="col-sm-3 mb-4 mb-sm-0 text-center">
        @if(!empty($testOrder->product))
          <img style="max-height: 190px" id="image" src="@if(empty($testOrder->product->images[0])) /images/package.svg @else{{ $testOrder->product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border">
        @else
          <img style="max-height: 190px" id="image" src="/images/package.svg" class="img-fluid img-thumbnail rounded border">
        @endif
      </div>
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Marchio e nome prodotto</b> @if(!empty($testOrder->product->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.products.view', $testOrder->product->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{ !empty($testOrder->product->id) ? $testOrder->product->brand . ' '. $testOrder->product->title : 'Prodotto assente' }}">
            </fieldset>
          </div>
          @if(!empty($testOrder->product->id))
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>ASIN</b></label>
              <input type="text" readonly class="form-control-plaintext" value="{{  $testOrder->product->ASIN }}">
            </fieldset>
          </div>
        @endif
        </div>
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Negozio</b> @if(!empty($testOrder->store->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.stores.view', $testOrder->store->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{ !empty($testOrder->store) ? $testOrder->store->name : 'Negozio assente' }}">
            </fieldset>
          </div>
          @if(!empty($testOrder->store->id))
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label><b>Venditore</b> @if(!empty($testOrder->store->seller->id))<a class="btn btn-sm btn-primary" href="{{ route('panel.sellers.view', $testOrder->store->seller->id)}}"><i class="fa fa-fw fa-external-link-alt"></i></a>@endif</label>
              <input type="text" readonly class="form-control-plaintext" value="{{  !empty($testOrder->store->seller->id) ? $testOrder->store->seller->nickname : '-' }}">
            </fieldset>
          </div>
        @endif
        </div>
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

    <h4 class="mb-3">Unit&agrave; di test <span class="badge-secondary badge-pill badge">{{ $testOrder->testUnits()->count() }}</small></h4>

    <a class="mb-3 btn btn-primary" href="{{ route('panel.testOrders.testUnits.massCreate', $testOrder->id) }}">Riempi</a>

    <h5>In corso <span class="badge-secondary badge-pill badge">{{ $testOrder->testUnits()->where(function($q) {
      $q->where(function($q) {
        $q->where('status', '<>', 3)->where('status', '>', 0);
      })->orWhere(function($q) {
        $q->where('status', 0)->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')));
      });
    })->count() }}</small></h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Indice</th>
        <th>Tester</th>
        <th>Ultimo stato</th>
        <th>Scadenza/Acquisto</th>
        <th></th>
      </thead>
      <tbody>
        @foreach($testOrder->testUnits()->where(function($q) {
          $q->where(function($q) {
            $q->where('status', '<>', 3)->where('status', '>', 0);
          })->orWhere(function($q) {
            $q->where('status', 0)->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')));
          });
        })->get() as $unit)
        <tr>
          <th class="p-2" scope="row">{{ $unit->hash_code }}</th>
          <td class="p-2">@if(!empty($unit->tester)) <a href="{{ route('panel.testers.view', $unit->tester->id) }}">{{ $unit->tester->name }}</a> @else - @endif</td>
          <td class="p-2">{{ config('testUnit.statuses')[$unit->status] }}</td>
          <td class="p-2">
            @if($unit->status > 0)
              @if($accepted_date = $unit->statuses()->where('status', 1)->select('created_at')->first())
                {{ $accepted_date->created_at }}
              @else
                -
              @endif
            @else
            @php $expiration = new \Carbon\Carbon($unit->expires_on, config('app.timezone')); @endphp
            @if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))))
            <div class="relative-time">{{ $expiration->toIso8601String() }}</div>
            @else
            <div class="text-danger"><b>Scaduto</b></div>
            @endif
          @endif
          </td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.view', $unit->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-external-link-alt"></i> Visualizza</a>
          </td>
        </tr>
        @endforeach
        @for($i = 0; $i < $testOrder->quantity-$testOrder->testUnits()->where(function($q) {
          $q->where('status', '>', 0)->orWhere(function($q) {
            $q->where('status', 0)->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')));
          });
        })->count(); $i++)
        <tr>
          <td class="p-2" colspan="4"><i>Unità di test mancante.</i></td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.create', $testOrder->id) }}" class="btn btn-success btn-sm"><i class="fas fa-plus fa-fw"></i> Crea unità di test</a>
          </td>
        </tr>
        @endfor
      </tbody>
    </table>

    <h5>Completate <span class="badge-secondary badge-pill badge">{{ $testOrder->testUnits()->where('status', 3)->count() }}</small></h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Indice</th>
        <th>Tester</th>
        <th>Ultimo stato</th>
        <th>Scadenza in</th>
        <th></th>
      </thead>
      <tbody>
        @foreach($testOrder->testUnits()->where('status', 3)->get() as $unit)
        <tr>
          <th class="p-2" scope="row">{{ $unit->hash_code }}</th>
          <td class="p-2">@if(!empty($unit->tester)) <a href="{{ route('panel.testers.view', $unit->tester->id) }}">{{ $unit->tester->name }}</a> @else - @endif</td>
          <td class="p-2">{{ config('testUnit.statuses')[$unit->status] }}</td>
          <td class="p-2 text-success">
            Completo
          </td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.view', $unit->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-external-link-alt"></i> Visualizza</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <h5>Scadute <span class="badge-secondary badge-pill badge">{{ $testOrder->testUnits()->where('status', 0)->where('expires_on', '<', \Carbon\Carbon::now(config('app.timezone')))->count() }}</small></h5>

    <table class="table table-sm table-striped">
      <thead>
        <th>Indice</th>
        <th>Tester</th>
        <th>Ultimo stato</th>
        <th>Scadenza in</th>
        <th></th>
      </thead>
      <tbody>
        @foreach($testOrder->testUnits()->where('status', 0)->where('expires_on', '<', \Carbon\Carbon::now(config('app.timezone')))->get() as $unit)
        <tr>
          <th class="p-2" scope="row">{{ $unit->hash_code }}</th>
          <td class="p-2">@if(!empty($unit->tester)) <a href="{{ route('panel.testers.view', $unit->tester->id) }}">{{ $unit->tester->name }}</a> @else - @endif</td>
          <td class="p-2">{{ config('testUnit.statuses')[$unit->status] }}</td>
          <td class="p-2">
            @php $expiration = new \Carbon\Carbon($unit->expires_on, config('app.timezone')); @endphp
            @if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))))
            <div class="relative-time">{{ $expiration->toIso8601String() }}</div>
            @else
            <div class="text-danger"><b>Scaduto</b></div>
            @endif
          </td>
          <td>
            <a href="{{ route('panel.testOrders.testUnits.view', $unit->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-external-link-alt"></i> Visualizza</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
