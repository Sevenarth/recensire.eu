@extends('layouts.panel')

@section('title')
@php if(!empty($tester->id)) echo 'Modifica tester #'.$tester->id; else echo 'Nuovo tester'; @endphp
@endsection

@section('breadcrumb')
@php echo !empty($tester->id) ? Breadcrumbs::render('testers.edit', $tester) : Breadcrumbs::render('testers.create') @endphp
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @php echo !empty($tester->id) ? 'Modifica tester #'.$tester->id : 'Nuovo tester' @endphp
  </div>
  <div class="px-4 py-3">
    @if(!empty($tester->id))
    @openForm('panel.testers.update', 'patch', arg="tester->id")
    @else
    @openForm('panel.testers.put', 'put')
    @endif
      @formTextfield('name', 'Nome tester', placeholder="Mario Rossi", editMode="tester")
      @formTextfield('email', 'Indirizzo email', placeholder="me@testers.it", type="email", required="false", editMode="tester")
      <fieldset class="form-group">
        <label for="amazon_profiles">Profili Amazon</label>
        <div class="input-group">
          <input class="form-control{{ $errors->has('amazon_profiles.0') ? ' is-invalid' : '' }}" value="{{ old('amazon_profiles.0', (!empty($tester->amazon_profiles[0])) ? $tester->amazon_profiles[0] : '') }}" type="text" name="amazon_profiles[]" placeholder="http://" required>
          <div class="input-group-append">
            <button type="button" id="add-amazon-profile" class="btn btn-success"><i class="fas fa-plus fa-fw"></i></button>
          </div>
          @if($errors->has('amazon_profiles.0'))
          <div class="invalid-feedback">
            @foreach($errors->get('amazon_profiles.0') as $err)
              {{ $err }} <br>
            @endforeach
          </div>
          @endif
        </div>
        <div id="extra-amazon-profiles">
          @if(count(old('amazon_profiles', $tester->amazon_profiles)) > 1)
            @for ($i=1; $i < count(old('amazon_profiles', $tester->amazon_profiles)); $i++)
              <div class="input-group mt-2">
                <input class="form-control{{ $errors->has('amazon_profiles.'.$i) ? ' is-invalid' : '' }}" type="text" name="amazon_profiles[]" value="{{ old('amazon_profiles.'.$i, $tester->amazon_profiles[$i]) }}" placeholder="http://" required>
                <div class="input-group-append">
                  <button type="button" class="remove-ig btn btn-danger"><i class="fas fa-times fa-fw"></i></button>
                </div>
                @if($errors->has('amazon_profiles.'.$i))
                <div class="invalid-feedback">
                  @foreach($errors->get('amazon_profiles.'.$i) as $err)
                    {{ $err }} <br>
                  @endforeach
                </div>
                @endif
              </div>
            @endfor
          @endif
        </div>
      </fieldset>
      <fieldset class="form-group">
        <label for="facebook_profiles">Profili Facebook <small class="text-muted">(opzionale)</small></label>
        <div class="input-group">
          <input class="form-control{{ $errors->has('facebook_profiles.0') ? ' is-invalid' : '' }}" value="{{ old('facebook_profiles.0', (!empty($tester->facebook_profiles[0])) ? $tester->facebook_profiles[0] : '') }}" type="text" name="facebook_profiles[]" placeholder="0000000">
          <div class="input-group-append">
              <button type="button" class="set-profile-image btn btn-outline-primary" title="Imposta come immagine del profilo"><i class="fas fa-fw fa-user-circle"></i></button>
              <button type="button" id="add-facebook-profile" class="btn btn-success"><i class="fas fa-plus fa-fw"></i></button>
          </div>
          @if($errors->has('facebook_profiles.0'))
          <div class="invalid-feedback">
            @foreach($errors->get('facebook_profiles.0') as $err)
              {{ $err }} <br>
            @endforeach
          </div>
          @endif
        </div>
        <div id="extra-facebook-profiles">
          @if(count(old('facebook_profiles', $tester->facebook_profiles)) > 1)
            @for ($i=1; $i < count(old('facebook_profiles', $tester->facebook_profiles)); $i++)
            <div class="input-group mt-2">
              <input class="form-control{{ $errors->has('facebook_profiles.'.$i) ? ' is-invalid' : '' }}" type="text" name="facebook_profiles[]" value="{{ old('facebook_profiles.'.$i, $tester->facebook_profiles[$i])}}" placeholder="0000000" required>
              <div class="input-group-append">
                <button type="button" class="set-profile-image btn btn-outline-primary" title="Imposta come immagine del profilo"><i class="fas fa-fw fa-user-circle"></i></button>
                <button type="button" class="remove-ig btn btn-danger"><i class="fas fa-times fa-fw"></i></button>
              </div>
              @if($errors->has('facebook_profiles.'.$i))
              <div class="invalid-feedback">
                @foreach($errors->get('facebook_profiles.'.$i) as $err)
                  {{ $err }} <br>
                @endforeach
              </div>
              @endif
            </div>
            @endfor
          @endif
        </div>
      </fieldset>
      @formTextfield('wechat', 'WeChat ID', prepend="@", placeholder="0000000", required="false", editMode="tester")
      <div class="row">
        <div class="col-3 text-center">
          <img style="max-height: 190px" data-original="/images/profile_image.svg" id="profile_image-thumbnail" src="@if(empty(old('profile_image'))) @if(!empty($tester->profile_image)) {{$tester->profile_image}} @else /images/profile_image.svg @endif @else{{ old('profile_image', '/images/profile_image.svg') }}@endif" class="img-fluid img-thumbnail rounded border">
        </div>
        <div class="col-9">
          @formTextfield('profile_image', 'Immagine del profilo', placeholder="http://", class="form-control image-preview", required="false", editMode="tester")
          <button class="btn btn-primary facebook-img-fetch" type="button" data-target="profile_image" data-field="facebook">Immagine da Facebook</button>
          <button class="btn btn-primary upload-image" id="upload-image" data-page="{{ route("panel.upload") }}" data-target="profile_image" type="button">Carica immagine</button>
        </div>
      </div>
      <button type="submit" class="mt-3 mb-2 btn btn-primary">@php echo !empty($tester->id) ? 'Modifica tester' : 'Aggiungi nuovo tester' @endphp</button>
    @closeForm
  </div>
@endsection
