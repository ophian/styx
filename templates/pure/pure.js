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

/* Make sure to have a independent "top-of-page" jumpback on mobiles
   with non-displayed (hidden and therefore being a non active selector) #serendipty_banner child anchor */
(function ($) {
    if (navigator.userAgent.indexOf('Mobile') !== -1 && $('#serendipity_banner').is(':hidden')) {
        $('body').prepend('<a id="topofpage"></a>');
    }
})(jQuery);

/* Allows color threaded nested blockquote styling */
(function ($) {
  let depth = 0;
  $('.post_content blockquote').each(function(index, element){
    depth = $(this).parents('blockquote').length;
    if (depth === 0) {
        $(element).addClass('even');
    } else {
        // is is nested
        $(this).addClass(depth % 2 === 1 ? 'odd' : 'even');
    }
  });
})(jQuery);

/* We had to remove the required attribute on the textarea element for Chromium to avoid:
   "An invalid form control with name=serendipity[comment]' is not focusable." */
(function ($) {
    $('#serendipity_comment').on('submit', function(e) {
        // check vanilla textarea vs TinyMCE area
        if ($("#serendipity_commentform_comment").style.display !== 'none') {
            if (!$("#serendipity_commentform_comment").val().trim()) {
                console.log('contents is empty, fill it!');
                e.preventDefault(); // cancel submit
            }
        } else {
            if (!$("#serendipity_commentform_comment_ifr").val().trim()) {
                console.log('HTMLcomment contents is empty, fill it!');
                e.preventDefault(); // cancel submit
            }
        }
    });
})(jQuery);

