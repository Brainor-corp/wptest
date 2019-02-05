$(function () {

    //SVG Fallback
    if (!Modernizr.svg) {
        $("img[src*='svg']").attr("src", function () {
            return $(this).attr("src").replace(".svg", ".png");
        });
    }
    ;
    try {
        $.browserSelector();
        if ($("html").hasClass("chrome")) {
            $.smoothScroll();
        }
    } catch (err) {

    }
    ;

    $("img, a").on("dragstart", function (event) {
        event.preventDefault();
    });

});



$("ul.v1").on("click", ".init", function () {
    $(this).closest("ul.v1").children('li:not(.init)').toggle();
});
$(document).on('click','*[data-src="#hidden-content-map"]',function () {
    var src=$(this).find('img').attr('src');
    $('#hidden-content-map img').attr('src',src);
})
var allOptions1 = $("ul.v1").children('li:not(.init)');
$("ul.v1").on("click", "li:not(.init)", function () {
    allOptions1.removeClass('selected');
    $(this).addClass('selected');
    $("ul.v1").children('.init').html($(this).html());
    allOptions1.toggle();
});

$("ul.v2").on("click", ".init", function () {
    $(this).closest("ul.v2").children('li:not(.init)').toggle();
});

var allOptions2 = $("ul.v2").children('li:not(.init)');
$("ul.v2").on("click", "li:not(.init)", function () {
    allOptions2.removeClass('selected');
    $(this).addClass('selected');
    $("ul.v2").children('.init').html($(this).html());
    allOptions2.toggle();
});


$("ul.v3").on("click", ".init", function () {
    $(this).closest("ul.v3").children('li:not(.init)').toggle();
});

var allOptions3 = $("ul.v3").children('li:not(.init)');
$("ul.v3").on("click", "li:not(.init)", function () {
    allOptions3.removeClass('selected');
    $(this).addClass('selected');
    $("ul.v3").children('.init').html($(this).html());
    allOptions3.toggle();
});






$("span.cl").click(function () {
    $(this).parent().find(".open").toggleClass("active");
    if ($(this).parent().find(".open").hasClass("active")) {
        $(this).text("Скрыть");
    }
    else {
        $(this).text("Читать отзыв полностью");
    }
});
(function ($) {

    $(document).ready(function () {

        $('#generic-tabs div').hide();

        $('#generic-tabs div:first').show();

        $('#generic-tabs ul#tabs li:first').addClass('active');

        $('#generic-tabs ul#tabs li a').click(function () {

            $('#generic-tabs ul#tabs li').removeClass('active');

            $(this).parent().addClass('active');

            var currentTab = $(this).attr('href');
            $('#generic-tabs div').hide();

            $(currentTab).show();

            return false;
        });
    });
})(window.jQuery);
(function ($) {

    $(document).ready(function () {

        $('#generic-tabss div').hide();

        $('#generic-tabss div:first').show();

        $('#generic-tabss ul#tabs li:first').addClass('active');

        $('#generic-tabss ul#tabs li a').click(function () {

            $('#generic-tabss ul#tabs li').removeClass('active');

            $(this).parent().addClass('active');

            var currentTab = $(this).attr('href');

            $('#generic-tabss div').hide();

            $(currentTab).show();

            return false;
        });
    });
})(window.jQuery);

$(".show_effects").click(function () {
    $(".last_icon_pack").addClass("show");
    $(this).css('display', 'none');

});
$(".last_icon_pack .closer").click(function () {
    console.log('ok');
    $(".last_icon_pack").removeClass("show");
    $('.show_effects').css('display', 'flex');

});
$(document).ready(function () {
    $('#nav-icon4').click(function () {
        $(this).toggleClass('open');
        $(this).parent().find('ul').toggleClass('create');
        $(this).parent().toggleClass('add_height');
    });


    $('.item_video').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false
    });
});

$('.page-template-complited-works .wide_button').click(function(e){
    e.preventDefault();
    var href = $(this).attr('href');
    var currEl = $('.item_works  ').length;
    console.log(href+'?quest='+(currEl+3));
    $.ajax({
        url: href,

    }).done(function(res) {
        $( ".block_complited_works.wrapper" )(res);
    });
});


$('.list-unstyled.v2 li').click(function () {
    $('input[name=data]').val($(this).text());
});
$('.list-unstyled.v3 li').click(function () {
    $('input[name=time]').val($(this).text());
});
$('.list-unstyled.v1 li[data-value]').click(function () {
    var city = $(this).attr('data-value');

    $('p.gorod span').text($(this).attr('data-value'));
    $('.third_block .text a').text($(this).attr('data-phone'));
    $('#hidden-content-map img').attr("src",$(this).attr('data-img'));
    $('.third_block a.btn').attr('data-city' , city);
});
$('.third_block a.btn').click(function () {
var mess = $('.third_block a.btn').attr('data-city');
$('#hidden-content input.hidden').attr('value' , mess);
$('#hidden-content input.hidden').val(mess);

    });

