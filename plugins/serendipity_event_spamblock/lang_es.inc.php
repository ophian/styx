<?php

/**
 *  @version 
 *  @author Rodrigo Lazo Paz <rlazo.paz@gmail.com>
 *  EN-Revision: 690
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Protecci�n anti-Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Una variedad de m�todos para prevenir el spam en los comentarios. Esta es la columna vertebral de las medidas anti-spam. No lo elimine.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Protecci�n anti-Spam: Mensaje inv�lido.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Protecci�n anti-Spam: No puedes colocar un nuevo comentario tan pronto luego de haber ya hecho uno.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Este blog est� en "Modo de Emergencia de Bloqueo de comentarios", por favor regresa m�s tarde');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'No permitir comentarios duplicados');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Evita que los usuarios envien un comentario que contiene el mismo cuerpo que un comentario distinto ya enviado');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Desactivaci�n de emergencia de comentarios');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Inhabilita temporalmente los comentarios de todas las entradas. �til si tu blog est� siendo atacado por spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'Intervaluo de bloqueo IP');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Permite enviar comentarios a un IP cada n minutos. �til para prevenir sobrecarga de comentarios.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Activar Captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Forzar� a los usuarios a introducir una cadena al azar mostrada en una imagen especialmente dise�ada. Esta opci�n imposibilitar� env�os autom�ticos a tu blog. Por favor ten en cuenta que personas con problemas visuales podr�an encontrar dificil leer los captchas. To avoid having to use Captchas at all, try out the extending Serendipity Spamblog Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Para prevenir un ataque spam en los comentarios por parte de bots, por favor ingresa la cadena que ves en la imagen mostrada m�s abajo en la apropiada caja de texto. Tu comentario ser� aceptado s�lo si ambas cadenas son iguales. Por favor, aseg�rate que tu navegador soporta y acepta cookies, o tu comentario no podr� ser verificado correctamente.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', '�Ingresa la cadena que ves m�s abajo en la caja de texto!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Ingresa la cadena de protecci�n contra spam de la imgen mostrada abajo: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'No has ingresado correctamente la cadena de protecci�n contra spam mostrada en la imagen. Por favor, m�rala e ingresa su valor en la caja de texto.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Captchas deshabilitados en tu servidor. Necesitas GDLib y las librer�as freetype compiladas con PHP, adem�s que de los archivos .TTF residan en tu directorio.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Forzar captchas luego de cuantos d�as');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Los captchas pueden ser reforzados dependiendo de la edad de tu art�culo. Ingresa la cantidad de d�as luego de los cuales ser� necesario ingresar el captcha. Si lo defines en 0, siempre ser�n utilizados.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Forzar la moderaci�n en los comentarios luego de cu�ntos d�as');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'Puedes configurar todos los comentarios a las entradas como autom�ticamente moderados. Ingresa la edad de la entrada en d�as, luego de la cual ser� auto-moderada. 0 significa no auto-moderaci�n.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Cu�ntos enlaces antes de que se modere un comentario');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'Cuando un comentario alcanza una cierta cantidad de enlaces, se puede configurar para que sea moderado. 0 significa que no se realizar� esta comprobaci�n de enlaces.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Cu�ntos enlaces antes de que se rechace un comentario');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Cuando un comentario alcance un cierto n�mero de enlaces, ese comentario puede ser rechazado. 0 significa que no se realizar� esta comprobaci�n de enlaces.');
@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Debido a ciertas condiciones, tu comentario ha sido marcado de manera que requiere moderaci�n por parte del due�o de este blog.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Color de fondo del captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Ingresa valores RGB: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Ubicaci�n de la bit�cora(logfile)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Informaci�n sobre comentarios rechazados/moderados puede ser puede ser escrita en una bit�cora. Si lo dejas en blanco no se har� reporte alguno.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Bloqueo de comentarios de emergencia');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Comentario duplicado');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'Bloqueo IP');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Captcha inv�lido (Ingresado: %s, Esperado: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Moderaci�n autom�tica luego de X d�as');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Demasiados hiper-enlaces');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'Demasiados hiper-enlaces');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Ocultar direcciones e-mail de los usuarios que comentan');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'No mostrar� direcci�n e-mail alguna de los usuarios que comenten');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Direcciones e-mail no ser�n mostradas y s�lo ser�n utilizadas para notificaciones a trav�s de esa v�a');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Elige el m�todo de reporte');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Reportes sobre comentarios rechazados pueden ser hechos a trav�s de la base de datos o en un archivo de texto plano');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Archivo (la opci�n "logfile" debajo)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Base de datos');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Sin reporte');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Como manejar los comentarios hechos via APIs');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'Esta opci�n afecta la moderaci�n sobre los comentarios hechos via llamadas API (Referencias, comentarios WFW:commentAPI). Si lo defines como "moderado", todos los comentarios de este tipo siempre necesitar�n ser aprobados primero. Si seleccionas "rechazar", ser�n completamente prohibidos. Si seleccionas "ninguno", los comentarios ser�n tratados como comentarios comunes..');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'moderado');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'rechazar');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'No se permiten comentarios creados por APIs (i.e. referencias)');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Activar filtro por palabras');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Busca comentarios con ciertas palabras y los marca como spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Filtro por palabras para URLs');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Se permiten expresiones regulares, separa las palabras con punto y coma(;).  Escribe las arrobas (@) por \\@.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Filtro por palabras para nombres de autor');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Filtrado por palabras para el cuerpo del comentario');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS', 'Filtrado por palabras para el correo electr�nico');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Direcci�n e-mail inv�lida');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', '�Revisar direcci�n e-mail?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Campos del comentario requeridos');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Ingresa una lista de los campos requeridos que necesitan ser llenados cuando un usuario hace un comentario. Separa m�ltiples campos con una ",". Las palabras claves disponibles son: nombre(name), email, url, replyTo, comentario(comment)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', '�No has especificado el campo %s!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Configura los m�todos Anti-Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Bloquear este autor a trav�s de la extensi�n Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Bloquear esta URL a trav�s de la extensi�n Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL', 'Bloquear este correo electr�nico a trav�s de la extensi�n Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Desbloquear este autor a trav�s de la extensi�n Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Desbloquear estea URL a trav�s de la extensi�n Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL', 'Desbloquear este correo electr�nico a trav�s de la extensi�n Spamblock');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Titulo de la entrada igual al comentario');
#@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Rechaza comentarios que s�lo contengan t�tulo'); // translate again
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'Revisar Trackback URLs');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'S�lo permitir trackbacks, cuando el URL contiene un enlace a tu blog');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'Trackback URL inv�lida.');

