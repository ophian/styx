<?php

@define('PLUGIN_EVENT_WRAPPER_NAME', 'Event-Ausgabe Wrapper');
@define('PLUGIN_EVENT_WRAPPER_DESC', 'Zeigt die Ausgabedaten eines Event-Plugins an, wenn dieses etwas über die generate_content(&$title) Methode ausgibt. Dieses Plugin ist ein Sonderling, da es die grundsätzliche Modularität und Abgrenzung von Ereignis- und Seitenleistenplugins verkürzt und ein event Plugin als schnelles Ausgabemedium für die Frontend Seitenleiste nutzt.');
@define('PLUGIN_EVENT_WRAPPER_PLUGIN', 'Quell Event-Plugin');
@define('PLUGIN_EVENT_WRAPPER_PLUGINDESC', 'Wählen Sie das Event-Plugin aus, für das die Ausgabe dargestellt werden soll. Sie sollten sich im Klaren darüber sein, dass das event Plugin Ihrer Wahl diese Methode auch wirklich mit zusätzlichen generierten Ausgabeinhalten als nur dem Plugin-Titel anbietet!');
@define('PLUGIN_EVENT_WRAPPER_TITLEDESC', 'Geben Sie den Titel für die Sidebar an. Die Eingabe eines leeren Titels zeigt den des Event-Plugins an. Wenn Sie ein Ereignis-Plugin ausgewählt haben, bei dem kein Titel generiert werden kann, wird als Titel „Sample“ mit dem Inhalt „This is a sample!“ ausgegeben.');
