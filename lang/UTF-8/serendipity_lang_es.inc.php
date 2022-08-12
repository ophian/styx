<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details
# Translation (c) by Luis Cervantes <LuisCervantes@ono.com>,
#                    Manuel García <lendulado@gmail.com>,
#                    Rodrigo Lazo <rlazo.paz@gmail.com>,
#                    Melvin D. Nava <mdnava@dotcomo.com>,
# Fixed entities by Leandro Lucarella <luca@llucax.hn.org>
/* vim: set sts=4 ts=4 expandtab : */

@define('LANG_CHARSET', 'UTF-8');
@define('SQL_CHARSET', 'utf8');
@define('DATE_LOCALES', 'es_ES.UTF-8, es_ES.UTF8, spanish, es, es_ES, es-ES');
@define('DATE_FORMAT_ENTRY', '%A, %e de %B del %Y');
@define('DATE_FORMAT_SHORT', '%d.%m.%Y %H:%M');
@define('WYSIWYG_LANG', 'es_ES');
@define('LANG_DIRECTION', 'ltr');

/* rlazo[20061114]: dado que el español tiene varias traducciones para ciertas
 * palabras sería bueno mantener una misma traducción para ciertas
 * palabras comunes (tratando de seguir http://es.tldp.org/ORCA/glosario.html):
 *
 * password -> contraseña
 * file -> fichero¿? (se podría utilizar archivo para referirse a las entradas pasadas)
 */
@define('SERENDIPITY_ADMIN_SUITE', 'Suite de Administración de Serendipity Styx');
@define('HAVE_TO_BE_LOGGED_ON', 'Debes identificarte para ver esta página');
@define('APPEARANCE', 'Apariencia');
@define('MANAGE_STYLES', 'Gestión de estilos');
@define('CONFIGURE_PLUGINS', 'Configurar extensiones');
@define('CONFIGURATION', 'Configuración');
@define('BACK_TO_BLOG', 'Volver al weblog');
@define('LOGOUT', 'Desconectar');
@define('LOGGEDOUT', 'Desconectado');
@define('CREATE_NEW_CAT', 'Crear una categoría nueva');
@define('CREATE', 'Crear');
@define('I_WANT_THUMB', 'Quiero usar la miniatura en mi entrada.');
@define('I_WANT_BIG_IMAGE', 'Quiero usar la imagen más grande en mi entrada.');
@define('I_WANT_NO_LINK', ' Quiero que aparezca como una imagen');
@define('I_WANT_IT_TO_LINK', 'Quiero que aparezca como un enlace a esta URL:');
@define('BACK', 'Atrás');
@define('FORWARD', 'Seguir');
@define('ANONYMOUS', 'Anónimo');
@define('NEW_TRACKBACK_TO', 'Nueva referencia hecha para');
@define('NEW_COMMENT_TO', 'Nuevo comentario para');
@define('RECENT', 'Recientes...');
@define('OLDER', 'Antiguos...');
@define('DONE', 'Hecho');
@define('WELCOME_BACK', 'Bienvenido de nuevo,');
@define('TITLE', 'Título');
@define('DESCRIPTION', 'Descripción');
@define('PLACEMENT', 'Localización');
@define('DELETE', 'Borrar');
@define('SAVE', 'Guardar');
@define('UP', 'ARRIBA');
@define('DOWN', 'ABAJO');
@define('ENTRIES', 'entradas');
@define('NEW_ENTRY', 'Nueva entrada');
@define('EDIT_ENTRIES', 'Editar entradas');
@define('CATEGORIES', 'Categorías');
@define('IMAGESYNC_WARNING', 'ATENCIóN:<br>Puede tardar si hay muchas imágenes que no tienen miniatura. Particularly with migrations of old blogs, further preliminary considerations and knowledge are necessary! Read about it on <a href="https://ophian.github.io/hc/en/media-migration-tasks.html" target="_new">this help page</a>, carefully.');
@define('CREATE_THUMBS', 'Crear las miniaturas');
@define('MANAGE_IMAGES', 'Gestión de imágenes');
@define('NAME', 'Nombre');
@define('EMAIL', 'Correo electrónico');
@define('HOMEPAGE', 'URL personal');
@define('COMMENT', 'Comentario');
@define('REMEMBER_INFO', '¿Recordar la información?');
@define('SUBMIT_COMMENT', 'Enviar comentario');
@define('NO_ENTRIES_TO_PRINT', 'No hay entradas para mostrar');
@define('COMMENTS', 'Comentarios');
@define('ADD_COMMENT', 'Añadir comentario');
@define('NO_COMMENTS', 'No hay comentarios');
@define('POSTED_BY', 'Publicado por');
@define('ON', 'activado');
@define('A_NEW_COMMENT_BLAHBLAH', 'Se ha realizado un nuevo comentario en tu blog "%s", para la entrada titulada "%s".');
@define('A_NEW_TRACKBACK_BLAHBLAH', 'Se ha realizado una nueva referencia a la entrada titulada "%s".');
@define('NO_CATEGORY', 'No existe la categoría');
@define('ENTRY_BODY', 'Texto de la entrada');
@define('EXTENDED_BODY', 'Texto ampliado');
@define('CATEGORY', 'Categoría');
@define('EDIT', 'Editar');
@define('NO_ENTRIES_BLAHBLAH', 'No se han encontrado resultados para la búsqueda %s');
@define('YOUR_SEARCH_RETURNED_BLAHBLAH', 'La búsqueda para %s ha obtenido %s resultados:');
@define('IMAGE', 'Imagen');
@define('ERROR_FILE_NOT_EXISTS', 'Error: ¡El fichero antiguo no existe!');
@define('ERROR_FILE_EXISTS', 'Error: ¡Un fichero con ese nombre ya existe, selecciona otro!');
@define('ERROR_SELECTION', 'Error: Changing both selection in media properties at the same time is not allowed. Go back and try again!');
@define('ERROR_SOMETHING', 'Error: Algo está mal.');
@define('ADDING_IMAGE', 'Añadiendo imagen...');
@define('THUMB_CREATED_DONE', 'Miniatura %s creada.<br>Hecho.');
@define('ERROR_FILE_EXISTS_ALREADY', 'Error: ¡El fichero ya existe en el sistema!');
@define('NOT_AVAILABLE', 'N/A'); // short!
@define('GO', '¡Ir!');
@define('NEWSIZE', 'Nuevo tamaño: ');
@define('RESIZE_BLAHBLAH', 'Cambiar tamaño %s');
@define('ORIGINAL_SIZE', 'Tamaño original: <i>%sx%s</i> pixel');
@define('HERE_YOU_CAN_ENTER_BLAHBLAH', 'Aquí puedes ajustar el nuevo tamaño de la imagen. Si quieres mantener las proporciones sólo introduce uno de los valores y presiona la tecla tabulador (TAB), de esta forma se ajustará automáticamente.<br><b>PLEASE NOTE:</b> This is not a high end image editor resizing tool, finetuned for the need of a specific image.<br>Every scale returns with a more or less increasing loss of image quality compared to the origin input file. And this increases with each further scaling!<br><b>VARIATION:</b> Since we assume you <b>keep</b> the files proportion, a scaled image "format" variation ["image.avif" and/or "image.webp"] change will be applied to the Origin files variation only and <b>NOT</b> to the variation thumbnail, which - by certain image property conditions - would probably blow up its filesize. If you really need an image scale with an <b>other</b> proportion <b>and</b> an additional changed variation thumb dimension size, activate the "<em>..thumb variation</em>" checkbox.');
@define('SCALE_THUMB_VARIATION', 'Force scaled thumb variation changes');
@define('QUICKJUMP_CALENDAR', 'Calendario rápido');
@define('QUICKSEARCH', 'Buscar');
@define('SEARCH_FOR_ENTRY', 'Busca una entrada');
@define('ARCHIVES', 'Archivos');
@define('BROWSE_ARCHIVES', 'Navega los archivos por mes');
@define('TOP_REFERRER', 'Sitios asociados');
@define('SHOWS_TOP_SITES', 'Muestra los sitios que enlazan a tu weblog');
@define('TOP_EXITS', 'Salidas');
@define('SHOWS_TOP_EXIT', 'Muestra los enlaces de salida más frecuentes desde tu weblog');
@define('SYNDICATION', 'Sindicación');
@define('SHOWS_RSS_BLAHBLAH', 'Muestra los enlaces RSS de sindicación');
@define('ADVERTISES_BLAHBLAH', 'Informa del programa en el que se basa tu weblog');
@define('HTML_NUGGET', 'Fragmento de HTML');
@define('HOLDS_A_BLAHBLAH', 'Presenta un fragmento de HTML en la barra lateral');
@define('TITLE_FOR_NUGGET', 'Título para el fragmento de HTML');
@define('THE_NUGGET', '¡Fragmento de HTML!');
@define('SUBSCRIBE_TO_BLOG', 'Suscríbete a este blog');
@define('YOU_CHOSE', 'Elige %s');
@define('IMAGE_ROTATE_LEFT', 'Rotate image 90 degrees counter-clockwise');
@define('IMAGE_ROTATE_RIGHT', 'Rotate image 90 degrees clockwise');
@define('FILE_SIZE', 'File size');// keep short!
@define('IMAGE_SIZE', 'Tamaño de la imagen');
@define('IMAGE_AS_A_LINK', 'Inserción de imagen');
@define('POWERED_BY', 'Basado en');
@define('TRACKBACKS', 'Referencias');
@define('TRACKBACK', 'Referencia');
@define('NO_TRACKBACKS', 'No hay referencias');
@define('TOPICS_OF', 'Temas de');
@define('VIEW_FULL', 'ver completo');
@define('VIEW_TOPICS', 'ver temas');
@define('AT', 'a las');
@define('SET_AS_TEMPLATE', 'Mantenlo como plantilla');
@define('IN', 'en');
@define('EXCERPT', 'Segmento');
@define('TRACKED', 'Tracked');
@define('LINK_TO_ENTRY', 'Enlazar a la entrada');
@define('LINK_TO_REMOTE_ENTRY', 'Enlazar a una entrada remota');
@define('IP_ADDRESS', 'Dirección IP');
@define('USER', 'Usuario');
@define('THUMBNAIL_USING_OWN', 'Usando %s como su miniatura debido al escaso tamaño.');
@define('THUMBNAIL_FAILED_COPY', 'Querías usar %s como tu miniatura, ¡pero ha fallado la copia!');
@define('AUTHOR', 'Autor');
@define('LAST_UPDATED', 'Última actualización');
@define('TRACKBACK_SPECIFIC', 'URI de referencia para esta entrada');
@define('DIRECT_LINK', 'Enlace directo a esta entrada');
@define('COMMENT_ADDED', 'Tu comentario ha sido añadido correctamente.');
@define('COMMENT_ADDED_CLICK', 'Pulsa %saquí para volver%s a los comentarios, y %saquí para cerrar%s esta ventana.');
@define('COMMENT_NOT_ADDED_CLICK', 'Pulsa %saquí para volver%s a los comentarios, y %saquí para cerrar%s esta ventana');
@define('COMMENTS_DISABLE', 'No permitir comentarios a esta entrada');
@define('COMMENTS_ENABLE', 'Permitir comentarios a esta entrada');
@define('COMMENTS_CLOSED', 'El autor no permite añadir comentarios a esta entrada');
@define('EMPTY_COMMENT', 'Tu comentario está vacío, por favor %svuelve%s e inténtalo de nuevo');
@define('ENTRIES_FOR', 'Entradas para %s');
@define('DOCUMENT_NOT_FOUND', 'El documento %s no se encontró.');
@define('USERNAME', 'Nombre de usuario');
@define('PASSWORD', 'Contraseña');
@define('AUTOMATIC_LOGIN', 'Guardar información');
@define('SERENDIPITY_INSTALLATION', 'Instalación de Serendipity');
@define('LEFT', 'Izquierda');
@define('RIGHT', 'Derecha');
@define('HIDDEN', 'Oculto');
@define('REMOVE_TICKED_PLUGINS', 'Quita las extensiones seleccionadas');
@define('SAVE_CHANGES_TO_LAYOUT', 'Guarda los cambios en el diseño');
@define('REQUIRED_FIELD', 'Required field');
@define('COMMENTS_FROM', 'Comentarios para');
@define('ERROR', 'Error');
@define('DELETE_SURE', '¿Estás seguro de que quieres eliminar #%s permanentemente?');
@define('NOT_REALLY', 'No...');
@define('DUMP_IT', 'Sí');
@define('RIP_ENTRY', 'Entrada #%s borrada.');
@define('CATEGORY_DELETED_ARTICLES_MOVED', 'Categoría #%s eliminada. Los artículos antiguos han sido movidos a la categoría #%s');
@define('CATEGORY_DELETED', 'Categoría #%s eliminada.');
@define('INVALID_CATEGORY', 'Ninguna categoría valida para borrar');
@define('CATEGORY_SAVED', 'Categoría guardada');
@define('SELECT_TEMPLATE', 'Selecciona la plantilla que deseas utilizar en tu weblog');
@define('ENTRIES_NOT_SUCCESSFULLY_INSERTED', '¡No se ha podido insertar las entradas!');
@define('YES', 'Sí');
@define('NO', 'No');
@define('USE_DEFAULT', 'Predeterminado');
@define('CHECK_N_SAVE', 'Revisa y guarda');
@define('DIRECTORY_WRITE_ERROR', 'No puedes escribir en el directorio %s. Comprueba los permisos.');
@define('DIRECTORY_CREATE_ERROR', 'El directorio %s no existe y no puede ser creado. Créalo manualmente.');
@define('DIRECTORY_RUN_CMD', ' - ejecuta <i>%s %s</i>');
@define('CANT_EXECUTE_BINARY', 'No se puede ejecutar %s');
@define('CANT_EXECUTE_EXTENSION', 'Cannot execute the %s extension library. Please allow in PHP.ini or load the missing module via servers package manager.');
@define('FILE_WRITE_ERROR', 'No se puede escribir el fichero %s.');
@define('FILE_CREATE_YOURSELF', 'Crea el fichero tu mismo o verifica los permisos');
@define('COPY_CODE_BELOW', '<br>* Tan solo copia el código situado debajo y ponlo %s dentro %s del directorio:<b><pre>%s</pre></b>' . "\n");
@define('WWW_USER', 'Cambia www por el usuario que está ejecutando el servidor web (p. ej. nobody).');
@define('BROWSER_RELOAD', 'Una vez hecho esto, pulsa en tu navegador el botón "Recargar".');
@define('RELOAD_THIS_PAGE', 'Please reload this <a href="%s">%s</a> page to fetch the changed values before submitting again!');
@define('DIAGNOSTIC_ERROR', 'Hemos detectado algunos errores mientras se ejecutaban varias comprobaciones en la información que has introducido:');
@define('SERENDIPITY_NOT_INSTALLED', 'Serendipity no está instalado todavía. Por favor <a href="%s">instálalo</a> ahora.');
@define('INCLUDE_ERROR', 'Error de Serendipity: no se puede incluir %s - saliendo.');
@define('DATABASE_ERROR', 'Error de Serendipity: no es posible conectar a la base de datos - saliendo.');
@define('CREATE_DATABASE', 'Creando la configuración predeterminada de la base de datos...');
@define('ATTEMPT_WRITE_FILE', 'Intentando escribir el fichero %s...');
@define('WRITTEN_N_SAVED', 'Configuración realizada y guardada');
@define('IMAGE_ALIGNMENT', 'Alineación de imágenes');
@define('ENTER_NEW_NAME', 'Introduzca el nuevo nombre para: ');
@define('RESIZING', 'Cambiando el tamaño');
@define('RESIZE_DONE', 'Terminado (las imágenes %s cambiaron de tamaño).');
@define('FILE_NOT_FOUND', 'No es posible encontrar el fichero con nombre <b>%s</b>, ¿es posible que lo haya borrado ya?');
@define('ABORT_NOW', 'Salir ahora');
@define('REMOTE_FILE_NOT_FOUND', 'El fichero no fue localizado en el servidor remoto, ¿estás seguro de que la URL: <b>%s</b> es correcta?');
@define('FILE_FETCHED', '%s enlazado como: %s');
@define('FILE_UPLOADED', 'El fichero %s fue transferido correctamente: %s');
@define('WORD_OR', 'O...');
@define('SCALING_IMAGE', 'Escalando %s a %s x %s px');
@define('FORCE_RELOAD', 'With certain image characteristics it can occasionally happen that the old image is still present in the browser cache. If so, check into the MediaLibrary again and force a hard reload of your browser [Ctrl]+[F5], to actually see the scaled image.');
@define('KEEP_PROPORTIONS', 'Mantener las proporciones');
@define('REALLY_SCALE_IMAGE', '¿Realmente deseas escalar la imagen? ¡No se podrá deshacer esta acción!');
@define('TOGGLE_ALL', 'Mostrar/Ocultar todo');
@define('TOGGLE_OPTION', 'Mostrar/Ocultar');
@define('SUBSCRIBE_TO_THIS_ENTRY', 'Suscribirse a esta entrada');
@define('UNSUBSCRIBE_OK', "%s ya no está suscrito a esta entrada");
@define('NEW_COMMENT_TO_SUBSCRIBED_ENTRY', 'Nuevo comentario en la entrada suscrita "%s"');
@define('SUBSCRIPTION_MAIL', "Hola %s,\n\nHay un nuevo comentario a la entrada \"%s\", titulada \"%s\"\nEl autor es: %s\n\nPuedes encontrar la entrada aquí: %s\n\nPuedes dejar de recibir información sobre esta entrada haciendo click aquí: %s\n");
@define('SUBSCRIPTION_TRACKBACK_MAIL', "Hola %s,\n\nHay una nueva referencia a la entrada \"%s\", titulada \"%s\"\nEl autor es: %s\n\nPuedes encontrar la entrada aquí: %s\n\nPuedes dejar de recibir información sobre esta entrada haciendo click aquí: %s\n");
@define('SIGNATURE', "\n-- \n%s está basado en %s.\nEl mejor programa para blog ;).\nVisita <%s> y compruébalo.");
@define('SYNDICATION_PLUGIN_20', 'RSS 2.0 feed');
@define('SYNDICATION_PLUGIN_20c', 'RSS 2.0 comentarios');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR', 'Campo "managingEditor"');
@define('SYNDICATION_PLUGIN_WEBMASTER', 'Campo "webMaster"');
@define('SYNDICATION_PLUGIN_BANNERURL', 'Imagen para el feed RSS');
@define('SYNDICATION_PLUGIN_BANNERWIDTH', 'Anchura de la imagen');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT', 'Altura de la imagen');
@define('SYNDICATION_PLUGIN_WEBMASTER_DESC', 'Correo electrónico del webmaster, si está disponible. (déjalo vacío para no mostrarlo) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR_DESC', 'Correo electrónico del editor, si está disponible. (déjalo vacío para no mostrarlo) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_BANNERURL_DESC', 'URL de una imagen en formato GIF/JPEG/PNG, si está disponible. (dejándolo vacío se usara el símbolo de Serendipity)');
@define('SYNDICATION_PLUGIN_BANNERWIDTH_DESC', 'en pixels, max. 144');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT_DESC', 'en pixels, max. 400');
@define('SYNDICATION_PLUGIN_TTL', 'Campo "ttl" (time-to-live)');
@define('SYNDICATION_PLUGIN_TTL_DESC', 'Cantidad de minutos, después de los cuales, tu blog no será verificado por ningún site o aplicación externa (si lo dejas vacío no se mostrara) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_PUBDATE', 'Campo "pubDate"');
@define('SYNDICATION_PLUGIN_PUBDATE_DESC', '¿Debería el campo "pubDate" estar incluido en un canal RSS para mostrar la fecha de la última entrada?');
@define('CONTENT', 'Contenido');
@define('TYPE', 'Tipo');
@define('DRAFT', 'Borrador');
@define('PUBLISH', 'Publicar');
@define('PREVIEW', 'Previsualización');
@define('ALL_ENTRIES', 'All entries');
@define('DATE', 'Fecha');
@define('DATE_FORMAT_2', 'Y-m-d H:i'); // Needs to be ISO 8601 compliant for date conversion!
@define('DATE_INVALID', 'Aviso: La fecha especificada no es válida. Debe tener el formato AAAA-MM-DD HH:MM.');
@define('CATEGORY_PLUGIN_DESC', 'Muestra la lista de categorías.');
@define('ALL_AUTHORS', 'Todos los autores');
@define('CATEGORIES_TO_FETCH', 'Categorías enlazadas');
@define('CATEGORIES_TO_FETCH_DESC', '¿De que autor quiere enlazar las categorías?');
@define('PAGE_BROWSE_ENTRIES', 'Página %s de %s, en total %s entradas');
@define('PAGE', 'Page');
@define('PREVIOUS_PAGE', 'página anterior');
@define('NEXT_PAGE', 'página siguiente');
@define('ALL_CATEGORIES', 'Todas las categorías');

