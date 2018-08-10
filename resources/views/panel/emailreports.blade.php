@extends('layouts.panel')

@section('title') Reports email @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('emailreports') }}
@endsection

@section('content')
<div class="px-4 py-3 h3 border-bottom">
        Reports email
      </div>
      <div class="px-4 py-3">
            <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
        <div id="reports"></div>
      </div>
    </div>
@endsection

@section('scripts')
<script>
<!--
window.reportsData = {!! json_encode($reports) !!};
window.reportsEntity = {};
window.reportsFields = {!! json_encode($fields) !!};
window.statuses = {!! json_encode($statuses); !!};
window.shortcodes = {!! json_encode(\App\Shortcode::all()) !!};
-->
</script>
@endsection