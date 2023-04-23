$(document).ready(function () {
  $(".slider").slick({
    // infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    arrows: true,
    dots: true,
    prevArrow: ".arrow_prev",
    nextArrow: ".arrow_next",
  });
});
