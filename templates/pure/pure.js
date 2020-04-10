(function($) {
    "use strict";

    var toggle = document.querySelector(".c-menu");
    if (toggle.length) {
        toggleHandler(toggle);
    }

    function toggleHandler(toggle) {
        toggle.addEventListener( "click", function(e) {
            e.preventDefault();

            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");

            if ( this.classList.contains("is-active") === true ) {
                $( "#serendipity_banner" ).slideDown(function() {
                    document.getElementById("buttonname").textContent="Hide Navigation";
                });
            } else {
                $( "#serendipity_banner" ).slideUp(function() {
                    document.getElementById("buttonname").textContent="Show Navigation";
                });
            }
        });
    }
})(jQuery);

(function ($) {
    // SVG polyfill
    //svg4everybody();
    $('#trackback_url').click(function(e) {
        e.preventDefault();
        $(this).next(".trackback-hint").show();
    });
})(jQuery);

/* toggle content/left sidebar markup nodes for responsiveness */
(function ($) {
    if ($(window).width() < 1024) {
        $("#serendipityLeftSideBar").before($("#content"));
    }
    else {
        $("#content").before($("#serendipityLeftSideBar"));
    }
    $(window).resize(function() {
        if ($(window).width() < 1024) {
            $("#serendipityLeftSideBar").before($("#content"));
            $("#serendipityRightSideBar").before($("#serendipityLeftSideBar"));
        }
        else {
            $("#content").before($("#serendipityLeftSideBar"));
        }
    });
})(jQuery);
