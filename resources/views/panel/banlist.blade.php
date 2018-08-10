
@extends('layouts.panel')

@section('title') Ban list @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('banlist') }}
@endsection

@section('content')
<div class="px-4 py-3 h3 border-bottom">
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.banlist.export")}}" class="btn btn-outline-info"><i class="fa fa-fw fa-download"></i> Esporta</a>
    </div>
        Ban list
      </div>
      <div class="px-4 py-3">
            <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
          @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
          @endif
          <div id="banlist">
          </div>
      </div>
    </div>
@endsection

@section('scripts')
<script>
<!--
window.bppData = {!! json_encode($bpp) !!};
window.shortcodes = {!! json_encode(\App\Shortcode::all()) !!};
-->
</script>
@endsection