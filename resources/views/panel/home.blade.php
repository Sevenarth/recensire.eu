@extends('layouts.panel')

@section('title') Homepage @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('home') }}
@endsection

@section('content')
  <div class="px-4 py-3 m-0 h3 border-bottom">
  Test accettati oggi <span class="badge badge-secondary">{{ count($acceptedToday) }}</span>
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($acceptedToday as $status)
      <tr>
        <td class="p-2 align-middle"><img style="min-width: 50px; max-height: 50px" src="@if(empty($status->unit->testOrder->product->images[0])) /images/package.svg @else{{ $status->unit->testOrder->product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border"></td>
        <td class="p-2 align-middle"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle {{ !empty($status->unit->tester) ? 'tester-status-' . $status->unit->tester->status : '' }}">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="p-2 text-center"><i>Non ci sono stati test accettati oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>

  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test recensiti oggi <span class="badge badge-secondary">{{ count($reviewedToday) }}</span>
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($reviewedToday as $status)
      <tr>
        <td class="p-2 align-middle"><img style="min-width: 50px; max-height: 50px" src="@if(empty($status->unit->testOrder->product->images[0])) /images/package.svg @else{{ $status->unit->testOrder->product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border"></td>
        <td class="p-2 align-middle"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle {{ !empty($status->unit->tester) ? 'tester-status-' . $status->unit->tester->status : '' }}">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="p-2 text-center"><i>Non ci sono stati test recensiti oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>

  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test completati oggi <span class="badge badge-secondary">{{ count($completedToday) }}</span>
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($completedToday as $status)
      <tr>
        <td class="p-2 align-middle"><img style="min-width: 50px; max-height: 50px" src="@if(empty($status->unit->testOrder->product->images[0])) /images/package.svg @else{{ $status->unit->testOrder->product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border"></td>
        <td class="p-2 align-middle"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle {{ !empty($status->unit->tester) ? 'tester-status-' . $status->unit->tester->status : '' }}">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 align-middle relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="p-2 text-center"><i>Non ci sono stati test completati oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>
@endsection