/* TRANSLATE */
@define('WRONG_USERNAME_OR_PASSWORD', 'Usuario o contraseña erróneo');
@define('HTACCESS_ERROR', 'Para verificar tu instalación del servidor web, Serendipity deber ser capaz de escribir en el fichero ".htaccess". Esto no fue posible debido a errores de permisos. Por favor, ajusta los permisos como: <br> %s<br>y recarga esta página.');
@define('SIDEBAR_PLUGINS', 'Extensiones de barra lateral');
@define('EVENT_PLUGINS', 'Extensiones de eventos');
@define('SYNCING', 'Sincronizando la base de datos con el directorio de imágenes.');
@define('SYNC_OPTION_LEGEND', 'Thumbnail Synchronization Options');
@define('SYNC_OPTION_KEEPTHUMBS', 'Keep all existing thumbnails');
@define('SYNC_OPTION_SIZECHECKTHUMBS', 'Keep existing thumbnails only if they are the correct size');
@define('SYNC_OPTION_DELETETHUMBS', 'Regenerate all (<em>*.%s</em>) thumbnails');
@define('SYNC_OPTION_CONVERTTHUMBS', 'Convert old existing thumbnail names');
@define('SYNC_OPTION_CONVERTTHUMBS_INFO', 'WARNING: This option is not active, as long the thumbSuffix has not changed.<br>It converts existing thumbnails, which are not named by the current thumbSuffix-scheme: <em>*.%s</em>, in the database, the filesystem and already used in entries to the same suffix naming scheme. This can take long! <b>It does not matter keeping them as is</b>, but to include them for the "Regenerate all" option, you need to do this first.');
@define('SYNC_DONE', 'Hecho (Sincronizadas %s imágenes).');
@define('SORT_ORDER', 'Ordenar por');
@define('SORT_ORDER_NAME', 'Nombre de fichero');
@define('SORT_ORDER_EXTENSION', 'Extensión de fichero');
@define('SORT_ORDER_SIZE', 'Tamaño de fichero');
@define('SORT_ORDER_WIDTH', 'Anchura de imagen');
@define('SORT_ORDER_HEIGHT', 'Altura de imagen');
@define('SORT_ORDER_DATE', 'Fecha de transferencia');
@define('SORT_ORDER_ASC', 'Ascendente');
@define('SORT_ORDER_DESC', 'Descendente');
@define('THUMBNAIL_SIZE', 'Thumb size'); // keep short
@define('THUMBNAIL_SHORT', 'Miniatura');
@define('ORIGINAL_SHORT', 'Origin');
@define('APPLY_MARKUP_TO', 'Aplicar marca a %s');
@define('CALENDAR_BEGINNING_OF_WEEK', 'Inicio de la semana');
@define('SERENDIPITY_NEEDS_UPGRADE', 'Se ha detectado una discrepancia entre tu configuración actual que es la versión %s, y la de Serendipity versión %s, ¡necesitas actualizar! <a href="%s">Haz click aquí</a>');
@define('SERENDIPITY_UPGRADER_WELCOME', 'Hola y bienvenido al agente de actualización de Serendipity.');
@define('SERENDIPITY_UPGRADER_PURPOSE', 'Estoy aquí para ayudarte a actualizar tu instalación %s de Serendipity.');
@define('SERENDIPITY_UPGRADER_WHY', 'Estás viendo este mensaje porque has instalado Serendipity %s, pero no has actualizado la instalación de la base de datos para coincidir con esta versión');
@define('SERENDIPITY_UPGRADER_DATABASE_UPDATES', 'Actualizaciones para la base de datos (%s)');
@define('SERENDIPITY_UPGRADER_FOUND_SQL_FILES', 'He encontrado los siguientes ficheros .sql que se necesitan ejecutar antes de que puedas continuar usando normalmente Serendipity');
@define('SERENDIPITY_UPGRADER_VERSION_SPECIFIC', 'Tareas específicas de la versión');
@define('SERENDIPITY_UPGRADER_NO_VERSION_SPECIFIC', 'No se ha encontrado tareas específicas de la versión');
@define('SERENDIPITY_UPGRADER_PROCEED_QUESTION', '¿Quieres que realice las tareas descritas?');
@define('SERENDIPITY_UPGRADER_PROCEED_ABORT', 'No, las ejecutaré manualmente');
@define('SERENDIPITY_UPGRADER_PROCEED_DOIT', 'Sí, por favor');
@define('SERENDIPITY_UPGRADER_NO_UPGRADES', 'Parece que no necesitas ejecutar ninguna actualización');
@define('SERENDIPITY_UPGRADER_CONSIDER_DONE', 'Considera actualizado Serendipity');
@define('SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED', 'Has ignorado el paso de actualización de Serendipity, asegúrate que tu base de datos está correctamente instalada y que las funciones planeadas son ejecutadas.');
@define('SERENDIPITY_UPGRADER_NOW_UPGRADED', 'Tu instalación de Serendipity se ha actualizado a la versión %s');
@define('SERENDIPITY_UPGRADER_RETURN_HERE', 'Puedes volver a tu blog haciendo click %saquí%s');
@define('MANAGE_USERS', 'Gestión de usuarios');
@define('CREATE_NEW_USER', 'Crear nuevo usuario');
@define('CREATE_NOT_AUTHORIZED', 'No puedes modificar usuarios con el mismo nivel que el tuyo');
@define('CREATE_NOT_AUTHORIZED_USERLEVEL', 'No puedes crear usuarios con un nivel mayor que el tuyo');
@define('CREATED_USER', 'Un nuevo usuario %s se ha creado');
@define('MODIFIED_USER', 'Las propiedades del usuario "%s" se han cambiado');
@define('USER_LEVEL', 'Nivel de usuario');
@define('DELETE_USER', 'Estás a punto de borrar al usuario #%d %s. ¿Estás seguro? Esto no permitirá mostrar las entradas escritas por él.');
@define('DELETED_USER', 'Usuario #%d %s borrado.');
@define('LIMIT_TO_NUMBER', '¿Cuántos elementos deberían mostrarse?');
@define('ENTRIES_PER_PAGE', 'entradas por página');
@define('DIRECTORIES_AVAILABLE', 'En la lista de subdirectorios disponibles puedes hacer click en el nombre de un directorio para crear un nuevo directorio dentro de esa estructura.');
@define('ALL_DIRECTORIES', 'todos los directorios');
@define('MANAGE_DIRECTORIES', 'Gestión de directorios');
@define('DIRECTORY_CREATED', 'Directorio <strong>%s</strong> creado.');
@define('PARENT_DIRECTORY', 'Directorio superior');
@define('CONFIRM_DELETE_DIRECTORY', '¿Estás seguro de que quieres eliminar todos los contenidos del directorio %s?');
@define('ERROR_NO_DIRECTORY', 'Error: Directorio %s no existe');
@define('CHECKING_DIRECTORY', 'Verificar ficheros en directorio %s');
@define('DELETING_FILE', 'Borrando fichero %s...');
@define('ERROR_DIRECTORY_NOT_EMPTY', 'No puedo borrar un directorio con ficheros. Marca "forzar borrado" si quieres eliminar también los ficheros e inténtalo de nuevo. Los ficheros que existen son:');
@define('DIRECTORY_DELETE_FAILED', 'Eliminación del directorio %s fallida. Revisa los permisos o los mensajes anteriores.');
@define('DIRECTORY_DELETE_SUCCESS', 'Directorio %s eliminado exitosamente.');
@define('SKIPPING_FILE_EXTENSION', 'Saltando fichero: falta extensión en %s.');
@define('SKIPPING_FILE_UNREADABLE', 'Saltando fichero: %s no se puede leer.');
@define('FOUND_FILE', 'Encontrado fichero nuevo/modificado: %s.');
@define('ALREADY_SUBCATEGORY', '%s es ya una subcategoría de %s.');
@define('PARENT_CATEGORY', 'Categoría superior');
@define('IN_REPLY_TO', 'En respuesta a');
@define('TOP_LEVEL', 'Nivel superior');
@define('SYNDICATION_PLUGIN_GENERIC_FEED', '%s feed');
@define('PERMISSIONS', 'Permisos');
@define('SECURITY', 'Security');
@define('INTEGRITY', 'Verify Installation Integrity');
@define('CHECKSUMS_NOT_FOUND', 'Unable to compare checksums! (No checksums.inc.php in main directory, or DEV version)');
@define('CHECKSUMS_PASS', 'All required files verified.');
@define('CHECKSUM_FAILED', '%s corrupt or modified: failed verification');

/* DATABASE SETTINGS */
@define('INSTALL_CAT_DB', 'Opciones de la base de datos');
@define('INSTALL_CAT_DB_DESC', 'Introduce aquí la información de tu base de datos. Serendipity la necesita para funcionar');
@define('INSTALL_DBTYPE', 'Tipo');
@define('INSTALL_DBTYPE_DESC', 'El tipo de gestor de la base de datos');
@define('INSTALL_DBHOST', 'Servidor');
@define('INSTALL_DBHOST_DESC', 'El servidor donde está el gestor de la base de datos');
@define('INSTALL_DBUSER', 'Usuario');
@define('INSTALL_DBUSER_DESC', 'El usuario que conecta a la base de datos');
@define('INSTALL_DBPASS', 'Contraseña');
@define('INSTALL_DBPASS_DESC', 'La contraseña para el usuario');
@define('INSTALL_DBNAME', 'Nombre');
@define('INSTALL_DBNAME_DESC', 'El nombre de la base de datos');
@define('INSTALL_DBPREFIX', 'Prefijo');
@define('INSTALL_DBPREFIX_DESC', 'Prefijo para los nombres de las tablas, p. ej. styx_');

/* PATHS */
@define('INSTALL_CAT_PATHS', 'Rutas');
@define('INSTALL_CAT_PATHS_DESC', 'Varias rutas a ficheros y directorios esenciales. ¡No olvides la barra final en los directorios!');
@define('INSTALL_FULLPATH', 'Ruta completa');
@define('INSTALL_FULLPATH_DESC', 'La ruta completa y absoluta a tu instalación de Serendipity');
@define('INSTALL_UPLOADPATH', 'Ruta para los ficheros transferidos');
@define('INSTALL_UPLOADPATH_DESC', 'Todos los ficheros transferidos irán aquí, relativo a  \'Ruta completa\' - normalmente \'uploads/\'');
@define('INSTALL_RELPATH', 'Ruta relativa');
@define('INSTALL_RELPATH_DESC', 'Ruta de Serendipity para tu navegador, normalmente \'/serendipity/\'');
@define('INSTALL_RELTEMPLPATH', 'Ruta relativa para las plantillas');
@define('INSTALL_RELTEMPLPATH_DESC', 'Ruta al directorio que contiene tus plantillas - Relativa a la \'ruta relativa\'');
@define('INSTALL_RELUPLOADPATH', 'Ruta relativa para los ficheros transferidos');
@define('INSTALL_RELUPLOADPATH_DESC', 'Ruta de los ficheros transferidos - Relativa a la \'ruta relativa\'');
@define('INSTALL_URL', 'URL para el blog');
@define('INSTALL_URL_DESC', 'URL base de tu instalación de Serendipity');
@define('INSTALL_INDEXFILE', 'Fichero índice');
@define('INSTALL_INDEXFILE_DESC', 'El nombre del fichero índice de Serendipity');

/* GENERAL SETTINGS */
@define('INSTALL_CAT_SETTINGS', 'Opciones generales');
@define('INSTALL_CAT_SETTINGS_DESC', 'Configura el comportamiento de Serendipity');
@define('INSTALL_USERNAME', 'Usuario administrador');
@define('INSTALL_USERNAME_DESC', 'Nombre de usuario del administrador');
@define('INSTALL_PASSWORD', 'Contraseña de administrador');
@define('INSTALL_PASSWORD_DESC', 'Contraseña del administrador');
@define('INSTALL_EMAIL', 'Correo electrónico');
@define('INSTALL_EMAIL_DESC', 'Correo electrónico del administrador');
@define('INSTALL_SENDMAIL', '¿Enviar correos al administrador?');
@define('INSTALL_SENDMAIL_DESC', '¿Quieres recibir un correo electrónico cuando envíen comentarios a tus entradas?');
@define('INSTALL_SUBSCRIBE', '¿Permitir la subscripción de los usuarios a las entradas?');
@define('INSTALL_SUBSCRIBE_DESC', 'Permite a los usuarios subscribirse a una entrada y de ese modo recibir un correo electrónico cuando se hacen nuevos comentarios a esa entrada');
@define('INSTALL_BLOGNAME', 'Nombre del blog');
@define('INSTALL_BLOGNAME_DESC', 'El título de tu blog');
@define('INSTALL_BLOGDESC', 'Descripción del blog');
@define('INSTALL_BLOGDESC_DESC', 'Descripción de tu blog');
@define('INSTALL_LANG', 'Idioma');
@define('INSTALL_LANG_DESC', 'Selecciona el idioma del blog');

