@extends('layouts.popup')

@section('title') Carica immagine @endsection

@section('content')
    <div class="px-4 py-3 h5 border-bottom border-top">
      Carica immagine
    </div>
    <div class="px-2 py-3">
      <form action ="{{ route('panel.postUpload') }}" method="post" enctype="multipart/form-data">
        @method('post')
        @csrf
        <div class="input-group mb-3">
          <div class="custom-file">
            <label class="custom-file-label" for="file_input">Seleziona immagine</label>
            <input accept="image/*" type="file" class="custom-file-input{{ $errors->has('uploaded_file') ? ' is-invalid': ''}}" id="file_input" name="image">
            @if($errors->has('image'))
            <div class="invalid-feedback">
              @foreach($errors->get('image') as $err)
                {{ $err }}<br>
              @endforeach
            </div>
            @endif
          </div>
        </div>
        <div class="h5 mb-3">
          Anteprima
        </div>
        <div id="preview" class="text-center mb-4">
          <i>Nessun immagine &egrave; stata selezionata.</i>
        </div>
        <button id="submitBtn" type="submit" class="btn btn-primary" disabled>Carica e seleziona immagine</button>
      </form>
      <script>
      <!--
      window.onload = function () {
        $("#file_input").change(function() {
          if(this.files && this.files.length > 0) {
            var image = new Image();

            image.onload = function () {
              var img = document.createElement("img");
              img.className = 'img-fluid img-thumbnail rounded border';
              img.style.maxHeight = '150px';
              img.src = this.src;
              $('#preview').html(img);
              $("#submitBtn").prop("disabled", false)
            }
            image.onerror = function () {
              $("#preview").html("<b>L'immagine selezionata non &egrave; valida.</b>");
              $("#submitBtn").prop("disabled", true)
            }

            image.src = URL.createObjectURL(this.files[0]);
          } else {
            $("#preview").html("<i>Nessun immagine &egrave; stata selezionata.</i>");
            $("#submitBtn").prop("disabled", true)
          }
        })
      };
      -->
      </script>
    </div>
@endsection
