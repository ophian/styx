            <h3>Styx Quick Tipp</h3>
            <ol class="plainList quick_info">
                <li>
                    <b>I. Was ist das hier?</b><br>
                    <span><em>Dies ist das &#187;<b>Backend</b>&#171;; Der Platz der Administration.<br>Dieses ist der �ffentlichkeit nicht zug�nglich, welche nur auf das &#187;<b>Frontend</b>&#171;, das ver�ffentlichte <u>Blog</u>, gestaltet durch ihr <u>Theme</u> zugreifen kann.</em></span>
                </li>
                <li>
                    <b>II. Die �bersicht konfigurieren?</b><br>
                    <span><em>�ffnen Sie die Einstellungen der &#187;{$CONST.PERSONAL_SETTINGS}&#171; �ber den oberen Navigations- <span class="icon-cog-alt" aria-hidden="true"></span> -Knopf.</em></span>
                </li>
                <li>
                    <b>III. Der �bersicht mehr hinzuf�gen?</b><br>
                    <span><em>�ffnen Sie die Plugin Liste �ber &#187;{$CONST.MENU_SETTINGS}</em> &#10140; <em>{$CONST.MENU_PLUGINS}&#171; und installieren Sie <u>(event)</u> Ereignis-Plugins, zB. das empfohlene &#187;Serendipity Autoupdate&#171; Plugin. Sie werden es in der &#187;{$CONST.PLUGIN_GROUP_BACKEND_DASHBOARD}&#171; Kategoriengruppe finden.</em></span>
                </li>
                <li>
                    <b>IV. Suche nach mehr Themes?</b><br>
                    <span><em>�ffnen Sie die Einstellung des &#187;Spartacus&#171; Ereignis-Plugins und erlauben Sie die &#187;Themes&#171; Option. Sie ist standardm��ig deaktiviert, da das erstmalige Laden durchaus ein wenig Zeit beanspruchen kann.</em></span>
                </li>
                <li>
                    <b>V. Spezifische Konfigurationen?</b><br>
                    <span><em>Die Einstellungen f�r das Autoupdate Plugin zum Beispiel werden an zwei Orten erteilt. In der Plugin Konfiguration und genereller in der &#187;{$CONST.CONFIGURATION}</em> &#10140; <em>{$CONST.INSTALL_CAT_SETTINGS}&#171; Sektion. Globale Theme Einstellungen sind ebenfalls dort zu setzen, aber manche Themes haben noch erweiterte eigene Konfigurations-Einstellungen, wie das Standard Theme "pure". Auch die Blog Sprache als weiteres Beispiel wird generell in &#187;{$CONST.CONFIGURATION}&#171;... und genauer f�r den Benutzer in &#187;{$CONST.PERSONAL_SETTINGS}&#171; eingestellt.</em></span>
                    <ul>
                        <li>
                            <b>Manuelle Einstellungen</b><br>
                            <span><em>Durch die Nutzung der globalen &#187;&nbsp;$serendipity&nbsp;&#171; Variable gibt es des Weiteren die M�glichkeit <u>bestimmte</u> Standard Einstellungen mit Hilfe der &#187;serendipity_config_local.inc.php&#171; Datei zu �berschreiben. Welche das sind, lesen Sie bitte <a href="https://ophian.github.io/hc/en/code-primer.html#docs-initializing-the-framework-serendipity_configincphp-and-serendipity_config_localincphp" target="_blank" rel="noopener">hier</a>, in der Styx Dokumentation, nach. Nicht alle dort genannten Variablen sind wirklich <u>nur</u> auf diese Weise manuell zu setzen, da einige auch besser in den Konfigurationsoptionen zu finden sind. Allgemein sollten nur Nutzer mit ein wenig Erfahrung diese manuelle Erweiterungsm�glichkeit nutzen.</em></span>
                        </li>
                    </ul>
                </li>
                <li>
                    <b>VI. Empfehlungen f�r den ersten Start:</b><br>
                    <span><em>Es ist <u>nicht</u> zu empfehlen aus Neugier eine gro�e Anzahl von Plugins vom Start weg zu installieren. Jedes beansprucht ein gewisses Quantum an Ressourcen, wie RAM, erweiterte Datenbank Abfragen, oder Zeit und verlangsamt ihr Blog. Wenn Sie diese Maxime beachten, k�nnen Sie mit der Zeit nat�rlich viele Plugins ausprobieren und wieder deaktivieren bzw. l�schen.</em></span><br>
                    <span><em>Themes basieren auf Templates der Smarty Template Engine, einfach zu erlernen. Jedes Theme kann leicht modifiziert werden, zB durch eine neu angelegte, update-unabh�ngige user.css Datei, die neue Stylesheets be-, oder bereits geladene �ber-schreibt. Wenn Sie noch gr��ere Flexibilit�t und Unabh�ngigkeit ben�tigen, kopieren Sie ein Theme (siehe Dokumentation) und gestalten Sie es ganz nach ihren W�nschen, ohne auf zuk�nftige System/Theme Updates R�cksicht nehmen zu m�ssen.</em></span><br>
                    <span><em><br>Lesen Sie die Dokumentation und die FAQ f�r mehr.</em></span><br>
                    <span><em>Serendipity Styx ist h�chst konfigurabel und manche dieser erweiterten Einstellungen sind nicht leichtfertig zu nutzen, ohne tieferen Einblick und in Kenntnis ihrer Auswirkungen.</em></span>
                </li>
                <li>
                    <b>VII. Styx Ratgeber:</b><br>
                    <span><em>F�r Migrationen, lesen Sie die wichtige Styx Upgrade Dokumentation und den &#187;hitchhikers "upgrade" guide&#171; f�r das Styx Backend im <a href="https://ophian.github.io/hc/en/installation.html#user-content-the-important-upgraders-howto---step-by-step-guide" target="_blank" rel="noopener">Help Center</a> der Styx Webseite</em>.</span><br>
                    <span><em>Erlernen Sie unseren RichText-Editor mit den daf�r empfohlenen Techniken zu verwenden. Probieren Sie unsere Schritt f�r Schritt <a href="https://ophian.github.io/hc/en/the-stockholm-lessons.html" target="_blank" rel="noopener">RichText-Editor-Lektionen</a> auf der englischen Hilfe Webseite aus</em>.</span>
                </li>
            </ol>