/* APPEARANCE AND OPTIONS */
@define('INSTALL_CAT_DISPLAY', 'Apariencia');
@define('INSTALL_CAT_DISPLAY_DESC', 'Configura el aspecto general de Serendipity');
@define('INSTALL_WYSIWYG', 'Usar editor WYSIWYG');
@define('INSTALL_WYSIWYG_DESC', '¿Quieres usar el editor WYSIWYG?<br>For more comfort and quicker updates it is recommended to install the extended CKEditor Plus event Plugin!');
@define('INSTALL_XHTML11', 'Forzar compatibilidad XHTML 1.1');
@define('INSTALL_XHTML11_DESC', '¿Quieres forzar la compatibilidad XHTML 1.1? (puede causar problemas con navegadores más antiguos que la 4ª generación)');
@define('INSTALL_POPUP', 'Activar el uso de ventanas emergentes');
@define('INSTALL_POPUP_DESC', '¿Quieres que el blog use una ventana emergente para los comentarios, referencias, etc.?');
@define('INSTALL_EMBED', '¿Está Serendipity insertado en otra web?');
@define('INSTALL_EMBED_DESC', 'Si quieres insertar Serendipity dentro de otra página, ajusta a "sí" para eliminar cualquier cabecera y sólo mostrar los contenidos. Puedes hacer uso de la opción indexFile y usar una clase donde pongas las cabeceras normales de tu página web. ¡Lee el fichero README para más información!');
@define('INSTALL_TOP_AS_LINKS', '¿Mostrar Salidas/Sitios asociados como enlaces?');
@define('INSTALL_TOP_AS_LINKS_DESC', '"no": Las Salidas y los Sitios asociados se muestran como texto sin formato para prevenir spam en Google. "yes": Las Salidas y Sitios asociando se muestran como enlaces. "default": Usar el valor de la configuración global (recomendado).');
@define('INSTALL_BLOCKREF', 'Sitios asociados bloqueados');
@define('INSTALL_BLOCKREF_DESC', '¿Hay algún servidor que no quieras que se muestre en la lista de Sitios asociados? Separa la lista de nombres de dominio con \';\' y advierte que se ¡bloquea por coincidencias en subcadenas!');
@define('INSTALL_REWRITE', 'Reescritura de URL');
@define('INSTALL_REWRITE_DESC', 'Selecciona la regla que quieres usar para generar URL. Activando estas reglas hará URL bonitas para tu blog y lo hará más indexable para los robots como Google. El servidor web necesita tener o mod_rewrite o "AllowOverride All" para el directorio de Serendipity. El valor predeterminado es autodetectado');

/* IMAGECONVERSION SETTINGS */
@define('INSTALL_CAT_IMAGECONV', 'Opciones para la conversión de imágenes');
@define('INSTALL_CAT_IMAGECONV_DESC', 'Información sobre cómo debería Serendipity manejar imágenes');
@define('INSTALL_IMAGEMAGICK', 'Usar ImageMagick');
@define('INSTALL_IMAGEMAGICK_DESC', '¿Tienes ImageMagick instalado y quieres usarlo para redimensionar imágenes?');
@define('INSTALL_IMAGEMAGICKPATH', 'Ruta hasta el programa convert');
@define('INSTALL_IMAGEMAGICKPATH_DESC', 'Ruta completa y nombre del programa convert de ImageMagick');
@define('INSTALL_THUMBSUFFIX', 'Sufijo de la miniatura');
@define('INSTALL_THUMBSUFFIX_DESC', 'Las miniaturas se crearán con el siguiente formato: original.[sufijo].ext');
@define('INSTALL_THUMBWIDTH', 'Dimensiones de las miniaturas');
@define('INSTALL_THUMBWIDTH_DESC', 'Anchura máxima estática de las miniaturas auto-generadas');
@define('INSTALL_THUMBDIM', 'Thumbnail constrained dimension');
@define('INSTALL_THUMBDIM_LARGEST', 'Largest');
@define('INSTALL_THUMBDIM_WIDTH', 'Width');
@define('INSTALL_THUMBDIM_HEIGHT', 'Height');
@define('INSTALL_THUMBDIM_DESC', 'Dimension to be constrained to the thumbnail max size. The default "' .
    INSTALL_THUMBDIM_LARGEST .  '" limits both dimensions, so neither can be greater than the max size; "' .
    INSTALL_THUMBDIM_WIDTH . '" and "' .  INSTALL_THUMBDIM_HEIGHT .
    '" only limit the chosen dimension, so the other could be larger than the max size.');

/* PERSONAL DETAILS */
@define('USERCONF_CAT_PERSONAL', 'Datos personales');
@define('USERCONF_CAT_PERSONAL_DESC', 'Cambia los datos personales');
@define('USERCONF_USERNAME', 'Nombre de usuario');
@define('USERCONF_USERNAME_DESC', 'El nombre de usuario que se usa para conectar al blog');
@define('USERCONF_PASSWORD', 'Contraseña');
@define('USERCONF_PASSWORD_DESC', 'La contraseña que se usará para conectar al blog');
@define('USERCONF_EMAIL', 'Correo electrónico');
@define('USERCONF_EMAIL_DESC', 'La dirección de correo electrónico personal');
@define('USERCONF_SENDCOMMENTS', '¿Enviar avisos de comentarios?');
@define('USERCONF_SENDCOMMENTS_DESC', '¿Desea que se le envíen notificaciones mediante correo electrónico cuando se reciban comentarios a las entradas?');
@define('USERCONF_SENDTRACKBACKS', '¿Enviar avisos de referencias?');
@define('USERCONF_SENDTRACKBACKS_DESC', '¿Desea que se le envíen notificaciones mediante correo electrónico cuando se hagan referencias a las entradas?');
@define('USERCONF_ALLOWPUBLISH', 'Derechos: ¿Publicar entradas?');
@define('USERCONF_ALLOWPUBLISH_DESC', '¿El usuario puede publicar entradas?');
@define('USERCONF_DARKMODE', 'Styx Theme Dark Mode');
@define('XML_IMAGE_TO_DISPLAY', 'Botón XML');
@define('XML_IMAGE_TO_DISPLAY_DESC','Enlaces a feeds XML se mostrarán con esta imagen. Déjalo vacío para el valor predeterminado, introduce \'none\' para desactivar.');
@define('ENTRY_SAVED', 'Tu entrada ha sido guardada');
@define('SUCCESS', 'Éxito');

@define('NUMBER_FORMAT_DECIMALS', '2');
@define('NUMBER_FORMAT_DECPOINT', ',');
@define('NUMBER_FORMAT_THOUSANDS', '.');

@define('POWERED_BY_SHOW_TEXT', 'Mostrar "Serendipity" como texto');
@define('POWERED_BY_SHOW_TEXT_DESC', 'Mostrará "Serendipity Weblog" como texto');
@define('POWERED_BY_SHOW_IMAGE', 'Mostrar "Serendipity" con una imagen');
@define('POWERED_BY_SHOW_IMAGE_DESC', 'Mostrará el logotipo de Serendipity');

