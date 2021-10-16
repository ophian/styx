function checkURL(url) {
    return(url.match(/\.(webp|avif)$/) != null);
};

function checkWebP(callback) {
    var webP = new Image();
    webP.onload = webP.onerror = function () {
        callback(webP.height == 2);
    };
    webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
};

function checkAVIF(callback) {
    var AVIF = new Image();
    AVIF.onload = AVIF.onerror = function () {
        callback(AVIF.height, "avif");
    };
    AVIF.src = "data:image/avif;base64,AAAAIGZ0eXBhdmlmAAAAAGF2aWZtaWYxbWlhZk1BMUIAAADybWV0YQAAAAAAAAAoaGRscgAAAAAAAAAAcGljdAAAAAAAAAAAAAAAAGxpYmF2aWYAAAAADnBpdG0AAAAAAAEAAAAeaWxvYwAAAABEAAABAAEAAAABAAABGgAAAB0AAAAoaWluZgAAAAAAAQAAABppbmZlAgAAAAABAABhdjAxQ29sb3IAAAAAamlwcnAAAABLaXBjbwAAABRpc3BlAAAAAAAAAAIAAAACAAAAEHBpeGkAAAAAAwgICAAAAAxhdjFDgQ0MAAAAABNjb2xybmNseAACAAIAAYAAAAAXaXBtYQAAAAAAAAABAAEEAQKDBAAAACVtZGF0EgAKCBgANogQEAwgMg8f8D///8WfhwB8+ErK42A=";
};

// first run for possible AVIF files
checkAVIF(function(support) {
    if (!support) {
        $('a.serendipity_image_link').each(function() {
            var $currentA = $(this);
            var       url = $currentA.attr('href');
            var      type = checkURL(url);
            var  dataHref = $currentA.attr('data-fallback');
            if (type == null && url.pathname.split(".")[1] == 'avif') {
                $currentA.attr('href', dataHref);
                $currentA.attr('data-fallback', '');// set empty for following webP check
            }
        });
    }
});

// then do it again for WebP
checkWebP(function(support) {
    if (!support) {
        $('a.serendipity_image_link').each(function() {
            var $currentA = $(this);
            var       url = $currentA.attr('href');
            var      type = checkURL(url);
            var  dataHref = $currentA.attr('data-fallback');
            if (type == null && url.pathname.split(".")[1] == 'webp') {
                $currentA.attr('href', dataHref);
            }
        });
    }
});

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
