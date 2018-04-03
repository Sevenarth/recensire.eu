@extends('layouts.panel')

@section('title')
Unità di test
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testUnits') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    Unità di test
  </div>
  <div class="px-4 py-3">

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form class="fakelink-get" data-action="{{ route("panel.testUnits.home") }}" method="get">
      <div class="row">
        <div class="input-group mb-3 col-sm-6">
            <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Codice, ID ordine Amazon, account PayPal...">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="fa fa-fw fa-search"></i>
              </button>
            </div>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">
              @orderable('hash_code', 'Codice')
            </th>
            <th scope="col"></th>
            <th scope="col" class="p-2">
              @orderable('test_order_id', 'Ordine di lavoro')
            </th>
            <th scope="col" class="p-2">
              @orderable('tester_name', 'Nome tester')
            </th>
            <th scope="col" class="p-2">
              @orderable('status', 'Stato')
            </th>
            <th scope="col" class="p-2">
              @orderable('test_unit.created_at', 'Data creazione')
            </th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($testUnits as $testUnit)
          <tr>
            <th class="align-middle" scope="row">{{ $testUnit->hash_code }}</th>
            <td class="align-middle">
              @if($testUnit->test_order_id)
              @php $product = \App\TestOrder::find($testUnit->test_order_id)->product; @endphp
              <img style="min-width: 50px; max-height: 50px" src="@if(empty($product->images[0])) /images/package.svg @else{{ $product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border">
              @endif
            </td>
            <td class="align-middle">
              @if($testUnit->test_order_id)
              {{ $testUnit->test_order_id }}
              <a title="Vai all'ordine di lavoro" href="{{ route('panel.testOrders.view', $testUnit->test_order_id) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-external-link-alt"></i>
              </a>@else <i>Assente</i> @endif
            </td>
            <td class="align-middle">
              @if($testUnit->tester_id)
                {{ $testUnit->tester_name }}
                <a title="Vai al tester" href="{{ route('panel.testers.view', ['tester' => $testUnit->tester_id]) }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-external-link-alt"></i>
                </a>@else <i>Assente</i> @endif
            </td>
            <td class="align-middle">
              @if($testUnit->status > 0)
                @if($testUnit->status == 3)
                  <div class="text-success"><b>Completato</b></div>
                @else
                  {{ config('testUnit.statuses')[$testUnit->status] }}
                @endif
              @else
                @php $expiration = new \Carbon\Carbon($testUnit->expires_on, config('app.timezone')); @endphp
                @if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))))
                In attesa
                @else
                <div class="text-danger"><b>Scaduto</b></div>
                @endif
              @endif
            </td>
            <td class="align-middle">
              {{ (new \Carbon\Carbon($testUnit->created_at, config('app.timezone')))->format('d/m/Y H:i:s') }}
            </td>
            <td class="align-middle">
              <a href="{{ route('panel.testUnits.view', ['testUnit' => $testUnit->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">
                <i>Non ci sono unità di test con questi criteri di ricerca.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="float-right mb-2">
      Mostrando pagina {{ $testUnits->currentPage() }} di {{ $testUnits->lastPage() }}
    </div>
    {{ $testUnits->appends(request()->query())->links() }}
    <div class="clearfix"></div>
  </div>
@endsection