/* TRANSLATE */
@define('SETTINGS_SAVED_AT', 'La nueva configuración se ha guardado a la hora %s');
@define('PLUGIN_ITEM_DISPLAY', '¿Dónde debería mostrarse el elemento?');
@define('PLUGIN_ITEM_DISPLAY_EXTENDED', 'Sólo en la entrada extendida');
@define('PLUGIN_ITEM_DISPLAY_OVERVIEW', 'Sólo en la entrada general');
@define('PLUGIN_ITEM_DISPLAY_BOTH', 'Siempre');
@define('RSS_IMPORT_CATEGORY', 'Usa esta categoría para las entradas importadas que no coincidan');
@define('ERROR_UNKNOWN_NOUPLOAD', 'Ocurrió un error desconocido, fichero no importado. Quizás el tamaño del fichero es mayor que el tamaño máximo permitido por tu instalación. Verifica con tu ISP o edita tu fichero php.ini para permitir transferir ficheros de tamaño más grande.');
@define('COMMENTS_WILL_BE_MODERATED', 'Los comentarios enviados serán sometidos a moderación antes de ser mostrados.');
@define('YOU_HAVE_THESE_OPTIONS', 'Tienes disponibles las siguientes opciones:');
@define('THIS_COMMENT_NEEDS_REVIEW', 'Aviso: Este comentario necesita aprobación antes de que se muestre.');
@define('DELETE_COMMENT', 'Borrar comentario');
@define('APPROVE_COMMENT', 'Aprobar comentario');
@define('REQUIRES_REVIEW', 'Requiere revisión');
@define('COMMENT_APPROVED', 'El comentario #%s ha sido aprobado exitosamente');
@define('COMMENT_DELETED', 'El comentario #%s ha sido borrado exitosamente');
@define('VIEW', 'Ver');
@define('COMMENT_ALREADY_APPROVED', 'El comentario #%s ya parece haber sido aprobado');
@define('COMMENT_EDITED', 'El comentario seleccionado ha sido editado');
@define('HIDE', 'Ocultar');
@define('VIEW_EXTENDED_ENTRY', 'Continua leyendo "%s"');
@define('TRACKBACK_SPECIFIC_ON_CLICK', 'This link is not active. It contains a copyable trackback URI to manually send ping- & trackbacks to this entry for older Blogs; Eg. (still valid) via the provided entry field of the serendipity_event_trackback plugin. Serendipity and other Blog systems nowadays recognize the trackback URL automatically by the article URL. The trackback URI for your Sender entry link therefore is as follows:');
@define('THIS_TRACKBACK_NEEDS_REVIEW', 'Aviso: Esta referencia necesita aprobación antes de que se muestre');
@define('DELETE_TRACKBACK', 'Borrar referencia');
@define('APPROVE_TRACKBACK', 'Aprobar referencia');
@define('TRACKBACK_APPROVED', 'La referencia #%s ha sido aprobada exitosamente');
@define('TRACKBACK_DELETED', 'La referencia #%s ha sido borrado exitosamente');
@define('COMMENTS_MODERATE', 'Comentarios y referencias a esta entrada requieren moderación');
@define('PLUGIN_SUPERUSER_HTTPS', 'Usar https para conectar');
@define('PLUGIN_SUPERUSER_HTTPS_DESC', 'Hacer que el enlace para conectar apunte a una conexión https. ¡Tu servidor web necesita soporte para esto!');
@define('INSTALL_SHOW_EXTERNAL_LINKS', '¿Poder hacer click en los enlaces externos?');
@define('INSTALL_SHOW_EXTERNAL_LINKS_DESC', '"no": Enlaces externos no verificados (Top Salidas, Top Sitios asociados, Comentarios de usuarios) no se muestran/se muestran como texto sin formato donde se pueda para prevenir spam de Google (recomendado). "sí": Enlaces externos no verificados se muestran como enlaces. ¡Puede ser modificado en la configuración de la extensión de la barra lateral!');
@define('PAGE_BROWSE_COMMENTS', 'Página %s de %s, total %s comentarios');
@define('FILTERS', 'Filtros');
@define('FIND_ENTRIES', 'Encontrar entradas');
@define('FIND_COMMENTS', 'Encontrar comentarios');
@define('FIND_MEDIA', 'Encontrar medios');
@define('FILTER_DIRECTORY', 'Directorio');
@define('SORT_BY', 'Ordenación');
@define('TRACKBACK_COULD_NOT_CONNECT', 'No se ha enviado la referencia: No se pudo establecer conexión con %s en el puerto %d');
@define('MEDIA', 'Medios');
@define('MEDIA_LIBRARY', 'Biblioteca de medios');
@define('ADD_MEDIA_PICTELEMENT', 'Use &lt;picture&gt; element');
@define('ADD_MEDIA', 'Añadir medio');
@define('ENTER_MEDIA_URL', 'Introduce una URL para obtener el fichero:');
@define('ENTER_MEDIA_UPLOAD', 'Selecciona el fichero que quieres transferir:');
@define('SAVE_FILE_AS', 'Guardar el fichero como:');
@define('STORE_IN_DIRECTORY', 'Almacenar dentro del siguiente directorio: ');
@define('MEDIA_RENAME', 'Renombrar este fichero');
@define('IMAGE_RESIZE', 'Redimensionar esta imagen');
@define('MEDIA_DELETE', 'Borrar este fichero');
@define('FILES_PER_PAGE', 'Ficheros por página');
@define('CLICK_FILE_TO_INSERT', 'Haz click en el fichero que quieres insertar:');
@define('SELECT_FILE', 'Selecciona el fichero a insertar');
@define('MEDIA_FULLSIZE', 'Tamaño real');
@define('CALENDAR_BOW_DESC', 'El día de la semana que debe considerarse como principio de semana. El predeterminado es el lunes');
@define('SUPERUSER', 'Administración del Blog');
@define('ALLOWS_YOU_BLAHBLAH', 'Muestra un enlace en la barra lateral para acceder a la administración de tu blog');
@define('CALENDAR', 'Calendario');
@define('SUPERUSER_OPEN_ADMIN', 'Abre administración');
@define('SUPERUSER_OPEN_LOGIN', 'Abre ventana de conexión');
@define('INVERT_SELECTIONS', 'Invertir selecciones');
@define('COMMENTS_DELETE_CONFIRM', '¿Estás seguro que deseas eliminar los comentarios seleccionados?');
@define('COMMENT_DELETE_CONFIRM', '¿Estás seguro que deseas eliminar el comentario #%d, escrito por %s?');
@define('DELETE_SELECTED_COMMENTS', 'Borrar comentarios seleccionados');
@define('VIEW_COMMENT', 'Ver comentario');
@define('VIEW_ENTRY', 'Ver entrada');
@define('DELETE_FILE_FAIL', 'No se pudo borrar el archivo <b>%s</b>');
@define('DELETE_THUMBNAIL', 'Eliminada la imagen miniatura llamada <b>%s</b>');
@define('DELETE_FILE', 'Borrado el fichero llamado <b>%s</b>');
@define('ABOUT_TO_DELETE_FILE', 'Estás a punto de borrar <b>%s</b><br>Si estás usando este fichero en alguna de tus entradas, esto causará enlaces o imágenes rotos<br>¿Estás seguro de que quieres seguir?<br><br>');
@define('TRACKBACK_SENDING', 'Enviando referencia a la URI %s...');
@define('TRACKBACK_SENT', 'Referencia exitosa');
@define('TRACKBACK_FAILED', 'Referencia fallida: %s');
@define('TRACKBACK_NOT_FOUND', 'No encontrada URI de referencia.');
@define('TRACKBACK_URI_MISMATCH', 'La referencia autodetectada no coincide con nuestra URI destino.');
@define('TRACKBACK_CHECKING', 'Comprobando <u>%s</u> para posibles referencias...');
@define('TRACKBACK_NO_DATA', 'Destino no contenía datos');
@define('TRACKBACK_SIZE', 'La URI de destino excedió el tamaño de fichero máximo de %s bytes.');
@define('COMMENTS_VIEWMODE_THREADED', 'Hilos');
@define('COMMENTS_VIEWMODE_LINEAR', 'Plano');
@define('DISPLAY_COMMENTS_AS', 'Mostrar comentarios como');
@define('LOGIN', 'Conectar');
@define('DO_MARKUP', 'Realizar transformaciones marcas');
@define('DO_MARKUP_DESCRIPTION', 'Aplicar transformaciones de marcas al texto (caras, marcas abreviadas como *. /, _, ...). Desactivando esto preservará cualquier código HTML en el texto.');
@define('GENERAL_PLUGIN_DATEFORMAT', 'Formato de fecha');
@define('GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH', 'El formato de fecha de la entrada actual, usa las variables strftime() de PHP. (Predeterminado: "%s")');
@define('ERROR_TEMPLATE_FILE', 'Incapaz de abrir el fichero plantilla, ¡por favor actualiza Serendipity!');
@define('ADVANCED_OPTIONS', 'Opciones avanzadas');
@define('EDIT_ENTRY', 'Editar entrada');
@define('ADD_MEDIA_BLAHBLAH', '<b>Añade un fichero a tu colección de medios:</b><p>Desde aquí puedes transferir un fichero de medios o puedes decirme que los coja de algún ¡lugar de la web! Si no tienes una imagen apropiada, <a href="https://images.google.com" rel="noopener" target="_blank">busca imágenes en Google</a> que vaya con tu forma de pensar, los resultados son útiles y divertidos a veces :)</p><p><b>Selecciona el método:</b></p><br>');
@define('COMMENTS_FILTER_SHOW', 'Mostrar');
@define('COMMENTS_FILTER_ALL', 'Todo');
@define('COMMENTS_FILTER_APPROVED_ONLY', 'Sólo lo aprobado');
@define('COMMENTS_FILTER_HIDDEN_ONLY', 'Only hidden');
@define('COMMENTS_FILTER_APPROVAL_ONLY', 'Only pending');
@define('COMMENTS_FILTER_CONFIRM_ONLY', 'Only confirmable');
@define('COMMENTS_FILTER_NEED_APPROVAL', 'Pendiente de aprobación');
@define('COMMENTS_FILTER_NEED_CONFIRM', 'Pending confirmation');
@define('RSS_IMPORT_BODYONLY', 'Coloca todo el texto importado en el "cuerpo" y no lo separes en la sección "entrada extendida".');
@define('SYNDICATION_PLUGIN_FULLFEED', 'Mostrar los artículos completos con la entrada extendida dentro del feed RSS');
@define('MT_DATA_FILE', 'fichero de datos de Movable Type');
@define('FORCE', 'Forzar');
@define('CREATE_AUTHOR', 'Crear autor \'%s\'.');
@define('CREATE_CATEGORY', 'Crear categoría \'%s\'.');
@define('MYSQL_REQUIRED', 'Debes tener la extensión MySQL para poder llevar a cabo esta acción.');
@define('COULDNT_CONNECT', 'No se puede conectar al gestor de base de datos MySQL: %s.');
@define('COULDNT_SELECT_DB', 'No se puede seleccionar la base de datos: %s.');
@define('COULDNT_SELECT_USER_INFO', 'No se puede seleccionar la información del usuario: %s.');
@define('COULDNT_SELECT_CATEGORY_INFO', 'No se puede seleccionar la información de la categoría: %s.');
@define('COULDNT_SELECT_ENTRY_INFO', 'No se puede seleccionar la información de la entrada: %s.');
@define('COULDNT_SELECT_COMMENT_INFO', 'No se puede seleccionar la información del comentario: %s.');
@define('WEEK', 'Semana');
@define('WEEKS', 'Semanas');
@define('MONTHS', 'Meses');
@define('DAYS', 'Días');
@define('ARCHIVE_FREQUENCY', 'Frecuencia de los elementos del Calendario');
@define('ARCHIVE_FREQUENCY_DESC', 'El intervalo temporal a usar entre cada elemento en la lista');
@define('ARCHIVE_COUNT', 'Número de elementos en la lista');
@define('ARCHIVE_COUNT_DESC', 'El número total de meses, semanas o días que se visualizan');
@define('BELOW_IS_A_LIST_OF_INSTALLED_PLUGINS', 'Abajo está la lista de las extensiones instaladas');
@define('SIDEBAR_PLUGIN', 'extensión de barra lateral');
@define('EVENT_PLUGIN', 'extensión de evento');
@define('CLICK_HERE_TO_INSTALL_PLUGIN', 'Click aquí para instalar una nueva %s');
@define('VERSION', 'Versión');
@define('INSTALL', 'Instalar');
@define('ALREADY_INSTALLED', 'Ya está instalado');
@define('SELECT_A_PLUGIN_TO_ADD', 'Selecciona la extensión que quieras instalar');
@define('INSTALL_OFFSET', 'Diferencia horaria del servidor');
@define('INSTALL_OFFSET_ON_SERVER_TIME', 'Base offset on server timezone?');
@define('INSTALL_OFFSET_ON_SERVER_TIME_DESC', 'Offset entry times on server timezone or not. Select yes to base offset on server timezone and no to offset on GMT.');
@define('STICKY_POSTINGS', 'Entradas permanentes');
@define('INSTALL_FETCHLIMIT', 'Entradas a mostrar en la página principal');
@define('INSTALL_FETCHLIMIT_DESC', 'Número de entradas a mostrar en la página principal');
@define('IMPORT_ENTRIES', 'Importar entradas');
@define('EXPORT_ENTRIES', 'Exportar entradas');
@define('IMPORT_WELCOME', 'Bienvenido a la utilidad de importación de Serendipity');
@define('IMPORT_WHAT_CAN', 'Aquí puedes importar entradas producidas en otros programas de weblog');
@define('IMPORT_SELECT', 'Por favor selecciona el software desde el que quieres importar');
@define('IMPORT_PLEASE_ENTER', 'Por favor introduce los datos como se requiere debajo');
@define('IMPORT_NOW', '¡Importar ahora!');
@define('IMPORT_STARTING', 'Iniciando procedimiento de importación...');
@define('IMPORT_FAILED', 'Importación fallida');
@define('IMPORT_DONE', 'Importación completada con éxito');
@define('IMPORT_WEBLOG_APP', 'Aplicación weblog');
@define('EXPORT_FEED', 'Exportar la sindicación RSS completa');
@define('IMPORT_STATUS', 'Estado después de importar');
@define('IMPORT_GENERIC_RSS', 'Importar RSS genérico');
@define('ACTIVATE_AUTODISCOVERY', 'Enviar referencias a los enlaces encontrados en la entrada');
@define('WELCOME_TO_ADMIN', 'Bienvenido a la Suite de Administración de Serendipity Styx.');
@define('PLEASE_ENTER_CREDENTIALS', 'Por favor introduce tus credenciales abajo.');
@define('ADMIN_FOOTER_POWERED_BY', 'Basado en Serendipity %s y PHP %s');
@define('INSTALL_USEGZIP', 'Usar páginas comprimidas con gzip');
@define('INSTALL_USEGZIP_DESC', 'Para acelerar el envío de páginas, se pueden comprimir las páginas que se envían al visitante, si su navegador lo admite. Esto es lo recomendado');
@define('INSTALL_SHOWFUTURE', 'Mostrar entradas futuras');
@define('INSTALL_SHOWFUTURE_DESC', 'Si se activa, mostrará todas las entradas con fecha futura en tu blog. La acción predeterminada es no mostrar esas entradas y sólo hacerlo cuando llegue la fecha de publicación.');
@define('INSTALL_DBPERSISTENT', 'Usar conexiones persistentes');
@define('INSTALL_DBPERSISTENT_DESC', 'Activar el uso de conexiones persistentes a la base de datos, lee más en <a href="https://php.net/manual/features.persistent-connections.php" rel="noopener" target="_blank">here</a>. Normalmente no se recomienda');
@define('NO_IMAGES_FOUND', 'No se encontraron imágenes');
@define('PERSONAL_SETTINGS', 'Configuración personal');
@define('REFERER', 'Referrer');
@define('NOT_FOUND', 'No encontrado');
@define('NOT_WRITABLE', 'No se puede escribir');
@define('WRITABLE', 'Se puede escribir');
@define('PROBLEM_DIAGNOSTIC', 'Debido a los problemas encontrados, no puedes continuar con la instalación sin antes solucionar los errores señalados');
@define('SELECT_INSTALLATION_TYPE', 'Selecciona qué tipo de instalación quieres usar');
@define('WELCOME_TO_INSTALLATION', 'Bienvenido a la Instalación de Serendipity Styx');
@define('FIRST_WE_TAKE_A_LOOK', 'Primero determinaremos tu configuración actual e intentaremos diagnosticar cualquier problema de compatibilidad');
@define('ERRORS_ARE_DISPLAYED_IN', 'Los errores se muestran en %s, las recomendaciones en %s y lo bien configurado en %s');
@define('RED', 'rojo');
@define('YELLOW', 'amarillo');
@define('GREEN', 'verde');
@define('PRE_INSTALLATION_REPORT', 'Informe de preinstalación de Serendipity v%s');
@define('RECOMMENDED', 'Recomendado');
@define('ACTUAL', 'Actual');
@define('PHPINI_CONFIGURATION', 'Configuración de php.ini');
@define('PHP_INSTALLATION', 'Instalación de PHP');
@define('THEY_DO', 'existen');
@define('THEY_DONT', 'no existen');
@define('SIMPLE_INSTALLATION', 'Instalación simple');
@define('EXPERT_INSTALLATION', 'Instalación avanzada');
@define('COMPLETE_INSTALLATION', 'Completar instalación');
@define('WONT_INSTALL_DB_AGAIN', 'no se instalará de nuevo la base de datos');
@define('CHECK_DATABASE_EXISTS', 'Comprobando si la base de datos y las tablas ya existen');
@define('CREATING_PRIMARY_AUTHOR', 'Creando el autor principal \'%s\'');
@define('SETTING_DEFAULT_TEMPLATE', 'Configurando la plantilla predeterminada');
@define('INSTALLING_DEFAULT_PLUGINS', 'Instalando extensiones predeterminadas');
@define('SERENDIPITY_INSTALLED', 'Serendipity Styx se ha instalado exitosamente');
@define('VISIT_BLOG_HERE', 'Visita tu nuevo blog aquí');
@define('THANK_YOU_FOR_CHOOSING', 'Gracias por elegir Serendipity Styx');
@define('ERROR_DETECTED_IN_INSTALL', 'Se detectó un error en la instalación');
@define('OPERATING_SYSTEM', 'Sistema operativo');
@define('WEBSERVER_SAPI', 'Webserver SAPI');
@define('TEMPLATE_SET', '\'%s\' ha sido configurada como tu plantilla activa');
@define('SEARCH_ERROR', 'La función de búsqueda no funcionó como se esperaba. Aviso para el administrador de este blog: esto ocurre porque faltan claves de índice en tu base de datos. En sistemas MySQL tu cuenta de usuario en la base de datos necesita tener privilegios para ejecutar esta consulta: <pre>CREATE FULLTEXT INDEX entry_idx on %sentries (title,body,extended)</pre> El error específico devuelto por la base de datos fue: <pre>%s</pre>');
@define('EDIT_THIS_CAT', 'Editando "%s"');
@define('CATEGORY_REMAINING', 'Borra esta categoría y mueve sus entradas a esta categoría');
@define('CATEGORY_INDEX', 'Abajo se muestra una lista de las categorías disponibles para tus entradas');
@define('NO_CATEGORIES', 'No hay categorías');
@define('RESET_DATE', 'Volver a poner la fecha');
@define('RESET_DATE_DESC', 'Click aquí para poner la fecha actual');
@define('PROBLEM_PERMISSIONS_HOWTO', 'Los permisos se pueden modificar ejecutando: `<em>%s</em>` sobre el directorio que ha fallado, lo puedes hacer desde la línea de comandos o usando un programa FTP');
@define('WARNING_TEMPLATE_DEPRECATED', 'Aviso: Tu plantilla actual está usando un método obsoleto de plantillas, actualízala si es posible');
@define('ENTRY_PUBLISHED_FUTURE', 'Esta entrada no se ha publicado todavía.');
@define('ENTRIES_BY', 'Entradas por %s');
@define('PREVIOUS', 'Anterior');
@define('NEXT', 'Siguiente');
@define('APPROVE', 'Aprobar');
@define('CATEGORY_ALREADY_EXIST', 'Una categoría con el nombre "%s" ya existe');
@define('IMPORT_NOTES', 'Nota:');
@define('ERROR_FILE_FORBIDDEN', 'No te está permitido transferir ficheros con contenido activo');
@define('ADMIN', 'Administración');
@define('ADMIN_FRONTPAGE', 'Página principal');
@define('QUOTE', 'Cita');
@define('IFRAME_SAVE', 'Serendipity está guardando tu entrada, creando referencias y llevando a cabo las posibles llamadas XML-RPC. Esto puede durar un tiempo..');
@define('IFRAME_SAVE_DRAFT', 'Se ha guardado un borrador de esta entrada');
@define('IFRAME_PREVIEW', 'Serendipity está creando la vista previa de tu entrada...');
@define('IFRAME_WARNING', 'Tu navegador no admite el concepto de "iframes". Por favor, edita tu fichero serendipity_config.inc.php y ajusta la variable $serendipity[\'use_iframe\'] a FALSE.');
@define('NONE', 'ninguno');
@define('USERCONF_CAT_DEFAULT_NEW_ENTRY', 'Configuración predeterminada para las nuevas entradas');
@define('UPGRADE', 'Actualizar');
@define('UPGRADE_TO_VERSION', '<b>Actualizar a la versión:</b> %s');
@define('DELETE_DIRECTORY', 'Borrar directorio');
@define('DELETE_DIRECTORY_DESC', 'Estás a punto de borrar los contenidos de un directorio que contiene ficheros de medios, posiblemente ficheros utilizados en algunas de tus entradas.');
@define('FORCE_DELETE', 'Borrar TODOS los ficheros de este directorio, incluyendo aquellos desconocidos para Serendipity');
@define('CREATE_DIRECTORY', 'Crear directorio');
@define('CREATE_NEW_DIRECTORY', 'Crear nuevo directorio');
@define('CREATE_DIRECTORY_DESC', 'Aquí puedes crear un nuevo directorio para almacenar ficheros de medios. Escoge el nombre del nuevo directorio y selecciona un directorio superior (opcional) donde ponerlo.');
@define('BASE_DIRECTORY', 'Directorio base');
@define('USERLEVEL_EDITOR_DESC', 'Editor estándar');
@define('USERLEVEL_CHIEF_DESC', 'Editor jefe');
@define('USERLEVEL_ADMIN_DESC', 'Administrador');
@define('USERCONF_USERLEVEL', 'Nivel de acceso');
@define('USERCONF_USERLEVEL_DESC', 'Este nivel se usa para determinar que clase de acceso al blog tiene el usuario. Los privilegios de los usuarios se gestionan mediante la pertenencia a grupos.');
@define('USER_SELF_INFO', 'Conectado como %s (%s)');
@define('USER_ALERT', 'Userinfo');
@define('USER_PERMISSION_NOTIFIER_DRAFT_MODE', 'You have not yet been granted the right to publish your entries directly. Until sufficient trust is built, inform your assigned editor-in-chief that your entry is ready for publication and approval.');
@define('USER_PERMISSION_NOTIFIER_RESET', 'In case of temporary revocation of rights, please clarify the reasons in a friendly personal conversation.');
@define('ADMIN_ENTRIES', 'Entradas');
@define('RECHECK_INSTALLATION', 'Volver a comprobar la instalación');
@define('IMAGICK_EXEC_ERROR', 'Incapaz de ejecutar: "%s", error: %s, variable devuelta: %d');
@define('INSTALL_OFFSET_DESC', 'Introduce la diferencia de horas entre la fecha de tu servidor web (actual: %clock%) y la zona horaria deseada');
@define('UNMET_REQUIREMENTS', 'Requisitos no alcanzados: %s');
@define('CHARSET', 'Juego de caracteres');
@define('AUTOLANG', 'Usar el lenguaje del navegador del visitante como predeterminado');
@define('AUTOLANG_DESC', 'Si está activado, se usará la configuración de lenguaje del navegador del visitante para determinar el lenguaje predeterminado de tu entrada así como de la interfaz.');
@define('INSTALL_AUTODETECT_URL', 'Autodetectar HTTP-Host usado');
@define('INSTALL_AUTODETECT_URL_DESC', 'Si se configura como "Sí", Serendipity asegurará que el nombre de Host HTTP que usó por tu visitante para acceder al blog se usa como la URL base. Activando esto te permitirá usar varios nombres de dominio para tu blog, y usar ese dominio para todos los enlaces que siga el usuario.');
@define('CONVERT_HTMLENTITIES', '¿Intentar autoconvertir las entidades HTML?');
@define('EMPTY_SETTING', '¡No especificaste un valor válido para "%s"!');
@define('USERCONF_REALNAME', 'Nombre real');
@define('USERCONF_REALNAME_DESC', 'El nombre completo del autor. Este es el nombre que verán los lectores.');
@define('HOTLINK_DONE', 'Fichero "%s" como recurso externo enlazado.<br>Internal name: \'%s\'. Hecho.');
@define('ENTER_MEDIA_URL_METHOD', 'Método de obtención:');
@define('ADD_MEDIA_BLAHBLAH_NOTE', 'Nota: Enlazar recursos externos te permite usar imágenes externas sin almacenarlas localmente. Si escoges esta opción, asegúrate de poseer los permisos para hacerlo en el servidor externo o bien el servidor es tuyo.');
@define('MEDIA_HOTLINKED', 'enlazado externamente');
@define('FETCH_METHOD_IMAGE', 'Descargar imagen a tu servidor');
@define('FETCH_METHOD_HOTLINK', 'Enlazar externamente al servidor');
@define('DELETE_HOTLINK_FILE', 'Borrado el recurso enlazado externamente con el nombre <b>%s</b>');
@define('SYNDICATION_PLUGIN_SHOW_MAIL', '¿Mostrar la dirección de correo?');
@define('IMAGE_MORE_INPUT', 'Añadir más imágenes');
@define('BACKEND_TITLE', 'Información adicional en la pantalla de configuración de extensiones');
//mine
@define('BACKEND_TITLE_FOR_NUGGET', 'Aquí puedes definir una cadena personalizada que se mostrará en la ventana de configuración de extensiones junto con la descripción de la extensión. Si tienes varios stacked plugins / fragmentos HTML sin título, esto te ayudará a diferenciarlos.');
@define('CATEGORIES_ALLOW_SELECT', '¿Permitir a los visitantes mostrar múltiples categorías a la vez?');
@define('CATEGORIES_ALLOW_SELECT_DESC', 'Si activas esta opción aparecerá un checkbox a lado de cada categoría en la extensión de la barra lateral. Los usuarios podrán marcar las categorías que deseen y luego ver las entradas pertenecientes a estas.');
@define('PAGE_BROWSE_PLUGINS', 'Página %s de %s, totalizando %s extensiones.');
@define('INSTALL_CAT_PERMALINKS', 'Enlaces permanentes');
@define('INSTALL_CAT_PERMALINKS_DESC', 'Define varios patrones URL para definir enlaces permanentes en tu blog. Es recomendado que utilices los valores por defecto; sino, debes tratar de utilizar el valor %id% donde sea posible para evitar que Serendipity busque en la base de datos la URL.');
@define('INSTALL_PERMALINK', 'Estructura URL de los enlaces permanentes');
@define('INSTALL_PERMALINK_DESC', 'Aquí puedes definir la estructura URL relativa desde la URL base hasta donde las entradas sean accesibles. Puedes utilizar las variables %id%, %title%, %day%, %month%, %year%  y cualquier otro caracter.');
@define('INSTALL_PERMALINK_AUTHOR', 'Estructura URL de los enlaces permanentes al autor');
@define('INSTALL_PERMALINK_AUTHOR_DESC', 'Aquí puedes definir la estructura URL relativa desde la URL base hasta donde las entradas pertenecientes a cierto autor sean accesibles. Puedes utilizar las variables %id%, %realname%, %username%, %email% y cualquier otro caracter.');
@define('INSTALL_PERMALINK_CATEGORY', 'Estructura URL de los enlaces permanentes a categorías');
@define('INSTALL_PERMALINK_CATEGORY_DESC', 'Aquí puedes definir la estructura URL relativa desde la URL base hasta donde las entradas pertenecientes a cierta categoría sean accesibles. Puedes utilizar las variables %id%, %name%, %parentname%, %description% y cualquier otro caracter.');
@define('INSTALL_PERMALINK_FEEDCATEGORY', 'Estructura URL de los enlaces permanentes a la sindicación RSS de las categorías');
@define('INSTALL_PERMALINK_FEEDCATEGORY_DESC', 'Aquí puedes definir la estructura URL relativa desde la URL base hasta donde las sindicaciones RSS de ciertas categorías sean accesibles. Puedes utilizar las variables %id%, %name%, %description% y cualquier otro caracter.');
@define('INSTALL_PERMALINK_ID_WARNING', 'If you remove the essential %id% variable, Serendipity cannot create an exact relationship. This has effects on various accesses and subsequent processes and is not recommended without your own responsibility!');
@define('INSTALL_PERMALINK_ARCHIVESPATH', 'Ruta a los archivos');
@define('INSTALL_PERMALINK_ARCHIVEPATH', 'Ruta para archivar');
@define('INSTALL_PERMALINK_CATEGORIESPATH', 'Ruta a las categorías');
@define('INSTALL_PERMALINK_UNSUBSCRIBEPATH', 'Ruta para desinscribirse de los comentarios'); //check later
@define('INSTALL_PERMALINK_DELETEPATH', 'Ruta para eliminar comentarios');
@define('INSTALL_PERMALINK_APPROVEPATH', 'Ruta para aprobar comentarios');
@define('INSTALL_PERMALINK_FEEDSPATH', 'Ruta a las sindicaciones RSS');
@define('INSTALL_PERMALINK_PLUGINPATH', 'Ruta a extensión');
@define('INSTALL_PERMALINK_ADMINPATH', 'Ruta a admin');
@define('INSTALL_PERMALINK_SEARCHPATH', 'Ruta para buscar');
@define('INSTALL_CAL', 'Tipo de calendario');
@define('INSTALL_CAL_DESC', 'Elige tu formato de calendario');
@define('REPLY', 'Responder');
@define('USERCONF_GROUPS', 'Membresías');
@define('USERCONF_GROUPS_DESC', 'Este usuario es miembro de los siguientes grupos. Múltiples membresías son posibles.');
@define('GROUPCONF_GROUPS', 'Selectable members of this group');
@define('MANAGE_GROUPS', 'Administración de grupos');
@define('DELETED_GROUP', 'Grupo #%d \'%s\' eliminado.');
@define('CREATED_GROUP', 'Un nuevo grupo #%d \'%s\' ha sido creado');
@define('MODIFIED_GROUP', 'Las propiedades del grupo \'%s\' han sido modificado');
@define('GROUP', 'Grupo');
@define('CREATE_NEW_GROUP', 'Crear nuevo grupo');
@define('DELETE_GROUP', 'Estás apunto de eliminar el grupo #%d \'%s\'. ¿Estás seguro?');
@define('SYNDICATION_PLUGIN_FEEDBURNERID', 'ID FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_DESC', 'El ID del feed que deseas publicar');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG', 'Imagen FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG_DESC', 'Nombre de la imagen a mostrar (déjalo en blanco para un contador), localizada en feedburner.com, ej: fbapix.gif');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE', 'Título FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE_DESC', 'Título (si hay) a mostrar al lado de la imagen');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT', 'Texto de la imagen FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT_DESC', 'Texto (si hay) a mostrar al colocar el puntero del ratón sobre la imagen');
@define('SEARCH_TOO_SHORT', 'El patrón de búsqueda debe ser mayor a 3 caracteres. Puedes añadirle * a palabras más cortas, como: s9y* para poder hacer búsquedas más cortas.');
@define('INSTALL_DBPORT', 'Puerto de la base de datos');
@define('INSTALL_DBPORT_DESC', 'El puerto utilizado para conectarse con el servidor de tu base de datos');
@define('PLUGIN_GROUP_FRONTEND_EXTERNAL_SERVICES', 'Interfaz: Servicios externos');
@define('PLUGIN_GROUP_FRONTEND_FEATURES', 'Interfaz: Características');