$('.s_block ul li[data-utm-key="novg"]').click(function () {
$('#tabs > li:nth-child(2) > a').trigger('click');
    });
$('.s_block ul li[data-utm-key="murm"]').click(function () {
$('#tabs > li:nth-child(3) > a').trigger('click');
    });
$('.s_block ul li[data-utm-key="spb"]').click(function () {
$('#tabs > li:nth-child(1) > a').trigger('click');
    });
$(document).ready(function(){   


    var params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            var a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
        );

    if ( params['utm_campaign']){
     $('.s_block ul li').each(function (argument) { 

         if(params['utm_campaign'].indexOf($(this).attr('data-utm-key'))>0){
           $('p.gorod span').text($(this).attr('data-value'));
           $('.third_block .text a').text($(this).attr('data-phone'));
           $('#hidden-content-map img').attr("src",$(this).attr('data-img'));
           $('.s_block ul li.init').text( $(this).text());
         
       }

   })
 }

 
 $(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
        $('#scroller').fadeIn();
    } else {
        $('#scroller').fadeOut();
    }
});
 $('#scroller').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 400);
    return false;
});
});
function forSidebar(window_size) {
    var sidebar_padding = (window_size-1170)/2;
    var size_of_sidebar = 287;
    if (window_size<1200) {
       sidebar_padding = (window_size-970)/2;
       size_of_sidebar = 250;
   }
   if (window_size<992) {
    $(".sidebar").css("width", '100%');
    $(".cage").css('width', "100%");
    return false
}
calc_size = sidebar_padding+size_of_sidebar;
$(".sidebar").css("width", calc_size+"px");
$(".cage").css('width', window_size - calc_size+"px");
}
$(window).on('resize', function() {
    forSidebar($(this).width());

});
var h = $('.price_list table tr:first-child td:first-child, .questions table tr:first-child td:first-child');
var new_h = h.map(function(el, g){
    return $(g).height();
});
$(document).ready(function(){
    forSidebar($(this).width());
    console.log(new_h);
    for (var i = 0; i < $('.wrapp_for_table').length; i++) {
        $($('.wrapp_for_table')[i]).css({'height': new_h[i]+'px'});
    }
// $('.wrapp_for_table').css({"height": "51px"});
$($('.wrapp_for_table')[0]).css({"height": "auto"});
setTimeout(range_company, 1000);
$(".slider_rew").slick({
 infinite: true,
 slidesToShow: 2,
 responsive: [
 { breakpoint: 768,
  settings: {
    slidesToShow: 1,
}
}
]

});
$('.add_menu').parent().addClass('sub-menu');
});

$('.nav_sidebar').click(function(){
    if ($(this).hasClass('active')) {
        $('.sidebar').animate({"height": "65px"}, 200);
        $(this).removeClass('active');
    }else{
        $('.sidebar').css({"height": "auto"});
        $(this).addClass('active');
    }
});

$(".wrapp_for_table table tr:first-child td:first-child").on('click', function(){
    var elem = $(this).parent().parent().parent();
    if (elem.hasClass('active')) {
        $(".wrapp_for_table table").removeClass('active');
        for (var i = 0; i < $('.wrapp_for_table').length; i++) {
            $($('.wrapp_for_table')[i]).css({'height': new_h[i]+'px'});
        }

    }else{
        $(".wrapp_for_table table").removeClass('active');
        for (var i = 0; i < $('.wrapp_for_table').length; i++) {
            $($('.wrapp_for_table')[i]).css({'height': new_h[i]+'px'});
        }
        elem.parent().css({"height": "auto"});
        elem.addClass('active');
    }
});

function range_company(){
    var range = $('.about_company').find(".inner");
    for (var i = 0; i < range.length; i++) {
       if ($(range[i]).attr("data-val")<2000) {
        if ($(range[i]).attr('data-val') > 800) {
            var width_range = $(range[i]).attr('data-val')/20+"%";
            $(range[i]).animate({"width": width_range}, 500);
            $(range[i]).html($(range[i]).attr('data-val'));
        }else{
            $(range[i]).animate({"width": "40px"}, 500);
            $(range[i]).html($(range[i]).attr('data-val'));
        }
    }else{
        $(range[i]).animate({"width": "100%"}, 500);
        $(range[i]).html(2000);
    }
}
}

$(document).ready(function () {
    $('body').css('opacity', '1');
    $(".loader").delay(400).fadeOut("slow");

});

window.onbeforeunload = function() {
  $('body').fadeOut('fast');
};
