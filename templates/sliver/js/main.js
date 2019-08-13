/* Author: Ian Styx
   Sliver v.4.55 main.js
*/

/* Check if selector is after or before another certain selector */
$.fn.isAfter = function(sel){
  return this.prevAll(sel).length !== 0;
}
$.fn.isBefore= function(sel){
  return this.nextAll(sel).length !== 0;
}

$(function () {
    $(window).on("resize", function () {
        var windowsize = $(this).width();
        if (windowsize > 1200) {
            if ($('#blog').hasClass('sbjs')) {
                //console.log('blog has class sbjs');
                $('#blog.sbjs').removeClass('twobar-right sbjs').addClass('twobar-left')
                $('#sidebar_left').removeClass('sbmoveright');
            }
            if ($('#sidebar_middle').hasClass('sbm_right sbjs')) {
                //console.log('middle has class sbjs');
                $('#sidebar_middle.sbjs').removeClass('sbm_right sbjs').addClass('sbm_left');
            }
            if ($('#blog').hasClass('twobar-left') && ($('#blog.twobar-left').isBefore('#sidebar_left') || $('#blog.twobar-left').isBefore('#sidebar_middle'))) {
                $('#blog.twobar-left').insertAfter('#sidebar_left').insertAfter('#sidebar_middle'); // insert twice, since we don't know if both sidebars were set

            }
        }
        if (windowsize <= 1200) {
            if ($('#blog').hasClass('twobar-left') && ($('#blog.twobar-left').isAfter('#sidebar_left') || $('#blog.twobar-left').isAfter('#sidebar_middle'))) {
                $('#blog').removeClass('twobar-left').addClass('twobar-right sbjs').insertBefore('#sidebar_middle').insertBefore('#sidebar_left'); // insert twice, since we don't know if both sidebars were set
                //console.log('check resize 1200');
                $('#sidebar_left').attr('sidebar_left','sidebar_right').addClass('sbmoveright');
            }
            if ($('#sidebar_middle').hasClass('sbm_left')) {
                $('#sidebar_middle').removeClass('sbm_left').addClass('sbm_right sbjs');
            }
            if (windowsize < 1200) {
                if ($('#blog').hasClass('onebar-left')) {
                    $('#blog').insertBefore('#sidebar_middle').insertBefore('#sidebar_left');
                }
            }
            if ($('#sidebar_left').hasClass('sbmrv')) {
                $('#sidebar_left').removeClass('sbmrv').addClass( 'sbmoveright' );
            }
        }
        if (windowsize <= 800) {
            $('#sidebar_left').removeClass( 'sbmoveright' ).addClass('sbmrv');
            $('#sidebar_middle').removeClass('sbm_right').addClass('sbm_footer');
        } else {
            $('#sidebar_middle').removeClass('sbm_footer');
        }
    });
});

$(function() {
    if ( $('#open-nav').length ) {
        var nav = responsiveNav('.nav-collapse', {
            customToggle: "#open-nav",
            closeOnNavClick: true,
        });
        // it exists
    }
});

$(function() {
    $('.burger').click(function () {
        $('#sidebar_left').toggleClass('open');
        $('#sidebar_right').toggleClass('open');
    });
});