@define('PLUGIN_GROUP_FRONTEND_FULL_MODS', 'Interfaz: Full Mods');//Translate

@define('PLUGIN_GROUP_FRONTEND_VIEWS', 'Interfaz: Vistas');
@define('PLUGIN_GROUP_FRONTEND_ENTRY_RELATED', 'Interfaz: Relacionado a la entrada'); //Frontend: Entry Related
@define('PLUGIN_GROUP_BACKEND_EDITOR', 'Motor: Editor');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_USERMANAGEMENT', 'Motor: Manejo de usuarios');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_METAINFORMATION', 'Motor: Meta información');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_TEMPLATES', 'Motor: Plantillas');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_FEATURES', 'Motor: Características');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_MAINTAIN', 'Motor: Maintenance');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_DASHBOARD', 'Motor: Dashboard');//Backend, better translation?
@define('PLUGIN_GROUP_BACKEND_ADMIN', ADMIN); // is constant, no quotes, no translate!
@define('PLUGIN_GROUP_IMAGES', 'Imágenes');
@define('PLUGIN_GROUP_ANTISPAM', 'Antispam');

@define('PLUGIN_GROUP_MARKUP', 'Markup');//Translate

@define('PLUGIN_GROUP_STATISTICS', 'Estadísticas');

 // GROUP PERMISSIONS   no translate first part until ':', since config variable!
@define('PERMISSION_PERSONALCONFIGURATION', 'personalConfiguration: Configuración de acceso personal');
@define('PERMISSION_PERSONALCONFIGURATIONUSERLEVEL', 'personalConfigurationUserlevel: Modificar los niveles de usuario');
@define('PERMISSION_PERSONALCONFIGURATIONNOCREATE', 'personalConfigurationNoCreate: Modificar "prohibir crear entradas"');
@define('PERMISSION_PERSONALCONFIGURATIONRIGHTPUBLISH', 'personalConfigurationRightPublish: Modificar los permisos de publicar entradas');
@define('PERMISSION_SITECONFIGURATION', 'siteConfiguration: Configuración del sistema de acceso');
@define('PERMISSION_SITEAUTOUPGRADES', 'siteAutoUpgrades: Access system autoupgrades');
@define('PERMISSION_BLOGCONFIGURATION', 'blogConfiguration: Configuración de acceso centralizado al blog');
@define('PERMISSION_ADMINENTRIES', 'adminEntries: Administrar entradas');
@define('PERMISSION_ADMINENTRIESMAINTAINOTHERS', 'adminEntriesMaintainOthers: Administrar entradas de otros usuarios');
@define('PERMISSION_ADMINIMPORT', 'adminImport: Importar entradas');
@define('PERMISSION_ADMINCATEGORIES', 'adminCategories: Administrar categorías');
@define('PERMISSION_ADMINCATEGORIESMAINTAINOTHERS', 'adminCategoriesMaintainOthers: Administrar categorías de otros usuarios');
@define('PERMISSION_ADMINCATEGORIESDELETE', 'adminCategoriesDelete: Eliminar categorías');
@define('PERMISSION_ADMINUSERS', 'adminUsers: Administrar usuarios');
@define('PERMISSION_ADMINUSERSDELETE', 'adminUsersDelete: Eliminar usuarios');
@define('PERMISSION_ADMINUSERSEDITUSERLEVEL', 'adminUsersEditUserlevel: Modificar nivel de usuario');
@define('PERMISSION_ADMINUSERSMAINTAINSAME', 'adminUsersMaintainSame: Administrar otros usuarios que pertenecen a tu mismo grupo(s)');
@define('PERMISSION_ADMINUSERSMAINTAINOTHERS', 'adminUsersMaintainOthers: Administrar otros usuarios que no pertenece a tu mismo grupo(s)');
@define('PERMISSION_ADMINUSERSCREATENEW', 'adminUsersCreateNew: Crear nuevos usuarios');
@define('PERMISSION_ADMINUSERSGROUPS', 'adminUsersGroups: Administrar grupo de usuarios');
@define('PERMISSION_ADMINPLUGINS', 'adminPlugins: Administrar extensiones');
@define('PERMISSION_ADMINPLUGINSMAINTAINOTHERS', 'adminPluginsMaintainOthers: Administrar extensiones de otros usuarios');
@define('PERMISSION_ADMINIMAGES', 'adminImages: Administrar medios');
@define('PERMISSION_ADMINIMAGESDIRECTORIES', 'adminImagesDirectories: Administrar directorios de medios');
@define('PERMISSION_ADMINIMAGESADD', 'adminImagesAdd: Añadir nuevo fichero de medios');
@define('PERMISSION_ADMINIMAGESDELETE', 'adminImagesDelete: Eliminar fichero de medios');
@define('PERMISSION_ADMINIMAGESMAINTAINOTHERS', 'adminImagesMaintainOthers: Administrar ficheros de medios de otros usuarios');
@define('PERMISSION_ADMINIMAGESVIEW', 'adminImagesView: Ver ficheros de medios');
@define('PERMISSION_ADMINIMAGESSYNC', 'adminImagesSync: Sincronizar miniaturas');
@define('PERMISSION_ADMINIMAGESVIEWOTHERS', 'adminImagesViewOthers: Ver ficheros de medios de otros usuario');
@define('PERMISSION_ADMINCOMMENTS', 'adminComments: Administrar comentarios');
@define('PERMISSION_ADMINTEMPLATES', 'adminTemplates: Administrar plantillas');

@define('GROUP_ADMIN_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights, might implement security risks. There are at least 3 permission flags [<em>adminPluginsMaintainOthers</em>, <em>adminUsersMaintainOthers</em> and <em>siteConfiguration</em>] which should stick to the ADMINISTRATOR <b>only</b>! Otherwise, vital conditions of your blog are endangered. Compare and understand what are the main differences between you, the ADMIN, and between "Editors in CHIEF" and normal "USERs". The [<em>siteAutoUpgrades</em>] permission flag is for a special cased and assigned CHIEF only. Read in the ChangeLog, the Styx Sites Help Center or the german Book on how to use it!');
@define('GROUP_CHIEF_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights to normal USERs, might implement security risks. You should deeply check which permission flag should be allowed/removed, compared to a standard USER! Otherwise, vital conditions of certain areas are endangered. Compare and understand what are the main differences between you, the "Editor in CHIEF" and normal "USERs". Read in the Styx Sites Help Center or the german Book for more information!');

@define('INSTALL_BLOG_EMAIL', 'Correo electrónico del Blog');
@define('INSTALL_BLOG_EMAIL_DESC', 'Aquí se configura la dirección de correo electrónico que se utiliza como "De:" en los correos salientes. Asegúrate de definir esta variable con una dirección que sea reconocida por el servidor de correo de tu hosts - muchos servidores de correo rechazan los mensajes que tienen una dirección "De:" desconocida.');
@define('CATEGORIES_PARENT_BASE', 'Sólo mostrar categorías bajo...');
@define('CATEGORIES_PARENT_BASE_DESC', 'Puedes elegir una categoría padre de manera que sólo las categorías hijos sean mostradas.');
@define('CATEGORIES_HIDE_PARALLEL', 'Ocultar las categorías que no son parte del árbol de categorías');
@define('CATEGORIES_HIDE_PARALLEL_DESC', 'Si deseas ocultar categorías que son parte de otro árbol de directorios, necesitas activar esta opción. This feature made most sense in the past, when used in conjunction with a "multi-Blog" like system using the "Properties/Templates of categories" plugin. However, this is no longer the case, since this plugin in its version greater than/equal to v.1.50 can calculate hidden categories independently and better. So you should only use this option if you have a specific use case outside of said categorytemplates plugin.');
@define('CHARSET_NATIVE', 'Nativo');
@define('INSTALL_CHARSET', 'Selección del juego de caracteres');
@define('INSTALL_CHARSET_DESC', 'Aquí puedes activar la codificación de caracteres UTF-8 o nativo (ISO, EUC, ...). Algunos lenguajes sólo tienen traducciones UTF-8 así que colocar la codificación en "nativo" no tendrá efectos. Se sugiere UTF-8 en nuevas instalaciones. No alteres la configuración si ya has hecho entradas con caracteres especiales ya que podría conllevar una corrupción de caracteres. Asegúrate de leer más sobre este problema en  https://ophian.github.io/hc/en/i18n.html.');
@define('CALENDAR_ENABLE_EXTERNAL_EVENTS', 'Habilitar API de conexiones de las extensiones');
@define('CALENDAR_EXTEVENT_DESC', 'Si está habilitada, esta opción permite que las extensiones se conecten con el calendario para mostrar sus eventos resaltados. Utilízalo sólo si has instalado extensiones que lo necesitan, de otra forma sólo disminuye el desempeño.');

