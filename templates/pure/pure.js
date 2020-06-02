(function($) {
    "use strict";

    var banner = $('#serendipity_banner'); // use jQuery selector since uses follow-up methods
    var toggle = document.querySelector(".c-menu");
    if (toggle != null) {
        toggleHandler(toggle);
    }

    function toggleHandler(toggle) {
        toggle.addEventListener( "click", function(e) {
            e.preventDefault();

            (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");

            if ( this.classList.contains("is-active") === true ) {
                banner.slideDown(function() {
                    document.getElementById("buttonname").textContent="Hide Navigation";
                });
            } else {
                banner.slideUp(function() {
                    document.getElementById("buttonname").textContent="Show Navigation";
                });
            }
        });
    }
})(jQuery);

/* toggle trackback hint information box */
(function ($) {
    $('#trackback_url').next(".trackback-hint").hide();
    $('#trackback_url').click(function(e) {
        e.preventDefault();
        $(this).next(".trackback-hint").show();
    });
})(jQuery);
