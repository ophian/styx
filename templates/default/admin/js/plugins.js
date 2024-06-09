// S9Y-MERGE START jquery.magnific-popup.js - 2024-06-09 11:27
// Magnific Popup v1.2.0 by Dmitry Semenov
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?e(require("jquery")):e(window.jQuery||window.Zepto)}(function(c){function e(){}function d(e,t){m.ev.on(x+e+I,t)}function p(e,t,n,o){var i=document.createElement("div");return i.className="mfp-"+e,n&&(i.innerHTML=n),o?t&&t.appendChild(i):(i=c(i),t&&i.appendTo(t)),i}function u(e,t){m.ev.triggerHandler(x+e,t),m.st.callbacks&&(e=e.charAt(0).toLowerCase()+e.slice(1),m.st.callbacks[e])&&m.st.callbacks[e].apply(m,Array.isArray(t)?t:[t])}function f(e){return e===A&&m.currTemplate.closeBtn||(m.currTemplate.closeBtn=c(m.st.closeMarkup.replace("%title%",m.st.tClose)),A=e),m.currTemplate.closeBtn}function r(){c.magnificPopup.instance||((m=new e).init(),c.magnificPopup.instance=m)}function a(){y&&(v.after(y.addClass(l)).detach(),y=null)}function i(){n&&c(document.body).removeClass(n)}function t(){i(),m.req&&m.req.abort()}var m,o,g,s,h,A,l,v,y,n,w="Close",F="BeforeClose",C="MarkupParse",b="Open",j="Change",x="mfp",I="."+x,T="mfp-ready",N="mfp-removing",k="mfp-prevent-close",P=!!window.jQuery,_=c(window),S=(c.magnificPopup={instance:null,proto:e.prototype={constructor:e,init:function(){var e=navigator.appVersion;m.isLowIE=m.isIE8=document.all&&!document.addEventListener,m.isAndroid=/android/gi.test(e),m.isIOS=/iphone|ipad|ipod/gi.test(e),m.supportsTransition=function(){var e=document.createElement("p").style,t=["ms","O","Moz","Webkit"];if(void 0!==e.transition)return!0;for(;t.length;)if(t.pop()+"Transition"in e)return!0;return!1}(),m.probablyMobile=m.isAndroid||m.isIOS||/(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),g=c(document),m.popupsCache={}},open:function(e){if(!1===e.isObj){m.items=e.items.toArray(),m.index=0;for(var t,n=e.items,o=0;o<n.length;o++)if((t=(t=n[o]).parsed?t.el[0]:t)===e.el[0]){m.index=o;break}}else m.items=Array.isArray(e.items)?e.items:[e.items],m.index=e.index||0;if(!m.isOpen){m.types=[],h="",e.mainEl&&e.mainEl.length?m.ev=e.mainEl.eq(0):m.ev=g,e.key?(m.popupsCache[e.key]||(m.popupsCache[e.key]={}),m.currTemplate=m.popupsCache[e.key]):m.currTemplate={},m.st=c.extend(!0,{},c.magnificPopup.defaults,e),m.fixedContentPos="auto"===m.st.fixedContentPos?!m.probablyMobile:m.st.fixedContentPos,m.st.modal&&(m.st.closeOnContentClick=!1,m.st.closeOnBgClick=!1,m.st.showCloseBtn=!1,m.st.enableEscapeKey=!1),m.bgOverlay||(m.bgOverlay=p("bg").on("click"+I,function(){m.close()}),m.wrap=p("wrap").attr("tabindex",-1).on("click"+I,function(e){m._checkIfClose(e.target)&&m.close()}),m.container=p("container",m.wrap)),m.contentContainer=p("content"),m.st.preloader&&(m.preloader=p("preloader",m.container,m.st.tLoading));var i=c.magnificPopup.modules;for(o=0;o<i.length;o++){var r=(r=i[o]).charAt(0).toUpperCase()+r.slice(1);m["init"+r].call(m)}u("BeforeOpen"),m.st.showCloseBtn&&(m.st.closeBtnInside?(d(C,function(e,t,n,o){n.close_replaceWith=f(o.type)}),h+=" mfp-close-btn-in"):m.wrap.append(f())),m.st.alignTop&&(h+=" mfp-align-top"),m.fixedContentPos?m.wrap.css({overflow:m.st.overflowY,overflowX:"hidden",overflowY:m.st.overflowY}):m.wrap.css({top:_.scrollTop(),position:"absolute"}),!1!==m.st.fixedBgPos&&("auto"!==m.st.fixedBgPos||m.fixedContentPos)||m.bgOverlay.css({height:g.height(),position:"absolute"}),m.st.enableEscapeKey&&g.on("keyup"+I,function(e){27===e.keyCode&&m.close()}),_.on("resize"+I,function(){m.updateSize()}),m.st.closeOnContentClick||(h+=" mfp-auto-cursor"),h&&m.wrap.addClass(h);var a=m.wH=_.height(),s={},l=(m.fixedContentPos&&m._hasScrollBar(a)&&(l=m._getScrollbarSize())&&(s.marginRight=l),m.fixedContentPos&&(m.isIE7?c("body, html").css("overflow","hidden"):s.overflow="hidden"),m.st.mainClass);return m.isIE7&&(l+=" mfp-ie7"),l&&m._addClassToMFP(l),m.updateItemHTML(),u("BuildControls"),c("html").css(s),m.bgOverlay.add(m.wrap).prependTo(m.st.prependTo||c(document.body)),m._lastFocusedEl=document.activeElement,setTimeout(function(){m.content?(m._addClassToMFP(T),m._setFocus()):m.bgOverlay.addClass(T),g.on("focusin"+I,m._onFocusIn)},16),m.isOpen=!0,m.updateSize(a),u(b),e}m.updateItemHTML()},close:function(){m.isOpen&&(u(F),m.isOpen=!1,m.st.removalDelay&&!m.isLowIE&&m.supportsTransition?(m._addClassToMFP(N),setTimeout(function(){m._close()},m.st.removalDelay)):m._close())},_close:function(){u(w);var e=N+" "+T+" ";m.bgOverlay.detach(),m.wrap.detach(),m.container.empty(),m.st.mainClass&&(e+=m.st.mainClass+" "),m._removeClassFromMFP(e),m.fixedContentPos&&(e={marginRight:""},m.isIE7?c("body, html").css("overflow",""):e.overflow="",c("html").css(e)),g.off("keyup.mfp focusin"+I),m.ev.off(I),m.wrap.attr("class","mfp-wrap").removeAttr("style"),m.bgOverlay.attr("class","mfp-bg"),m.container.attr("class","mfp-container"),!m.st.showCloseBtn||m.st.closeBtnInside&&!0!==m.currTemplate[m.currItem.type]||m.currTemplate.closeBtn&&m.currTemplate.closeBtn.detach(),m.st.autoFocusLast&&m._lastFocusedEl&&c(m._lastFocusedEl).trigger("focus"),m.currItem=null,m.content=null,m.currTemplate=null,m.prevHeight=0,u("AfterClose")},updateSize:function(e){var t;m.isIOS?(t=document.documentElement.clientWidth/window.innerWidth,t=window.innerHeight*t,m.wrap.css("height",t),m.wH=t):m.wH=e||_.height(),m.fixedContentPos||m.wrap.css("height",m.wH),u("Resize")},updateItemHTML:function(){var e=m.items[m.index],t=(m.contentContainer.detach(),m.content&&m.content.detach(),(e=e.parsed?e:m.parseEl(m.index)).type),n=(u("BeforeChange",[m.currItem?m.currItem.type:"",t]),m.currItem=e,m.currTemplate[t]||(n=!!m.st[t]&&m.st[t].markup,u("FirstMarkupParse",n),m.currTemplate[t]=!n||c(n)),s&&s!==e.type&&m.container.removeClass("mfp-"+s+"-holder"),m["get"+t.charAt(0).toUpperCase()+t.slice(1)](e,m.currTemplate[t]));m.appendContent(n,t),e.preloaded=!0,u(j,e),s=e.type,m.container.prepend(m.contentContainer),u("AfterChange")},appendContent:function(e,t){(m.content=e)?m.st.showCloseBtn&&m.st.closeBtnInside&&!0===m.currTemplate[t]?m.content.find(".mfp-close").length||m.content.append(f()):m.content=e:m.content="",u("BeforeAppend"),m.container.addClass("mfp-"+t+"-holder"),m.contentContainer.append(m.content)},parseEl:function(e){var t,n=m.items[e];if((n=n.tagName?{el:c(n)}:(t=n.type,{data:n,src:n.src})).el){for(var o=m.types,i=0;i<o.length;i++)if(n.el.hasClass("mfp-"+o[i])){t=o[i];break}n.src=n.el.attr("data-mfp-src"),n.src||(n.src=n.el.attr("href"))}return n.type=t||m.st.type||"inline",n.index=e,n.parsed=!0,m.items[e]=n,u("ElementParse",n),m.items[e]},addGroup:function(t,n){function e(e){e.mfpEl=this,m._openClick(e,t,n)}var o="click.magnificPopup";(n=n||{}).mainEl=t,n.items?(n.isObj=!0,t.off(o).on(o,e)):(n.isObj=!1,n.delegate?t.off(o).on(o,n.delegate,e):(n.items=t).off(o).on(o,e))},_openClick:function(e,t,n){var o=(void 0!==n.midClick?n:c.magnificPopup.defaults).midClick;if(o||!(2===e.which||e.ctrlKey||e.metaKey||e.altKey||e.shiftKey)){o=(void 0!==n.disableOn?n:c.magnificPopup.defaults).disableOn;if(o)if("function"==typeof o){if(!o.call(m))return!0}else if(_.width()<o)return!0;e.type&&(e.preventDefault(),m.isOpen)&&e.stopPropagation(),n.el=c(e.mfpEl),n.delegate&&(n.items=t.find(n.delegate)),m.open(n)}},updateStatus:function(e,t){var n;m.preloader&&(o!==e&&m.container.removeClass("mfp-s-"+o),n={status:e,text:t=t||"loading"!==e?t:m.st.tLoading},u("UpdateStatus",n),e=n.status,t=n.text,m.st.allowHTMLInStatusIndicator?m.preloader.html(t):m.preloader.text(t),m.preloader.find("a").on("click",function(e){e.stopImmediatePropagation()}),m.container.addClass("mfp-s-"+e),o=e)},_checkIfClose:function(e){if(!c(e).closest("."+k).length){var t=m.st.closeOnContentClick,n=m.st.closeOnBgClick;if(t&&n)return!0;if(!m.content||c(e).closest(".mfp-close").length||m.preloader&&e===m.preloader[0])return!0;if(e===m.content[0]||c.contains(m.content[0],e)){if(t)return!0}else if(n&&c.contains(document,e))return!0;return!1}},_addClassToMFP:function(e){m.bgOverlay.addClass(e),m.wrap.addClass(e)},_removeClassFromMFP:function(e){this.bgOverlay.removeClass(e),m.wrap.removeClass(e)},_hasScrollBar:function(e){return(m.isIE7?g.height():document.body.scrollHeight)>(e||_.height())},_setFocus:function(){(m.st.focus?m.content.find(m.st.focus).eq(0):m.wrap).trigger("focus")},_onFocusIn:function(e){if(e.target!==m.wrap[0]&&!c.contains(m.wrap[0],e.target))return m._setFocus(),!1},_parseMarkup:function(i,e,t){var r;t.data&&(e=c.extend(t.data,e)),u(C,[i,e,t]),c.each(e,function(e,t){if(void 0===t||!1===t)return!0;var n,o;1<(r=e.split("_")).length?0<(n=i.find(I+"-"+r[0])).length&&("replaceWith"===(o=r[1])?n[0]!==t[0]&&n.replaceWith(t):"img"===o?n.is("img")?n.attr("src",t):n.replaceWith(c("<img>").attr("src",t).attr("class",n.attr("class"))):n.attr(r[1],t)):m.st.allowHTMLInTemplate?i.find(I+"-"+e).html(t):i.find(I+"-"+e).text(t)})},_getScrollbarSize:function(){var e;return void 0===m.scrollbarSize&&((e=document.createElement("div")).style.cssText="width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",document.body.appendChild(e),m.scrollbarSize=e.offsetWidth-e.clientWidth,document.body.removeChild(e)),m.scrollbarSize}},modules:[],open:function(e,t){return r(),(e=e?c.extend(!0,{},e):{}).isObj=!0,e.index=t||0,this.instance.open(e)},close:function(){return c.magnificPopup.instance&&c.magnificPopup.instance.close()},registerModule:function(e,t){t.options&&(c.magnificPopup.defaults[e]=t.options),c.extend(this.proto,t.proto),this.modules.push(e)},defaults:{disableOn:0,key:null,midClick:!1,mainClass:"",preloader:!0,focus:"",closeOnContentClick:!1,closeOnBgClick:!0,closeBtnInside:!0,showCloseBtn:!0,enableEscapeKey:!0,modal:!1,alignTop:!1,removalDelay:0,prependTo:null,fixedContentPos:"auto",fixedBgPos:"auto",overflowY:"auto",closeMarkup:'<button title="%title%" type="button" class="mfp-close">&#215;</button>',tClose:"Close (Esc)",tLoading:"Loading...",autoFocusLast:!0,allowHTMLInStatusIndicator:!1,allowHTMLInTemplate:!1}},c.fn.magnificPopup=function(e){r();var t,n,o,i=c(this);return"string"==typeof e?"open"===e?(t=P?i.data("magnificPopup"):i[0].magnificPopup,n=parseInt(arguments[1],10)||0,o=t.items?t.items[n]:(o=i,(o=t.delegate?o.find(t.delegate):o).eq(n)),m._openClick({mfpEl:o},i,t)):m.isOpen&&m[e].apply(m,Array.prototype.slice.call(arguments,1)):(e=c.extend(!0,{},e),P?i.data("magnificPopup",e):i[0].magnificPopup=e,m.addGroup(i,e)),i},"inline"),E=(c.magnificPopup.registerModule(S,{options:{hiddenClass:"hide",markup:"",tNotFound:"Content not found"},proto:{initInline:function(){m.types.push(S),d(w+"."+S,function(){a()})},getInline:function(e,t){var n,o,i;return a(),e.src?(n=m.st.inline,(o=c(e.src)).length?((i=o[0].parentNode)&&i.tagName&&(v||(l=n.hiddenClass,v=p(l),l="mfp-"+l),y=o.after(v).detach().removeClass(l)),m.updateStatus("ready")):(m.updateStatus("error",n.tNotFound),o=c("<div>")),e.inlineElement=o):(m.updateStatus("ready"),m._parseMarkup(t,{},e),t)}}}),"ajax");c.magnificPopup.registerModule(E,{options:{settings:null,cursor:"mfp-ajax-cur",tError:"The content could not be loaded."},proto:{initAjax:function(){m.types.push(E),n=m.st.ajax.cursor,d(w+"."+E,t),d("BeforeChange."+E,t)},getAjax:function(o){n&&c(document.body).addClass(n),m.updateStatus("loading");var e=c.extend({url:o.src,success:function(e,t,n){e={data:e,xhr:n};u("ParseAjax",e),m.appendContent(c(e.data),E),o.finished=!0,i(),m._setFocus(),setTimeout(function(){m.wrap.addClass(T)},16),m.updateStatus("ready"),u("AjaxContentAdded")},error:function(){i(),o.finished=o.loadError=!0,m.updateStatus("error",m.st.ajax.tError.replace("%url%",o.src))}},m.st.ajax.settings);return m.req=c.ajax(e),""}}});var z;c.magnificPopup.registerModule("image",{options:{markup:'<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',cursor:"mfp-zoom-out-cur",titleSrc:"title",verticalFit:!0,tError:"The image could not be loaded."},proto:{initImage:function(){var e=m.st.image,t=".image";m.types.push("image"),d(b+t,function(){"image"===m.currItem.type&&e.cursor&&c(document.body).addClass(e.cursor)}),d(w+t,function(){e.cursor&&c(document.body).removeClass(e.cursor),_.off("resize"+I)}),d("Resize"+t,m.resizeImage),m.isLowIE&&d("AfterChange",m.resizeImage)},resizeImage:function(){var e,t=m.currItem;t&&t.img&&m.st.image.verticalFit&&(e=0,m.isLowIE&&(e=parseInt(t.img.css("padding-top"),10)+parseInt(t.img.css("padding-bottom"),10)),t.img.css("max-height",m.wH-e))},_onImageHasSize:function(e){e.img&&(e.hasSize=!0,z&&clearInterval(z),e.isCheckingImgSize=!1,u("ImageHasSize",e),e.imgHidden)&&(m.content&&m.content.removeClass("mfp-loading"),e.imgHidden=!1)},findImageSize:function(t){function n(e){z&&clearInterval(z),z=setInterval(function(){0<i.naturalWidth?m._onImageHasSize(t):(200<o&&clearInterval(z),3===++o?n(10):40===o?n(50):100===o&&n(500))},e)}var o=0,i=t.img[0];n(1)},getImage:function(e,t){function n(){e&&(e.img.off(".mfploader"),e===m.currItem&&(m._onImageHasSize(e),m.updateStatus("error",a.tError.replace("%url%",e.src))),e.hasSize=!0,e.loaded=!0,e.loadError=!0)}function o(){e&&(e.img[0].complete?(e.img.off(".mfploader"),e===m.currItem&&(m._onImageHasSize(e),m.updateStatus("ready")),e.hasSize=!0,e.loaded=!0,u("ImageLoadComplete")):++r<200?setTimeout(o,100):n())}var i,r=0,a=m.st.image,s=t.find(".mfp-img");return s.length&&((i=document.createElement("img")).className="mfp-img",e.el&&e.el.find("img").length&&(i.alt=e.el.find("img").attr("alt")),e.img=c(i).on("load.mfploader",o).on("error.mfploader",n),i.src=e.src,s.is("img")&&(e.img=e.img.clone()),0<(i=e.img[0]).naturalWidth?e.hasSize=!0:i.width||(e.hasSize=!1)),m._parseMarkup(t,{title:function(e){if(e.data&&void 0!==e.data.title)return e.data.title;var t=m.st.image.titleSrc;if(t){if("function"==typeof t)return t.call(m,e);if(e.el)return e.el.attr(t)||""}return""}(e),img_replaceWith:e.img},e),m.resizeImage(),e.hasSize?(z&&clearInterval(z),e.loadError?(t.addClass("mfp-loading"),m.updateStatus("error",a.tError.replace("%url%",e.src))):(t.removeClass("mfp-loading"),m.updateStatus("ready"))):(m.updateStatus("loading"),e.loading=!0,e.hasSize||(e.imgHidden=!0,t.addClass("mfp-loading"),m.findImageSize(e))),t}}});function O(e){var t;m.currTemplate[L]&&(t=m.currTemplate[L].find("iframe")).length&&(e||(t[0].src="//about:blank"),m.isIE8)&&t.css("display",e?"block":"none")}function M(e){var t=m.items.length;return t-1<e?e-t:e<0?t+e:e}function D(e,t,n){return e.replace(/%curr%/gi,t+1).replace(/%total%/gi,n)}c.magnificPopup.registerModule("zoom",{options:{enabled:!1,easing:"ease-in-out",duration:300,opener:function(e){return e.is("img")?e:e.find("img")}},proto:{initZoom:function(){var e,t,n,o,i,r,a=m.st.zoom,s=".zoom";a.enabled&&m.supportsTransition&&(t=a.duration,n=function(e){var e=e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),t="all "+a.duration/1e3+"s "+a.easing,n={position:"fixed",zIndex:9999,left:0,top:0,"-webkit-backface-visibility":"hidden"},o="transition";return n["-webkit-"+o]=n["-moz-"+o]=n["-o-"+o]=n[o]=t,e.css(n),e},o=function(){m.content.css("visibility","visible")},d("BuildControls"+s,function(){m._allowZoom()&&(clearTimeout(i),m.content.css("visibility","hidden"),(e=m._getItemToZoom())?((r=n(e)).css(m._getOffset()),m.wrap.append(r),i=setTimeout(function(){r.css(m._getOffset(!0)),i=setTimeout(function(){o(),setTimeout(function(){r.remove(),e=r=null,u("ZoomAnimationEnded")},16)},t)},16)):o())}),d(F+s,function(){if(m._allowZoom()){if(clearTimeout(i),m.st.removalDelay=t,!e){if(!(e=m._getItemToZoom()))return;r=n(e)}r.css(m._getOffset(!0)),m.wrap.append(r),m.content.css("visibility","hidden"),setTimeout(function(){r.css(m._getOffset())},16)}}),d(w+s,function(){m._allowZoom()&&(o(),r&&r.remove(),e=null)}))},_allowZoom:function(){return"image"===m.currItem.type},_getItemToZoom:function(){return!!m.currItem.hasSize&&m.currItem.img},_getOffset:function(e){var e=e?m.currItem.img:m.st.zoom.opener(m.currItem.el||m.currItem),t=e.offset(),n=parseInt(e.css("padding-top"),10),o=parseInt(e.css("padding-bottom"),10),e=(t.top-=c(window).scrollTop()-n,{width:e.width(),height:(P?e.innerHeight():e[0].offsetHeight)-o-n});return(B=void 0===B?void 0!==document.createElement("p").style.MozTransform:B)?e["-moz-transform"]=e.transform="translate("+t.left+"px,"+t.top+"px)":(e.left=t.left,e.top=t.top),e}}});var B,L="iframe",H=(c.magnificPopup.registerModule(L,{options:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',srcAction:"iframe_src",patterns:{youtube:{index:"youtube.com",id:"v=",src:"//www.youtube.com/embed/%id%?autoplay=1"},vimeo:{index:"vimeo.com/",id:"/",src:"//player.vimeo.com/video/%id%?autoplay=1"},gmaps:{index:"//maps.google.",src:"%id%&output=embed"}}},proto:{initIframe:function(){m.types.push(L),d("BeforeChange",function(e,t,n){t!==n&&(t===L?O():n===L&&O(!0))}),d(w+"."+L,function(){O()})},getIframe:function(e,t){var n=e.src,o=m.st.iframe,i=(c.each(o.patterns,function(){if(-1<n.indexOf(this.index))return this.id&&(n="string"==typeof this.id?n.substr(n.lastIndexOf(this.id)+this.id.length,n.length):this.id.call(this,n)),n=this.src.replace("%id%",n),!1}),{});return o.srcAction&&(i[o.srcAction]=n),m._parseMarkup(t,i,e),m.updateStatus("ready"),t}}}),c.magnificPopup.registerModule("gallery",{options:{enabled:!1,arrowMarkup:'<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',preload:[0,2],navigateByImgClick:!0,arrows:!0,tPrev:"Previous (Left arrow key)",tNext:"Next (Right arrow key)",tCounter:"%curr% of %total%",langDir:null,loop:!0},proto:{initGallery:function(){var r=m.st.gallery,e=".mfp-gallery";if(m.direction=!0,!r||!r.enabled)return!1;r.langDir||(r.langDir=document.dir||"ltr"),h+=" mfp-gallery",d(b+e,function(){r.navigateByImgClick&&m.wrap.on("click"+e,".mfp-img",function(){if(1<m.items.length)return m.next(),!1}),g.on("keydown"+e,function(e){37===e.keyCode?"rtl"===r.langDir?m.next():m.prev():39===e.keyCode&&("rtl"===r.langDir?m.prev():m.next())}),m.updateGalleryButtons()}),d("UpdateStatus"+e,function(){m.updateGalleryButtons()}),d("UpdateStatus"+e,function(e,t){t.text&&(t.text=D(t.text,m.currItem.index,m.items.length))}),d(C+e,function(e,t,n,o){var i=m.items.length;n.counter=1<i?D(r.tCounter,o.index,i):""}),d("BuildControls"+e,function(){var e,t,n,o,i;1<m.items.length&&r.arrows&&!m.arrowLeft&&(t="rtl"===r.langDir?(o=r.tNext,e=r.tPrev,i="next","prev"):(o=r.tPrev,e=r.tNext,i="prev","next"),n=r.arrowMarkup,o=m.arrowLeft=c(n.replace(/%title%/gi,o).replace(/%action%/gi,i).replace(/%dir%/gi,"left")).addClass(k),i=m.arrowRight=c(n.replace(/%title%/gi,e).replace(/%action%/gi,t).replace(/%dir%/gi,"right")).addClass(k),"rtl"===r.langDir?(m.arrowNext=o,m.arrowPrev=i):(m.arrowNext=i,m.arrowPrev=o),o.on("click",function(){"rtl"===r.langDir?m.next():m.prev()}),i.on("click",function(){"rtl"===r.langDir?m.prev():m.next()}),m.container.append(o.add(i)))}),d(j+e,function(){m._preloadTimeout&&clearTimeout(m._preloadTimeout),m._preloadTimeout=setTimeout(function(){m.preloadNearbyImages(),m._preloadTimeout=null},16)}),d(w+e,function(){g.off(e),m.wrap.off("click"+e),m.arrowRight=m.arrowLeft=null})},next:function(){var e=M(m.index+1);if(!m.st.gallery.loop&&0===e)return!1;m.direction=!0,m.index=e,m.updateItemHTML()},prev:function(){var e=m.index-1;if(!m.st.gallery.loop&&e<0)return!1;m.direction=!1,m.index=M(e),m.updateItemHTML()},goTo:function(e){m.direction=e>=m.index,m.index=e,m.updateItemHTML()},preloadNearbyImages:function(){for(var e=m.st.gallery.preload,t=Math.min(e[0],m.items.length),n=Math.min(e[1],m.items.length),o=1;o<=(m.direction?n:t);o++)m._preloadItem(m.index+o);for(o=1;o<=(m.direction?t:n);o++)m._preloadItem(m.index-o)},_preloadItem:function(e){var t;e=M(e),m.items[e].preloaded||((t=m.items[e]).parsed||(t=m.parseEl(e)),u("LazyLoad",t),"image"===t.type&&(t.img=c('<img class="mfp-img" />').on("load.mfploader",function(){t.hasSize=!0}).on("error.mfploader",function(){t.hasSize=!0,t.loadError=!0,u("LazyLoadError",t)}).attr("src",t.src)),t.preloaded=!0)},updateGalleryButtons:function(){m.st.gallery.loop||"object"!=typeof m.arrowPrev||null===m.arrowPrev||(0===m.index?m.arrowPrev.hide():m.arrowPrev.show(),m.index===m.items.length-1?m.arrowNext.hide():m.arrowNext.show())}}}),"retina");c.magnificPopup.registerModule(H,{options:{replaceSrc:function(e){return e.src.replace(/\.\w+$/,function(e){return"@2x"+e})},ratio:1},proto:{initRetina:function(){var n,o;1<window.devicePixelRatio&&(n=m.st.retina,o=n.ratio,1<(o=isNaN(o)?o():o))&&(d("ImageHasSize."+H,function(e,t){t.img.css({"max-width":t.img[0].naturalWidth/o,width:"100%"})}),d("ElementParse."+H,function(e,t){t.src=n.replaceSrc(t,o)}))}}}),r()});

// S9Y-MERGE END jquery.magnific-popup.js - 2024-06-09 11:27
// S9Y-MERGE START jquery.syncheight.js - 2016-04-20 08:19
/**
 * syncHeight - jQuery plugin to automagically Sync the heights of columns
 * Made to seamlessly work with the CCS-Framework YAML (yaml.de)
 * @requires jQuery v1.0.3
 *
 * http://blog.ginader.de/dev/syncheight/
 *
 * Copyright (c) 2007-2013
 * Dirk Ginader (ginader.de)
 * Dirk Jesse (yaml.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Version: 1.3
 */

!function(a){var b=function(){var a=0,b=[["min-height","0px"],["height","1%"]],c=/(msie) ([\w.]+)/.exec(navigator.userAgent.toLowerCase())||[],d=c[1]||"",e=c[2]||"0";return"msie"==d&&7>e&&(a=1),{name:b[a][0],autoheightVal:b[a][1]}};a.getSyncedHeight=function(c){var d=0,e=b();return a(c).each(function(){a(this).css(e.name,e.autoheightVal);var b=a(this).height();b>d&&(d=b)}),d},a.fn.syncHeight=function(c){var d={updateOnResize:!1,height:!1},e=a.extend(d,c),f=this,g=0,h=b().name;return g="number"==typeof e.height?e.height:a.getSyncedHeight(this),a(this).each(function(){a(this).css(h,g+"px")}),e.updateOnResize===!0&&a(window).resize(function(){a(f).syncHeight()}),this}}(jQuery);

// S9Y-MERGE END jquery.syncheight.js - 2016-04-20 08:19
// S9Y-MERGE START jquery.tabs.js - 2020-04-12 12:53
// STYX changed - 2018-07-13 12:06
// STYX changed - 2020-04-09 and  2020-04-12
/**
 * Accessible Tabs - jQuery plugin for accessible, unobtrusive tabs
 * Build to seamlessly work with the CCS-Framework YAML (yaml.de) not depending on YAML though
 * @requires jQuery - tested with 1.9.1, 1.7 and 1.4.2 but might as well work with older versions
 * 2018-07-13: Changed .size() to .length to support new jQuery libs https://github.com/ophian/styx/commit/704c2fe1bd6ceb2dceea34d6a5f5316a21e8a69d#diff-69e0797a0ef4665d3c2ab8c541682791
 * 2020-04-10: Changed accessibleTabs old $.cookie to use new js-cookies lib, see https://github.com/ophian/styx/commit/5aaeed45cfb7039fd63b694676f0bae3f8d0a75c
 * 2020-04-12: Changed accessibleTabs cookie for current path
 *
 * English article: http://blog.ginader.de/archives/2009/02/07/jQuery-Accessible-Tabs-How-to-make-tabs-REALLY-accessible.php
 * German article: http://blog.ginader.de/archives/2009/02/07/jQuery-Accessible-Tabs-Wie-man-Tabs-WIRKLICH-zugaenglich-macht.php
 * 
 * code: http://github.com/ginader/Accessible-Tabs
 * please report issues at: http://github.com/ginader/Accessible-Tabs/issues
 *
 * Copyright (c) 2007 Dirk Ginader (ginader.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
!function(a){function c(a,c){b&&window.console&&window.console.log&&(c?window.console.log(c+": ",a):window.console.log(a))}var b=!1;a.fn.extend({getUniqueId:function(a,b,c){return c=void 0===c?"":"-"+c,a+b+c},accessibleTabs:function(b){var d={wrapperClass:"content",currentClass:"current",tabhead:"h4",tabheadClass:"tabhead",tabbody:".tabbody",fx:"show",fxspeed:"normal",currentInfoText:"current tab: ",currentInfoPosition:"prepend",currentInfoClass:"current-info",tabsListClass:"tabs-list",syncheights:!1,syncHeightMethodName:"syncHeight",cssClassAvailable:!1,saveState:!1,autoAnchor:!1,pagination:!1,position:"top",wrapInnerNavLinks:"",firstNavItemClass:"first",lastNavItemClass:"last",clearfixClass:"clearfix"},e={37:-1,38:-1,39:1,40:1},f={top:"prepend",bottom:"append"};this.options=a.extend(d,b);var g=0;void 0!==a("body").data("accessibleTabsCount")&&(g=a("body").data("accessibleTabsCount")),a("body").data("accessibleTabsCount",this.length+g);var h=this;return this.each(function(b){var d=a(this),i="",j=0,k=[];a(d).wrapInner('<div class="'+h.options.wrapperClass+'"></div>'),a(d).find(h.options.tabhead).each(function(c){var d="",e=a(this).attr("id");if(e){if(0===e.indexOf("accessibletabscontent"))return;d=' id="'+e+'"'}var f=h.getUniqueId("accessibletabscontent",g+b,c),l=h.getUniqueId("accessibletabsnavigation",g+b,c);if(k.push(f),h.options.cssClassAvailable===!0){var m="";a(this).attr("class")&&(m=a(this).attr("class"),m=' class="'+m+'"'),i+='<li id="'+l+'"><a'+d+m+' href="#'+f+'">'+a(this).html()+"</a></li>"}else i+='<li id="'+l+'"><a'+d+' href="#'+f+'">'+a(this).html()+"</a></li>";a(this).attr({id:f,"class":h.options.tabheadClass,tabindex:"-1"}),j++}),h.options.syncheights&&a.fn[h.options.syncHeightMethodName]&&(a(d).find(h.options.tabbody)[h.options.syncHeightMethodName](),a(window).resize(function(){a(d).find(h.options.tabbody)[h.options.syncHeightMethodName]()}));var l="."+h.options.tabsListClass;a(d).find(l).length||a(d)[f[h.options.position]]('<ul class="'+h.options.clearfixClass+" "+h.options.tabsListClass+" tabamount"+j+'"></ul>'),a(d).find(l).append(i);var m=a(d).find(h.options.tabbody);if(m.length>0&&(a(m).hide(),a(m[0]).show()),a(d).find("ul."+h.options.tabsListClass+">li:first").addClass(h.options.currentClass).addClass(h.options.firstNavItemClass).find("a")[h.options.currentInfoPosition]('<span class="'+h.options.currentInfoClass+'">'+h.options.currentInfoText+"</span>").parents("ul."+h.options.tabsListClass).children("li:last").addClass(h.options.lastNavItemClass),h.options.wrapInnerNavLinks&&a(d).find("ul."+h.options.tabsListClass+">li>a").wrapInner(h.options.wrapInnerNavLinks),a(d).find("ul."+h.options.tabsListClass+">li>a").each(function(b){a(this).click(function(c){c.preventDefault(),d.trigger("showTab.accessibleTabs",[a(c.target)]),h.options.saveState&&Cookies.get&&Cookies.set("accessibletab_"+d.attr("id")+"_active",b, {path:window.location.pathname.split('/').slice(0, -1).join('/'),sameSite:'lax'}),a(d).find("ul."+h.options.tabsListClass+">li."+h.options.currentClass).removeClass(h.options.currentClass).find("span."+h.options.currentInfoClass).remove(),a(this).blur(),a(d).find(h.options.tabbody+":visible").hide(),a(d).find(h.options.tabbody).eq(b)[h.options.fx](h.options.fxspeed),a(this)[h.options.currentInfoPosition]('<span class="'+h.options.currentInfoClass+'">'+h.options.currentInfoText+"</span>").parent().addClass(h.options.currentClass),a(a(this).attr("href")).focus().keyup(function(c){e[c.keyCode]&&(h.showAccessibleTab(b+e[c.keyCode]),a(this).unbind("keyup"))})}),a(this).focus(function(){a(document).keyup(function(a){e[a.keyCode]&&h.showAccessibleTab(b+e[a.keyCode])})}),a(this).blur(function(){a(document).unbind("keyup")})}),h.options.saveState&&Cookies.get){var n=Cookies.get("accessibletab_"+d.attr("id")+"_active");c(Cookies.get("accessibletab_"+d.attr("id")+"_active")),null!==n&&h.showAccessibleTab(n,d.attr("id"))}if(h.options.autoAnchor&&window.location.hash){var o=a("."+h.options.tabsListClass).find(window.location.hash);o.length&&o.click()}if(h.options.pagination){var p='<ul class="pagination">';p+='    <li class="previous"><a href="#{previousAnchor}"><span>{previousHeadline}</span></a></li>',p+='    <li class="next"><a href="#{nextAnchor}"><span>{nextHeadline}</span></a></li>',p+="</ul>";var q=a(d).find(".tabbody"),r=q.length;q.each(function(b){a(this).append(p);var c=b+1;c>=r&&(c=0);var e=b-1;0>e&&(e=r-1);var f=a(this).find(".pagination"),g=f.find(".previous");g.find("span").text(a("#"+k[e]).text()),g.find("a").attr("href","#"+k[e]).click(function(b){b.preventDefault(),a(d).find(".tabs-list a").eq(e).click()});var h=f.find(".next");h.find("span").text(a("#"+k[c]).text()),h.find("a").attr("href","#"+k[c]).click(function(b){b.preventDefault(),a(d).find(".tabs-list a").eq(c).click()})})}})},showAccessibleTab:function(b,d){c("showAccessibleTab");var e=this;if(!d)return this.each(function(){var c=a(this);c.trigger("showTab.accessibleTabs");var d=c.find("ul."+e.options.tabsListClass+">li>a");c.trigger("showTab.accessibleTabs",[d.eq(b)]),d.eq(b).click()});var f=a("#"+d),g=f.find("ul."+e.options.tabsListClass+">li>a");f.trigger("showTab.accessibleTabs",[g.eq(b)]),g.eq(b).click()},showAccessibleTabSelector:function(b){c("showAccessibleTabSelector");var d=a(b);d&&("a"===d.get(0).nodeName.toLowerCase()?d.click():c("the selector of a showAccessibleTabSelector() call needs to point to a tabs headline!"))}})}(jQuery);

// S9Y-MERGE END jquery.tabs.js - 2020-04-10 10:48
// S9Y-MERGE START canvas-toBlob.js - 2016-04-20 08:19
/* canvas-toBlob.js
 * A canvas.toBlob() implementation.
 * 2013-12-27
 * 
 * By Eli Grey, http://eligrey.com and Devin Samarin, https://github.com/eboyjr
 * License: X11/MIT
 *   See LICENSE.md
 */

/*global self */
/*jslint bitwise: true, regexp: true, confusion: true, es5: true, vars: true, white: true,
  plusplus: true */

/*! @source http://purl.eligrey.com/github/canvas-toBlob.js/blob/master/canvas-toBlob.js */
!function(a){"use strict";var g,b=a.Uint8Array,c=a.HTMLCanvasElement,d=c&&c.prototype,e=/\s*;\s*base64\s*(?:;|$)/i,f="toDataURL",h=function(a){for(var k,l,m,c=a.length,d=new b(0|3*(c/4)),e=0,f=0,h=[0,0],i=0,j=0;c--;)l=a.charCodeAt(e++),k=g[l-43],255!==k&&k!==m&&(h[1]=h[0],h[0]=l,j=j<<6|k,i++,4===i&&(d[f++]=j>>>16,61!==h[1]&&(d[f++]=j>>>8),61!==h[0]&&(d[f++]=j),i=0));return d};b&&(g=new b([62,-1,-1,-1,63,52,53,54,55,56,57,58,59,60,61,-1,-1,-1,0,-1,-1,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51])),c&&!d.toBlob&&(d.toBlob=function(a,c){if(c||(c="image/png"),this.mozGetAsFile)return a(this.mozGetAsFile("canvas",c)),void 0;var l,d=Array.prototype.slice.call(arguments,1),g=this[f].apply(this,d),i=g.indexOf(","),j=g.substring(i+1),k=e.test(g.substring(0,i));Blob.fake?(l=new Blob,l.encoding=k?"base64":"URI",l.data=j,l.size=j.length):b&&(l=k?new Blob([h(j)],{type:c}):new Blob([decodeURIComponent(j)],{type:c})),a(l)},d.toBlobHD=d.toDataURLHD?function(){f="toDataURLHD";var a=this.toBlob();return f="toDataURL",a}:d.toBlob)}("undefined"!=typeof self&&self||"undefined"!=typeof window&&window||this.content||this);

// S9Y-MERGE END canvas-toBlob.js - 2016-04-20 08:19
// S9Y-MERGE START jquery.autoscroll.js - 2016-04-20 08:19
/*
 * AutoScroll Plugin for jQuery
 *
 * Copyright (c) 2006 Jonathan Sharp (jdsharp.us)
 * Licensed under the GPL license.
 *
 * http://jdsharp.us/code/AutoScroll/
 *
 * Date: 2014-09-19
 * Rev: 002
 * NOTE: This version got changed by s9y-developers! (stop-function, no mod-key)
 */

$.autoscroll = {
	settings: 	{},
	interval: 	0,
	event: 		null,

	init: function(opts) {
		$.autoscroll.settings = {
			step: 		80,
			trigger:	75,
			interval: 	80,
			mod_key: 	17
		};
		
		if (opts) {
			for (o in opts) {
				$.autoscroll.settings[o] = opts[o];
			}
		}
        $.autoscroll.setKeyEvent();
		document.onmousemove= $.autoscroll.setMouseEvent;
	},

    stop: function() {
        clearInterval($.autoscroll.interval);
        $.autoscroll.interval = 0;
    },

	setKeyEvent: function(e) {
		if ($.autoscroll.interval == 0) {
			$.autoscroll.interval = setInterval($.autoscroll.step, $.autoscroll.settings.interval);
		}
	},

	setMouseEvent: function(e) {
		var e	= e || window.event;
		var de	= document.documentElement;
		var b	= document.body;
		$.autoscroll.event = {
			cursor: {
				x: e.pageX || (e.clientX + (de.scrollLeft || b.scrollLeft) - (de.clientLeft || 0)),
				y: e.pageY || (e.clientY + (de.scrollTop || b.scrollTop) - (de.clientTop || 0))
			},
	
			win: {
				w: window.innerWidth  || (de.clientWidth && de.clientWidth != 0 ? de.clientWidth : b.offsetWidth),
				h: window.innerHeight || (de.clientHeight && de.clientWidth != 0 ? de.clientHeight : b.offsetHeight)
			},
	
			scroll: {
				x: (document.all ? 
						(!de.scrollLeft ? b.scrollLeft : de.scrollLeft)
						:
						(window.pageXOffset ? window.pageXOffset : window.scrollX)
						),
				y: (document.all ? 
						(!de.scrollTop ? b.scrollTop : de.scrollTop)
						:
						(window.pageYOffset ? window.pageYOffset : window.scrollY)
						)
			}
		};
	},
	
	step: function() {
		var e = $.autoscroll.event;
		if (!e) {
			return;
		}

		var hot_l 	= e.scroll.x;
		var hot_r 	= e.scroll.x + e.win.w;
		var x		= e.cursor.x;

		var hot_t	= e.scroll.y;
		var hot_b	= e.scroll.y + e.win.h;
		var y 		= e.cursor.y;
	
		if (hot_l <= x && x <= (hot_l + $.autoscroll.settings.trigger)) {
			var ratio 	= (1 - ((x - hot_l) / $.autoscroll.settings.trigger));
			var step	= Math.round(ratio * $.autoscroll.settings.step, 0);
			e.scroll.x += -step;
			e.cursor.x += -step;
		} else if ((hot_r - $.autoscroll.settings.trigger) <= x && x <= hot_r) {
			var ratio 	= (1 - ((hot_r - x) / $.autoscroll.settings.trigger));
			var step	= Math.round(ratio * $.autoscroll.settings.step, 0);
			e.scroll.x += step;
			e.cursor.x += step;
		}
	
		if (hot_t <= y && y <= (hot_t + $.autoscroll.settings.trigger)) {
			var ratio 	= (1 - ((y - hot_t) / $.autoscroll.settings.trigger));
			var step	= Math.round(ratio * $.autoscroll.settings.step, 0);
			e.scroll.y += -step;
			e.cursor.y += -step;
		} else if ((hot_b - $.autoscroll.settings.trigger) <= y && y <= hot_b) {
			var ratio 	= (1 - ((hot_b - y) / $.autoscroll.settings.trigger));
			var step	= Math.round(ratio * $.autoscroll.settings.step, 0);
			e.scroll.y += step;
			e.cursor.y += step;
		}
	
		if (e.scroll.x < 0) {
			e.scroll.x = 0;
			e.cursor.x = 0;
		}
		if (e.scroll.y < 0) {
			e.scroll.y = 0;
			e.cursor.y = 0;
		}

		window.scrollTo(e.scroll.x, e.scroll.y);
	}
};
// S9Y-MERGE END jquery.autoscroll.js - 2016-04-20 08:19
// S9Y-MERGE START jquery.sortable.js - 2016-04-20 08:19
/* ===================================================
 *  jquery-sortable.js v0.9.13
 *  http://johnny.github.com/jquery-sortable/
 * ===================================================
 *  Copyright (c) 2012 Jonas von Andrian
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *  * The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * ========================================================== */
 !function(d,B,m,f){function v(a,b){var c=Math.max(0,a[0]-b[0],b[0]-a[1]),e=Math.max(0,a[2]-b[1],b[1]-a[3]);return c+e}function w(a,b,c,e){var k=a.length;e=e?"offset":"position";for(c=c||0;k--;){var g=a[k].el?a[k].el:d(a[k]),l=g[e]();l.left+=parseInt(g.css("margin-left"),10);l.top+=parseInt(g.css("margin-top"),10);b[k]=[l.left-c,l.left+g.outerWidth()+c,l.top-c,l.top+g.outerHeight()+c]}}function p(a,b){var c=b.offset();return{left:a.left-c.left,top:a.top-c.top}}function x(a,b,c){b=[b.left,b.top];c=
 c&&[c.left,c.top];for(var e,k=a.length,d=[];k--;)e=a[k],d[k]=[k,v(e,b),c&&v(e,c)];return d=d.sort(function(a,b){return b[1]-a[1]||b[2]-a[2]||b[0]-a[0]})}function q(a){this.options=d.extend({},n,a);this.containers=[];this.options.rootGroup||(this.scrollProxy=d.proxy(this.scroll,this),this.dragProxy=d.proxy(this.drag,this),this.dropProxy=d.proxy(this.drop,this),this.placeholder=d(this.options.placeholder),a.isValidTarget||(this.options.isValidTarget=f))}function s(a,b){this.el=a;this.options=d.extend({},
 z,b);this.group=q.get(this.options);this.rootGroup=this.options.rootGroup||this.group;this.handle=this.rootGroup.options.handle||this.rootGroup.options.itemSelector;var c=this.rootGroup.options.itemPath;this.target=c?this.el.find(c):this.el;this.target.on(t.start,this.handle,d.proxy(this.dragInit,this));this.options.drop&&this.group.containers.push(this)}var z={drag:!0,drop:!0,exclude:"",nested:!0,vertical:!0},n={afterMove:function(a,b,c){},containerPath:"",containerSelector:"ol, ul",distance:0,delay:0,
 handle:"",itemPath:"",itemSelector:"li",bodyClass:"dragging",draggedClass:"dragged",isValidTarget:function(a,b){return!0},onCancel:function(a,b,c,e){},onDrag:function(a,b,c,e){a.css(b)},onDragStart:function(a,b,c,e){a.css({height:a.outerHeight(),width:a.outerWidth()});a.addClass(b.group.options.draggedClass);d("body").addClass(b.group.options.bodyClass)},onDrop:function(a,b,c,e){a.removeClass(b.group.options.draggedClass).removeAttr("style");d("body").removeClass(b.group.options.bodyClass)},onMousedown:function(a,
 b,c){if(!c.target.nodeName.match(/^(input|select|textarea)$/i))return c.preventDefault(),!0},placeholderClass:"placeholder",placeholder:'<li class="placeholder"></li>',pullPlaceholder:!0,serialize:function(a,b,c){a=d.extend({},a.data());if(c)return[b];b[0]&&(a.children=b);delete a.subContainers;delete a.sortable;return a},tolerance:0},r={},y=0,A={left:0,top:0,bottom:0,right:0},t={start:"touchstart.sortable mousedown.sortable",drop:"touchend.sortable touchcancel.sortable mouseup.sortable",drag:"touchmove.sortable mousemove.sortable",
 scroll:"scroll.sortable"};q.get=function(a){r[a.group]||(a.group===f&&(a.group=y++),r[a.group]=new q(a));return r[a.group]};q.prototype={dragInit:function(a,b){this.$document=d(b.el[0].ownerDocument);var c=d(a.target).closest(this.options.itemSelector);c.length&&(this.item=c,this.itemContainer=b,!this.item.is(this.options.exclude)&&this.options.onMousedown(this.item,n.onMousedown,a)&&(this.setPointer(a),this.toggleListeners("on"),this.setupDelayTimer(),this.dragInitDone=!0))},drag:function(a){if(!this.dragging){if(!this.distanceMet(a)||
 !this.delayMet)return;this.options.onDragStart(this.item,this.itemContainer,n.onDragStart,a);this.item.before(this.placeholder);this.dragging=!0}this.setPointer(a);this.options.onDrag(this.item,p(this.pointer,this.item.offsetParent()),n.onDrag,a);a=this.getPointer(a);var b=this.sameResultBox,c=this.options.tolerance;(!b||b.top-c>a.top||b.bottom+c<a.top||b.left-c>a.left||b.right+c<a.left)&&!this.searchValidTarget()&&(this.placeholder.detach(),this.lastAppendedItem=f)},drop:function(a){this.toggleListeners("off");
 this.dragInitDone=!1;if(this.dragging){if(this.placeholder.closest("html")[0])this.placeholder.before(this.item).detach();else this.options.onCancel(this.item,this.itemContainer,n.onCancel,a);this.options.onDrop(this.item,this.getContainer(this.item),n.onDrop,a);this.clearDimensions();this.clearOffsetParent();this.lastAppendedItem=this.sameResultBox=f;this.dragging=!1}},searchValidTarget:function(a,b){a||(a=this.relativePointer||this.pointer,b=this.lastRelativePointer||this.lastPointer);for(var c=
 x(this.getContainerDimensions(),a,b),e=c.length;e--;){var d=c[e][0];if(!c[e][1]||this.options.pullPlaceholder)if(d=this.containers[d],!d.disabled){if(!this.$getOffsetParent()){var g=d.getItemOffsetParent();a=p(a,g);b=p(b,g)}if(d.searchValidTarget(a,b))return!0}}this.sameResultBox&&(this.sameResultBox=f)},movePlaceholder:function(a,b,c,e){var d=this.lastAppendedItem;if(e||!d||d[0]!==b[0])b[c](this.placeholder),this.lastAppendedItem=b,this.sameResultBox=e,this.options.afterMove(this.placeholder,a,b)},
 getContainerDimensions:function(){this.containerDimensions||w(this.containers,this.containerDimensions=[],this.options.tolerance,!this.$getOffsetParent());return this.containerDimensions},getContainer:function(a){return a.closest(this.options.containerSelector).data(m)},$getOffsetParent:function(){if(this.offsetParent===f){var a=this.containers.length-1,b=this.containers[a].getItemOffsetParent();if(!this.options.rootGroup)for(;a--;)if(b[0]!=this.containers[a].getItemOffsetParent()[0]){b=!1;break}this.offsetParent=
 b}return this.offsetParent},setPointer:function(a){a=this.getPointer(a);if(this.$getOffsetParent()){var b=p(a,this.$getOffsetParent());this.lastRelativePointer=this.relativePointer;this.relativePointer=b}this.lastPointer=this.pointer;this.pointer=a},distanceMet:function(a){a=this.getPointer(a);return Math.max(Math.abs(this.pointer.left-a.left),Math.abs(this.pointer.top-a.top))>=this.options.distance},getPointer:function(a){var b=a.originalEvent||a.originalEvent.touches&&a.originalEvent.touches[0];
 return{left:a.pageX||b.pageX,top:a.pageY||b.pageY}},setupDelayTimer:function(){var a=this;this.delayMet=!this.options.delay;this.delayMet||(clearTimeout(this._mouseDelayTimer),this._mouseDelayTimer=setTimeout(function(){a.delayMet=!0},this.options.delay))},scroll:function(a){this.clearDimensions();this.clearOffsetParent()},toggleListeners:function(a){var b=this;d.each(["drag","drop","scroll"],function(c,e){b.$document[a](t[e],b[e+"Proxy"])})},clearOffsetParent:function(){this.offsetParent=f},clearDimensions:function(){this.traverse(function(a){a._clearDimensions()})},
 traverse:function(a){a(this);for(var b=this.containers.length;b--;)this.containers[b].traverse(a)},_clearDimensions:function(){this.containerDimensions=f},_destroy:function(){r[this.options.group]=f}};s.prototype={dragInit:function(a){var b=this.rootGroup;!this.disabled&&!b.dragInitDone&&this.options.drag&&this.isValidDrag(a)&&b.dragInit(a,this)},isValidDrag:function(a){return 1==a.which||"touchstart"==a.type&&1==a.originalEvent.touches.length},searchValidTarget:function(a,b){var c=x(this.getItemDimensions(),
 a,b),e=c.length,d=this.rootGroup,g=!d.options.isValidTarget||d.options.isValidTarget(d.item,this);if(!e&&g)return d.movePlaceholder(this,this.target,"append"),!0;for(;e--;)if(d=c[e][0],!c[e][1]&&this.hasChildGroup(d)){if(this.getContainerGroup(d).searchValidTarget(a,b))return!0}else if(g)return this.movePlaceholder(d,a),!0},movePlaceholder:function(a,b){var c=d(this.items[a]),e=this.itemDimensions[a],k="after",g=c.outerWidth(),f=c.outerHeight(),h=c.offset(),h={left:h.left,right:h.left+g,top:h.top,
 bottom:h.top+f};this.options.vertical?b.top<=(e[2]+e[3])/2?(k="before",h.bottom-=f/2):h.top+=f/2:b.left<=(e[0]+e[1])/2?(k="before",h.right-=g/2):h.left+=g/2;this.hasChildGroup(a)&&(h=A);this.rootGroup.movePlaceholder(this,c,k,h)},getItemDimensions:function(){this.itemDimensions||(this.items=this.$getChildren(this.el,"item").filter(":not(."+this.group.options.placeholderClass+", ."+this.group.options.draggedClass+")").get(),w(this.items,this.itemDimensions=[],this.options.tolerance));return this.itemDimensions},
 getItemOffsetParent:function(){var a=this.el;return"relative"===a.css("position")||"absolute"===a.css("position")||"fixed"===a.css("position")?a:a.offsetParent()},hasChildGroup:function(a){return this.options.nested&&this.getContainerGroup(a)},getContainerGroup:function(a){var b=d.data(this.items[a],"subContainers");if(b===f){var c=this.$getChildren(this.items[a],"container"),b=!1;c[0]&&(b=d.extend({},this.options,{rootGroup:this.rootGroup,group:y++}),b=c[m](b).data(m).group);d.data(this.items[a],
 "subContainers",b)}return b},$getChildren:function(a,b){var c=this.rootGroup.options,e=c[b+"Path"],c=c[b+"Selector"];a=d(a);e&&(a=a.find(e));return a.children(c)},_serialize:function(a,b){var c=this,e=this.$getChildren(a,b?"item":"container").not(this.options.exclude).map(function(){return c._serialize(d(this),!b)}).get();return this.rootGroup.options.serialize(a,e,b)},traverse:function(a){d.each(this.items||[],function(b){(b=d.data(this,"subContainers"))&&b.traverse(a)});a(this)},_clearDimensions:function(){this.itemDimensions=
 f},_destroy:function(){var a=this;this.target.off(t.start,this.handle);this.el.removeData(m);this.options.drop&&(this.group.containers=d.grep(this.group.containers,function(b){return b!=a}));d.each(this.items||[],function(){d.removeData(this,"subContainers")})}};var u={enable:function(){this.traverse(function(a){a.disabled=!1})},disable:function(){this.traverse(function(a){a.disabled=!0})},serialize:function(){return this._serialize(this.el,!0)},refresh:function(){this.traverse(function(a){a._clearDimensions()})},
 destroy:function(){this.traverse(function(a){a._destroy()})}};d.extend(s.prototype,u);d.fn[m]=function(a){var b=Array.prototype.slice.call(arguments,1);return this.map(function(){var c=d(this),e=c.data(m);if(e&&u[a])return u[a].apply(e,b)||this;e||a!==f&&"object"!==typeof a||c.data(m,new s(c,a));return this})}}(jQuery,window,"sortable");

// S9Y-MERGE END jquery.sortable.js - 2016-04-20 08:19
// S9Y-MERGE START js-cookie.js - 2020-04-09 08:47
/*! js-cookie v3.0.0-rc.0 | MIT | https://github.com/js-cookie/js-cookie */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self,function(){var r=e.Cookies,n=e.Cookies=t();n.noConflict=function(){return e.Cookies=r,n}}())}(this,function(){"use strict";function e(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)e[n]=r[n]}return e}var t={read:function(e){return e.replace(/%3B/g,";")},write:function(e){return e.replace(/;/g,"%3B")}};return function r(n,i){function o(r,o,u){if("undefined"!=typeof document){"number"==typeof(u=e({},i,u)).expires&&(u.expires=new Date(Date.now()+864e5*u.expires)),u.expires&&(u.expires=u.expires.toUTCString()),r=t.write(r).replace(/=/g,"%3D"),o=n.write(String(o),r);var c="";for(var f in u)u[f]&&(c+="; "+f,!0!==u[f]&&(c+="="+u[f].split(";")[0]));return document.cookie=r+"="+o+c}}return Object.create({set:o,get:function(e){if("undefined"!=typeof document&&(!arguments.length||e)){for(var r=document.cookie?document.cookie.split("; "):[],i={},o=0;o<r.length;o++){var u=r[o].split("="),c=u.slice(1).join("="),f=t.read(u[0]).replace(/%3D/g,"=");if(i[f]=n.read(c,f),e===f)break}return e?i[e]:i}},remove:function(t,r){o(t,"",e({},r,{expires:-1}))},withAttributes:function(t){return r(this.converter,e({},this.attributes,t))},withConverter:function(t){return r(e({},this.converter,t),this.attributes)}},{attributes:{value:Object.freeze(i)},converter:{value:Object.freeze(n)}})}(t,{path:"/"})});

