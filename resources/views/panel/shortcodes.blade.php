@extends('layouts.panel')

@section('title') Shortcodes @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('shortcodes') }}
@endsection

@section('content')
<div class="px-4 py-3 h3 border-bottom">
        Shortcodes
      </div>
      <div class="px-4 py-3">
            <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
          @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
          @endif
          <div id="shortcodes">
          </div>
      </div>
    </div>
@endsection

@section('scripts')
<script>
<!--
window.shortcodesData = {!! json_encode($shortcodes) !!};
-->
</script>
@endsection