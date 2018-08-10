@extends('layouts.panel')

@section('title')
Reports negozio #{{ $store->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('stores.reports', $store) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    Reports negozio #{{ $store->id }}
  </div>
  <div class="px-4 py-3">
        <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
    <div id="reports"></div>
  </div>
@endsection
@php $store->load('seller') @endphp
@section('scripts')
<script>
<!--
window.reportsData = {!! json_encode(is_array($store->custom_reports) ? $store->custom_reports : []) !!};
window.reportsEntity = {!! json_encode($store) !!};
window.reportsFields = {!! json_encode($fields) !!};
window.statuses = {!! json_encode($statuses); !!};
window.shortcodes = {!! json_encode(\App\Shortcode::all()) !!};
-->
</script>
@endsection