
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Bloodhound = require("typeahead.js");
require('bootstrap-tagsinput');
import SimpleMDE from 'simplemde';
import 'simplemde/dist/simplemde.min.css';
window.MD = require('markdown-it')();
window.SimpleMDE = SimpleMDE;
var moment = require('moment');

$(function () {
  moment.locale($("html").attr("lang"));

  $(".markdown").each(function() {
    $(this).html(MD.render($(this).text()));
  });

  $(".relative-time").each(function() {
    var date = $(this).text();
    $(this).attr("title", moment(date).format('llll'));
    $(this).text(moment(date).fromNow())
  });

  document.addEventListener('pjax:send', function() {
    $("body").css("cursor", "progress");
    $(".loading").css("width", "40%");
  });

  document.addEventListener('pjax:complete', function() {
    $(".markdown").each(function() {
      $(this).html(MD.render($(this).text()));
    });
    $("body").css("cursor", "default");
    $(".loading").css("width", "100%");
    setTimeout(function() {
      $(".loading").fadeOut(400, function() {
        $(".loading").css("width", "0%");
        $(".loading").show();
      });
    }, 200);
  });

  $("body").on('click', '.remove-confirmation', function() {
    if($(this).attr("data-popover-toggled") == "true") {
      $("#" + $(this).attr("aria-describedby")).remove()
      return true;
    }
    $(this).attr("data-popover-toggled", "true");
    $(this).popover('toggle');
    return false;
  });

  $("body").on('hidden.bs.popover', '.remove-confirmation', function() {
    $(this).attr("data-popover-toggled", "false");
  });

  var updateImage = function(obj) {
    var thumbnail = $("#profile_image-thumbnail");
    if($('#profile_image').val().length > 0)
      thumbnail.attr("src", $('#profile_image').val());
    else
      thumbnail.attr("src", thumbnail.attr("data-original"));
  };

  $("body").on('focusout', '.image-preview', updateImage);
  window.updateImage = updateImage;

  $("body").on('click', '.facebook-img-fetch', function () {
    if($("#" + $(this).attr("data-field")).val().length > 0) {
      $("#" + $(this).attr("data-target")).val("https://graph.facebook.com/"+$("#" + $(this).attr("data-field")).val()+"/picture?type=large");
      $("#" + $(this).attr("data-target")).focus().blur();
    }
  });

  $("body").on('click', '.upload-image', function () {
    var uploadWindow = window.open($(this).attr("data-page"),'uploader','height=480,width=350');
    if (window.focus)
      uploadWindow.focus()
  });

  $("body").on('submit', '.fakelink-get', function () {
    var link = document.createElement('a');
    link.id = 'fakelink-get';
    link.href = $(this).attr("data-action") + "?" + $(this).serialize();
    link.style.display = "none";
    $("main").append(link);
    $("#fakelink-get")[0].click();
    return false;
  });

  $('body').on('shown.bs.modal', '#select-seller', function () {
    $('#seller-search').focus();
  });

  $("body").on('submit', '.json-request', function () {
    var result = $(this).attr("data-result");
    var evt = $(this).attr("data-event");
    $(result).html('');
    axios.post($(this).attr("data-action"), $(this).serialize())
    .then(function (response) {

      if(response.data.length > 0) {
        response.data.forEach(function(elt) {
          $(result).append('<a class="list-group-item selectable-list-item" data-evt="'+evt+'" data-id="'+elt.id+'">'+(elt.nickname && elt.nickname.length > 0 ? elt.nickname + " - " : '')+elt.name+(elt.email ? ' <small><i>'+elt.email+'</i></small>': '') + '</a>')
        });
      } else
        $(result).append('<li class="list-group-item"><i>Nessun elemento presente con questi criteri di ricerca</i></li>')
    })
    .catch(function (error) {
      console.err(error);
    });
    return false;
  });

  $("body").on('click', 'a[data-evt=select-seller]', function() {
    $('#select-seller').modal('toggle');
    $("#seller-id").val($(this).attr("data-id"));
    $("#seller-name").val($(this).html().replace(/<[\w]+>.*<\/[\w]+>/ig, '').trim());
  })

  $("body").on('click', 'a[data-evt=select-store]', function() {
    $('#select-store').modal('toggle');
    $("#store-id").val($(this).attr("data-id"));
    $("#store-name").val($(this).html().replace(/<[\w]+>.*<\/[\w]+>/ig, '').trim());
  })

  $("body").on('click', 'a[data-evt=select-tester]', function() {
    $('#select-tester').modal('toggle');
    $("#tester-id").val($(this).attr("data-id"));
    $("#tester-name").val($(this).html().replace(/<[\w]+>.*<\/[\w]+>/ig, '').trim());
  })

  $("body").on('click', '.image-field', function() {
    var imageId = $(this).attr("id");
    if(imageId != "image-add") {
      var editMode = $("#images-box").attr("data-editMode");
      $("#images-box").attr("data-editMode", editMode == "true" ? "false" : "true");

      if(editMode == "true")
        $(this).removeClass("active-image");
      else
        $(this).addClass("active-image");

      $("#images-box").children().each(function(i, elt) {
        var current = $(elt);
        var id = current.attr("id");
        if(id == imageId + "-box") {
          if(editMode == "true")
            current.addClass("d-none")
          else
            current.removeClass("d-none");
        } else if(id != imageId + "-wrapper") {
          if(editMode == "true" && !current.hasClass("image-box"))
            current.removeClass("d-none");
          else
            current.addClass("d-none");
        }
      })
    }
  });

  $("body").on('click', '#image-add', function() {
    var quantity = parseInt($("#images-box").attr("data-quantity"));
    if(quantity < 1)
      quantity = 1;

    var index = quantity+1;
    $("#images-box").attr("data-quantity", index);

    $(this).parent().before(`<div id="image-`+index+`-wrapper" class="col-3 my-2 px-3">
      <img id="image-`+index+`" src="/images/package.svg" class="img-fluid rounded border image-field">
    </div>
    <div id="image-`+index+`-box" class="image-box col-9 d-none">
      <div class="rounded border px-3 py-3">
        <fieldset class="form-group">
          <label for="image-`+index+`-field">Link immagine</label>
          <input type="text" name="images[]" id="image-`+index+`-field" data-target="image-`+index+`" placeholder="http://" class="image-field-input form-control">
        </fieldset>
        <div class="btn-group">
          <button class="btn btn-primary upload-imageBox" data-target="image-`+index+`" type="button">Carica immagine</button>
          <button type="button" class="btn btn-danger image-remove" data-target="image-`+index+`">Elimina immagine</button>
        </div>
      </div>
    </div>`);
  });

  $("body").on('click', '.image-remove', function() {
    var imageId = $(this).attr("data-target");
    $("#" + imageId + "-box").remove();
    $("#" + imageId + "-wrapper").remove();

    $("#images-box").children().each(function(i, elt) {
      var current = $(elt);
      var id = current.attr("id");
      if(!current.hasClass("image-box"))
          current.removeClass("d-none");
    })
  });

  $("body").on('click', '.upload-imageBox', function () {
    var uploadWindow = window.open($("#images-box").attr("data-page") + "?field=" + $(this).attr("data-target"),'uploader','height=480,width=350');
    if (window.focus)
      uploadWindow.focus()
  });

  var updateImageField = function(obj) {
    if(typeof obj === 'object')
      var current = $(this),
          thumbnail = $("#" + $(this).attr("data-target"));
    else
      var current = $("#"+obj+"-field"),
          thumbnail = $("#"+obj);

    if(current.val().length > 0)
      thumbnail.attr("src", current.val());
    else
      thumbnail.attr("src", '/images/package.svg');
  };

  $("body").on('focusout', '.image-field-input', updateImageField);
  window.updateImageField = updateImageField;

  $("body").on('change', '.markdown', function() {
    $(this).html(MD.render($(this).text()));
  })

  $("body").on('click', '.remove-ig', function () {
    $(this).parent().parent().remove();
  })

  $("body").on('click', '#add-amazon-profile', function () {
    $("#extra-amazon-profiles").append(`
    <div class="input-group mt-2">
      <input class="form-control" type="text" name="amazon_profiles[]" placeholder="http://" required>
      <div class="input-group-append">
        <button type="button" class="remove-ig btn btn-danger"><i class="fas fa-times fa-fw"></i></button>
      </div>
    </div>`)
  });

  $("body").on('click', '#add-facebook-profile', function () {
    $("#extra-facebook-profiles").append(`
    <div class="input-group mt-2">
      <input class="form-control" type="text" name="facebook_profiles[]" placeholder="0000000" required>
      <div class="input-group-append">
        <button type="button" class="set-profile-image btn btn-outline-primary" title="Imposta come immagine del profilo"><i class="fas fa-fw fa-user-circle"></i></button>
        <button type="button" class="remove-ig btn btn-danger"><i class="fas fa-times fa-fw"></i></button>
      </div>
    </div>`)
  });

  $("body").on('click', '.set-profile-image', function () {
    var fbid = $($(this).parent().parent().children()[0]).val()
    if(fbid.length > 0) {
      $("#profile_image").val("https://graph.facebook.com/"+fbid+"/picture?type=large");
      $("#profile_image").focus().blur();
    }
  })
});