/*
Melvin TODO [20060128]: Reorganize (perhaps) next constants in the order they belong
Melvin TODO [20060128]: What spanish word do we use for "referrers" ??
*/
@define('XMLRPC_NO_LONGER_BUNDLED', 'El API de la interfase XML-RPC en Serendipity no está incluido debido a problemas de seguridad que hay en curso con este API, además del hecho que no es usada con mucha frecuencia. De esta forma, usted necesita instalar la extensión XML-RPC para utilizar API de XML-RPC. Las URL que usa en sus aplicaciones NO cambiarán y tan pronto como instale la extensión podrá usar el API.');
@define('PERM_READ', 'Permiso de lectura');
@define('PERM_WRITE', 'Permiso de escritura');
@define('PERM_DENIED', 'Permiso denegado.');
@define('INSTALL_ACL', 'Aplicar permisos de lectura por categorías');
@define('INSTALL_ACL_DESC', 'Cuando se activa, son aplicados los permisos para grupos de usuarios que estén configurados cuando los usuarios registrados vean el Blog. Cuando se desactiva, los permisos de lectura de las categorías NO son aplicadas, con el efecto positivo que el Blog carga un poco más rápido. Si no necesitas permisos de lectura para múltiples usuarios, deshabilita esta opción.');
@define('PLUGIN_API_VALIDATE_ERROR', 'Sintaxis errónea en la configuración de la opción "%s". Se requiere contenido de tipo "%s".');
@define('PLUGIN_API_GENERIC_SUBOPTION_DESC', '<b>ATTENTION</b>: Certain options open or close pending suboptions [+] only after submission sets. Also, certain options can deactivate already set options or reset them to the default value, so that in case of a reconsideration a new setting or activation might be necessary.');
@define('USERCONF_CHECK_PASSWORD', 'Contraseña actual');
@define('USERCONF_CHECK_PASSWORD_DESC', 'Si cambias la contraseña en el campo de arriba, debes escribir la contraseña actual en este campo.');
@define('USERCONF_CHECK_PASSWORD_DESC_ADDNOTE', 'Use carefully, since any following permissible backend action will force you to a new login afterwards - so only usable once, per Login-Session!');
@define('USERCONF_CHECK_PASSWORD_ERROR', 'No especificaste correctamente la contraseña actual, por lo tanto no estas autorizado para establecer una nueva. Tus cambios no fueron guardados.');
@define('ERROR_XSRF', 'Tu navegador no envió una cadena válida de HTTP-Referrer. Esto puede deberse a una mala configuración del navegador/proxy o por un "Cross Site Request Forgery (XSRF)" dirigido a ti. La acción solicitada no pudo ser completada.');
@define('INSTALL_PERMALINK_FEEDAUTHOR_DESC', 'Aquí puedes definir la estructura relativa de URLs comenzando por tu URL base hasta incluso las sindicaciones RSS de usuarios específicos que puedan ser vistos. Puedes usar las variables %id%, %realname%, %username%, %email% y cualquier otro carácter.');
@define('INSTALL_PERMALINK_FEEDAUTHOR', 'Estructura de los Enlaces Permanentes para las sindicaciones RSS de autores');
@define('INSTALL_PERMALINK_AUTHORSPATH', 'Ruta para los autores');
@define('AUTHORS', 'Autores');
@define('AUTHORS_ALLOW_SELECT', 'Permitir a los visitantes mostrar múltiples autores al mismo tiempo?');
@define('AUTHORS_ALLOW_SELECT_DESC', 'Si esta opción es activada, un checkbox estará al lado de cada autor en en la extensión lateral. Los usuarios pueden seleccionar estas casillas para ver las entradas de acuerdo a su selección.');
@define('AUTHOR_PLUGIN_DESC', 'Muestra una lista de autores');
@define('CATEGORY_PLUGIN_TEMPLATE', 'Activar Smarty-Templates?');
@define('CATEGORY_PLUGIN_TEMPLATE_DESC', 'Si esta opción es activada, la extensión utiliza las características de Smarty-Templating para producir el contenido del listado de las categorías. Si activas esto, puedes cambiar el diseño a través del fichero "plugin_categories.tpl". Toma en cuenta que esta opción tendrá un impacto en el desempeño, si no necesitas diseños particulares, déjalo deshabilitado.');
@define('CATEGORY_PLUGIN_SHOWCOUNT', 'Mostrar número de entradas por categoría?');
@define('AUTHORS_SHOW_ARTICLE_COUNT', 'Mostrar número de artículos del autor?');
@define('AUTHORS_SHOW_ARTICLE_COUNT_DESC', 'Si esta opción es activada, el número de artículos del autor se muestra al lado del nombre del autor en paréntesis.');
@define('CUSTOM_ADMIN_INTERFACE', 'Interfaz de la suite de administración personalizada');
@define('COMMENT_NOT_ADDED', 'Tu comentario no ha podido ser añadido debido a que, o bien, en esta entrada ha sido deshabilitado el envío de comentarios, ingresaste datos erróneos, o tu comentario ha sido capturado por medidas anti-spam.');
@define('INSTALL_TRACKREF', 'Activar registro de "referrers"?');
@define('INSTALL_TRACKREF_DESC', 'Activando el registro de "referrers" podrás ver cuales sitios envían visitas a tus artículos. Últimamente esto esta siendo abusado frecuentemente por spammers, así que puedes deshabilitarlo si quieres.');
@define('CATEGORIES_HIDE_PARENT', 'Ocular la categoría padre seleccionada?');
@define('CATEGORIES_HIDE_PARENT_DESC', 'Si restringes el listado de categorías a una categoría especifica, por defecto verás la categoría padre (superior) dentro del listado generado. Si deshabilitas esta opción, el nombre de la categoría padre no será mostrado.');
@define('WARNING_NO_GROUPS_SELECTED', 'Advertencia: No haz seleccionado ningún grupo de miembros. Esto efectivamente te dejaría fuera de la gerencia del grupo de usuarios, por lo tanto, tu membresía de grupo no fue cambiada.');
@define('INSTALL_RSSFETCHLIMIT', 'Entradas a mostrar en las sindicaciones');
@define('INSTALL_RSSFETCHLIMIT_DESC', 'Número de entradas a mostrar por cada página.');
@define('INSTALL_DB_UTF8', 'Activar conversión del juego de caracteres en la BD');
@define('INSTALL_DB_UTF8_DESC', 'Envía una consulta MySQL "SET NAMES" para definir el juego de caracteres requerido por la base de datos. Actívalo o desactívalo sí ves caracteres extraños en tu blog.');
@define('ONTHEFLYSYNCH', 'Activar sincronización de medios al-vuelo');
@define('ONTHEFLYSYNCH_DESC', 'Si lo activas, Serendipity comparará la base de datos de medios con los archivos almacenados en tu servidor y sincronizará la base de datos con el contenido del directorio. This is - especially due to the additional variation formats - a rather time-consuming monitoring instrument and can increasingly slow down a growing MediaLibrary, since each call of the same must permanently run through all(!) files, check and re-evaluate, including the resulting necessary changes. But since the latter happens correspondingly often, this step becomes accordingly shorter. Otherwise use the first two "Media library: Rebuild Thumbs" actions in the maintenance section from time to time, which also include a final synchronization! So a "Yes" is recommended here if you either often work around directly in the file system of the MediaLibrary yourself, use this option only temporarily or do not notice any particular slowdown, or are a developer/tester with correspondingly many false/positive results.');
@define('USERCONF_CHECK_USERNAME_ERROR', 'No puedes dejar el nombre de usuario en blanco.');
@define('FURTHER_LINKS', 'Más enlaces');
@define('FURTHER_LINKS_S9Y', 'Página principal de Serendipity');
@define('FURTHER_LINKS_S9Y_DOCS', 'Documentación de Serendipity');
@define('FURTHER_LINKS_S9Y_BLOG', 'Blog oficial');
@define('FURTHER_LINKS_S9Y_FORUMS', 'Foros');
@define('FURTHER_LINKS_S9Y_SPARTACUS', 'Spartacus');
@define('COMMENT_IS_DELETED', '(Comentario eliminado)');
@define('CURRENT_AUTHOR', 'Autor actual');
@define('WORD_NEW', 'Nuevo');
@define('SHOW_MEDIA_TOOLBAR', '¿Mostrar la barra de herramientas dentro de la ventana emergente selección de medios?');
@define('MEDIA_KEYWORDS', 'Palabras claves de medios');
@define('MEDIA_KEYWORDS_DESC', 'Ingresa una lista palabras separadas por ";" que quisieras utilizar como palabras clave predefinidas para los elementos de medios.');
@define('MEDIA_EXIF', 'Importar información de imágenes EXIF/JPEG');
@define('MEDIA_EXIF_DESC', 'Si lo activas, la metadata de las imágenes EXIF/JPEG existentes será analizada y almacenada en la base de datos para ser mostrada en la galería de medios.');
@define('MEDIA_PROP', 'Propiedades de Medios');
@define('MEDIA_PROP_STATUS', 'This Form values "alt", "comment"s and "title" as public media properties have not been saved yet, OR equal the default. Currently, an image title-attribute is auto-build by the files realname!');
@define('MEDIA_CREATEVARS', 'Add additional image variations');

@define('DIALOG_DELETE_VARIATIONS_PERITEM', 'Yes [ENTER-key] will delete all occurrences of this file; No [SPACE-key] only deletes the image variations (if any), so that they can be rebuilt afterwards via the [+] icon; Cancel [ESC-key] will do nothing! "Yes" and "No" confirmation actions in the following can also be aborted.');
@define('DIALOG_DELETE_FILE_CONTINUE', 'Delete file "%s"... Continue ?');
@define('DIALOG_DELETE_VARIATIONS', 'Delete Variations');

@define('GO_ADD_PROPERTIES', 'Ir & ingresar propiedades');
@define('MEDIA_PROPERTY_DPI', 'DPI');
@define('MEDIA_PROPERTY_COPYRIGHT', 'Copyright');
@define('MEDIA_PROPERTY_COMMENT1', 'Comentario público');
@define('MEDIA_PROPERTY_COMMENT2', 'Comentario interno');
@define('MEDIA_PROPERTY_TITLE', 'Título');
@define('MEDIA_PROP_DESC', 'Ingresa una lista de campos de propiedades separados por ";" que te gustaría definir por cada archivo de medios');
@define('MEDIA_PROP_MULTIDESC', '(Puedes añadir al final de cualquier item ":MULTI" para indicar que contendrá una descripción larga en ves de sólo algunos caracteres)');

@define('STYLE_OPTIONS_NONE', 'Este tema/estilo no tiene opciones específicas. Para ver como tu plantilla puede especificar opciones, lee la documentación técnica en "https://ophian.github.io/hc/en/templating.html#docs-theme-options" acerca de "Configuración de las opciones de los estilos".');
@define('STYLE_OPTIONS', 'Opciones de Tema/estilo');

@define('PLUGIN_AVAILABLE_COUNT', 'Total: %d extensiones.');
@define('SYNDICATION_RFC2616', 'Activar seguimiento estricto de la norma RFC2616 para sindicaciones RSS');
@define('SYNDICATION_RFC2616_DESC', 'NO forzar el seguimiento de RFC2616 significa que todos los GETs condicionales a Serendipity regresarán entradas modificadas desde la fecha de la última solicitud. Con esa característica definida a "false", tus visitantes obtendrán todos los artículos desde su última solicitud, lo cual es considerado algo bueno. Sin embargo, algunos agentes como Planet tienen un comportamiento extraño, dado viola la norma RFC2616. De esta manera, si defines esta opción como "TRUE" estarás cumpliendo con la norma, pero los lectores de tus sindicaciones RSS quizás pierdan algunas entradas en sus días de ausencia. De cualquier forma, o evitas un funcionamiento adecuado de agregadores como Planet, o dañas a tus lectores reales. Si estás enfrentando quejas de cualquiera de los dos, puedes cambiar esta opción.');
@define('MEDIA_PROPERTY_DATE', 'Fecha Asociada');

/*Translate*/
@define('MEDIA_PROPERTY_RUN_LENGTH', 'Run-Length');
/**/
@define('FILENAME_REASSIGNED', 'Nombre asignado automágicamente al nuevo fichero: %s');
@define('MEDIA_UPLOAD_SIZE', 'Tamaño máximo de los ficheros');//'upload' removed [rlazo]
@define('MEDIA_UPLOAD_SIZE_DESC', 'Ingresa el tamaño máximo en bytes de los ficheros que puedes subir al servidor. Esta opción puede ser sobreescrita por la configuración en el servidor en PHP.ini: upload_max_filesize, post_max_size, max_input_time, todas las anteriores tiene precedencia sobre esta opción. Si la dejas en blanco se usarán sólo los límites definidos en el servidor.');
@define('MEDIA_UPLOAD_SIZEERROR', 'Error: ¡No puedes subir ficheros de más de %s bytes!');
@define('MEDIA_UPLOAD_MAXWIDTH', 'Ancho máximo de las imágenes'); //'upload' removed [rlazo]
@define('MEDIA_UPLOAD_MAXWIDTH_DESC', 'Ingresa la anchura máxima de las imágenes que se pueden subir al servidor en pixels.');
@define('MEDIA_UPLOAD_MAXHEIGHT', 'Altura máxima de las imágenes');//'upload' removed [rlazo]
@define('MEDIA_UPLOAD_MAXHEIGHT_DESC', 'Ingresa la altura máxima de las imágenes que se pueden subir al servidor en pixels.');
@define('MEDIA_UPLOAD_DIMERROR', 'Error: One setting prevents to upload image files larger than %s x %s pixels! Check your Configuration section: "%s" settings. You may want to additionally activate the "%s"-Option to make this work.');
@define('MEDIA_TARGET', 'Objetivo de este enlace');
@define('MEDIA_TARGET_JS', 'Ventana emergente (a través de JavaScript, tamaño adaptativo)');
@define('MEDIA_ENTRY', 'Entrada aislada');
@define('MEDIA_TARGET_BLANK', 'Ventana emergente (a través de target=_blank)');
@define('MEDIA_DYN_RESIZE', '¿Permitir redimensionar dinámicamente las imágenes?');
@define('MEDIA_DYN_RESIZE_DESC', 'Si lo activas, el serendipity_admin_image_selector.php file puede regresar imágenes en cualquier tamaño solicitado a través de una variable GET. Los resultados se colocan en la caché, de esta manera puedes crear una base de ficheros muy grande si es que haces un uso intensivo de esta característica.');
@define('MEDIA_DIRECTORY_MOVED', 'El directorio y los ficheros fueron exitosamente movidos a %s');
@define('MEDIA_DIRECTORY_MOVE_ERROR', '¡El directorio y los ficheros no pudieron ser movidos a %s!');
#@define('MEDIA_DIRECTORY_MOVE_ENTRY', 'En bases de datos distintas a MySQL, iterar sobre cada artículo para reemplazar el URL del directorio antiguo con la nueva dirección no es posible. Necesitarás que arreglar tus entradas de manera manual. También puedes regresar tu directorio a su ubicación original si hacer todo aquello te resulta muy incómodo.');
/*translate*/
@define('MEDIA_DIRECTORY_MOVE_ENTRIES', 'Moved the URL of the moved directory in %s entries.');
@define('MEDIA_FILE_RENAME_ENTRY', 'The filename was changed in %s entries.');
@define('PLUGIN_ACTIVE', 'Activa');
@define('PLUGIN_INACTIVE', 'Inactiva');
/*rlazo [20060722] spell checked*/

@define('INSTALL_PERMALINK_COMMENTSPATH', 'Ruta a los comentarios');
@define('PERM_SET_CHILD', 'Define los mismos permisos en todos los directorios hijos');
@define('PERMISSION_FORBIDDEN_PLUGINS', 'Extensiones prohibidas');
@define('PERMISSION_FORBIDDEN_HOOKS', 'Eventos prohibidos');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE', '¿Activar la extensión ACL para grupos de usuarios?');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE_DESC', 'Si la opción "Extensión ACL para grupos de usuarios" es activada en la configuración, puedes especificar que grupos de usuarios son capaces de ejecutar ciertas extensiones/eventos.');
@define('PERMISSION_READ_WRITE_ACL_DESC', 'By default, the read/write permissions are set to "0", i.e. "All authors". However, if you set them as an administrator, for example to Standard editor, equal to "1", you can no longer change back afterwards, since you have withdrawn the right yourself. So make sure to always include higher-ranking user groups if you want them to continue to have access to it.');

@define('DELETE_SELECTED_ENTRIES', 'Eliminar las entradas seleccionadas');
@define('PLUGIN_AUTHORS_MINCOUNT', 'Sólo mostrar aquellos autores con al menos X artículos');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET', 'Bookmarklet');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET_DESC', 'Bookmark this link and then use it on any page you want to Blog about, to quickly access your Serendipity Blogs backend entry form (when logged in).');
@define('IMPORT_WP_PAGES', '¿Descargar archivos adjuntos y páginas estáticas como entradas normales del blog?');
@define('USERCONF_CREATE', '¿Deshabilitar usuario / prohibir actividad?');
@define('USERCONF_CREATE_DESC', 'Si activas esta opción el usuario ya no podrá crear o editar entradas en el blog. Cuando él vuelva a ingresar al sistema no podrá hacer nada más que desconectarse y ver su configuración personal.');
@define('CATEGORY_HIDE_SUB', '¿Ocultar las entradas realizadas en sub-categorías?');
@define('CATEGORY_HIDE_SUB_DESC', 'Por defecto, cuando se navega una categoría también se muestran las entradas hechas en cualquiera de sus sub-categorías. Si activas esta opción, se mostraran únicamente aquellas entradas que pertenezcan a la categoría seleccionada.');
@define('PINGBACK_SENDING', 'Enviando pingback a URI %s...');
@define('PINGBACK_SENT', 'Pingback enviado exitosamente');
@define('PINGBACK_FAILED', 'Falló envío de Pingback: %s');
@define('PINGBACK_NOT_FOUND', 'No se encontró pingback-URI.');
@define('CATEGORY_PLUGIN_HIDEZEROCOUNT', 'Oculta el enlace a los archivos de una fecha dada cuando, durante ese lapso de tiempo, no se hayan registrado entradas (requiere conteo de entradas)');
@define('RSS_IMPORT_WPXRSS', 'Importar el RSS eXtendido de WordPress (WPXRSS), necesita PHP5 y es posible que consuma bastante memoria');
@define('SET_TO_MODERATED', 'Moderar');
@define('COMMENT_MODERATED', 'El comentario #%s ha sido marcado como moderado exitosamente');
@define('CENTER', 'Centro');
@define('FULL_COMMENT_TEXT', 'Yes, with full comment text');

