@extends('layouts.panel')

@section('title') Opzioni @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('options') }}
@endsection

@section('content')
<div class="px-4 py-3 h3 border-bottom">
  Opzioni
</div>
<div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    @openForm('panel.options.update', 'post')
    <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
    <fieldset class="form-group">
        <label for="header-data">Header frontpage</label>
        <textarea id="header-data" name="header-data">{{ !empty($options['header-data']) ? $options['header-data'] : '' }}</textarea>
    </fieldset>
    <fieldset class="form-group">
        <label for="footer-data">Footer frontpage</label>
        <textarea id="footer-data" name="footer-data">{{ !empty($options['footer-data']) ? $options['footer-data'] : '' }}</textarea>
    </fieldset>
    <button type="submit" class="mt-3 mb-2 btn btn-primary">Aggiorna opzioni</button>
    @closeForm
</div>
@endsection

@section('scripts')
<script>
<!--
$("#header-data").mde();
$("#footer-data").mde();
-->
</script>
@endsection
