function checkWebP(callback) {
    var webP = new Image();
    webP.onload = webP.onerror = function () {
        callback(webP.height == 2);
    };
    webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
};

checkWebP(function(support) {
    if (!support) {
        $('a.serendipity_image_link').each(function() {
            var $currentA = $(this);
            var  dataHref = $currentA.attr('data-fallback');
            $currentA.attr('href', dataHref);
        });
    }
});

(function($) {
    $('#serendipity_replyTo').addClass('form-select');
    $('.form-group > .serendipity_emoticon_bar').attr('class', 'form-info alert alert-secondary');
    $('.serendipity_toggle_emoticon_bar.serendipityPrettyButton').attr('class', 'btn btn-outline-secondary btn-sm me-2');
    $('.serendipity_entrypaging_left a').addClass('btn btn-secondary');
    $('.serendipity_entrypaging_right a').addClass('btn btn-secondary');
    $('.serendipity_edit_nugget').attr('class', 'bi bi-pencil-square text-editicon serendipity_edit_nugget btn btn-admin btn-sm');
    $('.msg_notice.serendipity_subscription_off').attr('class', 'alert alert-warning serendipity_subscription_off').attr('role', 'alert');
    $('.serendipity_msg_important.msg_error').attr('class', 'alert alert-danger').attr('role', 'alert');
    $('.serendipity_msg_important').addClass('alert alert-secondary').attr('role', 'alert');
    $('.serendipity_msg_success').addClass('alert alert-success').attr('role', 'alert');
    $('.serendipity_msg_notice').addClass('alert alert-info').attr('role', 'alert');
    $('#category_submit').addClass('btn btn-outline-secondary btn-sm');
    $('.serendipity_freeTag_xmlTagEntry > .serendipity_xml_icon > img.serendipity_freeTag_xmlButton').replaceWith('<svg class="bi me-1" width="16" height="16" role="img" aria-labelledby="title"><title id="ftrss">XML</title><use xlink:href="#rss-fill"/></svg>');
    $('#serendipity_syndication_list .serendipity_xml_icon > img').replaceWith('<svg class="bi me-1" width="16" height="16" role="img" aria-labelledby="title"><title id="sycrss">XML</title><use xlink:href="#rss-fill"/></svg>');
    $('.trackback details > div > a').attr('class', 'btn btn-secondary btn-sm btn-admin trackbacks-delete');
    $('#trackback_url').click(function(e) { e.preventDefault(); $(this).next('.trackback-hint').show(); });
    $('.serendipity_entrypaging').addClass('mobile');
    $('a.serendipity_image_link[href=""]').each(function() { if (typeof $(this).data('fallback') !== 'undefined' || $(this).data("fallback")) { var linkHref = $(this).attr('data-fallback'); $(this).attr('href', linkHref); }});
})(jQuery);
