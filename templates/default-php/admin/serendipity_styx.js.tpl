/*
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details
*/

(function(serendipity, $, undefined ) {
    // Fires functions which are generated dynamically in backend PHP files
    // (i.e. include/functions_entries_admin.inc.php) which load the various
    // WYSIWYG editors in entries editor, HTML nuggets etc.
    serendipity.spawn = function() {
        if (self.Spawnextended) {
            Spawnextended();
        }

        if (self.Spawnbody) {
            Spawnbody();
        }

        if (self.Spawnnugget) {
            Spawnnugget();
        }
    }

    // Generic function to set cookies
    serendipity.SetCookie = function(name, value) {
        var today  = new Date();
        var expire = new Date();
        expire.setTime(today.getTime() + (60*60*24*30*1000));
        // get array like or simple string argument items
        if (name.indexOf("[") != -1) {
            document.cookie = 'serendipity' + name + '=' + escape(value) + ';expires=' + expire.toGMTString() + ';SameSite=Lax';
        } else {
            document.cookie = 'serendipity[' + name + ']=' + escape(value) + ';expires=' + expire.toGMTString() + ';SameSite=Lax';
        }
    }

    // Generic function to purge cookies
    serendipity.PurgeCookie = function(name) {
        if (serendipity.GetCookie(name) === null) return;
            // check whether it is a (namespaced key) serendipity array
            name = name.split('serendipity[').pop().split(']')[0];
        if (name.indexOf("[") != -1) {
            document.cookie = 'serendipity' + name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        } else {
            document.cookie = 'serendipity[' + name + ']=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }
    }

    // Generic function to get cookies
    serendipity.GetCookie = function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    // (De-) Pin Filtered (List) Entries temporary
    serendipity.PinFilter = function(el) {
        if (localStorage !== null) {
            if (localStorage.getItem('pin_entry_'+el) === null) {
                localStorage.setItem('pin_entry_'+el, Date.now());
                serendipity.SetCookie('entrylist_pin_entry_'+el, 'true');
            } else {
                localStorage.removeItem('pin_entry_'+el);
                serendipity.PurgeCookie('serendipity[entrylist_pin_entry_'+el+']');
            }
        }
    }

    serendipity.GetPinExpireTime = function(el) {
        var exp = localStorage.getItem('pin_entry_'+el);
        if (exp !== null) {
                exp = Number(exp);
            var now = Date.now();
            var day = Math.floor(((((exp - now)/1000)/60)/60)/24); // sec/min/hrs/days
            $('#entry_'+el+' .ucc-pinned-to').attr('title', function() { return $(this).attr('title') + '. Expires in '+(30+day)+' days.' }); // is like (30 + -2)
        }
    }

    /**
     * Based upon code written by chris wetherell
     * http://www.massless.org
     * chris [THE AT SIGN] massless.org
     */

    // Returns "position" of selection in textarea
    // Used internally by wrapSelectionWithLink()
    serendipity.getSelection = function($txtarea) {
        var start = $txtarea[0].selectionStart;
        var end = $txtarea[0].selectionEnd;
        return $txtarea.val().substring(start, end);
    }

    // Used by non-wysiwyg editor toolbar buttons to wrap selection
    // in a element associated with toolbar button
    serendipity.wrapSelection = function(txtarea, openTag, closeTag) {
        scrollPos = false;

        if (txtarea.scrollTop) {
            scrollPos = txtarea.scrollTop;
        }

        // http://stackoverflow.com/questions/1712417/jquery-wrap-selected-text-in-a-textarea
        var $txtarea = $(txtarea);

        if (!$txtarea.length) {
            return;
        }

        var len = $txtarea.val().length;
        var start = $txtarea[0].selectionStart;
        var end = $txtarea[0].selectionEnd;
        var selectedText = $txtarea.val().substring(start, end);
        var replacement = openTag + selectedText + closeTag;
        $txtarea.val($txtarea.val().substring(0, start) + replacement + $txtarea.val().substring(end, len));

        $txtarea[0].selectionStart = start + replacement.length;
        $txtarea[0].selectionEnd = start + replacement.length;

        if (scrollPos) {
            txtarea.focus();
            txtarea.scrollTop = scrollPos;
        }
    }

    // Used by non-wysiwyg editor toolbar buttons to wrap selection
    // in <a> element (only)
    serendipity.wrapSelectionWithLink = function(txtarea) {
        var my_link = prompt("Enter URL:","http://");

        if (my_link) {
            if (serendipity.getSelection($(txtarea) ) == "") {
                var my_desc = prompt("Enter Description", '');
            }
            var my_title = prompt("Enter title/tooltip:", "");
        }

        html_title = "";

        if (my_title != "" && my_title != null) {
            html_title = ' title="' + my_title + '"';
        }

        if (my_link != null) {
            lft = "<a href=\"" + my_link + "\"" + html_title + ">";

            if (my_desc != null && my_desc != "") {
                rgt = my_desc + "</a>";
            } else {
                rgt = "</a>";
            }

            serendipity.wrapSelection(txtarea, lft, rgt);
        }

        return;
    }
    /* end chris w. script */

    // Adds img element to selected text
    // Used internally by wrapInsImage()
    serendipity.insertText = function(txtarea, str) {
        $txtarea = $(txtarea);
        var selLength = $txtarea.val().length;
        var selStart = $txtarea[0].selectionStart;
        var selEnd = $txtarea[0].selectionEnd;

        if (selEnd==1 || selEnd==2) {
            selEnd=selLength;
        }

        var before = $txtarea.val().substring(0,selStart);
        var after = $txtarea.val().substring(selStart);

        $txtarea.val(before + str + after);

        $txtarea[0].selectionStart = selStart + str.length
        $txtarea[0].selectionEnd = selStart + str.length
    }

    // Used by non-wysiwyg editor toolbar buttons to wrap selection
    // in <img> element (only); does not really "wrap", merely inserts
    // an <img> element before selected text
    serendipity.wrapInsImage = function(txtarea) {
        var loc = prompt('Enter the image location: ');

        if (loc) {
            var alttxt = prompt('Enter alternative text for this image: ');
            serendipity.insertText(txtarea,'<img src="'+ loc + '" alt="' + alttxt + '">');
        }
    }
    /* end Better-Editor functions */

    // Switches preview of image selected from media db
    serendipity.change_preview = function(input, output) {
        var filename = document.getElementById(input).value;
        var $target = $('#' + output + '_preview img'); // removed > in '_preview > img' for < picture > containerized images
        $target.attr('src', filename);
    }

    // Opens media db image selection in new window
    serendipity.choose_media = function(id) {
        serendipity.openPopup('serendipity_admin.php?serendipity[adminModule]=media&serendipity[noBanner]=true&serendipity[noSidebar]=true&serendipity[noFooter]=true&serendipity[showMediaToolbar]=false&serendipity[showUpload]=true&serendipity[htmltarget]=' + id + '&serendipity[filename_only]=true');
    }

    // "Transfer" value from media db popup to form element, used for example for selecting a category-icon
    serendipity.serendipity_imageSelector_addToElement = function(str, id) {
        id = serendipity.escapeBrackets(id);
        var $input = $('#' + id);
        $input.val(str); // by category_icon, this adds to category template img tag, see serendipity.change_preview() for being peaceful to new picture containers

        if ($input.attr('type') != 'hidden') {
            $input.focus();    // IE would generate an error when focusing an hidden element
        }

        // calling the change-event for doing stuff like generating the preview-image
        $input.change();
    }

    // Escape [ and ] to be able to use the string as selector
    // jQuery fails to select the input when the selector contains unescaped [ or ]
    serendipity.escapeBrackets = function(str) {
        str = str.replace(/\[/g, "\\[");
        str = str.replace(/\]/g, "\\]");
        return str;
    }

    // Add another (image) keyword
    serendipity.AddKeyword = function(keyword) {
        s = document.getElementById('keyword_input').value;
        document.getElementById('keyword_input').value = (s != '' ? s + ';' : '') + keyword;
    }

    // "Transfer" value from media db popup to textarea, including wysiwyg
    // This gets textarea="body"/"extended" and tries to insert into the textarea
    // named serendipity[body]/serendipity[extended]
    serendipity.serendipity_imageSelector_addToBody = function(str, textarea) {
        var oEditor;
        if (typeof(TinyMCE) != 'undefined') {
            // for the TinyMCE editor we do not have a text mode insert
            tinyMCE.execInstanceCommand('serendipity[' + textarea + ']', 'mceInsertContent', false, str);
            return;
        } else if (typeof(CKEDITOR) != 'undefined') {
            oEditor = (typeof(isinstance) == 'undefined') ? CKEDITOR.instances[textarea] : isinstance;
            if (typeof(oEditor) == 'undefined') oEditor = popupEditorInstance;
            if (oEditor.mode == "wysiwyg") {
                oEditor.insertHtml(str);
                return;
            }
        }

        serendipity.noWysiwygAdd(str, textarea);
    }

    // The noWysiwygAdd JS function is the vanila serendipity_imageSelector_addToBody js function
    // which works fine in NO WYSIWYG mode
    // NOTE: the serendipity_imageSelector_addToBody could add any valid HTML string to the textarea
    serendipity.noWysiwygAdd = function(str, textarea) {
        escapedElement = serendipity.escapeBrackets(textarea);
        if ($('#' + escapedElement).length) {
            // Proper ID was specified (hopefully by plugins)
        } else {
            // Let us try the serendipity[] prefix
            escapedElement = serendipity.escapeBrackets('serendipity[' + textarea + ']');

            if (!$('#' + escapedElement).length) {
                console.log("Serendipity plugin error: " + escapedElement + " not found.");
            }
        }

        serendipity.wrapSelection($('#'+escapedElement), str, '');
    }

    // helper
    serendipity.hasClass = function(element, className) {
        return element.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(element.className);
    }

    // Changes the MediaLibrary Media Grid from default 2, to mid 3, or max 4 columns
    serendipity.changeMediaGrid = function(col) {
        var x = document.getElementsByClassName('media_file'),
            classes = ['mlMaxCol', 'mlMidCol', 'mlDefCol'];

        for (i = 0; i < x.length; i++) {
            for (var c = 0, j = classes.length; c < j; c++) {
                if (serendipity.hasClass(x[i], classes[c])) {
                    x[i].classList.remove(classes[c]);
                    x[i].classList.add(col);
                    serendipity.SetCookie('media_grid', col);
                }
            }
        }
    }

    // Changes the Theme List Media Grid from default 2, to mid 3, or max 4 columns
    serendipity.changeThemeGrid = function(col) {
        var x = document.getElementsByClassName('theme_file'),
            classes = ['tmMaxCol', 'tmMidCol', 'tmDefCol'];

        for (i = 0; i < x.length; i++) {
            for (var c = 0, j = classes.length; c < j; c++) {
                if (serendipity.hasClass(x[i], classes[c])) {
                    x[i].classList.remove(classes[c]);
                    x[i].classList.add(col);
                    serendipity.SetCookie('theme_grid', col);
                }
            }
        }
    }

    var pictureSubmit = false; // global scope
    serendipity.mediaPictureSubmit = function(event) {
        pictureSubmit = true; // local scope
    }

    // Inserting media db gallery images including s9y-specific container markup
    serendipity.serendipity_imageGallerySelector_done = function(textarea, g) {
        var gallery = '';

        if (g['files'][0]) {
            var img    = '';
            var float  = g['align'];
            var orient = g['orient'];
            var dc     = g['defcols'];
            if (orient != 'col') dc = 'null';
            var ac     = g['files'].length;
            var cc     = (g['orient'] == 'col' && ac < 3) ? ac : dc;
            var start  = '<div class="serendipity_image_block '+ orient +' c'+ cc +'">';
            var end    = '</div>';
            // open the gallery block element
            gallery += start;

            $.each(g['files'], function(k, v) {
                pic_el  = (v['full_thumb_avif'] || v['full_file_avif'] || v['full_thumb_webp'] || v['full_file_webp']) ? true : false;
                iAVFrmt = (v['sizeAVIF'] > 252 && v['sizeAVIF'] != 34165 && v['sizeAVIF'] != 3389 && ( v['sizeWebp'] == 0 || (v['sizeAVIF'] <= v['sizeWebp']) ));
                thbAVFt = (v['thumbSizeAVIF'] > 252 && v['thumbSizeAVIF'] != 3389 && v['thumbSizeAVIF'] != 34165 && ( v['thumbSizeWebp'] == 0 || (v['thumbSizeAVIF'] <= v['thumbSizeWebp']) ));
                imgID   = v['id'];
                imgWdth = v['thumbWidth'];
                imgHght = v['thumbHeight'];
                imgName = v['full_thumb'];
                ilink   = pic_el ? ((v['full_file_avif'] && iAVFrmt) ? v['full_file_avif'] : v['full_file_webp']) : v['full_file'];
                ilinkfb = v['full_file']; /* fallback case */
                title   = v['prop_title'] != '' ? v['prop_title'] : v['realname'];
                imgalt  = v['prop_alt'] ? v['prop_alt'] : v['realname']; /* yes check properties set alt first, then fallback */
                iftavif = v['full_thumb_avif'] ? v['full_thumb_avif'] : ''; /* to avoid having */
                iftwebp = v['full_thumb_webp'] ? v['full_thumb_webp'] : ''; /* null strings, when thumb variation not exists */
                hotlink = v['hotlink'];
                if (hotlink) {
                    imgName = v['realfile'];
                }

                if (pictureSubmit && pic_el) {
                    img = '<!-- s9ymdb:'+ imgID +' --><picture>'
                    + (thbAVFt ? '<source type="image/avif" srcset="' + iftavif + '">' : '')
                    + '<source type="image/webp" srcset="' + iftwebp + '">'
                    + '<img class="serendipity_image_'+ float +'" width="'+ imgWdth +'" height="'+ imgHght +'" src="'+ imgName +'"'+ ((title != '' && g['isLink'] == 'no') ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ imgalt +'">'
                    + '</picture>';
                } else {
                    img = '<!-- s9ymdb:'+ imgID +' --><img class="serendipity_image_'+ float +'" width="'+ imgWdth +'" height="'+ imgHght +'" src="'+ imgName +'"'+ ((title != '' && g['isLink'] == 'no') ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ imgalt +'">';
                }
                if (g['isLink'] == 'yes') {
                    img = '<a class="serendipity_image_link"'+ (title != '' ? ' title="'+ title +'"' : '') +' href="'+ ilink +'" data-fallback="'+ ilinkfb +'">'+ img +'</a>';
                }

                if (v['prop_imagecomment'] != '') {
                    var comment = v['prop_imagecomment'];

                    img = '<div class="serendipity_imageComment_'+ float +'" style="width:'+ imgWdth +'px">'
                      +      '<div class="serendipity_imageComment_img">'+ img +'</div>'
                      +      '<div class="serendipity_imageComment_txt">'+ comment +'</div>'
                      +   '</div>';
                }

                gallery += img;
            });
            // close the gallery block element
            gallery += end;
        }

        if (parent.self.opener == undefined) {
            // in iframes, there is no opener, and the magnific popup is wrapped
            parent.self = window.parent.parent.$.magnificPopup;
            parent.self.opener = window.parent.parent;
        }
        // add to textarea and close
        parent.self.opener.serendipity.serendipity_imageSelector_addToBody(gallery, textarea);
        parent.self.close();
    }

    // Inserting media db img markup including s9y-specific container markup
    serendipity.serendipity_imageSelector_done = function(textarea) {
        var insert = '';
        var img = '';
        var src = '';
        var altxt = '';
        var title = '';

        var f = document.forms['serendipity[selForm]'].elements;

            img       = f['imgName'].value;
        var imgWidth  = f['imgWidth'].value;
        var imgHeight = f['imgHeight'].value;
        var imgAVIFth = f['avifThumbName'].value;
        var imgWebPth = f['webPthumbName'].value;
        var imgAVIFfu = f['avifFileName'].value;
        var imgWebPfu = f['webPfileName'].value;
        var imgAVFrmt = f['srcAvifBestFormatSize'].value;
        var imgAVIF   = '';
        var imgWebP   = '';
        if (f['serendipity[linkThumbnail]']) {
             if (f['serendipity[linkThumbnail]'][0].checked === true) {
                img       = f['thumbName'].value;
                imgWidth  = f['imgThumbWidth'].value;
                imgHeight = f['imgThumbHeight'].value;
                imgAVIF   = (imgAVIFth != '') ? imgAVIFth : '';
                imgWebP   = (imgWebPth != '') ? imgWebPth : '';
            } else {
                imgAVIF   = (imgAVIFfu != '') ? imgAVIFfu : '';
                imgWebP   = (imgWebPfu != '') ? imgWebPfu : '';
            }
            // slightly different since the full image is needed for the ilink
            imgVariFullHref = (imgAVIFfu != '' && imgAVFrmt) ? imgAVIFfu : (imgWebPfu != '' ? imgWebPfu : '');
        }
        if (parent.self.opener == undefined) {
            // in iframes, there is no opener, and the magnific popup is wrapped
            parent.self = window.parent.parent.$.magnificPopup;
            parent.self.opener = window.parent.parent;
        }

        if (f['serendipity[filename_only]']) {
            // this part is used when selecting only the image without further markup (-> category-icon)
            var starget = f['serendipity[htmltarget]'] ? f['serendipity[htmltarget]'].value : 'serendipity[' + textarea + ']';

            switch(f['serendipity[filename_only]'].value) {
                case 'true':
                    parent.self.opener.serendipity.serendipity_imageSelector_addToElement(img, f['serendipity[htmltarget]'].value);
                    parent.self.close();
                    return true;
                case 'id':
                    parent.self.opener.serendipity.serendipity_imageSelector_addToElement(f['imgID'].value, starget);
                    parent.self.close();
                    return true;
                case 'thumb':
                    parent.self.opener.serendipity.serendipity_imageSelector_addToElement(f['thumbName'].value, starget);
                    parent.self.close();
                    return true;
                case 'big':
                    parent.self.opener.serendipity.serendipity_imageSelector_addToElement(f['imgName'].value, starget);
                    parent.self.close();
                    return true;
            }
        }

        altxt = f['serendipity[alt]'].value.replace(/"/g, "&quot;");
        title = f['serendipity[title]'].value.replace(/"/g, "&quot;");

        var imgID = 0;
        if (f['imgID']) {
            imgID = f['imgID'].value;
        }

        var isLink   = $(':input[name="serendipity[isLink]"]:checked').val() == "yes"; // bool
        var noLink   = $(':input[name="serendipity[isLink]"]:checked').val() == "no"; // bool
        var floating = $(':input[name="serendipity[align]"]:checked').val();
        if (floating == "") {
            floating = "center";
        }
        if (pictureSubmit) {
            img = '<!-- s9ymdb:'+ imgID +' --><picture>'
            + '<source type="image/avif" srcset="' + imgAVIF + '">'
            + '<source type="image/webp" srcset="' + imgWebP + '">'
            + '<img class="serendipity_image_'+ floating +'" width="'+ imgWidth +'" height="'+ imgHeight +'" src="'+ img +'"'+ ((title != '' && noLink) ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ altxt +'">'
            + '</picture>';
        } else {
            img = '<!-- s9ymdb:'+ imgID +' --><img class="serendipity_image_'+ floating +'" width="'+ imgWidth +'" height="'+ imgHeight +'" src="'+ img +'"'+ ((title != '' && noLink) ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ altxt +'">';
        }

        if (isLink) {
            // wrap the img in a link to the image
            var targetval = $('#select_image_target').val();
            var fallback  = (pictureSubmit && imgVariFullHref != '') ? ' data-fallback="'+ f['serendipity[url]'].value +'"' : '';

            var prepend   = '';
            // including check as-link usage targeting elsewhere
            var ilink     = (pictureSubmit && imgVariFullHref != '' && f['imgName'].value == f['serendipity[url]'].value) ? imgVariFullHref : f['serendipity[url]'].value;
            var itarget = '';

            switch (targetval) {
                case 'js':
                    var itarget = ' onclick="F1 = window.open(\'' + f['serendipity[url]'].value + '\',\'Zoom\',\''
                            + 'height=' + (parseInt(f['imgHeight'].value) + 15) + ','
                            + 'width='  + (parseInt(f['imgWidth'].value)  + 15) + ','
                            + 'top='    + (screen.height - f['imgHeight'].value) /2 + ','
                            + 'left='   + (screen.width  - f['imgWidth'].value)  /2 + ','
                            + 'toolbar=no,menubar=no,location=no,resize=1,resizable=1,scrollbars=yes\'); return false;"';
                    break;
                case '_blank':
                    var itarget = ' rel="noopener" target="_blank"';
                    break;
                case 'plugin':
                    var itarget = ' id="s9yisphref' + imgID + '" onclick="javascript:this.href = this.href + \'&amp;serendipity[from]=\' + self.location.href;"';
                    prepend = '<a title="' + ilink + '" id="s9yisp' + imgID + '"></a>';
                    ilink   = f['baseURL'].value + 'serendipity_admin_image_selector.php?serendipity[step]=showItem&amp;serendipity[image]=' + imgID;
                    break;
            }

            var img = prepend + "<a class=\"serendipity_image_link\"" + (title != '' ? ' title="' + title + '"' : '') + " href=\"" + ilink + "\"" + itarget + fallback +">" + img + "</a>";
        }

        if ($('#serendipity_imagecomment').val() != '') {
            var comment = f['serendipity[imagecomment]'].value;
            var ccenter = (floating == 'center' && imgWidth <= 400) ? '; display: block' : ''; // this overwrites pure themes display: contents for bigger images
            // in this #content image comment context for 1024px until 1594px wide screens. So it works for #content width 464px until ~1024px.

            var img = '<figure class="serendipity_imageComment_' + floating + '" style="width: ' + imgWidth + 'px' + ccenter + '">'
                  +     '<div class="serendipity_imageComment_img">' + img + '</div>'
                  +     '<figcaption class="serendipity_imageComment_txt">' + comment + '</figcaption>'
                  + '</figure>';
        }

        if (parent.self.opener.serendipity == undefined) {
            // in iframes, there is no opener, and the magnific popup is wrapped
            parent.self = window.parent.parent.$.magnificPopup;
            parent.self.opener = window.parent.parent;
        }
        // Add generic div for ALL pictureSubmit cases
        if (pictureSubmit && (imgWebPfu != '' || noLink)) {
            img = '<div>' + img + '</div>';
            //console.log('nolink img = '+img); // if not inside a container of what ever "p, div, span..." the picture/source element is magically removed when landing in your textarea
        }
        parent.self.opener.serendipity.serendipity_imageSelector_addToBody(img, textarea);
        parent.self.close();
    }

    // Toggle extended entry editor
    serendipity.toggle_extended = function(setCookie) {
        if ($('#toggle_extended').length == 0) {
            // this function got called on load of the editor
            var toggleButton = '#toggle_extended';
            $('#extended_entry_editor').parent().find('label').first().wrap('<button id="toggle_extended" class="icon_link" type="button"></button>');
            $(toggleButton).prepend('<span class="icon-down-dir" aria-hidden="true"></span> ');
            $(toggleButton).click(function(e) {
                e.preventDefault();
                serendipity.toggle_extended(true);
            });
            if (localStorage !== null && localStorage.show_extended_editor == "true" || $('#toggle_extended').length == 0) {
                // the editor is visible by default - note the value as string, since bool is not supported yet in localStorage
                return;
            }
        }

        if ($('#extended_entry_editor:hidden').length > 0) {
            $('#extended_entry_editor').show(); // use name selector instead of id here; id does not work
            $('#tools_extended').show();
            $('#toggle_extended').find('> .icon-right-dir').removeClass('icon-right-dir').addClass('icon-down-dir');
            if (localStorage !== null) {
                localStorage.show_extended_editor = "true";
            }
        } else {
            $('#extended_entry_editor').hide();
            $('#tools_extended').hide();
            $('#toggle_extended').find('> .icon-down-dir').removeClass('icon-down-dir').addClass('icon-right-dir');
            if (localStorage !== null) {
                localStorage.show_extended_editor = "false";
            }
        }
        if (setCookie) {
            document.cookie = 'serendipity[toggle_extended]=' + (($('#extended_entry_editor:hidden').length == 0) ? "true" : "") + ';';
        }
    }

    // Collapses/expands the category selector
    var categoryselector_stored_categories = null;
    serendipity.toggle_category_selector = function(id) {
        if ($('#toggle_' + id).length == 0) {
            // this function got called on load of the editor
            var toggleButton = '#toggle_' + id;

            $('#'+id).before('<button id="toggle_' + id + '" class="button_link" type="button" href="#' + id + '"><span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> <?= TOGGLE_ALL ?></span></button>');

            $(toggleButton).click(function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                serendipity.toggle_category_selector(id);
            });
            $('#'+id).change(function(e) {
                categoryselector_stored_categories = null;
            });

            if ($('#'+id).children('*[selected="selected"]').length > 1) {
                // when loading the page new for the preview and more than one category was
                // selected, collapsing the category-selector would lose those categories
                $('#'+id).attr('size', $('#'+id).children().length);
                $('#toggle_' + id).find('> .icon-right-dir').removeClass('icon-right-dir').addClass('icon-down-dir');
                return
            }

        }

        if ($('#'+id).attr('multiple')) {
            if ($('#'+id).children(':selected').filter('[value!="0"]').length > 1) {
                // when collapsing, all multiple selection needs to be saved to be restoreable if the click was a mistake
                var selected_categories = '';
                $('#'+id).children(':selected').filter('[value!="0"]').each(function(i, child) {
                    selected_categories += child.value + ','
                });
                categoryselector_stored_categories = selected_categories;
            }
            $('#'+id).removeAttr('multiple');
            $('#'+id).removeAttr('size');
            $('#toggle_' + id).find('> .icon-down-dir').removeClass('icon-down-dir').addClass('icon-right-dir');

        } else {
            $('#'+id).attr('multiple', '');
            $('#'+id).attr('size', $('#'+id).children().length);
            $('#toggle_' + id).find('> .icon-right-dir').removeClass('icon-right-dir').addClass('icon-down-dir');

            var selected_categories = categoryselector_stored_categories;
            if (selected_categories != null) {
                selected_categories = selected_categories.split(',');
                selected_categories.forEach(function(cat_id) {
                    if (cat_id) {
                        $('#'+id).find('[value="'+ cat_id +'"]').prop('selected', true);
                    }
                });
            }

        }
    }

    // save in the cookie which options were selected when inserting a image from the media db
    serendipity.rememberMediaOptions = function() {
        $('#imageForm :input').each(function(index, element) {
            if (element.type == 'radio' && element.checked !== false && element.name.trim() != '') {
                serendipity.SetCookie(element.name.replace(/\[/g, '_').replace(/\]/g, ''), $(element).val());
            }
        });
    }

    // Rescale image
    serendipity.rescale = function(dim, newval) {
        var ratio          = $('#serendipityScaleImg').attr('data-imgheight')/$('#serendipityScaleImg').attr('data-imgwidth');
        var trans          = new Array();
        trans['width']     = new Array('serendipity[height]', ratio);
        trans['height']    = new Array('serendipity[width]', 1/ratio);

        if ($('#resize_keepprops').is(':checked')) {
            document.serendipityScaleForm.elements[trans[dim][0]].value=Math.round(trans[dim][1]*newval);
        }
    }

    // Rename file in media db
    serendipity.rename = function(id, fname) {
        var newname;
        var media_rename = '<?= ENTER_NEW_NAME ?>';
        if (newname = prompt(media_rename + fname, fname)) {
            var media_token_url = $('input[name*="serendipity[token]"]').val();
            $.ajax({
                type: 'POST',
                url: '?serendipity[adminModule]=images&serendipity[adminAction]=rename&serendipity[fid]='+ escape(id) +'&serendipity[newname]='+ escape(newname) +'&serendipity[token]='+ media_token_url,
                async: true,
                cache: false,
                success: function(response) {
                    $response = (response.trim() == '')
                        ? '<p><?= DONE ?>!</p>\
                           <button id="rename_ok" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                          '
                        : response + ((response.indexOf("error") > -1) ? '\
                           <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?= MEDIA_RENAME_ERROR_RELOAD ?></span>\
                           <input class="go_back" type="button" onClick="$.magnificPopup.close();" value="<?= BACK ?>">\
                          ' : ' <button id="rename_ok" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                          ');
                    $.magnificPopup.open({
                        items: {
                            type: 'inline',
                                src: $('<div id="rename_msg">\
                                        <h4><?= MEDIA_RENAME ?></h4>\
                                        '+ $response +'\
                                        </div>')
                        },
                        type: 'inline',
                        midClick: true,
                        callbacks: {
                            open: function() {
                                this.content.on('click', '#rename_ok', function() {
                                    window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default';
                                });
                            },
                        }
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $.magnificPopup.open({
                        items: {
                            type: 'inline',
                                src: $('<div id="rename_msg">\
                                        <h4><?= MEDIA_RENAME ?></h4>\
                                        <p>"Status: " + textStatus</p>\
                                        '+ errorThrown +'\
                                        <button id="rename_error" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                                        </div>')
                        },
                        type: 'inline',
                        midClick: true,
                        callbacks: {
                            open: function() {
                                this.content.on('click', '#rename_error', function() {
                                    window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default';
                                });
                            },
                        }
                    });
                }
            });
        }
    }

    // magnificPopup front-end tristate dialog deleting media files and/or variations
    serendipity.confirmDialog = function(message, file, $el) {
        var dialog = '<div class="mfp-dialog mfp-confirm">';
        if (file) {
            var prep = '<?= DIALOG_DELETE_FILE_CONTINUE ?>';
            dialog += '  <h2> ' + prep.replace(/%s/i, file)  + '</h2>';
        }
        dialog += '  <div class="mfp-dialog-content"><p>' + message + '</p></div>';
        dialog += '  <div class="mfp-dialog-actions">';
        dialog += '    <button class="mfp-btn mfp-btn-primary mfp-btn-yes state_submit" type="button" role="button" aria-labelledby="ENTER-key"> <?= YES ?> </button>';
        dialog += '    <button class="mfp-btn mfp-btn-default mfp-btn-cancel" type="button" role="button" aria-labelledby="ESC-key"> <?= ABORT_NOW ?> </button> ';
        dialog += '    <button class="mfp-btn mfp-btn-secondary mfp-btn-no state_submit" type="button" role="button" aria-labelledby="SPACE-key"> <?= NO ?> </button> ';
        dialog += '  </div>';
        dialog += '</div>';

        $.magnificPopup.open( {
            modal: true,
            items: {
                src: dialog,
                type: 'inline'
            },
            callbacks: {
                open: function() {
                    var $content = $(this.content);

                    $content.on('click', '.mfp-btn-yes', function() {
                        serendipity.deleteFromML($el.attr('data-fileid'), $el.attr('data-filename'), 'doDelete', $el.attr('data-getpage'));
                        $.magnificPopup.close();
                        $(document).off('keydown', keydownHandler);
                    });

                    $content.on('click', '.mfp-btn-cancel', function() {
                        $.magnificPopup.close();
                        $(document).off('keydown', keydownHandler);
                    });

                    $content.on('click', '.mfp-btn-no', function() {
                        serendipity.deleteFromML($el.attr('data-fileid'), '.v/'+$el.attr('data-filename'), 'doDeleteVariations', $el.attr('data-getpage'));
                        $.magnificPopup.close();
                        $(document).off('keydown', keydownHandler);
                    });

                    var keydownHandler = function (e) {
                        if (e.keyCode == 13) { // ENTER key
                            $content.find('.mfp-btn-yes').click();
                            return false;
                        } else if (e.keyCode == 32) { // SPACE key
                            $content.find('.mfp-btn-no').click();
                            return false;
                        } else if (e.keyCode == 27) { // ESC key
                            $content.find('.mfp-btn-cancel').click();
                            return false;
                        }
                    };
                    $(document).on('keydown', keydownHandler);
                }
            }
        });
    };

    // Delete file from ML - per option all occurrences or only the variations
    serendipity.deleteFromML = function(id, fname, param, page=null) {
        var headline = (param == 'doDeleteVariations') ? '<?= DIALOG_DELETE_VARIATIONS ?>' : '<?= MEDIA_DELETE ?>';
        if (confirm(headline +' "'+ fname +'" ?')) {
            var media_token_url = $('input[name*="serendipity[token]"]').val();
            $.post('?serendipity[adminModule]=images&serendipity[adminAction]='+param+'&serendipity[fid]='+ escape(id) +'&serendipity[token]='+ media_token_url)
            .done(function(jqXHR, textStatus) {
                if (textStatus == 'success') {
                    $.magnificPopup.open({
                        items: {
                            type: 'inline',
                                src: $('<div id="delete_msg">\
                                        <h4>'+headline+'</h4>\
                                        '+ jqXHR + '\
                                        <button id="delete_ok" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                                        </div>')
                        },
                        type: 'inline',
                        midClick: true,
                        callbacks: {
                            open: function() {
                                this.content.on('click', '#delete_ok', function() {
                                    if (param == 'doDeleteVariations') {
                                        window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default&serendipity[page]='+ page;
                                    } else {
                                        window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default';/*media is on purge, fallback to ML pages start, since shrinking*/
                                    }
                                });
                            },
                        }
                    });
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                if (textStatus != null) { // what could that be ? "timeout", "error", "abort", "parsererror" ?
                    $.magnificPopup.open({
                        items: {
                            type: 'inline',
                                src: $('<div id="delete_msg">\
                                        <h4>'+headline+'</h4>\
                                        '+ jqXHR + '\
                                        <p>"Status: " + textStatus</p>\
                                        '+ errorThrown +'\
                                        <button id="delete_error" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                                        </div>')
                        },
                        type: 'inline',
                        midClick: true,
                        callbacks: {
                            open: function() {
                                this.content.on('click', '#delete_error', function() {
                                    window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default&serendipity[page]='+ page;
                                });
                            },
                        }
                    });
                }
            });
        }
    }

    // Add image variation files via ML media item request
    serendipity.addVariationsPerItem = function(id, fname, page=null) {
        var headline = '<?= MEDIA_CREATEVARS ?>';
        var media_token_url = $('input[name*="serendipity[token]"]').val();
        $.post('?serendipity[adminModule]=images&serendipity[adminAction]=variations&serendipity[fid]='+ escape(id) +'&serendipity[token]='+ media_token_url)
        .done(function(jqXHR, textStatus) {
            if (textStatus == 'success') {
                $.magnificPopup.open({
                    items: {
                        type: 'inline',
                            src: $('<div id="addvar_msg">\
                                    <h4>'+headline+'</h4>\
                                    '+ jqXHR + '\
                                    <button id="addvar_ok" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                                    </div>')
                    },
                    type: 'inline',
                    midClick: true,
                    callbacks: {
                        open: function() {
                            this.content.on('click', '#addvar_ok', function() {
                                window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default&serendipity[page]='+ page;
                            });
                        },
                    }
                });
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            if (textStatus != null) { // what could that be ? "timeout", "error", "abort", "parsererror" ?
                $.magnificPopup.open({
                    items: {
                        type: 'inline',
                            src: $('<div id="addvar_msg">\
                                    <h4>'+headline+'</h4>\
                                    '+ jqXHR + '\
                                    <p>"Status: " + textStatus</p>\
                                    '+ errorThrown +'\
                                    <button id="addvar_error" class="button_link state_submit" type="button"> <?= GO ?> </button>\
                                    </div>')
                    },
                    type: 'inline',
                    midClick: true,
                    callbacks: {
                        open: function() {
                            this.content.on('click', '#addvar_error', function() {
                                window.parent.parent.location.href= '?serendipity[adminModule]=images&serendipity[adminAction]=default&serendipity[page]='+ page;
                            });
                        },
                    }
                });
            }
        });
    }

    // Collapse/expand tree view in media db choose img popup window
    var tree_toggle_state = 'expand';

    serendipity.treeToggleAll = function() {
        if (tree_toggle_state == 'expand') {
            tree_toggle_state = 'collapse';
            tree.expandAll();
        } else {
            tree_toggle_state = 'expand';
            tree.collapseAll();
            coreNode.expand();
        }
    }

    // Used by media_upload.tpl to ..?
    serendipity.getfilename = function(value) {
        re = /^.+[\/\\]+?(.+)$/;
        return value.replace(re, "$1");
    }

    var inputStorage = new Array();

    serendipity.checkInputs = function() {
        upload_fieldcount = $('.uploadform_userfile').length;

        for (i = 1; i <= upload_fieldcount; i++) {
            if (!inputStorage[i]) {
                serendipity.fillInput(i, i);
            } else if (inputStorage[i] == document.getElementById('target_filename_' + i).value) {
                serendipity.fillInput(i, i);
            }
        }
    }

    serendipity.fillInput = function(source, target) {
        sourceval = serendipity.getfilename(document.getElementById('userfile_' + source).value);

        if (sourceval.length > 0) {
            document.getElementById('target_filename_' + target).value = sourceval;
            inputStorage[target] = sourceval;
        }
    }

    // ..?
    serendipity.checkSave = function() {
        <?php serendipity_plugin_api::hook_event('backend_entry_checkSave', $GLOBALS['tpl']); ?>
        return true;
    }

    // save in which directory the first uploaded files is stored (also the default when only inserting one file)
    serendipity.rememberUploadOptions = function() {
        serendipity.SetCookie('addmedia_directory', $('#target_directory_1').val());
        $('#waitingspin').toggle();
    }

    // Clones the upload form template
    serendipity.addUploadField = function() {
        upload_fieldcount = $('.uploadform_userfile').length + 1;

        var $fields = $('#uploads > div:last-child').clone(true);
        $fields.attr('id', 'upload_form_' + upload_fieldcount);

        var userfile             = $('.uploadform_userfile',               $fields);
        var userfile_label       = $('.uploadform_userfile_label',         $fields);
        var targetfilename       = $('.uploadform_target_filename',        $fields);
        var targetfilename_label = $('.uploadform_target_filename_label',  $fields);
        var targetdir            = $('.uploadform_target_directory',       $fields);
        var targetdir_label      = $('.uploadform_target_directory_label', $fields);

        userfile.attr('id', 'userfile_' + upload_fieldcount);
        userfile.attr('name', 'serendipity[userfile][' + upload_fieldcount + ']');
        userfile_label.attr('for', 'userfile_' + upload_fieldcount);

        targetfilename.attr('id', 'target_filename_' + upload_fieldcount);
        targetfilename.attr('name', 'serendipity[target_filename][' + upload_fieldcount + ']');
        targetfilename.val('');
        targetfilename_label.attr('for', 'target_filename_' + upload_fieldcount);

        targetdir.attr('id', 'target_directory_' + upload_fieldcount);
        targetdir.attr('name', 'serendipity[target_directory][' + upload_fieldcount + ']');
        // This looks weird, but works. If anyone can improve this, by all means do so.
        targetdir.val($($('#target_directory_' + (upload_fieldcount - 1))).val());
        targetdir_label.attr('for', 'target_directory_' + upload_fieldcount);

        $fields.appendTo('#uploads');
    }

    // Inverts a selection of checkboxes
    serendipity.invertSelection = function() {
        $('#formMultiSelect .multicheck').each(function() {
            var $box = $(this);
            var boxId = $box.attr('id');
            if ($box.is(':checked')) {
                $(boxId).prop('checked', false);
            } else {
                $(boxId).prop('checked', true);
            }
            $box.trigger('click');
        });
    }

    // Highlight/dehighlight elements in lists
    serendipity.highlightComment = function(id, checkvalue) {
        $('#' + id).toggleClass('multidel_selected');
    }

    serendipity.closeCommentPopup = function() {
        <?php if ($GLOBALS['tpl']['use_backendpopups'] || isset($GLOBALS['tpl']['force_backendpopups']['comments'])): ?>
            parent.self.close();
        <?php else: ?>
            window.parent.parent.$.magnificPopup.close();
        <?php endif; ?>
    }

    serendipity.openPopup = function(url) {
        <?php if ($GLOBALS['tpl']['use_backendpopups'] || isset($GLOBALS['tpl']['force_backendpopups']['images']) || isset($GLOBALS['tpl']['force_backendpopups']['comments'])): ?>
            window.open(url,
                        'ImageSel',
                        'width=800,height=600,toolbar=no,scrollbars=1,scrollbars,resize=1,resizable=1');
        <?php else: ?>
            $.magnificPopup.open({
              items: {
                src: url
              },
              type: 'iframe'
            });
        <?php endif; ?>
    };

    serendipity.reloadImage = function(img) {
        var chash = Math.random();
        $(img).attr('src', $(img).attr('src').split('?')[0]+'?'+chash);
        $(img).siblings().each(function() {
            var varimg = $(this).attr('srcset').split('?')[0];
            if (varimg.length > 0) {
                $(this).attr('srcset', varimg+'?'+chash);
            }
        });
        var slink = $(img).closest('article').find('> ul.media_file_actions .media_fullsize');
        $(slink).attr('href', $(slink).attr('href').split('?')[0]+'?'+chash);
    }

    serendipity.catsList = function() {
        var $source = $('#edit_entry_category');
        var $target = $('#cats_list > ul');
        var $selected = $source.find('input:checkbox:checked');

        $target.empty();

        if ($selected.length > 0) {
            $selected.each(function() {
                var catText = $(this).next('label').text();
                catText = $('<span>').text(catText).html();
                $('<li class="selected">'+ catText +'</li>').appendTo($target);
            });
        } else {
            $('<li><?= NO_CATEGORIES ?></li>').appendTo($target);
        }
    }

    serendipity.tagsList = function() {
        var $source = $('#properties_freetag_tagList').val();
        var $target = $('#tags_list > ul');

        if (typeof $source !== 'undefined') {
            var tagged = $source.split(',');
            $target.empty();

            if (tagged == '') {
                $('<li><?= EDITOR_NO_TAGS ?></li>').appendTo($target);
            } else {
                $.each(tagged, function(key, tag) {
                    $('<li class="tagged">'+ tag +'</li>').appendTo($target);
                });
            }
        }
    }

    serendipity.liveFilters = function(input, target, elem) {
        var currentInput = $(input).val().toLowerCase();

        if (currentInput == '') {
            $(target).toggle(true);
        } else {
            $(target).each(function() {
                var $el = $(this);

                if ($el.find(elem).html().toLowerCase().indexOf(currentInput) > -1) {
                    $el.toggle(true);
                } else {
                    $el.toggle(false);
                }
            });
        }
    }

    serendipity.liveFiltersHeader = function(group) {
        $(group).each(function() {
            var $el = $(this);
            var $cH = 0; // clientHeight

            $el.each( function() {
                // check the clientHeight of nextElementSibling (ul)
                $cH = $(this).next().height();
            });
            if ($cH == 0) {
                $el.hide();
            } else {
                $el.show();
            }
        });
    }

    if (Modernizr.indexeddb && <?= $GLOBALS['tpl']['use_autosave'] ?>) {
        serendipity.startEntryEditorCache = function() {
            if ($('textarea[name="serendipity[body]"]').val() == "") {
                serendipity.getCached("serendipity[body]",  function(res) {
                    if (res && res != null && res != "null") {
                        $('textarea[name="serendipity[body]"]').text(res);
                    }
                });
                serendipity.getCached("serendipity[extended]",  function(res) {
                    if (res && res != null && res != "null") {
                        if ($('textarea[name="serendipity[extended]"]').val() == "") {
                            $('textarea[name="serendipity[extended]"]').text(res);
                            if (! $('textarea[name="serendipity[extended]"]').is(':visible')) {
                                serendipity.toggle_extended();
                            }
                        }
                    }
                });
            }

            $('textarea[name="serendipity[body]"]').one('keyup', function() {
                setInterval(function() {
                    serendipity.cache("serendipity[body]", $('textarea[name="serendipity[body]"]').val())
                }, 5000);
            });
            $('textarea[name="serendipity[extended]"]').one('keyup', function() {
                setInterval(function() {
                    serendipity.cache("serendipity[extended]", $('textarea[name="serendipity[extended]"]').val());
                }, 5000);
            });
        }

        serendipity.eraseEntryEditorCache = function() {
            serendipity.cache("serendipity[body]", '');
            serendipity.cache("serendipity[extended]", '');
        }

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;

        serendipity.cache = function (id, data) {
            if (typeof indexedDB !== 'undefined') {
                var request = indexedDB.open("cache", 1);
                request.onupgradeneeded = function (event) {
                    event.target.result.createObjectStore("cache");
                };
                request.onsuccess = function(event) {
                    event.target.result.transaction(["cache"], 'readwrite').objectStore("cache").put(data, id);
                };
            }
        }

        serendipity.getCached = function(id, success) {
            if (typeof indexedDB !== 'undefined') {
                var request = indexedDB.open("cache", 1);
                request.onupgradeneeded = function (event) {
                    event.target.result.createObjectStore("cache");
                };
                request.onsuccess = function(event) {
                    event.target.result.transaction(["cache"], 'readwrite').objectStore("cache").get(id).onsuccess = function (event) {
                        success(event.target.result);
                    };
                };
            }
        }
    }

    serendipity.toggle_collapsible = function(toggler, target, stateClass, stateIcon, stateOpen, stateClosed) {
        // argument defaults
        stateClass = stateClass || 'additional_info';
        stateIcon = stateIcon || '> span';
        if (toggler[0].className.substring(0, 31) == 'button_link toggle_comment_full') {
            stateOpen = stateOpen || 'icon-up-dir';
        } else {
            stateOpen = stateOpen || 'icon-down-dir';
        }
        stateClosed = stateClosed || 'icon-right-dir';

        var $toggleIcon = $(toggler).find(stateIcon);
        var toggleState = $toggleIcon.attr('class');

        var tgdata = $(toggler).data('href'); // (string) data-href content
        var comment = new RegExp('#c([0-9]+_full)').test(tgdata); // check using in comments list single comment toggle

        if (comment && tgdata !== undefined) {
            var summary = tgdata.replace('_full','_summary'); // to catch object of eg. (string) #c197_summary
            $(summary).toggleClass(stateClass);
        }

        // if toggler does not have an id, do not store state
        var togglerId = $(toggler).attr('id');
        if (togglerId !== undefined) {
            var storageKey = 'show_' + $(toggler).attr('id');
        }

        if (toggleState == stateOpen) {
            $(toggler).removeClass('active');
            $toggleIcon.removeClass(stateOpen).addClass(stateClosed);
            if (togglerId !== undefined && localStorage !== null) {
                localStorage.setItem(storageKey, "false");
            }
        } else {
            $(toggler).addClass('active');
            $toggleIcon.removeClass(stateClosed).addClass(stateOpen);
            if (togglerId !== undefined && localStorage !== null) {
                localStorage.setItem(storageKey, "true");
            }
        }

        $(target).toggleClass(stateClass);
    }

    serendipity.sync_heights = function() {
        if ($('.equal_heights').length > 0) {
            $('.equal_heights').syncHeight({
                updateOnResize: true
            });
        }
    }

    serendipity.skipScroll = function(target, time) {
        time = typeof time !== 'undefined' ? time : 250;

        $('html, body').animate({
            scrollTop: $(target).offset().top
        }, time);
    }

    serendipity.updateAll = function() {
        var $overlay = $('<div id="overlay" />');
        $.get('?serendipity[adminModule]=plugins&serendipity[adminAction]=renderOverlay')
        .done(function(data) {
            $overlay.append(data);
            $overlay.appendTo(document.body);
            $('#updateProgress').attr('max', $('.plugin_status').length);
            serendipity.updateNext();
        });
    }

    serendipity.updateNext = function() {
        $('#updateMessage').text("Updating " + $('.plugins_installable > li:visible h4').first().text());
        $.get($('.plugin_status .button_link:visible').first().attr('href'))
        .done(function(messages) {
            $('.plugins_installable > li:visible').first().fadeOut();
            $('#updateProgress').attr('value', parseInt($('#updateProgress').attr('value')) + 1);
            if ($('.plugins_installable > li:visible').length > 0) {
                serendipity.updateNext();
            } else {
                $('#overlay').fadeOut("normal", function () {
                    window.location = $('#back').attr('href') + '&serendipity[updateAllMsg]=true';
                    // purge plugup plugin cookies after all
                    serendipity.PurgeCookie('plugsEvent');
                    serendipity.PurgeCookie('plugsPlugin');
                    serendipity.PurgeCookie('plugsCheckTime');
                });
            }
        })
        .fail(function(data) {
            $('#content').prepend(data.responseText);
            $('#updateAll').hide();
            $('#overlay').fadeOut();
        });
    }

}( window.serendipity = window.serendipity || {}, jQuery ))

$(function() {
    // Breakpoints for responsive JS
    var mq_small = Modernizr.mq('(min-width:640px)');

    // Fire responsive nav
    if ($('#main_menu').length > 0) {
        $('#nav-toggle').click(function(e) {
            var $el = $(this);
            var $target = $('body');
            var $icon = $el.find('span:first-child');
            $($target).toggleClass('active_nav');
            if ($($target).hasClass('active_nav')) {
                $icon.removeClass('icon-menu').addClass('icon-cancel');
            } else {
                $icon.removeClass('icon-cancel').addClass('icon-menu');
            }
            e.preventDefault();
        });
    }

    // Fire details polyfill
    $('html').addClass($.fn.details.support ? 'details' : 'no-details');
    $('details').details();

    // Fire WYSIWYG editor(s)
    serendipity.spawn();

    // Fire a11y helper script
    AccessifyHTML5({
        header: '#top',
        footer: '#meta'
    });

    // Editor-area
    if ($('#serendipityEntry').length > 0) {
        serendipity.catsList();
        serendipity.tagsList();
        serendipity.toggle_category_selector('categoryselector');
        serendipity.toggle_extended();
    }

    // Form submit events
    $('#uploadform').submit(function() {
        serendipity.rememberUploadOptions();
    });

    $('#imageForm').submit(function() {
        serendipity.serendipity_imageSelector_done();
    });

    // Click events
    //
    // Make the timestamp readable in browser not supporting datetime-local.
    // Has no effect in those supporting it, as the timestamp is invalid in HTML5
    if ($('#serendipityEntry').length > 0) {
        if (!Modernizr.inputtypes.date) {
            $('#serendipityNewTimestamp').val($('#serendipityNewTimestamp').val().replace("T", " "));
        }
        if (Modernizr.indexeddb && <?= $GLOBALS['tpl']['use_autosave'] ?>) {
            serendipity.startEntryEditorCache();
        }
    }

    // Set entry timestamp
    $('#reset_timestamp').click(function(e) {
        $('#serendipityNewTimestamp').val($(this).attr('data-currtime'));
        if (!Modernizr.inputtypes.date) {
            $('#serendipityNewTimestamp').val($('#serendipityNewTimestamp').val().replace("T", " "));
        }
        e.preventDefault();
        // Inline notification, we might want to make this reusable
        $('<span id="msg_timestamp" class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?= TIMESTAMP_RESET ?> <a class="remove_msg" href="#msg_timestamp"><span class="icon-cancel" aria-hidden="true"></span><span class="visuallyhidden"><?= HIDE ?></span></a></span>').insertBefore('#edit_entry_title');
        // Remove timestamp msg
        $('.remove_msg').click(function(e) {
            e.preventDefault();
            var $target = $(this).parent();
            $target.fadeOut(250, function() { $target.remove(); });
        });
        // Automagic removal after 5 secs
        setTimeout(function() {
            $('#msg_timestamp').fadeOut(250).remove();
        }, 5000);
    });

    // Switch entry status
    $('#switch_entry_status').click(function(e) {
        var $el = $(this);
        var oldState = $el.attr('title');
        var newState = $el.attr('data-title-alt');
        var entryState = $('#entry_status option');
        var stateIcon = $el.find("[class^='icon']");

        $(entryState).filter(function() {
            return $(this).html() == oldState;
        }).prop('selected', false);

        $(entryState).filter(function() {
            return $(this).html() == newState;
        }).prop('selected', true);

        $el.attr({
            'title': newState,
            'data-title-alt': oldState
        }).find('> .visuallyhidden').text(newState);

        if (stateIcon.hasClass('icon-toggle-on')) {
            stateIcon.removeClass('icon-toggle-on').addClass('icon-toggle-off');
        } else {
            stateIcon.removeClass('icon-toggle-off').addClass('icon-toggle-on');
        }

        // Inline notification, we might want to make this reusable
        $('<span id="msg_entrystatus" class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?= ENTRY_STATUS ?>: ' + newState + ' <a class="remove_msg" href="#msg_entrystatus"><span class="icon-cancel" aria-hidden="true"></span><span class="visuallyhidden"><?= HIDE ?></span></a></span>').insertBefore('#edit_entry_title');
        // Remove entrystatus msg
        $('.remove_msg').click(function(e) {
            e.preventDefault();
            var $target = $(this).parent();
            $target.fadeOut(250, function() { $target.remove(); });
        });
        // Automagic removal after 5 secs
        setTimeout(function() {
            $('#msg_entrystatus').fadeOut(250).remove();
        }, 5000);

        e.preventDefault();
    });

    // Matching change event
    $('#entry_status').change(function() {
        $('#switch_entry_status').trigger('click');
    });

    // Editor tools
    $('.wrap_selection').click(function() {
        var $el = $(this);
        var $tagOpen = $el.attr('data-tag-open');
        var $tagClose = $el.attr('data-tag-close');
        //var target = document.forms['serendipityEntry']['serendipity[' + $el.attr('data-tarea') + ']'];
        var target =  $('#'+serendipity.escapeBrackets($el.attr('data-tarea')));
        if ($el.hasClass('lang-html')) {
            var open = '<' + $tagOpen + '>';
            var close = '</' + $tagClose + '>';
        } else {
            var open = $tagOpen;
            var close = $tagClose;
        }
        serendipity.wrapSelection(target, open, close);
    });

    $('.wrap_insimg').click(function() {
        var target =  $('#'+serendipity.escapeBrackets($(this).attr('data-tarea')));
        serendipity.wrapInsImage(target);
    });

    $('.wrap_insurl').click(function() {
        var target =  $('#'+serendipity.escapeBrackets($(this).attr('data-tarea')));
        serendipity.wrapSelectionWithLink(target);
    });

    $('.wrap_insmedia').click(function() {
        serendipity.openPopup('serendipity_admin.php?serendipity[adminModule]=media&serendipity[noBanner]=true&serendipity[noSidebar]=true&serendipity[noFooter]=true&serendipity[showMediaToolbar]=false&serendipity[showUpload]=true&serendipity[textarea]=' + $(this).attr('data-tarea'));
    });

    $('.wrap_insgal').click(function() {
        serendipity.openPopup('serendipity_admin.php?serendipity[adminModule]=media&serendipity[noBanner]=true&serendipity[noSidebar]=true&serendipity[noFooter]=true&serendipity[showMediaToolbar]=false&serendipity[showGallery]=true&serendipity[textarea]=' + $(this).attr('data-tarea'));
    });

    // Entry metadata
    if ($('#edit_entry_metadata').length > 0) {
        $('#toggle_metadata').click(function() {
            serendipity.toggle_collapsible($(this), '#meta_data');
        });
        if (localStorage !== null && localStorage.getItem("show_toggle_metadata") == "true") {
            $('#toggle_metadata').click();
        }
    }

    // Show category selector
    <?php if ($GLOBALS['tpl']['use_backendpopups'] || isset($GLOBALS['tpl']['force_backendpopups']['categories'])): ?>
        if ($('#serendipityEntry').length > 0) {
            $('#select_category').click(function(e) {
                e.preventDefault();

                if ($('#meta_data').hasClass('additional_info')) {
                    $('#toggle_metadata').click();
                }

                serendipity.skipScroll($(this).attr('href'));
            });

            $('#cats_list').on('click', 'h3, li', function() {
                $('#select_category').trigger('click');
            });
        }
    <?php else: ?>
        $('#meta_data #edit_entry_category').addClass('mfp-hide');

        if ($('#serendipityEntry').length > 0) {
            var btnText = '<?= DONE ?>';

            $('#select_category').magnificPopup({
                type: "inline",
                closeMarkup: '<button title="%title%" class="mfp-close" type="button">'+ btnText +'</button>',
                callbacks: {
                    open: function() {
                        // Accessibility helper
                        $('#edit_entry_category .form_check input[type="checkbox"]').attr('aria-hidden', 'true');
                        if (localStorage !== null && localStorage.cat_view_state == "compact") {
                            $('.mfp-content').addClass('compact_categories');
                            $('#toggle_cat_view').find('.icon-th').removeClass('icon-th').addClass('icon-th-list');
                        }
                    },
                    afterClose: function() {
                        // Accessibility helper
                        $('#edit_entry_category .form_check input[type="checkbox"]').attr('aria-hidden', 'false');
                        serendipity.catsList();
                    }
                }
            });

            $('#cats_list').on('click', 'h3, li', function() {
                $('#select_category').trigger('click');
            });
        }
    <?php endif; ?>

    // Switch category view
    if ($('#serendipityEntry').length > 0) {
        $('#toggle_cat_view').click(function() {
            var $el = $(this);
            var $target = $('.mfp-content');

            if ($target.hasClass('compact_categories')) {
                $target.removeClass('compact_categories');
                $el.find('.icon-th-list').removeClass('icon-th-list').addClass('icon-th');
                if (localStorage !== null) {
                    localStorage.cat_view_state = "hierarchical";
                }
            } else {
                $target.addClass('compact_categories');
                $el.find('.icon-th').removeClass('icon-th').addClass('icon-th-list');
                if (localStorage !== null) {
                    localStorage.cat_view_state = "compact";
                }
            }
        });
    };

    // Show tag selector
    <?php if ($GLOBALS['tpl']['use_backendpopups'] || isset($GLOBALS['tpl']['force_backendpopups']['tags'])): ?>
        if ($('#serendipityEntry').length > 0) {
            $('#select_tags').click(function(e) {
                e.preventDefault();

                if ($('#adv_opts').hasClass('additional_info')) {
                    $('#toggle_advanced').click();
                }

                serendipity.skipScroll($(this).attr('href'));
            });

            $('#tags_list').on('click', 'h3, li', function() {
                $('#select_tags').trigger('click');
            });
        }
    <?php else: ?>
        $('#adv_opts #edit_entry_freetags').addClass('mfp-hide');

        if ($('#serendipityEntry').length > 0) {
            var btnText = '<?= DONE ?>';

            $('#select_tags').magnificPopup({
                type: "inline",
                closeMarkup: '<button title="%title%" class="mfp-close" type="button">'+ btnText +'</button>',
                callbacks: {
                    afterClose: function() {
                        serendipity.tagsList();
                    }
                }
            });

            $('#backend_freetag_list > a').click(function(e) {
                e.preventDefault();
            });

            $('#tags_list').on('click', 'h3, li', function() {
                $('#select_tags').trigger('click');
            });
        }
    <?php endif; ?>

    // Category live filter
    $('#categoryfilter').keyup(function() {
        serendipity.liveFilters($(this), '#edit_entry_category .form_check', 'label');
    });

    // Oldie helper for selecting categories
    $('#edit_entry_category .form_check input').change(function(e) {
        var $el = $(this);

        if ($el.is(":checked")) {
            $el.siblings('label').addClass('selected');
        } else {
            $el.siblings('label').removeClass('selected');
        }
    });

    // Plugin update check added by spartacus/plugup
    $('#upgrade_notice').click(function() {
      $( '#waitingspin' ).toggle();
    });

    // Plugin single update / install / import
    $('.button_link.state_update, .button_link.state_install, .button_link.state_import').click(function() {
      $('body, html').animate({ 'scrollTop' : $('#serendipity_admin_page').offset().top }, 500);
      $( '#waitingspin' ).toggle();
    });
    // Plugin statistics independently, since living in index.tpl which needs a different CSS styling
    $('#plugin_stats, #maintenance_thumbs input[type="submit"]').click(function() {
      $('body, html').animate({ 'scrollTop' : $('#serendipity_admin_page').offset().top }, 500);
      $( '#idx_waitingspin' ).toggle();
    });

    // Filter entries list by entry ID for ENTER
    $('input#skipto_entry').keypress(function(e) {
      if (e.which == 13) {
        $('input[name="serendipity[editSubmit]"]').click();
        return false;
      }
      return true;
    });

    // Plugins live filter
    $('#pluginfilter').keyup(function() {
        serendipity.liveFilters($(this), '.plugins_installable > li', '.plugin_features');
        serendipity.liveFiltersHeader('#content h3');
    });

    // Reset button for live filters
    $('.reset_livefilter').click(function() {
        var target = '#' + $(this).attr('data-target');
        $(target).val('').keyup();
    });

    // Advanced options
    if ($('#advanced_options').length > 0) {
        $('#toggle_advanced').click(function() {
            serendipity.toggle_collapsible($(this), '#adv_opts');
        });
        if (localStorage !== null && localStorage.getItem("show_toggle_advanced") == "true") {
            $('#toggle_advanced').click();
        }
    }

    // Entry preview
    $('.entry_preview:not(.comment_preview)').click(function() {
        document.forms['serendipityEntry'].elements['serendipity[preview]'].value='true';
    });

    // Collapsible configuration elements
    if ($('#serendipity_config_options, #serendipity_category, #image_directory_edit_form').length > 0) {
        var optsCollapsed = true;
        var defaultConfigState = true;

        $('.show_config_option').click(function(e) {
            var $el = $(this);
            if ($el.attr('href')) {
                var $toggled = $el.attr('href');
            } else {
                var $toggled = $el.data('href');
            }

            serendipity.toggle_collapsible($el, $toggled);
            e.preventDefault();
        });

        $('#show_config_all').click(function(e) {
            if ($(this).attr('href')) {
                var $container = $(this).attr('href');
            } else {
                var $container = $(this).data('href');
            }
            var $toggleIcon = $(this).find('span:first-child');
            var $toggleIcons = $($container).find('.show_config_option > span');
            var $toggleOption = $($container).find('.config_optiongroup');
            if (optsCollapsed) {
                $toggleIcons.removeClass('icon-right-dir').addClass('icon-down-dir');
                $toggleOption.removeClass('additional_info');
                $toggleIcon.removeClass('icon-right-dir').addClass('icon-down-dir');
                optsCollapsed = false;
            } else {
                $toggleIcons.removeClass('icon-down-dir').addClass('icon-right-dir');
                $toggleOption.addClass('additional_info');
                $toggleIcon.removeClass('icon-down-dir').addClass('icon-right-dir');
                optsCollapsed = true;
            }
            $(this).toggleClass('active');
            e.preventDefault();
            defaultConfigState = false;
        });

        // Since having changed the config_group default state behaviour to "open",
        // to support non-js erroneousness pages in special for the main and personal configuration groups (!)
        // this is to catch all different pages to set the default state
        if (defaultConfigState && ($('#plugin_options').length > 0 || $('#template_options').length > 0)) {
            // Plugin and Theme config groups only
            // void
        } else if (defaultConfigState && $('#serendipity_config_options').length > 0) {
            // Be strict to the default Serendipity configuration groups state only
            $('#show_config_all').click(); // Yes, click twice to open and close the config groups,
            $('#show_config_all').click(); // to remove and reset possible default wrong icon switches which occur with the else click part
            // Now reopen and reset the icon-dir for the first group
            $('#optionel1').find('span').removeClass('icon-right-dir').addClass('icon-down-dir');
            $('#el0').removeClass('additional_info');
        } else {
            $('.show_config_option:not(.show_config_option_hide)').click();
        }
    }

    $('.change_preview').change(function() {
        serendipity.change_preview($(this).attr('id'), $(this).attr('data-configitem'));
    });

    $('.choose_media').click(function() {
        var configitem = $(this).parent().find('.change_preview').attr('id');
        serendipity.choose_media(configitem);
    });

    $('.customfieldMedia').click(function() {
        var configitem = $(this).parent().find('textarea').attr('id');
        serendipity.choose_media(configitem);
    });

    $('#tree_toggle_all').click(function(e) {
        e.preventDefault();
        serendipity.treeToggleAll();
    });

    // Comments
    $('.comments_delete, .comments_multidelete, .build_cache').click(function() {
        var $msg = $(this).attr('data-delmsg');
        return confirm($msg);
    });

    $('.comments_reply').click(function(e) {
        e.preventDefault();
        serendipity.openPopup($(this).attr('href'));
    });

    // Selection for multicheck
    $('.multicheck').click(function() {
        var $el = $(this);
        serendipity.highlightComment($el.attr('data-multixid'), $el.prop('checked'));
    });

    // Invert checkboxes
    $('.invert_selection').click(function() {
        serendipity.invertSelection();
    });

    // Go back one step
    $('.go_back').click(function() {
        history.go(-1);
    });

    // Add media db upload field
    $('#add_upload').click(function(e) {
        e.preventDefault();
        serendipity.addUploadField();
    });

    // Check media db inputs
    $('.check_input').change(function() {
        serendipity.checkInputs();
    });

    $('.check_inputs').click(function() {
        serendipity.checkInputs();
    });

    // Extra function for media db download
    $('#imageurl').change(function() {
        sourceval = serendipity.getfilename(document.getElementById('imageurl').value);

        if (sourceval.length > 0) {
            document.getElementById('imagefilename').value = sourceval;
        }
    });

    // Dashboard bookmarklet hint
    $('.s9y_bookmarklet').click(function(e) {
        e.preventDefault();
        alert('<?= FURTHER_LINKS_S9Y_BOOKMARKLET_DESC ?>');
    });

    // Show media file info, template info, label info or filters
    $('.media_show_info, .template_show_info, .filters_toolbar li > a[href*=\\#], .toggle_info').click(function(e) {
        var $el = $(this);
        if ($el.attr('href')) {
            $($el.attr('href')).toggleClass('additional_info');
            if (e) $el.parent().siblings('div.comment_type.pingback').toggleClass('pingup');
        } else {
            $($el.data('href')).toggleClass('additional_info');
            if (e) $el.parent().siblings('div.comment_type.pingback').toggleClass('pingup');
        }
        if (mq_small) {
            $el.closest('.has_info').toggleClass('info_expanded');
        }
        $el.toggleClass('active');
        e.preventDefault();
    });

    // Show further links - we already made sure this is not mpf layered per default (w/o having self preferences ever saved)
    <?php if ((isset($GLOBALS['tpl']['use_backendpopups']) && $GLOBALS['tpl']['use_backendpopups']) || isset($GLOBALS['tpl']['force_backendpopups']['links'])): ?>
        if ($('#dashboard').length > 0) {
            $('.toggle_links').click(function(e) {
                $('#s9y_links').toggleClass('mfp-hide');
                $('#s9y_quicktip').toggleClass('mfp-hide');

                e.preventDefault();
                serendipity.skipScroll($(this).attr('href'));
            });
        }
    <?php else: ?>
        if ($('#dashboard').length > 0) {
            $('.toggle_links').magnificPopup({ type: "inline" });
        }
    <?php endif; ?>

    // Media file actions
    <?php if ($GLOBALS['tpl']['use_backendpopups'] || isset($GLOBALS['tpl']['force_backendpopups']['images'])): ?>
    $('.media_fullsize').click(function(e) {
        e.preventDefault();
        var $el = $(this);
        var filepath = $el.attr('href');
        var pwidth = $el.attr('data-pwidth');
        var pheight = $el.attr('data-pheight');
        var ptop = (screen.height - pheight)/2;
        var pleft = (screen.width - pwidth)/2;
        window.open(filepath, 'Zoom', 'height='+pheight+',width='+pwidth+',top='+ptop+',left='+pleft+',toolbar=no,menubar=no,location=no,resize=1,resizable=1,scrollbars=yes');
    });
    <?php else: ?>
    if ($('.media_fullsize').length > 0) {
        $('.media_fullsize').magnificPopup({ type:'image' });
    }
    <?php endif; ?>

    $('.media_rename').click(function(e) {
        e.preventDefault();
        var $el = $(this);
        serendipity.rename($el.attr('data-fileid'), $el.attr('data-filename'));
    });

    $('.media_delete').click(function(e) {
        e.preventDefault();
        var $el = $(this);
        serendipity.confirmDialog('<?= DIALOG_DELETE_VARIATIONS_PERITEM ?>', $el.attr('data-filename'), $el);
    });

    $('#media_crop').click(function(e) {
        window.open($(this).attr('href'), 'ImageCrop', 'width=800,height=600,toolbar=no,scrollbars=1,scrollbars,resize=1,resizable=1').focus();
        e.preventDefault();
    });

    $('.add_keyword').click(function(e) {
        serendipity.AddKeyword($(this).attr('data-keyword'));
        e.preventDefault();
    });

    // Confirm media scale
    $('.image_scale').click(function() {
        if (confirm('<?= REALLY_SCALE_IMAGE ?>')) {
            document.serendipityScaleForm.submit();
            $('#waitingspin').toggle();
        }
    });

    // Send media on properties form to a new format
    $('#mediaPropertyForm').submit(function() {
      $('body, html').animate({ 'scrollTop' : $('form').offset().top }, 500);
      $( '#waitingspin' ).toggle();
    });

    // Media scale change events
    $('#resize_width').change(function() {
        serendipity.rescale('width' , $(this).val());
    });

    $('#resize_height').change(function() {
        serendipity.rescale('height' , $(this).val());
    });

    // Show extended comment
    $('.toggle_comment_full').click(function(e) {
        var $el = $(this);
        if ($el.data('href') && $el.data('href').indexOf('#') > -1) {
            var cArray = $el.data('href').split(',');
        } else {
            if ($el.attr('href')) {
                var $toggled = $($el.attr('href'));
            } else {
                var $toggled = $($el.data('href'));
            }
        }

        if (Array.isArray(cArray)) {
            cArray.forEach(function(item){
                $n = item.substring(1);
                var $f = $('#c'+$n+'_full');
                var $s = $('#c'+$n+'_summary');
                serendipity.toggle_collapsible($el, $($el.data('href').replace(/,$/,'')));

                if ($f.toggleClass('additional_info')) {
                    $s.toggleClass('additional_info');
                }
                e.preventDefault();
            });
        } else {
            serendipity.toggle_collapsible($el, $toggled);

            $toggled.prev().toggleClass('additional_info');
            e.preventDefault();
        }
    });

    // MediaDB-Filter-Buttons shall react instantly
    $('input[name="serendipity[filter][fileCategory]"]').on('change', function() {
        $('#media_library_control').submit();
    });

    // Clone form submit buttons
    $('#sort_entries > .form_buttons').clone().appendTo('#filter_entries');
    $('#media_pane_sort > .form_buttons').clone().appendTo('#media_pane_filter');

    // Clone pagination - this could become a function, which should also
    // check if the cloned pagination already exists
    $('.media_pane .pagination').clone().prependTo('.media_pane');
    $('.comments_pane .pagination').clone().prependTo('.comments_pane');
    $('.entries_pane .pagination').clone().prependTo('.entries_pane');
    $('.karma_pane .pagination').clone().prependTo('.karma_pane');

    // close comment reply on button click
    if ($('#comment_replied').length > 0) {
        $('#comment_replied').click(function() {
            serendipity.closeCommentPopup();
        });
    }

    // UI-flow when selecting multiple images in ML upload
    if ($('.uploadform_userfile').length > 0) {
        $('.uploadform_userfile').change(function() {
            if ($(this).get(0).files.length > 1) {
                $(this).parent().siblings(':first').fadeOut();
                $(this).parent().siblings(':first').find('input').val('');
                $(this).attr('name', $(this).attr('name') + '[]');
            }
            if ($(this).get(0).files.length == 1) {
                $(this).parent().siblings(':first').fadeIn();
            }
        });
    }

    // update all button in plugin upgrade menu
    if ($('#updateAll').length > 0) {
        $('#updateAll').click(function() {
            serendipity.updateAll();
        });
    }

    // minify images before upload, approach taken from https://github.com/joelvardy/javascript-image-upload/
    <?php if (serendipity_get_config_var('uploadResize') && (serendipity_get_config_var('maxImgWidth') > 0 || serendipity_get_config_var('maxImgHeight') > 0)): ?>
        if ($('#uploadform').length > 0) {
            $('input[name="go_properties"]').hide();
            var progressIcon = document.createElement('span');
            progressIcon.className = 'uploadIcon icon-info-circled';
            errorIcon = document.createElement('span');
            errorIcon.className = 'uploadIcon icon-attention-circled';
            successIcon = document.createElement('span');
            successIcon.className = 'uploadIcon icon-ok-circled';
            $('#uploadform').submit(function(event) {
                if (! $('#imageurl').val()) {
                    event.preventDefault();
                    $('#uploadform .check_inputs').prop('disabled', true);
                    var sendDataToML = function(data, progressContainer, progress) {
                        $.ajax({
                            type: 'post',
                            url: $('#uploadform').attr('action'),
                            data: data,
                            cache: false,
                            processData: false,
                            contentType: false,
                            xhr: function() {
                                var xhr = $.ajaxSettings.xhr();
                                xhr.upload.onprogress = function(e) {
                                    if (e.lengthComputable) {
                                        progress.value = e.loaded / e.total * 100;
                                    }
                                };
                                return xhr;
                            }
                        }).done(function(data) {
                            progress.value = 100;
                            progressContainer.className = "msg_success";
                            $(progressContainer).find('.uploadIcon').replaceWith(successIcon.cloneNode(true));
                            $('#mediaupload_tabs').hide();
                            $('#imageselectorplus').hide();
                            $('.form_buttons input.check_inputs').hide();
                            var toMl = document.createElement('a');
                            $('.form_buttons').prepend(toMl);
                        }).fail(function(data) {
                            progressContainer.className = "msg_error";
                            progress.disabled = true;
                            progressContainer.innerHTML += "<?= ERROR_UNKNOWN_NOUPLOAD ?>";
                            $(progressContainer).find('.uploadIcon').replaceWith(errorIcon.cloneNode(true));
                        }).always(function() {
                            if ($('#ml_link').length == 0) {
                                var mlLink = document.createElement('a');
                                mlLink.id = "ml_link";
                                mlLink.className = "button_link";
                                mlLink.href = $('#uploadform').attr('action');
                                mlLink.innerHTML = "<?= MEDIA_LIBRARY ?>";
                                $(mlLink).hide();
                                $('.form_buttons').prepend(mlLink);
                                $(mlLink).fadeIn();
                            }
                            // Stop the working pulsator after first added image since the rest is done over progress container success green
                            $('#waitingspin').css({ 'display': 'none' });
                            $('#uploadform .check_inputs').prop('disabled', false);
                        });
                    };
                    $('.uploadform_userfile').each(function() {
                        var files = this.files;
                        for (var i = 0; i < files.length; i++) {
                            var reader = new FileReader();
                            reader.file = files[i];
                            reader.onload = function(readerEvent) {
                                var image = new Image();
                                var file = this.file;
                                var type = file.type;
                                var data = new FormData();
                                data.append('serendipity[action]', 'admin');
                                data.append('serendipity[adminModule]', 'media');
                                data.append('serendipity[adminAction]', 'add');
                                data.append('serendipity[token]', $('input[name*="serendipity[token]"]').val());
                                data.append('serendipity[target_filename][1]', $('input[name*="serendipity[target_filename][1]"]').val());
                                data.append('serendipity[target_directory][1]', $('select[name*="serendipity[target_directory][1]"]').val());
                                var progress = document.createElement('progress');
                                var progressContainer = document.createElement('span');
                                progressContainer.className = 'msg_notice';
                                progress.max = 100;
                                progress.value = 0;
                                $(progressContainer).append(progressIcon);
                                progressContainer.innerHTML += file.name + ": "
                                $(progressContainer).append(progress);
                                $('.form_buttons').append(progressContainer);
                                if (type.substring(0, 6) == "image/") {
                                    image.onload = function (imageEvent) {
                                        var canvas = document.createElement('canvas'),
                                            max_width = <?php if (serendipity_get_config_var('maxImgWidth')): ?><?= serendipity_get_config_var('maxImgWidth'); ?><?php else: ?>0<?php endif; ?>,
                                            max_height = <?php if (serendipity_get_config_var('maxImgHeight')): ?><?= serendipity_get_config_var('maxImgHeight'); ?><?php else: ?>0<?php endif; ?>,
                                            width = image.width,
                                            height = image.height;

                                        if (max_width > 0 && width > max_width) {
                                            height *= max_width / width;
                                            width = max_width;
                                        }
                                        if (max_height > 0 && height > max_height) {
                                            width  *= max_height / height;
                                            height = max_height;
                                        }

                                        canvas.width = width;
                                        canvas.height = height;
                                        canvas.getContext('2d').drawImage(image, 0, 0, width, height);

                                        if (type == "image/bmp") {
                                            {* bmp is not supported *}
                                            type = "image/png";
                                            data.append('serendipity[target_filename][1]', file.name.replace('.bmp', '.png'));
                                        }
                                        canvas.toBlob(function(blob) {
                                            data.append('serendipity[userfile][1]', blob, file.name);
                                            sendDataToML(data, progressContainer, progress);
                                        }, type);
                                    }
                                    image.src = readerEvent.target.result;
                                } else {
                                    data.append('serendipity[userfile][1]', file, file.name);
                                    sendDataToML(data, progressContainer, progress);
                                }
                            }
                            reader.readAsDataURL(reader.file);
                        }
                    });
                }
            });
        }
    <?php endif; ?>

    if ($('#serendipity_only_path').length > 0) {
        $('#serendipity_only_path').change(function() {
            serendipity.SetCookie('serendipity_only_path', $('#serendipity_only_path').val());
        });
    }

    if ($('.image_move').length > 0) {
        $('.image_move').removeClass('hidden');
        $('.image_move').magnificPopup({
            type: 'inline',
        });
        $('#move-popup form').submit(function(e) {
            e.preventDefault();
            $('#newDir').val($('#move-popup form select').val());
            $.magnificPopup.close();
            $('input[name="toggle_move"]').click();
        });
    }

    // reopen detail element after spamblock action
    if ($('#serendipity_comments_list').length > 0 && window.location.hash && $('#' + window.location.hash.replace('#', '')).length > 0) {
        $('#' + window.location.hash.replace('#', '')).find(".toggle_info").click();
    }

    // run spinner when adding single image variations via [+] icon
    $('.media_addvar').click(function(e) {
        e.preventDefault();
        var $el = $(this);
        $preview = $el.parent().parent().siblings().find('.media_file_preview');
        $preview.addClass('dimdark');
        $preview.find('.pulsator').toggle();
        serendipity.addVariationsPerItem($el.attr('data-fileid'), $el.attr('data-filename'), $el.attr('data-getpage'));
    })

    // ajaxify image rotate, solving cache issue
    $('.media_rotate_right,.media_rotate_left').click(function(e) {
        e.preventDefault();
        $rotateButton = $(this)
        $preview = $rotateButton.parent().parent().siblings().find('.media_file_preview');
        $image   = $rotateButton.closest('.media_file').find('img');
        $spinner = $preview.find('.pulsator');

        $preview.addClass('dimdark');
        $image.addClass('media_file_rotate');
        $.ajaxSetup({
          beforeSend: function() {
            $spinner.toggle();
          },
          complete: function(){
            $spinner.toggle();
            $image.toggleClass('media_file_rotate');
            $preview.toggleClass('dimdark');
          },
          success: function() {}
        });
        $.get($rotateButton.attr('href'), function() {
            serendipity.reloadImage($image);
        });
    })

    // Tabs
    if ($('.tabs').length > 0) {
        var currTabText = '<?= CURRENT_TAB ?>';

        $('.tabs').accessibleTabs({
            wrapperClass: 'tabcontent',
            currentClass: 'on',
            tabhead: 'h3',
            tabheadClass: 'visuallyhidden',
            tabbody: '.panel',
            fx: 'fadeIn',
            currentInfoText: currTabText,
            currentInfoClass: 'visuallyhidden',
            syncheights: false,
            saveState: true
        });
    }

    // Drag 'n' drop
    if (! Modernizr.touch){
        function getDragdropConfiguration(group) {
            return {
                containerSelector: '.pluginmanager_container',
                group: group,
                handle: '.pluginmanager_grablet',

                onDrop: function ($item, container, _super) {
                    var placement = $item.parents('.pluginmanager_container').data("placement");
                    $item.find('select[name$="placement]"]').val(placement);
                    $item.removeClass('dragged').removeAttr('style');
                    $('body').removeClass('dragging');
                    $.autoscroll.stop();
                },
                onDragStart: function ($item, container, _super) {
                    $.autoscroll.init();
                    $item.css({
                        height: $item.height(),
                        width: $item.width()
                    });
                    $item.addClass('dragged');
                    $('body').addClass('dragging');
                }
            }
        }

        $('.pluginmanager_sidebar .pluginmanager_container').sortable(getDragdropConfiguration('plugins_sidebar'));
        $('.pluginmanager_event .pluginmanager_container').sortable(getDragdropConfiguration('plugins_event'));
        $('.configuration_group .pluginmanager_container').sortable(getDragdropConfiguration('plugins_event'));
    }

    // Equal Heights
    $(window).on('load', function() {
        if (!Modernizr.flexbox) {
            if (mq_small) {
                serendipity.sync_heights();
            }
        }
    });

    // Make sure plugin list heights are recalculated when switching tabs
    $('#pluginlist_tabs a').click(function() {
        if (!Modernizr.flexbox) {
            if (mq_small) {
                serendipity.sync_heights();
            }
        }
    });

    // Toggle plugin_info t(oggle)group_info container (plugins.inc.tpl)
    $('.plugin_show_info.toggle_info.button_link').click(function() {
        $('#plugin_options div.plugin_info.tgroup_info').toggleClass('additional_info');
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const bool=(v)=>v=='true' ? true : false;

    const clickhandler=function(e){
        this.setAttribute('aria-expanded', !bool(this.ariaExpanded));

        let pnode = this.closest('ul'); // = get this.parentNode.parentNode.parentNode;
        let lex = document.querySelectorAll('#'+pnode.id+' .list-flex');
        let first = this.firstElementChild;
        let next = this.lastElementChild;

        if (first.className == 'open') {
            first.className = 'hide';
            // working on multi li.list-flex elements
            for (i=0; i < lex.length; i++) {
                lex[i].classList.remove('ease-out');
                lex[i].classList.add('ease-in');
                lex[i].removeAttribute('style');
            }
        } else {
            first.className = 'open';
            // working on multi li.list-flex elements
            for (i=0; i < lex.length; i++) {
                lex[i].classList.remove('ease-in');
                lex[i].classList.add('ease-out');
            }
        }

        if (next.className == 'open') {
            next.className = 'hide';
        } else {
            next.className = 'open';
        }

        if (pnode.id == 'entry_hooks' || pnode.id == 'activity_hooks') {
            let anim_in = document.querySelectorAll('#'+pnode.id+' .list-flex.ease-in');
            let anim_out = document.querySelectorAll('#'+pnode.id+' .list-flex.ease-out');

            for (i=0; i < anim_in.length; i++) {
                anim_in[i].classList.remove('ao')
                anim_in[i].offsetWidth
                anim_in[i].classList.add('ai')
            }
            for (i=0; i < anim_out.length; i++) {
                anim_out[i].classList.remove('ai')
                anim_out[i].offsetWidth
                anim_out[i].classList.add('ao')
            }
        }
    }

    // count delivered elements
    const ct_eh = document.querySelectorAll('#entry_hooks .list-flex').length;
    const ct_ah = document.querySelectorAll('#activity_hooks .list-flex').length;

    if (ct_eh > 1) {
        document.querySelectorAll('#entry_hooks .expandable-group > .ex > button').forEach(btn=>btn.addEventListener('click',clickhandler));
    } else {
        // reset the hidden sets and the toggle
        document.querySelectorAll('#entry_hooks .list-flex:not(.ease-in,.ease-out)').forEach(lst=>lst.className = 'remain-flex');
        let eh_tgrp = document.querySelector('#entry_hooks .expandable-group');
        if (eh_tgrp !== null) { eh_tgrp.remove(); }
    }
    if (ct_ah > 1) {
        document.querySelectorAll('#activity_hooks .expandable-group > .ex > button').forEach(btn=>btn.addEventListener('click',clickhandler));
    } else {
        // reset the hidden sets and the toggle
        document.querySelectorAll('#activity_hooks .list-flex:not(.ease-in,.ease-out)').forEach(lst=>lst.className = 'remain-flex');
        let ah_tgrp = document.querySelector('#activity_hooks .expandable-group');
        if (ah_tgrp !== null) { ah_tgrp.remove(); }
    }
});