// S9Y-MERGE END js-cookie.js - 2020-04-09 08:47
// S9Y-MERGE START jquery.details.js - 2016-04-20 08:19
/*! http://mths.be/details v0.1.0 by @mathias | includes http://mths.be/noselect v1.0.3 */
;(function(a,f){var e=f.fn,d,c=Object.prototype.toString.call(window.opera)=='[object Opera]',g=(function(l){var j=l.createElement('details'),i,h,k;if(!('open' in j)){return false}h=l.body||(function(){var m=l.documentElement;i=true;return m.insertBefore(l.createElement('body'),m.firstElementChild||m.firstChild)}());j.innerHTML='<summary>a</summary>b';j.style.display='block';h.appendChild(j);k=j.offsetHeight;j.open=true;k=k!=j.offsetHeight;h.removeChild(j);if(i){h.parentNode.removeChild(h)}return k}(a)),b=function(i,l,k,h){var j=i.prop('open'),m=j&&h||!j&&!h;if(m){i.removeClass('open').prop('open',false).triggerHandler('close.details');l.attr('aria-expanded',false);k.hide()}else{i.addClass('open').prop('open',true).triggerHandler('open.details');l.attr('aria-expanded',true);k.show()}};e.noSelect=function(){var h='none';return this.bind('selectstart dragstart mousedown',function(){return false}).css({MozUserSelect:h,msUserSelect:h,webkitUserSelect:h,userSelect:h})};if(g){d=e.details=function(){return this.each(function(){var i=f(this),h=f('summary',i).first();h.attr({role:'button','aria-expanded':i.prop('open')}).on('click',function(){var j=i.prop('open');h.attr('aria-expanded',!j);i.triggerHandler((j?'close':'open')+'.details')})})};d.support=g}else{d=e.details=function(){return this.each(function(){var h=f(this),j=f('summary',h).first(),i=h.children(':not(summary)'),k=h.contents(':not(summary)');if(!j.length){j=f('<summary>').text('Details').prependTo(h)}if(i.length!=k.length){k.filter(function(){return this.nodeType==3&&/[^ \t\n\f\r]/.test(this.data)}).wrap('<span>');i=h.children(':not(summary)')}h.prop('open',typeof h.attr('open')=='string');b(h,j,i);j.attr('role','button').noSelect().prop('tabIndex',0).on('click',function(){j.focus();b(h,j,i,true)}).keyup(function(l){if(32==l.keyCode||(13==l.keyCode&&!c)){l.preventDefault();j.click()}})})};d.support=g}}(document,jQuery));

