
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(function () {
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
});
