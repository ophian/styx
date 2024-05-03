<!-- hidden break for indent -->
                    <section id="staticblock_entries_dategroup" class="serendipity_Entry_Date">

                        <header>
                            <p class="serendipity_date">{$staticblock.timestamp|formatTime:DATE_FORMAT_ENTRY}</p>
                        </header>

                        <article class="post post_single">
                            <header>
                                <h3>{$staticblock.title}</h3>
                                <p class="post_byline">{$CONST.POSTED_BY} {$staticblock.author} {$CONST.ON} <time datetime="{$staticblock.timestamp|serendipity_html5time}">{$staticblock.timestamp|formatTime:$template_option.date_format}</time></p>
                            </header>

                            <div class="post_content">
                                <div class="serendipity_entry_body">{$staticblock.body}</div>
                                <div class="serendipity_entry_extended">{$staticblock.extended}</div>
                            </div>
                        </article>
                    </section>