/* Floated entries navigation panel */
if (quickAccessPanel === true && $(window).width() >= 1024) {
    // Allow a dynamic navigation in right sidebar - if entries are sooooo looooong... and I'm too lazy to scroll... !
    const sidebar = document.getElementById("serendipityRightSideBar");

    const tocNav = document.createElement("nav");
    tocNav.setAttribute("id", "toc", "aria-label", "floating Sidebarnavigation");
    // add the newly created element and its content into the DOM
    const currentToc = document.getElementById("toc");
    sidebar.appendChild(tocNav, currentToc);

    const articles = document.querySelectorAll('#content > section > article.post');
    const toc = document.getElementById('toc'); // Now it is there; Fill it up with anchors

    articles.forEach((art, i) => {
      const base = art.id || 'article';
      const anchor = `${base}-${i}`;       // Unique Key: foo-0, foo-1, foo-2 ...
      art.dataset.anchor = anchor;         // Targeting data-anchor="foo-1" etc

      const label = art.dataset.navLabel || art.querySelector('h2')?.textContent || anchor;
      const a = document.createElement('a');
      a.href = `#${anchor}`;
      a.textContent = label;
      a.dataset.target = anchor;
      toc.appendChild(a);
    });

    // Add a header to the entries list
    const tocHead = document.createElement("h3");
    const tocLang = {
      en:    { title: 'Quick Access Panel' },
      de:    { title: 'Schnellzugriffsleiste' },
      da:    { title: 'Hurtigadgangspanel' },
      es:    { title: 'Panel de acceso rápido' },
      fr:    { title: 'Panneau d\'accès rapide' },
      fi:    { title: 'Pikakäyttöpaneeli' },
      cz:    { title: 'Panel rychlého přístupu' },
      sk:    { title: 'Panel rýchleho prístupu' },
      nl:    { title: 'Sneltoegang paneel' },
      is:    { title: 'Skjótaðgangsspjald' },
      tr:    { title: 'Hızlı Erişim Paneli' },
      se:    { title: 'Snabbåtkomstpanel' },
      pt:    { title: 'Painel de acesso rápido' },
      pt_PT: { title: 'Painel de acesso rápido' },
      bg:    { title: 'Панел за бърз достъп' },
      hu:    { title: 'Gyorselérési panel' },
      no:    { title: 'Hurtigtilgangspanel' },
      pl:    { title: 'Panel szybkiego dostępu' },
      ro:    { title: 'Panou acces rapid' },
      it:    { title: 'Pannello accesso rapido' },
      ru:    { title: 'Панель быстрого доступа' },
      fa:    { title: 'پانل دسترسی سریع' },
      tw:    { title: '快速存取面板' },
      tn:    { title: '快速存取面板' },
      zh:    { title: '快速访问面板' },
      cn:    { title: '快速访问面板' },
      ja:    { title: 'クイックアクセスパネル' },
      ko:    { title: '빠른 접근 패널' },
      sa:    { title: 'لوحة الوصول السريع' },
      ta:    { title: 'விரைவு அணுகல் பலகம்' },
    };

    tocHead.textContent = (typeof language == 'undefined')
      ? tocLang['en'].title
      : (tocLang[language] ?? tocLang['en']).title;
    toc.prepend(tocHead);

    const content = document.getElementById('content');
    const sidebarSections = document.querySelectorAll('#serendipityRightSideBar > section');

    // Place toTop anchor underneath
    const up = document.createElement('div');
          up.setAttribute("class", "nav-panel");
    const prev = document.querySelector('nav.pager .pager_prev a');
    const next = document.querySelector('nav.pager .pager_next a');
    const down = document.getElementById('footer');
          down.dataset.anchor = 'bottomofpage';
    const last = sidebarSections[sidebarSections.length - 1];
          last.dataset.anchor = 'sidebar-end';
    const left = document.querySelector('#content div.serendipity_entrypaging span.serendipity_entrypaging_left');
    const right = document.querySelector('#content div.serendipity_entrypaging span.serendipity_entrypaging_right');
    const start = document.querySelector('#serendipity_banner > h1 a');
    //let last = 0;
//console.log(left, right);
    if (left || right || prev || next) {
        up.insertAdjacentHTML("beforeend", `<a href="${start.href}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-left" viewBox="0 0 16 16">
                              <title>blog start</title>
                              <path fill-rule="evenodd" d="M7.364 3.5a.5.5 0 0 1 .5-.5H14.5A1.5 1.5 0 0 1 16 4.5v10a1.5 1.5 0 0 1-1.5 1.5h-10A1.5 1.5 0 0 1 3 14.5V7.864a.5.5 0 1 1 1 0V14.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5v-10a.5.5 0 0 0-.5-.5H7.864a.5.5 0 0 1-.5-.5"/>
                              <path fill-rule="evenodd" d="M0 .5A.5.5 0 0 1 .5 0h5a.5.5 0 0 1 0 1H1.707l8.147 8.146a.5.5 0 0 1-.708.708L1 1.707V5.5a.5.5 0 0 1-1 0z"/>
                            </svg>
                        </a>`);
    }
    if (prev) {
        up.insertAdjacentHTML("beforeend", `<a href="${prev.href}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                              <title>to previous page</title>
                              <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                              <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                            </svg>
                        </a>`);
    }
    if (left) {
        up.insertAdjacentHTML("beforeend", `<a href="${left.lastElementChild.href}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                              <title>${left.textContent}</title>
                              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                            </svg>
                        </a>`);
    }
    up.insertAdjacentHTML("beforeend", `<a href="#topofpage">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up" viewBox="0 0 16 16">
                              <title>to top of page</title>
                              <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"></path>
                              <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"></path>
                            </svg>
                        </a>
                        <a href="#bottomofpage" data-target="bottomofpage">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-down" viewBox="0 0 16 16">
                              <title>to bottom of page</title>
                              <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z"/>
                              <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                            </svg>
                        </a>
                        <a href="#sidebar-end" data-target="sidebar-end">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16">
                              <title>to last sidebar widget</title>
                              <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5m-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5"/>
                            </svg>
                        </a>`);
    if (right) {
        up.insertAdjacentHTML("beforeend", `<a href="${right.firstElementChild.href}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                              <title>${right.textContent}</title>
                              <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                            </svg>
                        </a>`);
    }
    if (next) {
        up.insertAdjacentHTML("beforeend", `<a href="${next.href}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                              <title>to next page</title>
                              <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                              <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                        </a>`);
    }

    // add the newly created element and its content into the DOM
    toc.appendChild(up);

    // Click: Jump instantly to the relevant section (using `data-anchor` instead of `id`)
    toc.addEventListener('click', e => {
      const a = e.target.closest('a');
      if (!a || !a.dataset.target) return; // if none || <a href="#topofpage"> has no dataset.target :: so behave normal - but #bottomofpage and sidebar-end have
      e.preventDefault();
      const footer = document.querySelector(`#footer[data-anchor="${a.dataset.target}"]`);
      const target = document.querySelector(`#content > section > article[data-anchor="${a.dataset.target}"]`);
      (target||footer)?.scrollIntoView({ block: 'start', behavior: 'auto' });
      const sidebar = document.querySelector(`#serendipityRightSideBar > section[data-anchor="${a.dataset.target}"]`);
      sidebar?.scrollIntoView({ block: 'end', behavior: 'auto' });
      history.replaceState(null, '', `#${a.dataset.target}`); // Deep-Link stays intact
    });

    // Active highlighting via IntersectionObserver, also using the `data-anchor` attribute
    const links = toc.querySelectorAll('a');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(l => l.classList.toggle('active', l.dataset.target === entry.target.dataset.anchor));
        }
      });
    }, { rootMargin: '-40% 0px -50% 0px' });

    articles.forEach(art => observer.observe(art));

    let sidebarContentHeight = 0;
    let tocHeight = 0;

    function measure(caller) {
      sidebarContentHeight = 0;
      sidebarSections.forEach(sec => {
        sidebarContentHeight += sec.getBoundingClientRect().height;
      });
      tocHeight = toc.getBoundingClientRect().height;
    }

    function updateTocMode() {
      ($(window).width() >= 1024) ? toc.classList.remove("hide") : toc.classList.add("hide");
      const overflow = document.documentElement.scrollTop - sidebarContentHeight;
      toc.classList.toggle('can-float', overflow > tocHeight);
    }

    // One Observer for Sections + content
    const ro = new ResizeObserver(() => { measure('observer'); updateTocMode(); });
    measure('init'); // initial call measure() once
    sidebarSections.forEach(sec => ro.observe(sec));
    ro.observe(content);

    // Safety-Net for Lazy-Load-Images which were ready before Observer-Registration
    window.addEventListener('load', () => { measure('load'); updateTocMode(); });

    window.addEventListener('scroll', updateTocMode, { passive: true });
    window.addEventListener('resize', () => { measure('resize'); updateTocMode(); });
}
