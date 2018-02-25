
require('./bootstrap');

window.MD = require('markdown-it')();
var moment = require('moment');
var countdown = require('countdown');
window.Slick = require('slick-carousel');

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

  $('.slideshow').slick({
	   dots: true,
     arrows: false
  });

  singular = ' millisecondo| secondo| minuto| ora| giorno| settimana| mese| anno| decennio| secolo| millenio'
	plural = ' millisecondi| secondi| minuti| ore| giorni| settimane| mesi| anni| decenni| secoli| millenni'
	last = ' e '

  countdown.setLabels(singular, plural, last);

  $(".countdown").each(function(){
      var time = moment($(this).attr("data-time"));
      var obj = $(this);
      obj.text(countdown(time).toString())
      setInterval(function () {
        obj.text(countdown(time).toString())
      }, 1000);
  });
});