// S9Y-MERGE END jquery.details.js - 2016-04-20 08:19
// S9Y-MERGE START accessifyhtml5.js - 2016-04-20 08:19
/*
 * Accessifyhtml5.js
 *
 * Source: https://github.com/yatil/accessifyhtml5.js
 */
var AccessifyHTML5=function(e,f){var c={article:{role:"article"},aside:{role:"complementary"},nav:{role:"navigation"},main:{role:"main"},output:{"aria-live":"polite"},section:{role:"region"},"[required]":{"aria-required":"true"}},g={ok:[],warn:[],fail:[]},k=g.fail,b,h,a,d,n,p,l,r,m,s=RegExp("aria-[a-z]+|role|tabindex|title|alt|data-[\\w-]+|lang|style|maxlength|placeholder|pattern|required|type|target|accesskey|longdesc"),t=0,q=document;if(q.querySelectorAll){e&&(e.header&&(c[e.header]={role:"banner"}),
e.footer&&(c[e.footer]={role:"contentinfo"}),e.main&&(c[e.main]={role:"main"},c.main={role:""}));if(f&&f._CONFIG_&&f._CONFIG_.ignore_defaults)c=f;else for(a in f)c[a]=f[a];for(b in c)if(!b.match(/^_(CONFIG|[A-Z]+)_/)&&c.hasOwnProperty(b)){try{h=q.querySelectorAll(b)}catch(u){k.push({sel:b,attr:null,val:null,msg:"Invalid syntax for `document.querySelectorAll` function",ex:u})}p=c[b];(!h||1>h.length)&&g.warn.push({sel:b,attr:null,val:null,msg:"Not found"});for(l=0;l<h.length;l++)for(n in p)if(p.hasOwnProperty(n)&&
(a=n,d=p[n],!a.match(/_?note/)))if(a.match(s))if((typeof d).match(/string|number|boolean/)){if(r=a.match(/(describ|label)l?edby/)){try{m=q.querySelector(d)}catch(v){k.push({sel:b,attr:a,val:d,msg:"Invalid selector syntax (2) - see 'val'",ex:v})}if(!m){k.push({sel:b,attr:a,val:d,msg:"Labelledby ref not found - see 'val'"});continue}m.id||(m.id="acfy-id-"+t);d=m.id;a="aria-"+("label"===r[1]?"labelledby":"describedby");t++}h[l].hasAttribute(a)?g.warn.push({sel:b,attr:a,val:d,msg:"Already present, skipped"}):
(h[l].setAttribute(a,d),g.ok.push({sel:b,attr:a,val:d,msg:"Added"}))}else k.push({sel:b,attr:a,val:d,msg:"Value-type not allowed"});else k.push({sel:b,attr:a,val:null,msg:"Attribute not allowed",re:s})}}g.input=c;return g};

// S9Y-MERGE END accessifyhtml5.js - 2016-04-20 08:19
