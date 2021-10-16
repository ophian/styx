function addClass(selector, clsname) {
    els = document.querySelectorAll(selector);
    for (var i = 0; i < els.length; i++) {
        els[i].className += clsname;
    }
};

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

let exmSVG = '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>';

document.addEventListener("DOMContentLoaded", function() {

    addClass('#serendipity_replyTo', 'form-select'); // no space since added class

    let b = document.querySelector('.form-group > .serendipity_emoticon_bar');
    if (b !== null) b.setAttribute('class', 'form-info alert alert-secondary');
    let bp = document.querySelector('.serendipity_toggle_emoticon_bar.serendipityPrettyButton');
    if (bp !== null) bp.setAttribute('class', 'btn btn-outline-secondary btn-sm me-2');

    addClass('.serendipity_entrypaging_left a', 'btn btn-secondary'); // no space since added class

    addClass('.serendipity_entrypaging_right a', 'btn btn-secondary'); // no space since added class

    let n = document.querySelector('.serendipity_edit_nugget');
    if (n !== null) n.setAttribute('class', 'bi bi-pencil-square text-editicon serendipity_edit_nugget btn btn-admin btn-sm');

    let s = document.querySelector('.msg_notice.serendipity_subscription_off');
    if (s !== null) s.setAttribute('class', 'alert alert-warning serendipity_subscription_off');
    let so = document.querySelector('.alert.alert-warning.serendipity_subscription_off');
    if (so !== null) so.setAttribute('role', 'alert');

    // convert important error alerts
    let error = document.querySelectorAll('.serendipity_msg_important.msg_error');
    for(let i = 0; i < error.length; i++) {
        error[i].setAttribute('class', 'alert alert-danger');
        error[i].setAttribute('role', 'alert');
        error[i].insertAdjacentHTML('afterbegin', exmSVG);
    }

    // convert normal important alerts
    let important = document.querySelectorAll('.serendipity_msg_important');
    for(let i = 0; i < important.length; i++) {
        important[i].setAttribute('class', 'alert alert-secondary');
        important[i].setAttribute('role', 'alert');
    }

    // convert success message alerts
    let success = document.querySelectorAll('.serendipity_msg_success');
    for(let i = 0; i < success.length; i++) {
        success[i].setAttribute('class', 'alert alert-success');
        success[i].setAttribute('role', 'alert');
    }

    // convert notice message alerts
    let notice = document.querySelectorAll('.serendipity_msg_notice');
    for(let i = 0; i < notice.length; i++) {
        notice[i].setAttribute('class', 'alert alert-info');
        notice[i].setAttribute('role', 'alert');
    }

    addClass('#category_submit', 'btn btn-outline-secondary btn-sm'); // no space since added class

    let ftx = document.querySelectorAll('.serendipity_freeTag_xmlTagEntry > .serendipity_xml_icon > img.serendipity_freeTag_xmlButton');
    for(let i = 0; i < ftx.length; i++) {
        ftx[i].insertAdjacentHTML('afterend','<svg class="bi me-1" width="16" height="16" role="img" aria-labelledby="title"><title id="ftrss">XML</title><use xlink:href="#rss-fill"/></svg>');
        ftx[i].parentNode.removeChild(ftx[i]);
    }

    let spx = document.querySelectorAll('#serendipity_syndication_list .serendipity_xml_icon > img');
    for(let i = 0; i < spx.length; i++) {
        spx[i].insertAdjacentHTML('afterend','<svg class="bi me-1" width="16" height="16" role="img" aria-labelledby="title"><title id="sycrss">XML</title><use xlink:href="#rss-fill"/></svg>');
        spx[i].parentNode.removeChild(spx[i]);
    }

    let tbd = document.querySelectorAll('.trackback details > div > a');
    for(let i = 0; i < tbd.length; i++) {
        tbd[i].setAttribute('class', 'btn btn-secondary btn-sm btn-admin trackbacks-delete');
    }

    // hide and open trackback url info box
    let t = document.querySelector('#trackback_url');
    if (t !== null) t.addEventListener('click', function(e) { e.preventDefault(); if (t.nextElementSibling.classList.contains('d-none')) { t.nextElementSibling.classList.remove('d-none'); }});

    addClass('.serendipity_entrypaging', ' d-flex mb-3'); // add with space since added to exiting class selector
    addClass('.serendipity_entrypaging_left a.bt.btn-secondary', ' truncate pe-2'); // add with space since added to exiting class selector
    addClass('.serendipity_entrypaging_right a.btn.btn-secondary', ' truncate ps-2'); // add with space since added to exiting class selector

    let linkdata = document.querySelectorAll('a.serendipity_image_link[href=""]');
    if (linkdata !== null) {
        linkdata.forEach.call(
          this, function (elem) {
            if (typeof elem.data('fallback') !== 'undefined' || elem.data("fallback")) {
              var linkHref = elem.setAttribute('data-fallback');
              elem.setAttribute('href', linkHref);
            }
          }
        );
    }
});
