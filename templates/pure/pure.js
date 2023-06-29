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
            var      type = checkURL(url); // type is true when not a variation itself
            var  dataHref = $currentA.attr('data-fallback');
            var extension = url.split('.').pop();
            if (!type && extension == 'avif') {
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
            var      type = checkURL(url); // type is true when not a variation itself
            var  dataHref = $currentA.attr('data-fallback');
            var extension = url.split('.').pop();
            if (!type && extension == 'webp') {
                $currentA.attr('href', dataHref);
            }
        });
    }
});

const MATCH_SESSIONSTORAGE = true;

let dark_mode = sessionStorage.getItem('dark_mode');

if (dark_mode == null) {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches || dark_mode == "dark") {
        document.documentElement.setAttribute('data-dark-theme', 'dark');
        sessionStorage.setItem("dark_mode", "dark");
        document.getElementById('daynight').src = themePath + '/icons/sun-fill.svg';
    } else {
        sessionStorage.setItem("dark_mode", "light");
    }
} else if (dark_mode == 'dark') {
    document.documentElement.setAttribute('data-dark-theme', 'dark');
    sessionStorage.setItem("dark_mode", "dark");
    document.getElementById('daynight').src = themePath + '/icons/sun-fill.svg';
    document.getElementById('blink').title = "Theme: Light (Browser preferences|Session override)";
} else {
    document.documentElement.removeAttribute('data-dark-theme');
    sessionStorage.setItem("dark_mode", "light");
    document.getElementById('daynight').src = themePath + '/icons/moon-fill.svg';
    document.getElementById('blink').title = "Theme: Dark (Browser preferences|Session override)";
}

const dark = () => {
    let dark_mode = sessionStorage.getItem("dark_mode");
    localStorage.removeItem('theme');/* remove possible [ b53 ] theme toggler to not get in conflict within HTML comment RichTextEditor */
    if (dark_mode == "dark") {
        sessionStorage.setItem("dark_mode", "light");
        document.documentElement.removeAttribute('data-dark-theme');
        document.getElementById('daynight').src = themePath + '/icons/moon-fill.svg';
        $("#cke_1_contents iframe").contents().find('html').attr('data-dark-mode', 'light'); // only jQuery seems able to do that to the iframe
    } else {
        sessionStorage.setItem("dark_mode", "dark");
        document.documentElement.setAttribute('data-dark-theme', 'dark');
        document.getElementById('daynight').src = themePath + '/icons/sun-fill.svg';
        $("#cke_1_contents iframe").contents().find('html').attr('data-dark-mode', 'dark'); // only jQuery seems able to do that to the iframe
    }
}

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
