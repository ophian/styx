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
