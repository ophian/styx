            <h3>Styx Quick Tip</h3>
            <ol class="plainList quick_info">
                <li>
                    <b>I. What is this?</b><br>
                    <span><em>This is the &#187;<b>Backend</b>&#171;; The place for administration.<br>It is not accessible for the public, which only has access to the &#187;<b>Frontend</b>&#171;, the published <u>View</u>, ordered by your <u>Theme</u>.</em></span>
                </li>
                <li>
                    <b>II. Configurate the Dashboard?</b><br>
                    <span><em>Open &#187;{$CONST.PERSONAL_SETTINGS}&#171; options via top nav <span class="icon-cog-alt" aria-hidden="true"></span> button.</em></span>
                </li>
                <li>
                    <b>III. Add even more to the Dashboard?</b><br>
                    <span><em>Open up the plugin list via &#187;{$CONST.MENU_SETTINGS}</em> &#10140; <em>{$CONST.MENU_PLUGINS}&#171; and install an <u>event</u> plugin, eg the recommended &#187;Serendipity Autoupdate&#171; Plugin. You may find it in the &#187;{$CONST.PLUGIN_GROUP_BACKEND_DASHBOARD}&#171; group category.</em></span>
                </li>
                <li>
                    <b>IV. Searching for more themes?</b><br>
                    <span><em>Open the &#187;Spartacus&#171; Event Plugin Configuration and enable the themes option. This is disabled by default, since it can take a little longer to fetch the data on first call.</em></span>
                </li>
                <li>
                    <b>V. Specific Configurations?</b><br>
                    <span><em>For example the configuration for the Autoupdate is done specifically in its plugin configuration and the more general behaviour is set in the &#187;{$CONST.CONFIGURATION}</em> &#10140; <em>{$CONST.INSTALL_CAT_SETTINGS}&#171; Section. Global theme options are set near that too, but some themes have their own configuration page, like the standard theme "pure". The blog language in example is set in &#187;{$CONST.CONFIGURATION}&#171;... and in &#187;{$CONST.PERSONAL_SETTINGS}&#171; for the user.</em></span>
                    <ul>
                        <li>
                            <b>Manually controlled options</b><br>
                            <span><em>Since using the global &#187;&nbsp;$serendipity&nbsp;&#171; variable, you can also overwrite <u>certain</u> standard options in your &#187;serendipity_config_local.inc.php&#171; file. For a comprehensive list, see <a href="https://ophian.github.io/hc/en/code-primer.html#docs-initializing-the-framework-serendipity_configincphp-and-serendipity_config_localincphp" target="_blank" rel="noopener">here</a> in the Styx documentation. Not all mentioned variables in there may <u>only</u> be set manually, since some of them are found better in the global configurations. You should only use this potential with a little knowledge and experience of your blog.</em></span>
                        </li>
                    </ul>
                </li>
                <li>
                    <b>VI. First Start Recommendation:</b><br>
                    <span><em>Do <u>not</u> start by installing various plugins at once. Each one allocates additional resources, like RAM, additional database queries, or time and slows down your blog. Keeping this in mind you may test and remove all you want.</em></span><br>
                    <span><em>Themes are template based by the Smarty template engine, easy to learn. Each theme may easily be extended by creating a user.css file with some overwriting stylesheets of your need. If you want to have even more flexibility and independency, make yourself a copy-theme and extend it without having to mind any further system update.</em></span><br>
                    <span><em><br>Read the Documentation and the FAQ for more.</em></span><br>
                    <span><em>This System is highly configurable and some of these advanced options are not recommended to use naively without deeper knowledge of what they will do.</em></span>
                </li>
                <li>
                    <b>VII. Styx Guide:</b><br>
                    <span><em>Read the important Styx Upgrade Documentation and the hitchhikers guide to the Styx Backend in the Serendipity Styx Website <a href="https://ophian.github.io/hc/en/installation.html#user-content-the-important-upgraders-howto---step-by-step-guide" target="_blank" rel="noopener">Help Center</a></em>.</span>
                </li>
            </ol>
