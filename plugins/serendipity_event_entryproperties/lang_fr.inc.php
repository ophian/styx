<?php

##########################################################################
# serendipity - another blogger...                                       #
##########################################################################
#                                                                        #
# (c) 2003 Jannis Hermanns <J@hacked.it>                                 #
# http://www.jannis.to/programming/serendipity.html                      #
#                                                                        #
# Translated by                                                          #
# Sebastian Mordziol <argh@php-tools.net>                                #
# http://sebastian.mordziol.de                                           #
#                                                                        #
##########################################################################

@define('PLUGIN_EVENT_ENTRYPROPERTIES_TITLE', 'Propri�t�s �tendues pour les billets');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DESC', '(cache, billets non publics, billets collants)');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS', 'Render ce billet collant');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS', 'Billets peuvent �tre lus par');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE', 'Moi-m�me');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBER', 'Co-auteurs');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC', 'Tout le monde');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE', 'Activer le cache des billets?');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC', 'Si activ�, une version cach�e sera cr�e � chaque enregistrement du billet. Le cache augmente la performance, mais peut aussi diminuer la flexibilit� pour les autres plugins actifs. If you use the rich-text editor (wysiwyg) a cache is actually useless, unless you use many plugins that further change the output markup.');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE', 'Cr�er le cache pour les billets');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT', 'Chargement du prochain jeu de billets...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO', 'Chargement des billets %d � %d');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING', 'Cr�ation du cache pour le billet #%d, <em>%s</em>...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHED', 'Billet cach�.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE', 'Cr�ation du cache termin�.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED', 'Cr�ation du cache ANNUL�.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL', ' (%d billets au total)...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NL2BR', 'D�sactiver nl2br');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE', 'Ne pas afficher sur la page principale');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS', 'Utiliser les restrictions par groupes');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC', 'Si cette option est activ�e, vous pouvez d�finir quels utilisateurs d\'un groupe sont autoris�s � lire des billets. Attention: si activ�e cette option a un grand impact sur la performance sur les listes de billets, ne l\'activez donc que si vous l\'utilisez vraiment.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS', 'Utiliser les restrictions par utilisateur');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC', 'Si activ�e, cette option permet de d�finir quels utilisateurs sp�cifiques sont autoris�s � lire des billets. Attention: si activ�e cette option a un grand impact sur la performance sur les listes de billets, ne l\'activez donc que si vous l\'utilisez vraiment.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS', 'Exclure du fil RSS');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS_DESC', 'Si vous activez cette option, le contenu de ce billet ne sera pas inclu dans le fil RSS de votre blog.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS', 'Variables sur mesure');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1', 'Des variables suppl�mentaires peuvent �tre utilis�s dans les templates de votre th�me pour qu\'ils soient affich�s quand vous le d�sirez. Pour cela, modifiez le fichier template \'entries.tpl\' et placez des balises Smarty comme par ex. {$entry.properties.ep_MonChampsSurMesure} o� vous voulez dans le code HTML. Notez que chaque variable doit commencer par \'ep_\'. ');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2', 'Ici vous pouvez d�finir une liste s�pr�e par des virgules de noms de champs (variables) � remplir pour chaque billet. Attention, le nom de ces variables ne doit pas contenir de caract�res sp�ciaux ni d\'espaces. Exemples: "Variable1, Variable2, ChouetteVariable". ' . PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1);
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3', 'La liste des variables sur mesure peut �tre modifi�e dans la <a href="%s" target="_blank" rel="noopener" title="' . PLUGIN_EVENT_ENTRYPROPERTIES_TITLE . '">configuration du plugin</a>.');

