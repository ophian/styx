                  <div class="serendipity_amazonchr_body">
                   <?php if ($GLOBALS['tpl']['plugin_amazonchooser_page'] == 'Search'): ?>
                        <?php if (isset($thingy['strings']['mediumurl'])): ?>
                            <div class="serendipity_amazonchr_image">
                               <a href="<?= $GLOBALS['tpl']['plugin_amazonchooser_select_url'] ?><?= $thingy['strings']['ASIN'] ?>"><img class="serendipity_amazonchr_pic" src="<?= $thingy['strings']['mediumurl'] ?>"></a>
                            </div>
                        <?php endif; ?>
                     <div class="serendipity_amazonchr_block">
                        <div class="serendipity_amazonchr_title"><a href="<?= $GLOBALS['tpl']['plugin_amazonchooser_select_url'] ?><?= $thingy['strings']['ASIN'] ?>"><?= $thingy['strings']['title'] ?> <span class="serendipity_amazonchr_productgroup">(<?= $thingy['ITEMATTRIBUTES']['ITEMATTRIBUTES_PRODUCTGROUP'] ?>)</span></a></div>
                        <?php if (!isset($thingy['strings']['mediumurl'])): ?><div class="serendipity_amazonchr_noimage">(<?= PLUGIN_EVENT_AMAZONCHOOSER_NOIMAGE ?>)</div><?php endif; ?>
                        <div class="serendipity_amazonchr_detail"><a href="<?= $thingy['strings']['DETAILPAGEURL'] ?>" target="_new"><?= PLUGIN_EVENT_AMAZONCHOOSER_DISTINCTURL ?></a></div>
                   <?php elseif ($GLOBALS['tpl']['plugin_amazonchooser_page'] == 'Lookup'): ?>
                        <?php if (isset($thingy['strings']['mediumurl'])): ?>
                           <div class="serendipity_amazonchr_image">
                             <img border="0" src="<?= $thingy['strings']['mediumurl'] ?>">
                           </div>
                        <?php endif; ?>
                     <div class="serendipity_amazonchr_block">
                        <div class="serendipity_amazonchr_title"><?= $thingy['strings']['title'] ?> <span class="serendipity_amazonchr_productgroup">(<?= $thingy['ITEMATTRIBUTES']['ITEMATTRIBUTES_PRODUCTGROUP'] ?>)</span></div>
                        <?php if (!isset($thingy['strings']['mediumurl'])): ?><div class="serendipity_amazonchr_noimage">(<?= PLUGIN_EVENT_AMAZONCHOOSER_NOIMAGE ?>)</div><?php endif; ?>
                        <div class="serendipity_amazonchr_detail"><a href="<?= $thingy['strings']['DETAILPAGEURL'] ?>" target="_new"><?= PLUGIN_EVENT_AMAZONCHOOSER_DISTINCTURL ?></a></div>
                     <?php else: ?>
                        <?php if (isset($thingy['strings']['mediumurl'])): ?>
                           <div class="serendipity_amazonchr_image">
                              <a href="<?= $thingy['strings']['DETAILPAGEURL'] ?>" target="_new"><img class="serendipity_amazonchr_pic" src="<?= $thingy['strings']['mediumurl'] ?>"></a>
                           </div>
                        <?php endif; ?>
                     <div class="serendipity_amazonchr_block">
                         <div class="serendipity_amazonchr_title"><?= $thingy['strings']['title'] ?> <span class="serendipity_amazonchr_productgroup">(<?= $thingy['ITEMATTRIBUTES']['ITEMATTRIBUTES_PRODUCTGROUP'] ?>)</span></div>
                         <?php if (!isset($thingy['strings']['mediumurl'])): ?><div class="serendipity_amazonchr_noimage">(<?= PLUGIN_EVENT_AMAZONCHOOSER_NOIMAGE ?>)</div><?php endif; ?>
                         <div class="serendipity_amazonchr_detail"><a href="<?= $thingy['strings']['DETAILPAGEURL'] ?>" target="_new"><?= PLUGIN_EVENT_AMAZONCHOOSER_DISTINCTURL ?></a></div>
                   <?php endif; ?>
                     <?php if (isset($thingy['strings']['author'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_author"><?= PLUGIN_EVENT_AMAZONCHOOSER_AUTHOR ?>: <?= $thingy['strings']['author'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['director'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_director"><?= PLUGIN_EVENT_AMAZONCHOOSER_DIRECTOR ?>: <?= $thingy['strings']['director'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['actor'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_starring"><?= PLUGIN_EVENT_AMAZONCHOOSER_STARRING ?>: <?= $thingy['strings']['actor'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['artist'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_artists"><?= PLUGIN_EVENT_AMAZONCHOOSER_ARTISTS ?>: <?= $thingy['strings']['artist'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['artist'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_productlanguage"><?= PLUGIN_EVENT_AMAZONCHOOSER_PRODUCTLANGUAGE ?>: <?= $thingy['strings']['artist'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['manufacturer'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_manufacturer"><?= PLUGIN_EVENT_AMAZONCHOOSER_MANUFACTURER ?>: <?= $thingy['strings']['manufacturer'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['distributor'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_distributor"><?= PLUGIN_EVENT_AMAZONCHOOSER_DISTRIBUTOR ?>: <?= $thingy['strings']['distributor'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['publisher'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_publisher"><?= PLUGIN_EVENT_AMAZONCHOOSER_PUBLISHER ?>: <?= $thingy['strings']['publisher'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['brand'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_brand"><?= PLUGIN_EVENT_AMAZONCHOOSER_BRAND ?>: <?= $thingy['strings']['brand'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['model'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_model"><?= PLUGIN_EVENT_AMAZONCHOOSER_MODEL ?>: <?= $thingy['strings']['model'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['releasedate'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_releasedate"><?= PLUGIN_EVENT_AMAZONCHOOSER_RELEASED ?>: <?= $thingy['strings']['releasedate'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['publicationdate'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_publicationdate"><?= PLUGIN_EVENT_AMAZONCHOOSER_PUBLISHED ?>: <?= $thingy['strings']['publicationdate'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['format'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_format"><?= PLUGIN_EVENT_AMAZONCHOOSER_FORMAT ?>: <?= $thingy['strings']['format'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['platform'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_platform"><?= PLUGIN_EVENT_AMAZONCHOOSER_PLATFORM ?>: <?= $thingy['strings']['platform'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['genre'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_genre"><?= PLUGIN_EVENT_AMAZONCHOOSER_GENRE ?>: <?= $thingy['strings']['genre'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['numberofpages'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_numberofpages"><?= PLUGIN_EVENT_AMAZONCHOOSER_NUMPAGES ?>: <?= $thingy['strings']['numberofpages'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['numberofdiscs'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_numberofdiscs"><?= PLUGIN_EVENT_AMAZONCHOOSER_NUMDISKS ?>: <?= $thingy['strings']['numberofdiscs'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['running'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_running"><?= PLUGIN_EVENT_AMAZONCHOOSER_RUNNING ?>: <?= $thingy['strings']['running'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['esrbarating'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_esrbarating"><?= PLUGIN_EVENT_AMAZONCHOOSER_ESRBAGERATING ?>: <?= $thingy['strings']['esrbarating'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['audiencerating'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_audiencerating"><?= PLUGIN_EVENT_AMAZONCHOOSER_AUDIENCERATING ?>: <?= $thingy['strings']['audiencerating'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['ISBN'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_ISBN"><?= PLUGIN_EVENT_AMAZONCHOOSER_ISBN ?>: <?= $thingy['strings']['ISBN'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['EAN'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_EAN"><?= PLUGIN_EVENT_AMAZONCHOOSER_EAN ?>: <?= $thingy['strings']['EAN'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['price'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_price"><?= PLUGIN_EVENT_AMAZONCHOOSER_PRICE ?>: <?= $thingy['strings']['price'] ?></div>
                     <?php endif; ?>
                     <?php if (isset($thingy['strings']['feature'])): ?>
                      <div class="serendipity_amazonchr_attr" id="amazonchr_feature"><?= PLUGIN_EVENT_AMAZONCHOOSER_FEATURE ?>: <?= $thingy['strings']['feature'] ?></div>
                     <?php endif; ?>
                     <div class="serendipity_amazonchr_offers">
                         <?php if (isset($thingy['strings']['offersurl'])): ?>
                            <div class="serendipity_amazonchr_offer"><a href="<?= $thingy['strings']['offersurl'] ?>" target="_new"><?= PLUGIN_EVENT_AMAZONCHOOSER_ALLOFFERS ?></a></div>
                         <?php endif; ?>
                         <?php if (isset($thingy['strings']['newoffers'])): ?>
                           <div class="serendipity_amazonchr_offers" id="amazonchr_newoffers"><?= $thingy['strings']['newoffers'] ?></div>
                         <?php endif; ?>
                         <?php if (isset($thingy['strings']['usedoffers'])): ?>
                           <div class="serendipity_amazonchr_offers" id="amazonchr_usedoffers"><?= $thingy['strings']['usedoffers'] ?></div>
                         <?php endif; ?>
                         <?php if (isset($thingy['strings']['collectableoffers'])): ?>
                           <div class="serendipity_amazonchr_offers" id="amazonchr_collectableoffers"><?= $thingy['strings']['collectableoffers'] ?></div>
                         <?php endif; ?>
                         <?php if (isset($thingy['strings']['refurboffers'])): ?>
                           <div class="serendipity_amazonchr_offers" id="amazonchr_refurboffers"><?= $thingy['strings']['refurboffers'] ?></div>
                         <?php endif; ?>
                     </div>
                    </div>
                    <div class="serendipity_amazonchr_cache"><?= PLUGIN_EVENT_AMAZONCHOOSER_ASOF ?> <?= $GLOBALS['tpl']['plugin_amazonchooser_cache_time'] ?></div>
                  </div>
