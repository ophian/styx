            <h3>Styx Quick Tipp</h3>
            <ol class="plainList quick_info">
                <li>
                    <b>I. Was ist das hier?</b><br>
                    <span><em>Dies ist das &#187;<b>Backend</b>&#171;; Der Platz der Administration.<br>Dieses ist der Öffentlichkeit nicht zugänglich, welche nur auf das &#187;<b>Frontend</b>&#171;, das veröffentlichte <u>Blog</u>, gestaltet durch ihr <u>Theme</u> zugreifen kann.</em></span>
                </li>
                <li>
                    <b>II. Die Übersicht konfigurieren?</b><br>
                    <span><em>Öffnen Sie die Einstellungen der &#187;{$CONST.PERSONAL_SETTINGS}&#171; über den oberen Navigations- <span class="icon-cog-alt" aria-hidden="true"></span> -Knopf.</em></span>
                </li>
                <li>
                    <b>III. Der Übersicht mehr hinzufügen?</b><br>
                    <span><em>Öffnen Sie die Plugin Liste über &#187;{$CONST.MENU_SETTINGS}</em> &#10140; <em>{$CONST.MENU_PLUGINS}&#171; und installieren Sie <u>(event)</u> Ereignis-Plugins, zB. das empfohlene &#187;Serendipity Autoupdate&#171; Plugin. Sie werden es in der &#187;{$CONST.PLUGIN_GROUP_BACKEND_DASHBOARD}&#171; Kategoriengruppe finden.</em></span>
                </li>
                <li>
                    <b>IV. Suche nach mehr Themes?</b><br>
                    <span><em>Öffnen Sie die Einstellung des &#187;Spartacus&#171; Ereignis-Plugins und erlauben Sie die &#187;Themes&#171; Option. Sie ist standardmäßig deaktiviert, da das erstmalige Laden durchaus ein wenig Zeit beanspruchen kann.</em></span>
                </li>
                <li>
                    <b>V. Spezifische Konfigurationen?</b><br>
                    <span><em>Die Einstellungen für das Autoupdate Plugin zum Beispiel werden an zwei Orten erteilt. In der Plugin Konfiguration und genereller in der &#187;{$CONST.CONFIGURATION}</em> &#10140; <em>{$CONST.INSTALL_CAT_SETTINGS}&#171; Sektion. Globale Theme Einstellungen sind ebenfalls dort zu setzen, aber manche Themes haben noch erweiterte eigene Konfigurations-Einstellungen, wie das Standard Theme "pure". Auch die Blog Sprache als weiteres Beispiel wird generell in &#187;{$CONST.CONFIGURATION}&#171;... und genauer für den Benutzer in &#187;{$CONST.PERSONAL_SETTINGS}&#171; eingestellt.</em></span>
                    <ul>
                        <li>
                            <b>Manuelle Einstellungen</b><br>
                            <span><em>Durch die Nutzung der globalen &#187;&nbsp;$serendipity&nbsp;&#171; Variable gibt es des Weiteren die Möglichkeit <u>bestimmte</u> Standard Einstellungen mit Hilfe der &#187;serendipity_config_local.inc.php&#171; Datei zu überschreiben. Welche das sind, lesen Sie bitte <a href="https://ophian.github.io/hc/en/code-primer.html#docs-initializing-the-framework-serendipity_configincphp-and-serendipity_config_localincphp" target="_blank" rel="noopener">hier</a>, in der Styx Dokumentation, nach. Nicht alle dort genannten Variablen sind wirklich <u>nur</u> auf diese Weise manuell zu setzen, da einige auch besser in den Konfigurationsoptionen zu finden sind. Allgemein sollten nur Nutzer mit ein wenig Erfahrung diese manuelle Erweiterungsmöglichkeit nutzen.</em></span>
                        </li>
                    </ul>
                </li>
                <li>
                    <b>VI. Empfehlungen für den ersten Start:</b><br>
                    <span><em>Es ist <u>nicht</u> zu empfehlen aus Neugier eine große Anzahl von Plugins vom Start weg zu installieren. Jedes beansprucht ein gewisses Quantum an Ressourcen, wie RAM, erweiterte Datenbank Abfragen, oder Zeit und verlangsamt ihr Blog. Wenn Sie diese Maxime beachten, können Sie mit der Zeit natürlich viele Plugins ausprobieren und wieder deaktivieren bzw. löschen.</em></span><br>
                    <span><em>Themes basieren auf Templates der Smarty Template Engine, einfach zu erlernen. Jedes Theme kann leicht modifiziert werden, zB durch eine neu angelegte, update-unabhängige user.css Datei, die neue Stylesheets be-, oder bereits geladene über-schreibt. Wenn Sie noch größere Flexibilität und Unabhängigkeit benötigen, kopieren Sie ein Theme (siehe Dokumentation) und gestalten Sie es ganz nach ihren Wünschen, ohne auf zukünftige System/Theme Updates Rücksicht nehmen zu müssen.</em></span><br>
                    <span><em><br>Lesen Sie die Dokumentation und die FAQ für mehr.</em></span><br>
                    <span><em>Serendipity Styx ist höchst konfigurabel und manche dieser erweiterten Einstellungen sind nicht leichtfertig zu nutzen, ohne tieferen Einblick und in Kenntnis ihrer Auswirkungen.</em></span>
                </li>
                <li>
                    <b>VII. Styx Ratgeber:</b><br>
                    <span><em>Lesen Sie die wichtige Styx Upgrade Dokumentation und den &#187;hitchhikers "upgrade" guide&#171; für das Styx Backend im Styx Webseite <a href="https://ophian.github.io/hc/en/installation.html#user-content-the-important-upgraders-howto---step-by-step-guide" target="_blank" rel="noopener">Help Center</a></em>.</span>
                </li>
            </ol>
