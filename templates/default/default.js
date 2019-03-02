/* toggle content/left sidebar markup nodes for responsiveness
 - DO NOT touch for this default engine, but if you need to avoid
   to happen possible sibling sidebars on the right between 980 and
   1024 px page width in your COPY theme, change both following sizes
   to 980. */
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
