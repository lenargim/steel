//import $ from './jquery';
// потребовать jQuery нормально
const $ = require('jquery');
// создать глобальные переменные $ и jQuery
global.$ = global.jQuery = $;

$('.side-menu__burger').on('click', function () {
  $('.side-menu').toggleClass('active');
})


$('body').mouseup(function (e) { // событие клика по веб-документу
  let div = $('.side-menu'); // тут указываем элемент
  if (!div.is(e.target) // если клик был не по нашему блоку
    && div.has(e.target).length === 0) { // и не по его дочерним элементам
    div.removeClass('active')
  }
});


// Homepage
let prevArrow = '<div class="banner__arrow left"><i class="arrow"></i></div>'
let nextArrow = '<div class="banner__arrow right"><i class="arrow"></i></div>'

$('#banner-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    appendArrows: $('.banner__slider-wrap'),
    prevArrow: prevArrow,
    nextArrow: nextArrow,
    draggable: false,
});

let $slider = $('#slider')
let sliderCounter = $('.slider-block__counter');


let updateSliderCounter = function (slick) {
    currentSlide = slick.slickCurrentSlide() + 1;
    slidesCount = slick.slideCount;
    sliderCounter.html(`${currentSlide}/${slidesCount}`);
};

$slider.on('init afterChange', function (event, slick) {
    updateSliderCounter(slick);
});

let defaultPrevArrow = $('.slider-block__arrow-box .default-arrow_left');
let defaultNextArrow = $('.slider-block__arrow-box .default-arrow_right');

$slider.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    appendArrows: $('.slider-block__arrow-box'),
    prevArrow: defaultPrevArrow,
    nextArrow: defaultNextArrow,
    draggable: false,
    responsive: [
        {
          breakpoint: 1279,
          settings: {
          draggable: true,
          }
        },
      ]
});

let reviewsSlider = $('#reviews-slider');
let reviewsSliderCounter = $('.reviews__slider-counter');
reviewsSlider.on('init afterChange', function (event, slick) {
    updateReviewsSliderCounter(slick);
});

let updateReviewsSliderCounter = function (slick) {
    currentSlide = slick.slickCurrentSlide() + 1;
    slidesCount = slick.slideCount - 1;
    reviewsSliderCounter.html(`${currentSlide}/${slidesCount}`);
};

let reviewsPrevArrow = $('.reviews__slider-arrow-box .default-arrow_left');
let reviewsNextArrow = $('.reviews__slider-arrow-box .default-arrow_right');

reviewsSlider.slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    arrows: true,
    appendArrows: $('.reviews__slider-arrow-box'),
    prevArrow: reviewsPrevArrow,
    nextArrow: reviewsNextArrow,
    draggable: false,
    infinite: false,
    variableWidth: true,
    adaptiveHeight: true,
    responsive: [
                {
                  breakpoint: 1279,
                  settings: {
                  variableWidth: true,
                  }
                },
                {
                  breakpoint: 767,
                  settings: {
                  slidesToShow: 1,
                  variableWidth: false,
                  draggable: true,
                  }
                },
        ]
});

/* Interior detailed Page */

let interiorSlider = $('#interior-slider');
let interiorSliderCounter = $('.interior-detailed__slider-counter');
interiorSlider.on('init afterChange', function (event, slick) {
    updateInteriorSliderCounter(slick);
});

let updateInteriorSliderCounter = function (slick) {
    currentSlide = slick.slickCurrentSlide() + 1;
    slidesCount = slick.slideCount;
    interiorSliderCounter.html(`${currentSlide}/${slidesCount}`);
};

let interiorPrevArrow = $('.interior-detailed__arrow-box .default-arrow_left');
let interiorNextArrow = $('.interior-detailed__arrow-box .default-arrow_right');

interiorSlider.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: interiorPrevArrow,
    nextArrow: interiorNextArrow,
    draggable: false,
    infinite: true,
    responsive: [
            {
              breakpoint: 1279,
              settings: {
              draggable: true,
              arrows: false
              }
            },
    ]
});

/* Interior detailed modal */

$('.interior-detailed__img').on('click', function(){
    $('.overlay_interior').addClass('active')
    $('.modal-slider').addClass('active')
    let modalSlider = $('.modal-slider__wrap')
    let modalSliderCounter = $('.modal-slider__counter')
    modalSlider.on('init afterChange', function (event, slick) {
        updateModalSliderCounter(slick);
    });
    let updateModalSliderCounter = function (slick) {
        currentSlide = slick.slickCurrentSlide() + 1;
        slidesCount = slick.slideCount;
        modalSliderCounter.html(`${currentSlide}/${slidesCount}`);
    };

    let modalPrevArrow = $('.modal__arrow_left');
    let modalNextArrow = $('.modal__arrow_right');

    modalSlider.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: modalPrevArrow,
        nextArrow: modalNextArrow,
        draggable: false,
        infinite: true
    });
})

$('.modal__close').on('click', function(){
    $('.overlay').removeClass('active')
    $('.modal').removeClass('active')
    let modalSlider = $('.modal-slider__wrap')
    modalSlider !== undefined ? modalSlider.slick('unslick') : false
})

$('.mobile-swipe').on('touchstart click', swipeFunction );
function swipeFunction(event) {
    $(event.target).removeClass('mobile-swipe');
}

$('.map__box-button').on('click', function(){
    $('.overlay').addClass('active')
    $('.modal-msg').addClass('active')
})


$(window).scroll(function() {
    let dark = $('.dark');
    let element = $('.inverting');
    dark.each(function() {
       let offsetT = $(this).offset().top
       let offsetB = $(this).offset().top + $(this).height();
       element.each(function() {
           if ( $(this).hasClass('processing') ) {
            return false;
           }
           let elementT = $(this).offset().top;
           let elementB = elementT + $(this).height();
           if ( elementT > offsetT && elementB < offsetB ) {
            $(this).addClass('invert processing')
           } else {
            $(this).removeClass('invert')
           }
       });
    });
    element.removeClass('processing')
});
