
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
var Pjax = require('pjax')
var axios = require('axios')

var pjax = new Pjax({
  selectors: ["title", "main", "#header-nav"],
  cacheBust: false
})

$(function () {
  document.addEventListener('pjax:send', function() {
    $("body").css("cursor", "progress");
    $(".loading").css("width", "40%");
  });

  document.addEventListener('pjax:complete', function() {
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

  $("body").on('focusout', '.image-preview', function() {
    var fieldName = $(this).attr("name");
    var thumbnail = $("#" + fieldName + "-thumbnail");
    if($(this).val().length > 0) {
      //thumbnail.attr("data-original", thumbnail.attr("src"));
      thumbnail.attr("src", $(this).val());
    } else {
      thumbnail.attr("src", thumbnail.attr("data-original"));
    }
  });
  $("body").on('click', '.facebook-img-fetch', function () {
    if($("#" + $(this).attr("data-field")).val().length > 0) {
      $("#" + $(this).attr("data-target")).val("https://graph.facebook.com/"+$("#" + $(this).attr("data-field")).val()+"/picture?type=large");
      $("#" + $(this).attr("data-target")).focus().blur();
    }
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
      response.data.forEach(function(elt) {
        $(result).append('<a class="list-group-item selectable-list-item" data-evt="'+evt+'" data-id="'+elt.id+'">'+elt.name+' <small><i>'+elt.email+'</i></small></a>')
      });
    })
    .catch(function (error) {
      console.err(error);
    });
    return false;
  });

  $("body").on('click', 'a[data-evt=select-seller]', function() {
    $('#select-seller').modal('toggle');
    $("#seller-id").val($(this).attr("data-id"));
    $("#seller-name").val($(this).text());
  })
});
