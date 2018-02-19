@extends('layouts.panel')

@section('title')
@php if(!empty($seller)) echo 'Modifica venditore #'.$seller->id; else echo 'Nuovo venditore'; @endphp
@endsection

@section('breadcrumb')
@php echo !empty($seller) ? Breadcrumbs::render('sellers.edit', $seller) : Breadcrumbs::render('sellers.create') @endphp
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @php echo !empty($seller) ? 'Modifica venditore #'.$seller->id : 'Nuovo venditore' @endphp
  </div>
  <div class="px-4 py-3">
    @if(!empty($seller))
    @openForm('panel.sellers.update', 'patch', arg="seller->id")
    @else
    @openForm('panel.sellers.put', 'put')
    @endif
      @formTextfield('nickname', 'Pseudonimo', placeholder="Supermario", required="false", editMode="seller")
      @formTextfield('name', 'Nome venditore', placeholder="Mario Rossi", editMode="seller")
      @formTextfield('email', 'Indirizzo email', placeholder="manager@azienda.it", type="email", editMode="seller")
      @formTextfield('facebook', 'Facebook ID', prepend="facebook.com/profile.php?id=", placeholder="0000000", required="false", editMode="seller")
      @formTextfield('wechat', 'WeChat ID', prepend="@", placeholder="0000000", required="false", editMode="seller")
      <div class="row">
        <div class="col-3 text-center">
          <img style="max-height: 190px" data-original="/images/profile_image.svg" id="profile_image-thumbnail" src="@if(empty(old('profile_image'))) @if(!empty($seller->profile_image)) {{$seller->profile_image}} @else /images/profile_image.svg @endif @else{{ old('profile_image', '/images/profile_image.svg') }}@endif" class="img-fluid img-thumbnail rounded border">
        </div>
        <div class="col-9">
          @formTextfield('profile_image', 'Immagine del profilo', placeholder="http://", class="form-control image-preview", required="false", editMode="seller")
          <button class="btn btn-primary facebook-img-fetch" type="button" data-target="profile_image" data-field="facebook">Immagine da Facebook</button>
        </div>
      </div>
      <button type="submit" class="mt-3 mb-2 btn btn-primary">@php echo !empty($seller) ? 'Modifica venditore' : 'Aggiungi nuovo venditore' @endphp</button>
    @closeForm
  </div>
@endsection
