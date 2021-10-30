<?php

/**
 *  @version  
 *  @file 
 *  @author Sebastian Mordziol <argh@php-tools.net> http://sebastian.mordziol.de
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Protection contre le Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Offre une multitude de possibilit�s pour prot�ger votre blog contre le Spam dans les commentaires. C\'est l\'�pine dorsale des mesures anti-spam. Ne le supprimez pas !');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Protection contre le Spam: message non valide.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Protection contre le Spam: Vous ne pouvez pas ajouter de commentaire suppl�mentaire dans un intervalle si court.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_RBL', 'Protection contre le Spam: L\'adresse IP de l\'ordinateur duquel vous �crivez votre commentaire est list� comme relais ouvert.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_SURBL', 'Protection contre le Spam: Votre commentaire contient une adresse list�e dans SURBL.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Ce blog est en mode "Bloquage d\'urgence des commentaires", merci de r�essayer un peu plus tard.');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Ne pas autoriser les doublons de commentaires');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Ne pas autoriser les utilisateurs d\'ajouter de commentaires qui ont le m�me contenu qu\'un commentaire existant.');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Bloquage d\'urgence des commentaires');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Vous permet de d�sactiver temporairement les commentaires pour tous les billets. Pratique si votre blog est sous attaque de Spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'Intervalle de bloquage IP');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'N\'autoriser une adresse IP de commenter que toutes les n minutes. Pratique pour �viter l\'inondation de commentaires.');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL', 'Refuser les commentaires par liste noire');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL_DESC', 'Si active, cette option permet de refuser les commentaires ventant d\'h�tes list�s dans les RBLs (listes noires). Notez que cela peut avoir un effet sur les utilisateurs derri�re un proxy ou dont le fournisseur internet est sur liste noire.');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL', 'Refuser les commentaires par SURBL');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL_DESC', 'Refuse les commentaires contenant des liens vers des h�tes list�s dans la base de donn�es <a href="http://www.surbl.org">SURBL</a>');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST', 'RBLs � contacter');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST_DESC', 'Bloque les commentaires en se basant sur les listes RBL d�finies ici.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Activer les captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Force les utilisateurs � entrer un texte affich� dans une image g�n�r�e automatiquement pour �viter que des syst�mes automatis�s puissent ajouter des commentaires. Notez cependant que cela peut poser des probl�mes aux personnes malvoyantes. To avoid having to use Captchas at all, try out the extending Serendipity Spamblog Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Pour �viter le spam par des robits automatis�s (spambots), merci d\'entrer les caract�res que vous voyez dans l\'image ci-dessous dans le champ de fomulaire pr�vu � cet effet. Assurez-vous que votre navigateur g�re et accepte les cookies, sinon votre commentaire ne pourra pas �tre enregistr�.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Entrez le texte que vous voyez ici dans le champs!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Entrez le texte de l\'image anti-spam ci-dessus ici: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'Vous n\'avez pas entr� correctement le texte de l\'image anti-spam. Merci de corriger votre code en rev�rifiant avec l\'image.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Les captchas ne sont pas disponibles sur votre serveur. Il faut que la GDLib et les librairies freetype soient compil�es dans votre installation de PHP.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Captchas automatiques apr�s X jours');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Les captchaes peuvent �tre activ�s automatiquement apr�s un nombre d�fini de jours pour chaque billet. Pour toujours activer les captchas, entrez un 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Mod�ration automatique apr�s X jours');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'La mod�ration des commentaires peut �tre activ�e automatiquement apr�s un nombre d�fini de jours apr�s la publication d\'un billet. Pour ne pas utiliser la mod�ration automatique, entrez un 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Mod�ration autmatique apr�s X liens');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'La mod�ration des commentaires peut �tre activ�e automatiquement si le nombre des liens contenus dans un commentaire d�passe un nombre d�fini. Pour ne pas utiliser cette fonction, entrez un 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Refus automatique apr�s X liens');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Un commentaire peut �tre refus� automatiquement si le nombre de liens qu\'il contient d�passe le nombre d�fini. Pour ne pas utiliser cette fonction, entrez un 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', '� cause de certaines conditions, votre commentaire est sujet � mod�ration par l\'auteur du blog avant d\'�tre affich�.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Couleur d\'arri�re-plan du captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Entrez des valeurs RVB: 0,255,255');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Fichier log');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'L\'information sur les commentaires refus�s/mod�r�s peut �tre enregistr�e dans un fichier log, pr�cisez un emplacement pour ce fichier ici si vous voulez utiliser cette fonction.');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Bloquage d\'urgence des commentaires');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Doublon de commentaire');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'Bloquage IP');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_RBL', 'Bloquage RBL');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_SURBL', 'Bloquage SURBL');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Captcha invalide (Entr�: %s, Valide: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Mod�ration automatique apr�s X jours');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Top de liens');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'trop de liens');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Masquer les adresses Email');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Masque les adresses Emqil des utilisateurs qui ont �crit des commentaires');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Les adresses Email ne sont pas affich�es, et sont seulement utilis�es pour la communication.');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Choisissez une m�thode de logage');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Le logage des commentaires refus�s peut se faire dans un fichier texte, ou dans la base de donn�es.');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Fichier (voir l\'option \'Fichier log\')');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Base de donn�es');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Pas de logage');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Gestion des commentaires par interface');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'D�finit comment Serendipity g�re les commentaires faits par l\'interface (r�troliens, commentaires WFW:commentAPI). Si vous s�lectionnez "mod�ration", ce commentaires seront toujours sujets � mod�ration. Avec "refus", ils ne sont pas autoris�s. Avec "aucune", ces commentaires seront g�r�s comme des commentaires traditionnels.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'mod�ration');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'refus');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'aucune');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Filtrage par mots cl�s');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Marque tous les commentaires contenant les mots cl�s d�finis comme Spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Filtrage par mots cl�s pour les liens');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Marque tous les commentaires dont les liens contiennent les mots cl�s d�finis comme Spam. Les expressions r�guli�res sont autoris�es, s�parez les mots cl�s par des points virgule (;).');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Filtrage par nom d\'auteur');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', 'Marque tous les commentaires dont le nom d\'auteur contient les mots cl�s d�finis comme Spam. Les expressions r�guli�res sont autoris�es, s�parez les mots cl�s par des points virgule (;).');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Filtrage par mots cl�s du contenu du billet');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Adresse email invalide');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'V�rifier les adresses email?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Champs de commentaire requis');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Entrez une liste de champs requis pour chaque commentaire. S�parez les noms de champs avec des virgules (,). Les champs disponibles sont: name, email, url, replyTo, comment.');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'Vous n\'avez pas d�fini le champs %s.');
@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Configurer les m�thodes anti-ind�sirables');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Bloquer cet auteur � l\'aide du plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Bloquer cette adresse � l\'aide du plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'D�bloquer cet auteur dans le plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'D�bloquer cette adresse dans le plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Titre du billet �gal commentaire');
#@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Rejeter les commentaires qui ne contiennent que le titre du billet'); // translate again
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'V�rifier les adresses des r�troliens');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'N\'autoriser les r�troliens que si l\'adresse contient un lien vers votre blog');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'Adresse de r�trolien invalide.');