@define('COMMENT_TOKENS', 'Use Tokens for Comment Moderation?');
@define('COMMENT_TOKENS_DESC', 'If tokens are used, comments can be approved and deleted by clicking the email links without requiring login access to the blog. Note that this is a convenience feature, and if your mails get hijacked, those people can approve/delete the referenced comment without further authentication.');
@define('COMMENT_NOTOKENMATCH', 'Moderation link has expired or comment #%s has already been approved or deleted');
@define('TRACKBACK_NOTOKENMATCH', 'Moderation link has expired or trackback #%s has already been approved or deleted');
@define('BADTOKEN', 'Invalid Moderation Link');

@define('CONFIRMATION_MAIL_ALWAYS', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n");
@define('CONFIRMATION_MAIL_ONCE', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled one-time mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n\nAfter you have done that, you can always post comments on that blog with your username and e-mail address without receiving such notifications.");
@define('INSTALL_SUBSCRIBE_OPTIN', 'Use Double-Opt In for comment subscriptions?');
@define('INSTALL_SUBSCRIBE_OPTIN_DESC', 'If enabled, when a comment is made where the person wants to be notified via e-mail about new comments to the same entry, he must confirm his subscription to the entry. This Double-Opt In is required by german law, for example.');
@define('CONFIRMATION_MAIL_SUBSCRIPTION', "Hello %s,\n\nYou have requested to be notified for comments to \"%s\" (<%s>). To approve this subscription (\"Double Opt In\") please click this link:\n<%s>\n.");
@define('NOTIFICATION_CONFIRM_SUBMAIL', 'Your confirmation of your comment subscription has been successfully entered.');
@define('NOTIFICATION_CONFIRM_MAIL', 'Your confirmation of the comment has been successfully entered.');
@define('NOTIFICATION_CONFIRM_SUBMAIL_FAIL', 'Your comment subscription could not be confirmed. Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must request a new confirmation mail.');
@define('NOTIFICATION_CONFIRM_MAIL_FAIL', 'Your comment confirmation could not be confirmed.  Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must send your comment again.');
@define('PLUGIN_DOCUMENTATION', 'Documentation');
@define('PLUGIN_DOCUMENTATION_LOCAL', 'Local Documentation');
@define('PLUGIN_DOCUMENTATION_CHANGELOG', 'Version history');
@define('SYNDICATION_PLUGIN_BIGIMG', 'Big Image');
@define('SYNDICATION_PLUGIN_BIGIMG_DESC', 'Display a (big) image at the top of the feeds in sidebar, enter full or absolute URL to image file. Set to "none" to show a textlink (the old default)');
@define('SYNDICATION_PLUGIN_FEEDNAME', 'Displayed name for "feed"');
@define('SYNDICATION_PLUGIN_FEEDNAME_DESC', 'Enter an optional custom name for the feeds (defaults to "feed" when empty)');
@define('SYNDICATION_PLUGIN_COMMENTNAME', 'Displayed name for "comment" feed');
@define('SYNDICATION_PLUGIN_COMMENTNAME_DESC', 'Enter an optional custom name for the comment feed');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD', '(If you enter an absolute URL with http://... here, this URL will be used as the redirection target in case you have enabled the "Force" option for FeedBurner. Note that this can also be a URL independent to FeedBurner. For new Google FeedBurner feeds, you need to enter http://feeds2.feedburner.com/yourfeedname here)');

@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD2', 'If you set this option to "Force" you can forward the RSS feed to any webservice, not only FeedBurner. Look at the option "Feedburner ID" below to enter an absolute URL)');
@define('NOT_WRITABLE_SPARTACUS', ' Recommended! (Required, when you plan to use Spartacus plugin for remote plugin download.)');
@define('MEDIA_ALT', 'ALT-Attribute (depiction or short description)');
@define('MEDIA_PROPERTY_ALT', 'Depiction (summary for ALT-Attribute)');

@define('MEDIA_TITLE', 'TITLE-Attribute (will be displayed on mouse over)');

@define('QUICKSEARCH_SORT', 'How should search-results be sorted?');

@define('QUICKSEARCH_SORT_RELEVANCE', 'Relevance');

@define('PERMISSION_HIDDENGROUP', 'Hidden group / Non-Author');

@define('SEARCH_FULLENTRY', 'Show full entry');
@define('NAVLINK_AMOUNT', 'Enter number of links in the navbar (and save this form)');
@define('NAV_LINK_TEXT', 'Enter the navbar link text');
@define('NAV_LINK_URL', 'Enter the full URL of your link');
@define('MODERATE_SELECTED_COMMENTS', 'Accept selected comments');
@define('WEBLOG', 'Weblog');
@define('ACTIVE_COMMENT_SUBSCRIPTION', 'Subscribed');
@define('PENDING_COMMENT_SUBSCRIPTION', 'Pending confirmation');
@define('NO_COMMENT_SUBSCRIPTION', 'Not subscribed');
@define('SUMMARY', 'Summary');

@define('ABOUT_TO_DELETE_FILES', 'You are about to delete a bunch of files at once.<br>If you are using these in some of your entries, it will cause dead links or images<br>Are you sure you wish to proceed?<br><br>');
@define('ARCHIVE_SORT_STABLE', 'Stable Archives');
@define('ARCHIVE_SORT_STABLE_DESC', 'Sort the archive-pages descending, so they are stable. Default sort is ascending.');
@define('PLAIN_ASCII_NAMES', '(no special characters, umlauts)');

// New 2.0 constants
@define('SIMPLE_FILTERS', 'Simplified filters');
@define('SIMPLE_FILTERS_DESC', 'When enabled, search forms and filter functions are reduced to essential options. When disabled, you will see every possible filter option, like in the "Media library" or the "Edit entries" list, under condition of actual permission.');
@define('TOGGLE_SELECT', 'Mark for selection');
@define('MORE', 'More');
@define('ENTRY_STATUS', 'Entry status');
@define('SCHEDULED', 'Scheduled');
@define('PUBLISHED', 'Published');
@define('ENTRY_METADATA', 'Entry metadata');
@define('NAVIGATION', 'Navigation');
@define('MAIN_MENU', 'Main menu');
@define('MENU_PERSONAL', 'Personal menu');
@define('MENU_DASHBOARD', 'Dashboard');
@define('MENU_ACTIVITY', 'Activity');
@define('MENU_SETTINGS', 'Settings');
@define('MENU_TEMPLATES', 'Templates');
@define('MENU_PLUGINS', 'Plugins');
@define('MENU_USERS', 'Users');
@define('MENU_GROUPS', 'Groups');
@define('MENU_MAINTENANCE', 'Maintenance');
@define('ALIGN_TOP', 'Top');
@define('ALIGN_LEFT', 'Left');
@define('ALIGN_RIGHT', 'Right');
@define('SHOW_METADATA', 'Show metadata');
@define('RANGE_FROM', 'From');
@define('RANGE_TO', 'To');
@define('UPLOAD', 'Upload');
@define('DOWNLOAD', 'Download');
@define('ENTRY_PUBLISHED', 'Entry #%s published');
@define('PUBLISH_ERROR', 'Error publishing entry:');
@define('UPDATE_NOTIFICATION', 'Update notification');
@define('NEW_VERSION_AVAILABLE', 'New Serendipity version available: ');
@define('MOVE', 'Move');
@define('MOVE_UP', 'Move up');
@define('MOVE_DOWN', 'Move down');
@define('INSTALL_NEW_SIDEBAR_PLUGIN', 'Install a new sidebar plugin');
@define('INSTALL_NEW_EVENT_PLUGIN', 'Install a new event plugin');
@define('TEMPLATE_OPTIONS', 'Template options');
@define('CURRENT_TEMPLATE', 'Current Template');
@define('TEMPLATE_INFO', 'Show template info');
@define('AVAILABLE_TEMPLATES', 'Available Templates');
@define('TIMESTAMP_RESET', 'The timestamp has been reset to the current time.');

@define('CLEANCOMPILE_PASS', '[smarty clearCompiledTemplate(%s)]');
@define('CLEANCOMPILE_FAIL', 'No files available for clearing.');
@define('CLEANCOMPILE_TITLE', 'Clear template cache');
@define('CLEANCOMPILE_INFO', 'This will purge all compiled template files of the current active template. Compiled templates will be automatically re-created on demand by the Smarty framework.');
@define('INSTALLER_KEY', 'Key');
@define('INSTALLER_VALUE', 'Value');
@define('CURRENT_TAB', 'Current tab: ');
@define('PINGBACKS', 'Pingbacks');
@define('PINGBACK', 'Pingback');
@define('NO_PINGBACKS', 'No Pingbacks');
@define('GROUP_NAME_DESC', "Use as uppercased eg. 'EXAMPLE_GROUP' name, but not as a constant 'USERLEVEL_XYZ' group name.");
@define('INSTALLER_CLI_TOOLS', 'Server-side command line tools');
@define('INSTALLER_CLI_TOOLNAME', 'CLI tool');
@define('INSTALLER_CLI_TOOLSTATUS', 'Executable?');
@define('VIDEO', 'Video');
@define('RESET_STATUS', 'Reset status');
@define('RESET_FILTERS', 'Reset filters');
@define('UPDATE_FAILMSG', 'Check for new Serendipity version failed. This can happen because either the URL %s is down, your server blocks outgoing connections or there are other connection issues.');
@define('UPDATE_FAILACTION', 'Disable automatic update check');
@define('UPDATE_NOTIFICATION_DESC', 'Show the update notification on the backend startpage, and for which channel?');
@define('FRONTEND', 'Frontend');
@define('BACKEND', 'Backend');
@define('MEDIA_UPLOAD_RESIZE', 'Resize before Upload');
@define('MEDIA_UPLOAD_RESIZE_DESC', 'Resize images according to configured maximum/minimum dimensions before the upload using Javascript. This will also change the uploader to use Ajax and thus remove the Property-Button.<br>PLEASE NOTE: Setting this option true will prevent other options to behave like they should, in special, when the imageselectorplus event plugin is used!');
@define('LOG_LEVEL', 'Log Level');
@define('LOG_LEVEL_DESC', 'At certain places in the Serendipity code we have placed debugging breakpoints. If this option is set to "Debug", it will write this debug output to templates_c/logs/. You should only enable this option if you are experiencing bugs in those areas, or if you are a developer. Setting this option to "Error" will enable logging PHP errors, overwriting the PHP error_log setting.');
@define('DEBUG', 'Debug');
@define('CUSTOM_CONFIG', 'Custom configuration file');
@define('PLUGIN_ALREADY_INSTALLED', 'Plugin already installed, and does not support multiple installation ("stackable").');
@define('PLUGIN_UPDATES_DONE', 'All Plugins updated!');
@define('STACKABLE_PLUGIN', 'Stackable plugin!');
@define('STACKED_PLUGIN', 'Stacked plugin!');
@define('MULTISTACK_PLUGIN', 'Multi-stacked plugin!');
@define('INSTALL_DBPREFIX_INVALID', 'The database table name prefix must not be empty and may only contain letters, numbers and the underscore character.');
@define('SYNDICATION_PLUGIN_SUBTOME', 'subToMe');
@define('SYNDICATION_PLUGIN_SUBTOME_DESC', 'Load the external subToMe javascript and show the internal subToMe icon-button, a layer to make feed subscription easier.');
@define('INSTALL_BACKENDPOPUP', 'Enable use of popup windows for the backend');
@define('INSTALL_BACKENDPOPUP_DESC', 'Do you want to use popup windows for some backend functionality? When disabled (default), inline modal dialogs will be used for e.g. the category selector and MediaLibrary. On the other hand this popup-window option only works for some elements, like the MediaLibrary and some plugins. Others, like categories, will show up embedded.');
@define('UPDATE_STABLE', 'stable');
@define('UPDATE_BETA', 'beta');
@define('SYNDICATION_PLUGIN_FEEDFORMAT', 'Feed format');
@define('SYNDICATION_PLUGIN_FEEDFORMAT_DESC', 'Which format shall be used for all feeds. Both are supported in all common readers');
@define('SYNDICATION_PLUGIN_COMMENTFEED', 'Comment feed');
@define('SYNDICATION_PLUGIN_COMMENTFEED_DESC', 'Show an additional link to a comment feed. This should be interesting only to the blogauthor itself');
@define('SYNDICATION_PLUGIN_FEEDICON', 'Feed icon');
@define('SYNDICATION_PLUGIN_FEEDICON_DESC', 'Show a (big) icon instead of a textlink to the feed. Set to "none" to deactivate, or to "feedburner" to show a feedburner counter if an id is given below');
@define('SYNDICATION_PLUGIN_CUSTOMURL', 'Custom URL');
@define('SYNDICATION_PLUGIN_CUSTOMURL_DESC', 'If you want to link to the custom feed specified in the Blog configuration, enable this option.');
@define('FEED_CUSTOM', 'Custom feed URL');
@define('FEED_CUSTOM_DESC', 'If set, a custom feed URL can be set to forward Feedreaders to a specific URL. Useful for statistical analyzers like Feedburner, in which case you would enter your Feedburner-URL here.');
@define('FEED_FORCE', 'Force custom feed URL?');
@define('FEED_FORCE_DESC', 'If enabled, the URL entered above will be mandatory for Feedreaders, and your usual feed cannot be accessed from clients.');
@define('NO_UPDATES', 'No plugin updates are available');
@define('PLUGIN_GROUP_ALL', 'All categories');

@define('CONF_USE_AUTOSAVE', 'Enable autosave-feature');
@define('CONF_USE_AUTOSAVE_DESC', 'When enabled, the text you enter into Blog entries will be periodically saved in your browser\'s session storage. If your browser crashes during writing, the next time you create a new entry, the text will be restored from this autosave.');
@define('INSTALL_CAT_FEEDS', 'Feed Settings');
@define('INSTALL_CAT_FEEDS_DESC', 'Customize how Serendipity feeds Feeds');

@define('CATEGORY_PLUGIN_SHOWALL', 'Show a link to "All categories"?');
@define('CATEGORY_PLUGIN_SHOWALL_DESC', 'If enabled, a link for the visitor to display the Blog with no category restriction will be added.');
@define('SERENDIPITY_PHPVERSION_FAIL', 'Serendipity requires a PHP version >= %2$s - you are running a lower version (%1$s) and need to upgrade your PHP version. Most providers offer you to switch to newer PHP versions through their admin panels or .htaccess directives.');
@define('TOGGLE_VIEW', 'Switch category view mode');
@define('PUBLISH_NOW', 'Publish this entry now (sets current time and date)');
@define('EDITOR_TAGS', 'Tags');
@define('EDITOR_NO_TAGS', 'No tags');
@define('DASHBOARD_ENTRIES', 'In Progress');
@define('INSTALL_PASSWORD2', 'Admin password (verify)');
@define('INSTALL_PASSWORD2_DESC', 'Password for admin login, enter again to verify.');
@define('INSTALL_PASSWORD_INVALID', 'Your entered passwords for the administrator user do not match.');
@define('INSTALL_BACKENDPOPUP_GRANULAR', 'Force specific backend embed/popup behavior');
@define('INSTALL_BACKENDPOPUP_GRANULAR_DESC', 'If popups are generally disabled (see above), the use of "popups" can be bypassed in special places. This applies to real window-popups with ("<em>images</em>" via entry form and "<em>comments</em>" via comment Reply), and <u>vice versa</u> in writing, for modal "popups" only, with (<em>categories, tags, links</em>) as (embedded default) settings, in that they are written here for the first two and exactly <b>not</b> listed for the latter three, on account of the default settings. The complete (comma separated) list is: ');
@define('START_UPDATE', 'Starting Update ...');
@define('UPDATE_ALL', 'Update All');
@define('JS_FAILURE', 'The Serendipity JavaScript-library could not be loaded. This can happen due to PHP or Plugin errors, or even a malformed browser cache. To check the exact error please open <a href="%1$s">%1$s</a> manually in your browser and check for error messages.');
@define('THEMES_PREVIEW_BLOG', 'See demo on blog.s9y.org');
@define('SYNDICATION_PLUGIN_XML_DESC', 'Set to "none" if you only want to show a text link.');
@define('MULTICHECK_NO_ITEM', 'No item selected, please check at least one. <a href="%s">Return to previous page</a>.');
@define('MULTICHECK_NO_DIR', 'No directory selected, please choose one. <a href="%s">Return to previous page</a>.');
@define('BULKMOVE_INFO', 'Bulk-move info');
@define('BULKMOVE_INFO_DESC', 'You can select multiple files to bulk-move them to a new location. <strong>Note:</strong> This action takes effect immediately without any further demand. All checked files will be physically moved, and referring Blog entries are rewritten to point to the new location. Static pages by the staticpage plugin are rewritten too.');
@define('FIRST_PAGE', 'First Page');
@define('LAST_PAGE', 'Last Page');
@define('MEDIA_PROPERTIES_DONE', 'Properties of #%d changed.');
@define('DIRECTORY_INFO', 'Directory info');
@define('DIRECTORY_INFO_DESC', 'Directories reflect their physical folders directory name. If you want to change or move directories which contain items, you have two choices. Either create the directory or subdirectory you want, then move the items to the new directory via the MediaLibrary and afterwards, delete the empty old directory there. Or completely change the whole old directory via the edit directory button below and rename it to whatever you like (existing subdir/ + newname). This will move all directories and items and change referring Blog entries.');
@define('MEDIA_RESIZE_EXISTS', 'File dimensions already exist!');
@define('USE_CACHE', 'Enable caching');
@define('USE_CACHE_DESC', 'Enables an internal cache to not repeat specific database queries. This reduces the load on servers with medium to high traffic and improves page load time.');
@define('CONFIG_PERMALINK_PATH_DESC', 'Please note that you have to use a prefix so that Serendipity can properly map the URL to the proper action. You may change the prefix to any unique name, but not remove it. This applies to all path prefix definitions.');

@define('HIDE_SUBDIR_FILES', 'Hide Files of Subdirectories');

@define('UPDATE_NOTIFICATION_URL', 'Update RELEASE-file URL');
@define('UPDATE_NOTIFICATION_URL_DESC', 'This is Styx! Do not change, if not applying a different RELEASE file location for custom core downloads in combination with the Serendipity Autoupdate plugin. An intranet URL could then be alike "https://localhost/git/you/styx/docs/RELEASE" or whatever. A here provided URL points to a file containing the current released Serendipity stable and beta version numbers per line, eg. "stable:5.3.0". The Styx repository default file URL is: "https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE".');

@define('URL_NOT_FOUND', '[ 404 ] - The page you have requested could not be found. Continue reading here.');

@define('CONFIG_ALLOW_LOCAL_URL', 'Allow to fetch data from local URLs');
@define('CONFIG_ALLOW_LOCAL_URL_DESC', 'By default, it is forbidden due to security constrains to fetch data from local URLs to prevent Server-Side Request Forgery (SSRF). If you use a local intranet, you can enable this option to allow fetching data.');
@define('REMOTE_FILE_INVALID', 'The given URL "%s" appears to be local and is not allowed to be fetched. You can allow this by setting the option "Allow to fetch data from local URLs" in your Blog configuration.');

@define('INSTALLER_TOKEN', 'Temporary installer token');
@define('INSTALLER_TOKEN_CHECK', 'Token check');
@define('INSTALLER_TOKEN_STATUS', 'Token status');
@define('INSTALLER_TOKEN_NOTE', 'To continue a secure installation, you need to create a file called "%s" with the string "<strong>&lt;?php $install_token = \'%s\'; ?&gt;</strong>" within this directory. Once that file exists, you must continue the installation within the next %s minutes and do not close your browser window, and you need to have Cookies enabled.');
@define('INSTALLER_TOKEN_MISMATCH', 'You are not authorized to continue installation, since your install token is not identical (%s) to the one contained in the file "%s". Please make sure you created the file with the right content. You can get a new token by deleting the file.');
@define('INSTALLER_TOKEN_MATCH', 'Your secure install token is valid.');

@define('DASHBOARD_INFO_HEADER', 'Overview');
@define('DASHBOARD_INFO_CONTENT', 'Shortcuts');
@define('DASHBOARD_INFO_EMPTY', 'We don\'t have enough data to show anything useful. No pending comments, future or draft entries are available.');
@define('COMMENTS_PENDING', 'Pending comments');
@define('FUTURES_AVAILABLE', 'Future entries');
@define('DRAFTS_AVAILABLE', 'Draft entries');

@define('MEDIA_GALLERY_SELECTION', 'This particular selection for media galleries only shows directory images of the same level. It does not contain a statement to also display the images of sub folders, as you might be used to. The number of possible preview images that can be displayed at the same time is limited to <b>48 items</b>. Restructure your MediaLibrary accordingly!<br>This media gallery directory selection only shows thumbnails (optionally configurable linking to the big picture). If your preview images do not meet the standard width of 400px, and are much smaller than the defined gallery format of 260px for the per row option, it is possible that you get into display problems in this selection as well as afterwards in the frontend entry.<br>The <b>order</b> of displayed gallery items is not configurable here and can only be changed afterwards within the displayed source code of your editors dropping textarea window. If you there use the WYSIWYG-Editor, please do <strong>not</strong> simply use the internal <em>drag & drop</em> feature of the WYSIWYG-mode (editor) window, since that may destroy or at least mess up the gallery selection for your entry. Though <b>it is</b> possible to use the drag & drop feature, you then need to check the dragged & dropped image moves in source afterwards to be correctly placed within the gallery block container.');

@define('IMAGE_LINK_TO_BIG', 'Link to the larger image');

@define('MAINTENANCE_COLUMN_SORTNOTE', 'This page uses a variable block column sorting; It may change the column block order dynamically by expanding block content.');

@define('UTF8MB4_MIGRATION_TITLE', 'Database: UTF-8-MB4 migration');
@define('UTF8MB4_MIGRATION_ERROR', 'An error occurred with the UTF-8 migration:<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>%s</em><br>Do <b>not</b> continue without having solved the error!');
@define('UTF8MB4_MIGRATION_TASK_RETURN', 'The migration task returned:');
@define('UTF8MB4_MIGRATION_TASK_HAVE', 'The following SQL commands have been executed:');
@define('UTF8MB4_MIGRATION_TASK_CAN', 'The following SQL commands can be executed:');
@define('UTF8MB4_MIGRATION_TASK_DONE_SHORT', 'UTF-8-MB4 charset');
@define('UTF8MB4_MIGRATION_TASK_DONE', 'Your Blog is using the UTF-8 charset with Multibyte-Extension.');
@define('UTF8MB4_MIGRATION_INFO', 'When using Serendipity Styx 2.4+ with MySQLi and UTF-8 charsets (this is the default), the database tables and indexes can be migrated from UTF-8 to UTF-8 with Multibyte-Extension, to also support unicode characters outside the Basic Multilingual Plane (BMP), such as <b>Emojis</b>. Utf8mb4 is a superset of utf8.');
@define('UTF8MB4_MIGRATION_BUTTON_CHECK', 'Simulate / Check');
@define('UTF8MB4_MIGRATION_BUTTON_EXECUTE', 'Execute');
@define('UTF8MB4_MIGRATION_INFO_DESC', 'This task will allow you to perform an upgrade from UTF-8 to UTF8MB4. This will also automatically try to adjust index sizes and issue converting MySQL server commands.
<p>This is a task that can probably fail to properly convert your data, so be sure to make a full SQL backup before you perform the upgrade!</p>
Please run the simulation first, to get a list of SQL statements, that will be issued on your installation, and make sure there are no (red) errors in the simulation or first execution before continuing.
Run the <b>executor</b> [execute] task as long it appears, to fully convert the database tables and re-set the new dbCharset to "utf8mb4" in your configuration file. Do not reload your browser tab. You are finished with a <b>green</b> success message!');
@define('UTF8MB4_MIGRATION_FAIL', 'Your current installation either does not use the UTF-8 charset already, does not use the MySQLi driver, or the server version is lower than 5.5.3 and does not support UTF8MB4. To be able to use UTF8MB4, make sure your Blog is configured for the UTF-8 charset, and make sure existing data is also converted to UTF-8 (by using a tool like mysqldump to export, convert to UTF-8 and import).');

#@define('MEDIA_THUMBURL_REPLACE_ENTRY', 'On Non-MySQL databases, iterating through every article to replace the old thumbSuffix URLs with the new thumbSuffix URLs is <b>not</b> possible. You will need to manually edit your entries to fix these old URLs. You can still rename your thumbSuffix back to the old name, or just live with all current stored suffixes (see above), if that is too cumbersome for you.');

@define('HTML_COMMENTS', 'Allow HTML comments');
@define('HTML_COMMENTS_DESC', 'If the RICH Text editor (WYSIWYG) option in personal preferences is set true, you may additionally allow tag-restricted HTML comments and "pre/code" tag parts displayed in backend and frontend pages, but edited by Editor in backend only. Keep in mind: This options liberates old comments to display their content. So better check them up before (!), that you don\'t have accidentally approved spoofed content in your database stored comments. Otherwise, RICH Text editor comments are also suitable to get rid of annoying bot program spammers. This option currently only works completely with the standard "Pure" theme and a few others and is therefore only recommended there!');

@define('CORE_THEMES', 'Styx Core Themes');
@define('THEMEMANAGER', 'Theme Cleanup Manager');
@define('THEMEMANAGER_ZOMB_OK', 'Well done! Chosen themes purged!');
@define('THEMEMANAGER_LOCALTHEMES', 'Check old local themes');
@define('THEMEMANAGER_SUBMIT', 'Purge selected themes');
@define('THEMEMANAGER_INFO', 'Multi-select and purge old theme zombies. Make Styx happy and delete this old crap! :) If you ever need it again just load it with Spartacus, to get the last maintained fresh version. On the other hand, don\'t do this when having changed anything within such theme without having a backup!');

@define('GALLERY_ORIENTATION', 'Gallery Orientation');
@define('GALLERY_ORIENTATION_PERCOL', 'Thumb item order per <b>column</b>, arranged vertical (recommended). No size restriction!');
@define('GALLERY_ORIENTATION_PERROW', 'Thumb item order per <b>row</b>, left to right. Max-width: 260px restriction.');
@define('GALLERY_ORIENTATION_STRICTCOL', 'Strict <b>columns</b> depend containers content size:');

@define('ADDITIONAL_PROPERTIES_BY_PLUGIN', 'Additional properties by Plugin: %s');

@define('PLUGINMANAGER', 'Plugin Cleanup Manager');
@define('PLUGINMANAGER_ZOMB_OK', 'Well done! Old plugin zombies purged!');
@define('PLUGINMANAGER_LOCALPLUGINS', 'Check for local plugin zombies');
@define('PLUGINMANAGER_SUBMIT', 'Purge plugin zombies');
@define('PLUGINMANAGER_INFO', 'Often there are older systems with plugins that were never activated or stored as local zombies in the database and thus never synchronized. Such plugins appear in the list as local plugins when searching for new plugins to install, although a newer version might be found on Spartacus. Such plugin zombies should be deleted so that the latest version can always be accessed in case of need. Sometimes, however, these are developer versions that have only fallen into oblivion. So think carefully before deleting marked plugins.');

@define('MEDIA_RENAME_ERROR_RELOAD', 'If you see this message there was probably an error message and something could not be done. To be able to undo any partial changes you may have made, the current MediaLibrary page is not reloaded by the following button. This means that the same action can be repeated directly with the old name so that the successing changes are back to the old state. On the other hand, partial changes that have already been made only become visible when you reload the current page manually! It is a question of which option you give priority to. The former is recommended, even if the error message probably repeats itself similarly, in order to only then trace the cause of the actual error message. Copy the error message for the subsequent debugging!');

@define('THUMBFILE_SIZE', 'Thumb size');
@define('PATH', 'Path');

@define('MEDIA_EXTENSION_FAILURE', 'The uploaded file "<b>%s</b>" was identified as a mime originating "<b>%s</b>" file, with a media library database stored extension name of "<b>%s</b>". This extension name has a length format of "%s", being <b>greater</b> than the allowed max extension length of "%s" for image files. Maybe it is not in the right format for <b>web based</b> image files ("bmp", "gif", "jpg", "jpeg", "png", "tiff", "webp", "avif")?');
@define('MEDIA_EXTENSION_FAILURE_REPAIR', 'Please change the file manually in your "uploads/" file system and run a sync for thumbnail creation in the backends "Maintenance" section afterwards, to make it available as a valid image. Deleting the file via the MediaLibrary and additionally uploading a corrected file version will do too, but it shatters the incremental ID counter of the database and is therefore not necessarily recommended.');

@define('SYNC_OPTION_BUILDVARIATIONS', 'Build Image Picture-Element Format-Variations');
@define('SYNC_OPTION_PURGEVARIATIONS', 'Purge all Image Picture-Element Format-Variations');
@define('SYNC_BUILD_VARIATIONS', 'Build Picture-Element Format-Variations');
@define('SYNC_PURGED_VARIATIONS', 'Purged Picture-Element Format-Variation files');
@define('SYNC_VARIATION_ITERATION_LIST_TITLE', 'Variations Image list iteration for purge request:');
@define('SYNC_IMAGE_LIST_ITERATION_RANGE_PART', 'Image list iteration part: <b>%s</b> of <b>"%s"</b> items in total');
@define('SYNC_IMAGE_LIST_ITERATION_RANGE_DONE', 'Iteration <b>%s</b> %s. <b>%s</b> items have been successfully created.');

@define('PLEASESELECT', 'Please select');
@define('XOR', 'Either / Or');
@define('FORMATS', 'Image format');
@define('VARIATION', 'Image variation');
@define('MEDIA_PROPERTIES_SELECT_INFO_DESC', 'If a files selection change is necessary: Either use the directory <b>OR</b> the Image format selection change per submit.<br>You cannot change both at the same time! This will also not work if a filename with this new format already exists. Make sure to have this checked before!');
@define('MEDIA_PROPERTIES_FORMAT_VARIATIONS', '<b>CAREFULLY NOTE FOR WEBP / AVIF FORMAT CASES:</b><br>ORIGIN files format variations probably already exist and are not affected by this change. This action here turns your ORIGIN file and Thumbnail into the variation format, but uses a quality level of 100% when using the "GD Lib", to avoid any quality loss. (<em>On the other hand the ImageMagick Library is set to "auto" here.</em>) For this very reason you should not use WebP/AVIF formats for <b>GD</b> cases, where your existing file format has already been optimized for the Web. In this case formatting to variation formats will probably just blow up the files filesize.');

@define('ENTRY_QUICKPIN', 'Set as a temporary Quick-Pin to the entries list');

@define('COMMENT_CHANGE_PARENT_INFO', 'Be careful changing the replyTo comment.<br><strong>Know</strong> what you do! (Check the Link c# ID)');

@define('ERROR_TRY_ANOTHER_USERNAME', 'Please try another username');
@define('ERROR_TRY_ANOTHER_GROUPNAME', 'Please try another groupname');
@define('ERROR_DONT_SHOOT_YOURSELF', 'You should never delete yourself: %s: %s, %s.');
@define('ERROR_DONT_CUT_YOUR_WHINEYARD', 'You should never delete the highest GROUP LEVEL you are in: %s: %s.');

@define('MEDIA_SERVE_INFO', 'Serve media buttons description info');
@define('PICTURE_FORMAT_BUTTON_DESC', 'Simple img element - <b>vs</b> - The modern & recommended & containerized form of delivering images including Variations! Normally called "responsive images" container, but here used for responsive Variation formats!');

@define('ENABLEAVIF', 'Enable use of AVIF Variations up from PHP 8.1');
@define('ENABLEAVIF_DESC', 'Image AVIF variations can be very demanding on resources, since a lot of Ram and CPU/GPU cores are needed to encode images into the AV1 format. Mass uploads and mass conversions (see "Maintenance") are therefore not recommended. Learn to handle on some examples before you generally allow to keep it enabled. PHP 8.1 still lacks a crucial build-in feature to read size information from AVIF files using the usual methods. For the time being, this also means that the image functions of the MediaLibrary "Resize this image" and "Rotate image 90 degrees" cannot be used for all formats when using AVIF, since each of these actions affects the original image as well as its variations. PHP 8.2 solves this issue by adding the missing feature.');

