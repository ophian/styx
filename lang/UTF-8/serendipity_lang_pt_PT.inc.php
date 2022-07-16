<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details
# Translation for european portuguese (c) by J P Matos <jmatos@math.ist.utl.pt>
# based on work (c) by Ranulfo Netto <rcnetto@yahoo.com>
# and comparison with the work (c) by Agner Olson <agner@agner.net>
# and even more work from Angel pticore@users.sourceforge.net
/* vim: set sts=4 ts=4 expandtab : */

@define('LANG_CHARSET', 'UTF-8');
@define('SQL_CHARSET', 'utf8');
@define('DATE_LOCALES', 'pt_PT.UTF-8, pt_PT.UTF8, pt_PT, european portuguese, pt');
@define('DATE_FORMAT_ENTRY', '%A, %e de %B de %Y');
@define('DATE_FORMAT_SHORT', '%Y-%m-%d %H:%M');
@define('WYSIWYG_LANG', 'pt_PT');
@define('NUMBER_FORMAT_DECIMALS', '2');
@define('NUMBER_FORMAT_DECPOINT', ',');
@define('NUMBER_FORMAT_THOUSANDS', '.');
@define('LANG_DIRECTION', 'ltr');

@define('SERENDIPITY_ADMIN_SUITE', 'Painel de Administração do Serendipity Styx');
@define('HAVE_TO_BE_LOGGED_ON', 'Tem que efectuar login no sistema para visualizar esta página');
@define('WRONG_USERNAME_OR_PASSWORD', 'Deve ter fornecido um nome de utilizador, senha ou dados inválidos');
@define('APPEARANCE', 'Aparência');
@define('MANAGE_STYLES', 'Gerir estilos');
@define('CONFIGURE_PLUGINS', 'Configurar Plugins');
@define('CONFIGURATION', 'Configuração');
@define('BACK_TO_BLOG', 'Regressar ao Weblog');
@define('LOGIN', 'Login'); //
@define('LOGOUT', 'Sair'); // Verify
@define('LOGGEDOUT', 'Saída completada.'); // Verify
@define('CREATE', 'Criar');
@define('CREATE_NEW_CAT', 'Criar uma nova categoria');
@define('I_WANT_THUMB', 'Quero usar miniaturas no meu artigo.');
@define('I_WANT_BIG_IMAGE', 'Quero usar a maior imagem no meu artigo.');
@define('I_WANT_NO_LINK', ' Quero mostrá-la como imagem');
@define('I_WANT_IT_TO_LINK', 'Quero mostrá-la como uma ligação para este URL:');
@define('BACK', 'Volta');
@define('FORWARD', 'Encaminhar');
@define('ANONYMOUS', 'Anónimo');
@define('NEW_TRACKBACK_TO', 'Novo trackback feito para');
@define('NEW_COMMENT_TO', 'Novo comentário enviado para');
@define('RECENT', 'Recentes...');
@define('OLDER', 'Mais antigos...');
@define('DONE', 'Pronto');
@define('WELCOME_BACK', 'Bem vindo de volta,');
@define('TITLE', 'Título');
@define('DESCRIPTION', 'Descrição');
@define('PLACEMENT', 'Localização');
@define('DELETE', 'Apagar');
@define('SAVE', 'Guardar');
@define('UP', 'Para cima');
@define('DOWN', 'Para baixo');
@define('ENTRIES', 'Artigos:');
@define('NEW_ENTRY', 'Novo artigo');
@define('EDIT_ENTRIES', 'Editar artigos');
@define('CATEGORIES', 'Categorias');
@define('IMAGESYNC_WARNING', 'ATENÇÃO:<br>Isso pode demorar muito tempo se existirem muitas imagens sem miniaturas. Particularly with migrations of old blogs, further preliminary considerations and knowledge are necessary! Read about it on <a href="https://ophian.github.io/hc/en/media-migration-tasks.html" target="_new">this help page</a>, carefully.');
@define('CREATE_THUMBS', 'Reconstruir miniaturas');
@define('MANAGE_IMAGES', 'Gerir imagens');
@define('NAME', 'Nome');
@define('EMAIL', 'E-mail');
@define('HOMEPAGE', 'Página pessoal');
@define('COMMENT', 'Comentário');
@define('REMEMBER_INFO', 'Relembrar Informações? ');
@define('SUBMIT_COMMENT', 'Enviar Comentário');
@define('NO_ENTRIES_TO_PRINT', 'Nenhum artigo para imprimir');
@define('COMMENTS', 'Comentários');
@define('ADD_COMMENT', 'Adicionar Comentários');
@define('NO_COMMENTS', 'Nenhum comentário');
@define('POSTED_BY', 'Publicado por');
@define('ON', 'em');
@define('A_NEW_COMMENT_BLAHBLAH', 'Um novo comentário foi enviado para o seu Blog "%s", no artigo entitulado "%s".');
@define('A_NEW_TRACKBACK_BLAHBLAH', 'Um novo trackback foi feito para o seu Blog "%s", no artigo entitulado "%s".');
@define('NO_CATEGORY', 'Sem categoria');
@define('ENTRY_BODY', 'Corpo do artigo');
@define('EXTENDED_BODY', 'Extensão do corpo do artigo');
@define('CATEGORY', 'Categoria:');
@define('EDIT', 'Editar');
@define('NO_ENTRIES_BLAHBLAH', 'Nenhum artigo encontrado para a consulta %s');
@define('YOUR_SEARCH_RETURNED_BLAHBLAH', 'A sua busca de %s obteve %s resultados:');
@define('IMAGE', 'Imagem');
@define('ERROR_FILE_NOT_EXISTS', 'Erro: O nome de ficheiro antigo não existe!');
@define('ERROR_FILE_EXISTS', 'Erro: O novo nome de ficheiro já existe, escolha outro!');
@define('ERROR_SELECTION', 'Error: Changing both selection in media properties at the same time is not allowed. Go back and try again!');
@define('ERROR_SOMETHING', 'Erro: Problema desconhecido.');
@define('ADDING_IMAGE', 'Adicionando imagem...');
@define('THUMB_CREATED_DONE', 'Miniatura %s criada.<br>Pronto.');
@define('ERROR_FILE_EXISTS_ALREADY', 'Erro: O ficheiro já existe no seu servidor!');
@define('NOT_AVAILABLE', 'N/A'); // short!
@define('GO', 'Vamos!');
@define('NEWSIZE', 'Novo tamanho: ');
@define('RESIZE_BLAHBLAH', 'Redimensionar %s');
@define('ORIGINAL_SIZE', 'Tamanho original: <i>%sx%s</i> pixéis');
@define('HERE_YOU_CAN_ENTER_BLAHBLAH', 'Aqui pode ajustar o novo tamanho das suas imagens. Se pretender manter as proporções, preencha apenas um valor e pressione a tecla TAB de maneira a ser automaticamente calculado o novo tamanho de modo às proporções não ficarem erradas.<br><b>PLEASE NOTE:</b> This is not a high end image editor resizing tool, finetuned for the need of a specific image.<br>Every scale returns with a more or less increasing loss of image quality compared to the origin input file. And this increases with each further scaling!<br><b>VARIATION:</b> Since we assume you <b>keep</b> the files proportion, a scaled image "format" variation ["image.avif" and/or "image.webp"] change will be applied to the Origin files variation only and <b>NOT</b> to the variation thumbnail, which - by certain image property conditions - would probably blow up its filesize. If you really need an image scale with an <b>other</b> proportion <b>and</b> an additional changed variation thumb dimension size, activate the "<em>..thumb variation</em>" checkbox.');
@define('SCALE_THUMB_VARIATION', 'Force scaled thumb variation changes');
@define('QUICKJUMP_CALENDAR', 'Calendário de acesso rápido');
@define('QUICKSEARCH', 'Pesquisa rápida');
@define('SEARCH_FOR_ENTRY', 'Procure um artigo');
@define('ARCHIVES', 'Arquivos');
@define('BROWSE_ARCHIVES', 'Navegar nos arquivos por mês');
@define('TOP_REFERRER', 'Referenciadores mais importantes');
@define('SHOWS_TOP_SITES', 'Exibe os links de entrada mais utilizados para aceder no seu blogue');
@define('TOP_EXITS', 'Saídas Maiores');
@define('SHOWS_TOP_EXIT', 'Exibe os links de saída mais requisitados de seu blogue');
@define('SYNDICATION', 'Sindicalização');
@define('SHOWS_RSS_BLAHBLAH', 'Mostrar ligações de sindicalização RSS');
@define('ADVERTISES_BLAHBLAH', 'Publicita a origem do seu blogue');
@define('HTML_NUGGET', 'Fragmento de HTML');
@define('HOLDS_A_BLAHBLAH', 'Apresenta um fragmento de HTML na sua barra lateral');
@define('TITLE_FOR_NUGGET', 'Título para o fragmento de HTML');
@define('THE_NUGGET', 'Fragmento de HTML!');
@define('SUBSCRIBE_TO_BLOG', 'Inscreva-se neste Blogue');
@define('YOU_CHOSE', 'Escolheu %s');
@define('FILE_SIZE', 'File size');// keep short!
@define('IMAGE_SIZE', 'Tamanho da imagem');
@define('IMAGE_AS_A_LINK', 'Inserção de imagem');
@define('POWERED_BY', 'Equipado com');
@define('TRACKBACKS', 'Trackbacks');
@define('TRACKBACK', 'Trackback');
@define('NO_TRACKBACKS', 'Nenhuns Trackbacks');
@define('TOPICS_OF', 'Tópicos de');
@define('VIEW_FULL', 'ver tudo');
@define('VIEW_TOPICS', 'ver tópicos');
@define('AT', 'às');
@define('SET_AS_TEMPLATE', 'Definir como modelo');
@define('IN', 'em');
@define('EXCERPT', 'Excerto');
@define('TRACKED', 'Tracked');
@define('LINK_TO_ENTRY', 'Ligação para o artigo');
@define('LINK_TO_REMOTE_ENTRY', 'Ligação para artigo remoto');
@define('IP_ADDRESS', 'Endereço IP');
@define('USER', 'Utilizador');
@define('THUMBNAIL_USING_OWN', 'Usando %s como a sua própria miniatura devido ao seu pequeno tamanho.');
@define('THUMBNAIL_FAILED_COPY', 'A tentativa de usar %s como a sua própria miniatura falhou devido a um erro na cópia!');
@define('AUTHOR', 'Autor');
@define('LAST_UPDATED', 'Última actualização');
@define('TRACKBACK_SPECIFIC', 'URI específica do trackback para este artigo');
@define('DIRECT_LINK', 'Ligação directa para este artigo');
@define('COMMENT_ADDED', 'O seu comentário foi adicionado correctamente.');
@define('COMMENT_ADDED_CLICK', 'Clique %saqui para retornar%s aos comentários, ou %saqui para fechar%s esta janela.');
@define('COMMENT_NOT_ADDED_CLICK', 'Clique %saqui para retornar%s aos comentários, ou %saqui para fechar%s esta janela.');
@define('COMMENTS_DISABLE', 'Não são permitidos comentários neste artigo');
@define('COMMENTS_ENABLE', 'São permitidos comentários neste artigo');
@define('COMMENTS_CLOSED', 'O autor não autorizou comentários deste artigo');
@define('EMPTY_COMMENT', 'O seu comentário está vazio, por favor, %svolte%s e tente novamente');
@define('ENTRIES_FOR', 'Artigos para %s');
@define('DOCUMENT_NOT_FOUND', 'O documento %s não foi encontrado.');
@define('USERNAME', 'Utilizador');
@define('PASSWORD', 'Senha');
@define('AUTOMATIC_LOGIN', 'Guardar informações');
@define('SERENDIPITY_INSTALLATION', 'Instalação do Serendipity');
@define('LEFT', 'esquerda');
@define('RIGHT', 'direita');
@define('HIDDEN', 'oculto');
@define('REMOVE_TICKED_PLUGINS', 'Remover plugins marcados');
@define('SAVE_CHANGES_TO_LAYOUT', 'Guardar modificações ao layout');
@define('REQUIRED_FIELD', 'Required field');
@define('COMMENTS_FROM', 'Comentários de');
@define('ERROR', 'Erro');
@define('ENTRY_SAVED', 'Artigo guardado');
@define('DELETE_SURE', 'Tem a certeza que deseja excluir #%s definitivamente?');
@define('NOT_REALLY', 'Não mesmo...');
@define('DUMP_IT', 'Eliminá-lo!');
@define('RIP_ENTRY', 'Destruir artigo #%s');
@define('CATEGORY_DELETED', 'Categoria #%s apagada. Os artigos antigos foram movidos para a categoria #%s');
@define('INVALID_CATEGORY', 'Nenhuma categoria fornecida para exclusão');
@define('CATEGORY_SAVED', 'Categoria guardada');
@define('SELECT_TEMPLATE', 'Selecione o modelo pretendido para o seu Blogue');
@define('ENTRIES_NOT_SUCCESSFULLY_INSERTED', 'Artigos não adicionados!');
@define('YES', 'Sim');
@define('NO', 'Não');
@define('USE_DEFAULT', 'Por omissão');
@define('CHECK_N_SAVE', 'Verificar &amp; guardar');
@define('DIRECTORY_WRITE_ERROR', 'Não foi possível escrever na directoria %s. Verifique as permissões.');
@define('DIRECTORY_CREATE_ERROR', 'A directoria %s não existe e não pode ser criada. Tente criá-la manualmente');
@define('DIRECTORY_RUN_CMD', '&nbsp;-&gt; execute <i>%s %s</i>');
@define('CANT_EXECUTE_BINARY', 'Não foi possível executar o binário %s');
@define('CANT_EXECUTE_EXTENSION', 'Cannot execute the %s extension library. Please allow in PHP.ini or load the missing module via servers package manager.');
@define('FILE_WRITE_ERROR', 'Não foi possível editar o ficheiro %s.');
@define('FILE_CREATE_YOURSELF', 'Por favor, crie você mesmo o ficheiro ou verifique as suas permissões');
@define('COPY_CODE_BELOW', '<br>* Copie o código abaixo e o coloque em %s na sua %s directoria:<b><pre>%s</pre></b>' . "\n");
@define('WWW_USER', 'Altere www para o utilizador com o qual o apache é executado (ex.: ninguêm).');
@define('BROWSER_RELOAD', 'Uma vez feito isso, pressione o botão "actualizar" ("reload") do seu navegador.');
@define('RELOAD_THIS_PAGE', 'Please reload this <a href="%s">%s</a> page to fetch the changed values before submitting again!');
@define('DIAGNOSTIC_ERROR', 'Detectámos alguns erros quando verificámos as informações que forneceu:');
@define('SERENDIPITY_NOT_INSTALLED', 'Serendipity não se encontra instalado. Por favor <a href="%s">instale-o</a> agora.');
@define('INCLUDE_ERROR', 'Erro do Serendipity: não foi possível incluir %s - abortando.');
@define('DATABASE_ERROR', 'Erro do Serendipity: não foi possível conectar-se à base de dados - abortando.');
@define('CREATE_DATABASE', 'Criando a instalação padrão da base de dados...');
@define('ATTEMPT_WRITE_FILE', 'Tentando editar o ficheiro %s...');
@define('WRITTEN_N_SAVED', 'Configuração escrita &amp; guardada');
@define('IMAGE_ALIGNMENT', 'Alinhamento de imagem');
@define('ENTER_NEW_NAME', 'Indique um nome novo: ');
@define('RESIZING', 'Redimensionando');
@define('RESIZE_DONE', 'Pronto (%s imagens redimensionadas).');
@define('SYNCING', 'Sincronizando a base de dados com a directoria de imagens');
@define('SYNC_OPTION_LEGEND', 'Thumbnail Synchronization Options');
@define('SYNC_OPTION_KEEPTHUMBS', 'Keep all existing thumbnails');
@define('SYNC_OPTION_SIZECHECKTHUMBS', 'Keep existing thumbnails only if they are the correct size');
@define('SYNC_OPTION_DELETETHUMBS', 'Regenerate all (<em>*.%s</em>) thumbnails');
@define('SYNC_OPTION_CONVERTTHUMBS', 'Convert old existing thumbnail names');
@define('SYNC_OPTION_CONVERTTHUMBS_INFO', 'WARNING: This option is not active, as long the thumbSuffix has not changed.<br>It converts existing thumbnails, which are not named by the current thumbSuffix-scheme: <em>*.%s</em>, in the database, the filesystem and already used in entries to the same suffix naming scheme. This can take long! <b>It does not matter keeping them as is</b>, but to include them for the "Regenerate all" option, you need to do this first.');
@define('SYNC_DONE', 'Pronto (%s imagens sincronizadas).');
@define('FILE_NOT_FOUND', 'Não foi possível localizar o ficheiro entitulado <b>%s</b>, será que foi apagado?');
@define('ABORT_NOW', 'Cancelar agora');
@define('REMOTE_FILE_NOT_FOUND', 'O arquivo não foi localizado no servidor, tem a certeza que a URL: <b>%s</b> está correcta?');
@define('FILE_FETCHED', '%s obtido como %s');
@define('FILE_UPLOADED', 'O arquivo %s foi transferido correctamente: %s');
@define('WORD_OR', 'Ou');
@define('SCALING_IMAGE', 'Redimensionando %s para %s x %s px');
@define('FORCE_RELOAD', 'With certain image characteristics it can occasionally happen that the old image is still present in the browser cache. If so, check into the MediaLibrary again and force a hard reload of your browser [Ctrl]+[F5], to actually see the scaled image.');
@define('KEEP_PROPORTIONS', 'Manter proporções');
@define('REALLY_SCALE_IMAGE', 'Quer mesmo redimensionar a imagem? Não é possível desfazer!!');
@define('TOGGLE_ALL', 'Alternar para expandir tudo');
@define('TOGGLE_OPTION', 'Opção para alternar');
@define('SUBSCRIBE_TO_THIS_ENTRY', 'Subscrever a este artigo');
@define('UNSUBSCRIBE_OK', "%s teve a subscrição deste artigo cancelada");
@define('NEW_COMMENT_TO_SUBSCRIBED_ENTRY', 'Novo comentário no artigo subscrito "%s"');
@define('SUBSCRIPTION_MAIL', "Olá %s,\n\nUm novo comentário foi acrescentado a um artigo que você está seguindo em \"%s\", intitulado \"%s\"\nO comentário foi enviado por: %s\n\nO artigo a que nos referimos pode ser encontrado em: %s\n\nPode cancelar sua subscrição clicando esta ligação: %s\n");
@define('SUBSCRIPTION_TRACKBACK_MAIL', "Olá %s,\n\nUm novo trackback foi adicionado num artigo que você está seguindo em \"%s\", intitulado \"%s\"\nO comentário foi enviado por: %s\n\nO artigo a que nos referimos pode ser encontrada em: %s\n\nPode cancelar a sua subscrição clicando nesta ligação: %s\n");
@define('SIGNATURE', "\n-- \n%s faz uso do %s.\nO melhor Blogue que existe, e você também pode usá-lo.\nConsulte <%s> para descobrir como.");
@define('SYNDICATION_PLUGIN_20', 'RSS 2.0 feed');
@define('SYNDICATION_PLUGIN_20c', 'RSS 2.0 comentários');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR', 'Campo "managingEditor"');
@define('SYNDICATION_PLUGIN_WEBMASTER', 'Campo "webMaster"');
@define('SYNDICATION_PLUGIN_BANNERURL', 'Imagem para o RSS feed');
@define('SYNDICATION_PLUGIN_BANNERWIDTH', 'Largura da imagem');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT', 'Altura da imagem');
@define('SYNDICATION_PLUGIN_WEBMASTER_DESC', 'E-mail do webmaster, se disponível. (vazio: oculto) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR_DESC', 'E-mail do editor, se disponível. (vazio: oculto) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_BANNERURL_DESC', 'URL de uma imagem no formato GIF/JPEG/PNG, se disponível. (vazio: logotipo do serendipity)');
@define('SYNDICATION_PLUGIN_BANNERWIDTH_DESC', 'em pixéis, max. 144');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT_DESC', 'em pixéis, max. 400');
@define('SYNDICATION_PLUGIN_TTL', 'Campo "ttl" (tempo de vida)');
@define('SYNDICATION_PLUGIN_TTL_DESC', 'Quantidade de minutos depois do qual o seu blogue não deverá ser mais "cacheado" por sítios ou aplicações (vazio: oculto) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_PUBDATE', 'Campo "pubDate"');
@define('SYNDICATION_PLUGIN_PUBDATE_DESC', 'O campo "pubDate" deve ser empacotado para o canal RSS, para mostrar a data do último artigo?');
@define('CONTENT', 'Conteúdo');
@define('TYPE', 'Tipo');
@define('DRAFT', 'Rascunho');
@define('PUBLISH', 'Publicar');
@define('PREVIEW', 'Pré-visualização');
@define('ALL_ENTRIES', 'All entries');
@define('DATE', 'Data');
@define('DATE_FORMAT_2', 'Y-m-d H:i'); // Needs to be ISO 8601 compliant for date conversion!
@define('DATE_INVALID', 'ATENÇÃO: A data que você especificou é inválida. Deve ser fornecida no formato inglês AAAA-MM-DD HH:MM.');
@define('CATEGORY_PLUGIN_DESC', 'Mostra a listagem de categorias.');
@define('ALL_AUTHORS', 'Todos os Autores');
@define('CATEGORIES_TO_FETCH', 'Categorias para trazer');
@define('CATEGORIES_TO_FETCH_DESC', 'Trazer categorias de que Autor?');
@define('PAGE_BROWSE_ENTRIES', 'Página %s de %s, totalizando %s artigos');
@define('PAGE', 'Page');
@define('PREVIOUS_PAGE', 'página anterior');
@define('NEXT_PAGE', 'página seguinte');
@define('ALL_CATEGORIES', 'Todas as categorias');
@define('DO_MARKUP', 'Executar transformações de marcação');
@define('DO_MARKUP_DESCRIPTION', 'Aplicar transformações nos códigos do texto (smilies, abreviaturas via *, /, _, ...). Desligando esta função irá preservará qualquer código HTML no texto.');
@define('GENERAL_PLUGIN_DATEFORMAT', 'Formatação de data');
@define('GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH', 'Formato da data actual do artigo, usando a mesma formatação da função strftime() do PHP. (Padrão: "%s")');
@define('ERROR_TEMPLATE_FILE', 'Não foi possível abrir o ficheiro modelo, por favor actualize o Serendipity!');
@define('ADVANCED_OPTIONS', 'Opções avançadas');
@define('EDIT_ENTRY', 'Editar artigo');
@define('HTACCESS_ERROR', 'Para verificar a instalação do servidor web, Serendipity necessita ter o poder para criar o arquivo ".htaccess". Isso não foi possível devido a erros nas permissões. Por favor, ajuste as permissões para: <br>&nbsp;&nbsp;%s<br>e recarregue esta página.');
@define('SIDEBAR_PLUGINS', 'Plugins da barra lateral');
@define('EVENT_PLUGINS', 'Plugins de eventos');
@define('SORT_ORDER', 'Ordenação');
@define('SORT_ORDER_NAME', 'Nome do ficheiro');
@define('SORT_ORDER_EXTENSION', 'Extensão do ficheiro');
@define('SORT_ORDER_SIZE', 'Tamanho do ficheiro');
@define('SORT_ORDER_WIDTH', 'Largura da imagem');
@define('SORT_ORDER_HEIGHT', 'Altura da imagem');
@define('SORT_ORDER_DATE', 'Data de transferência');
@define('SORT_ORDER_ASC', 'Ascendente');
@define('SORT_ORDER_DESC', 'Descendente');
@define('THUMBNAIL_SIZE', 'Thumb size'); // keep short
@define('THUMBNAIL_SHORT', 'Miniatura');
@define('ORIGINAL_SHORT', 'Origin');
@define('APPLY_MARKUP_TO', 'Aplicar código a %s');
@define('CALENDAR_BEGINNING_OF_WEEK', 'Começo da semana');
@define('SERENDIPITY_NEEDS_UPGRADE', 'O Serendipity detectou que a configuração está de acordo com a versão %s, embora a versão %s de Serendipity esteja instalada. Precisa de actualizar o Serendipity! <a href="%s">Clique aqui</a>');
@define('SERENDIPITY_UPGRADER_WELCOME', 'Olá, e bem-vindo ao agente de actualização do Serendipity.');
@define('SERENDIPITY_UPGRADER_PURPOSE', 'Estou aqui para ajudá-lo a actualizar na instalação %s do seu Serendipity.');
@define('SERENDIPITY_UPGRADER_WHY', 'Você está a ver esta mensagem porque acabou de instalar o Serendipity %s, mas ainda não actualizou a instalação da base de dados para compatibilizar com esta versão');
@define('SERENDIPITY_UPGRADER_DATABASE_UPDATES', 'Actualização da base de dados (%s)');
@define('SERENDIPITY_UPGRADER_FOUND_SQL_FILES', 'Encontrei os seguintes arquivos .sql u]que precisam ser executados antes que você prossiga com a utilização normal do Serendipity');
@define('SERENDIPITY_UPGRADER_VERSION_SPECIFIC', 'Tarefas específicas da versão');
@define('SERENDIPITY_UPGRADER_NO_VERSION_SPECIFIC', 'Nenhuma tarefa específica da versão foi encontrada');
@define('SERENDIPITY_UPGRADER_PROCEED_QUESTION', 'Quer realizar as tarefas acima?');
@define('SERENDIPITY_UPGRADER_PROCEED_ABORT', 'Não, eu as executarei manualmente');
@define('SERENDIPITY_UPGRADER_PROCEED_DOIT', 'Por favor, faça isso');
@define('SERENDIPITY_UPGRADER_NO_UPGRADES', 'Parece que que você não precisa executar nenhuma actualização');
@define('SERENDIPITY_UPGRADER_CONSIDER_DONE', 'Considere a actualização do Serendipity');
@define('SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED', 'Você ignorou a fase de actualização do Serendipity, certifique-se de que a base de dados está instalada correctamente e que as funções necessárias foram executadas');
@define('SERENDIPITY_UPGRADER_NOW_UPGRADED', 'A sua instalação do Serendipity foi actualizada para a versão %s');
@define('SERENDIPITY_UPGRADER_RETURN_HERE', 'Pode retornar ao seu Blogue clicando %saqui%s');

@define('MANAGE_USERS', 'Gerir utilizadores');
@define('CREATE_NEW_USER', 'Criar novo utilizador');
@define('CREATE_NOT_AUTHORIZED', 'Você não pode modificar utilizadores que tenham o mesmo nível que o seu');
@define('CREATE_NOT_AUTHORIZED_USERLEVEL', 'Você não pode criar utilizadores com um nível maior que o seu');
@define('CREATED_USER', 'Um novo utilizador %s foi criado');
@define('MODIFIED_USER', 'As propriedades do utilizador "%s" foram alteradas');
@define('USER_LEVEL', 'Nível do utilizador');
@define('DELETE_USER', 'Você está prestes a suprimir o utilizador #%d %s. Tem a certeza disso? Isso fará com que os artigos escritos por ele deixem de ser consultáveis.');
@define('DELETED_USER', 'Utilizador #%d %s excluído.');
@define('LIMIT_TO_NUMBER', 'Quantos itens devem ser apresentados?');
@define('ENTRIES_PER_PAGE', 'artigos por página');
@define('XML_IMAGE_TO_DISPLAY', 'Botão XML');
@define('XML_IMAGE_TO_DISPLAY_DESC','Links para XML Feeds serão exibidos por esta imagem. Deixe em branco para padrão, digite \'none\' para tornar inactivo.');

@define('DIRECTORIES_AVAILABLE', 'Na lista de subdirectorias disponíveis, você pode clicar em qualquer nome de subdirectoria para criar uma nova directoria dentro daquela estrutura.');
@define('ALL_DIRECTORIES', 'todas as directorias');
@define('MANAGE_DIRECTORIES', 'Gerir directorias');
@define('DIRECTORY_CREATED', 'Directoria <strong>%s</strong> foi criada.');
@define('PARENT_DIRECTORY', 'Directoria superior');
@define('CONFIRM_DELETE_DIRECTORY', 'Tem a certeza que quer apagar todo o conteúdo da directoria %s?');
@define('ERROR_NO_DIRECTORY', 'Erro: A Directoria %s não existe');
@define('CHECKING_DIRECTORY', 'Verificando arquivos na directoria %s');
@define('DELETING_FILE', 'Apagando ficheiro %s...');
@define('ERROR_DIRECTORY_NOT_EMPTY', 'Não foi possível remover uma directoria que não está vazia. Marque a opção "forçar apagar" se também deseja remover os ficheiros nela contidos, e volte a dar o comando. Os ficheiros existentes são:');
@define('DIRECTORY_DELETE_FAILED', 'A remoção da directoria %s falhou. Verifique as permissões ou as mensagens acima.');
@define('DIRECTORY_DELETE_SUCCESS', 'Directoria %s removida correctamente.');
@define('SKIPPING_FILE_EXTENSION', 'Ignorando ficheiro: Falta extensão em %s.');
@define('SKIPPING_FILE_UNREADABLE', 'Ignorando ficheiro: %s ilegível.');
@define('FOUND_FILE', 'Encontrado ficheiro novo/modificado: %s.');
@define('ALREADY_SUBCATEGORY', '%s já é uma subcategoria de %s.');
@define('PARENT_CATEGORY', 'Categoria superior');
@define('IN_REPLY_TO', 'Em resposta a');
@define('TOP_LEVEL', 'Nível de topo');
@define('SYNDICATION_PLUGIN_GENERIC_FEED', '%s feed');
@define('PERMISSIONS', 'Permissões');
@define('SECURITY', 'Security');
@define('INTEGRITY', 'Verify Installation Integrity');
@define('CHECKSUMS_NOT_FOUND', 'Unable to compare checksums! (No checksums.inc.php in main directory, or DEV version)');
@define('CHECKSUMS_PASS', 'All required files verified.');
@define('CHECKSUM_FAILED', '%s corrupt or modified: failed verification');

/* DATABASE SETTINGS */
@define('INSTALL_CAT_DB', 'Configurações da base de dados');
@define('INSTALL_CAT_DB_DESC', 'Aqui você pode inserir toda a informação acerca da sua base de dados. O Serendipity requer esta informação para funcionar correctamente.');
@define('INSTALL_DBTYPE', 'Tipo de base de dados');
@define('INSTALL_DBTYPE_DESC', 'Tipo de base de dados');
@define('INSTALL_DBHOST', 'Servidor de base de dados');
@define('INSTALL_DBHOST_DESC', 'Endereço/Nome do seu servidor de base de dados');
@define('INSTALL_DBUSER', 'Utilizador da base de dados');
@define('INSTALL_DBUSER_DESC', 'O nome de utilizador que liga à base de dados');
@define('INSTALL_DBPASS', 'Senha da base de dados');
@define('INSTALL_DBPASS_DESC', 'A senha correspondente ao utilizador acima');
@define('INSTALL_DBNAME', 'Nome da base de dados');
@define('INSTALL_DBNAME_DESC', 'O nome da base de dados');
@define('INSTALL_DBPREFIX', 'Prefixo para as tabelas da base de dados');
@define('INSTALL_DBPREFIX_DESC', 'Prefixo utilizado para designar as tabelas, ex.: serendipity_');

/* PATHS */
@define('INSTALL_CAT_PATHS', 'Caminhos');
@define('INSTALL_CAT_PATHS_DESC', 'Os vários caminhos para directorias e ficheiros essenciais. Não se esqueça de terminar com barras no caso das directorias');
@define('INSTALL_FULLPATH', 'Caminho completo');
@define('INSTALL_FULLPATH_DESC', 'O caminho completo e absoluto para a sua instalação de Serendipity');
@define('INSTALL_UPLOADPATH', 'Caminho para o Upload');
@define('INSTALL_UPLOADPATH_DESC', 'Todos os arquivos transferidos irão parar aí, relativo ao \'Caminho completo\' - geralmente \'uploads/\'');
@define('INSTALL_RELPATH', 'Caminho relativo');
@define('INSTALL_RELPATH_DESC', 'Caminho para o Serendipity no seu navegador, geralmente \'/serendipity/\'');
@define('INSTALL_RELTEMPLPATH', 'Caminho relativo do padrão');
@define('INSTALL_RELTEMPLPATH_DESC', 'Caminho para a directoria onde estão os seus padrões - Relativo ao \'caminho relativo\'');
@define('INSTALL_RELUPLOADPATH', 'Caminho relativo do Upload');
@define('INSTALL_RELUPLOADPATH_DESC', 'Caminho para o \'uploads\' em seu navegador - Relativo ao \'caminho relativo\'');
@define('INSTALL_URL', 'URL do blogue');
@define('INSTALL_URL_DESC', 'URL base para a instalação do Serendipity');
@define('INSTALL_INDEXFILE', 'Arquivo inicial');
@define('INSTALL_INDEXFILE_DESC', 'Nome do arquivo inicial do Serendipity');

/* GENERAL SETTINGS */
@define('INSTALL_CAT_SETTINGS', 'Configurações gerais');
@define('INSTALL_CAT_SETTINGS_DESC', 'Padrão de como o Serendipity se deve comportar');
@define('INSTALL_USERNAME', 'Nome de utilizador do Administrador');
@define('INSTALL_USERNAME_DESC', 'Nome de utilizador do utilizador para o login do Administrador');
@define('INSTALL_PASSWORD', 'Senha do Administrador');
@define('INSTALL_PASSWORD_DESC', 'Senha de acesso do Administrador');
@define('INSTALL_EMAIL', 'E-mail do Administrador');
@define('INSTALL_EMAIL_DESC', 'E-mail do Administrador do blogue');
@define('INSTALL_SENDMAIL', 'Enviar e-mails ao Administrador?');
@define('INSTALL_SENDMAIL_DESC', 'Você deseja receber notificações via e-mail quando novos comentários forem incluídos nos seus artigos?');
@define('INSTALL_SUBSCRIBE', 'Permitir que os utilizadores se inscrevam nos artigos?');
@define('INSTALL_SUBSCRIBE_DESC', 'Permitir que utilizadores se inscrevam nos artigos e com isso, recebam notificações via e-mail quando novos comentários forem adicionados?');
@define('INSTALL_BLOGNAME', 'Nome do Blogue');
@define('INSTALL_BLOGNAME_DESC', 'Título do Blogue');
@define('INSTALL_BLOGDESC', 'Descrição do Blogue');
@define('INSTALL_BLOGDESC_DESC', 'Descrição');
@define('INSTALL_LANG', 'Idioma');
@define('INSTALL_LANG_DESC', 'Seleccione o idioma para o seu blogue');

/* APPEARANCE AND OPTIONS */
@define('INSTALL_CAT_DISPLAY', 'Aparência e opções');
@define('INSTALL_CAT_DISPLAY_DESC', 'Padroniza como o Serendipity é exibido');
@define('INSTALL_WYSIWYG', 'Usar editor WYSIWYG');
@define('INSTALL_WYSIWYG_DESC', 'Você quer usar o editor WYSIWYG?<br>For more comfort and quicker updates it is recommended to install the extended CKEditor Plus event Plugin!');
@define('INSTALL_XHTML11', 'Forçar compatibilidade com XHTML 1.1');
@define('INSTALL_XHTML11_DESC', 'Quer forçar compatibilidade com o standard XHTML 1.1 (pode causar problemas de formatação nos navegadores de 4ª geração (4.x))');
@define('INSTALL_POPUP', 'Permitir o uso de janelas popups');
@define('INSTALL_POPUP_DESC', 'Quer que o weblog utilize janelas popups para comentários, trackbacks, etc?');
@define('INSTALL_EMBED', 'O Serendipity está integrado?');
@define('INSTALL_EMBED_DESC', 'Se quiser arquivar o conteúdo do Serendipity dum website, mude para verdadeiro para descartar quaisquer cabeçalhos e apenas imprimir o conteúdo. Pode fazer uso da opção \'Arquivo inicial\' para usar uma classe mais abrangente aonde colocaria os cabeçalhos normais de sua página. Veja o ficheiro README para mais informações!');
@define('INSTALL_BLOCKREF', 'Referências bloqueadas');
@define('INSTALL_BLOCKREF_DESC', 'Existem servidores especiais que você não gostaria de listar na sua lista de referências? Separe a lista dos servidores com \';\' e note que o servidor será bloqueado por uma busca parcial em sua string!');
@define('INSTALL_REWRITE', 'Reescrita de URL');
@define('INSTALL_REWRITE_DESC', 'Selecione as regras que você gostaria de usar na geração de URLs. A habilitação da reescrita de URL criará URLS bem formatadas para o seu blogue e o deixará indexável de um melhor modo para os bots como o do Google. O servidor web precisa de ter suporte para o mod_rewrite ou para o "AllowOverride All" para a directoria do seu Serendipity. A configuração padrão é auto detectada');

/* IMAGECONVERSION SETTINGS */
@define('INSTALL_CAT_IMAGECONV', 'Configurações da conversão de imagens');
@define('INSTALL_CAT_IMAGECONV_DESC', 'Informações gerais sobre como o Serendipity deve lidar com imagens');
@define('INSTALL_IMAGEMAGICK', 'Usar Image magick');
@define('INSTALL_IMAGEMAGICK_DESC', 'Você tem o \'image magick\' instalado e quer utilizá-lo para redimensionar imagens?');
@define('INSTALL_IMAGEMAGICKPATH', 'Caminho para o executável do conversor');
@define('INSTALL_IMAGEMAGICKPATH_DESC', 'Caminho completo e nome do executável do image magick');
@define('INSTALL_THUMBSUFFIX', 'Sufixo das miniaturas');
@define('INSTALL_THUMBSUFFIX_DESC', 'As miniaturas serão nomeadas com o seguinte formato: original.[sufixo].ext');
@define('INSTALL_THUMBWIDTH', 'Dimensão das miniaturas ');
@define('INSTALL_THUMBWIDTH_DESC', 'Largura máxima estática das miniaturas geradas automaticamente');
@define('INSTALL_THUMBDIM', 'Thumbnail constrained dimension');
@define('INSTALL_THUMBDIM_LARGEST', 'Largest');
@define('INSTALL_THUMBDIM_WIDTH', 'Width');
@define('INSTALL_THUMBDIM_HEIGHT', 'Height');
@define('INSTALL_THUMBDIM_DESC', 'Dimension to be constrained to the thumbnail max size. The default "' .
    INSTALL_THUMBDIM_LARGEST .  '" limits both dimensions, so neither can be greater than the max size; "' .
    INSTALL_THUMBDIM_WIDTH . '" and "' .  INSTALL_THUMBDIM_HEIGHT .
    '" only limit the chosen dimension, so the other could be larger than the max size.');

/* PERSONAL DETAILS */
@define('USERCONF_CAT_PERSONAL', 'Detalhes pessoais');
@define('USERCONF_CAT_PERSONAL_DESC', 'Altere os seus detalhes pessoais');
@define('USERCONF_USERNAME', 'O seu nome de utilizador');
@define('USERCONF_USERNAME_DESC', 'O nome de utilizador que você usa para se identificar no blogue');
@define('USERCONF_PASSWORD', 'A sua senha');
@define('USERCONF_PASSWORD_DESC', 'A senha que você quer usar para se identificar no blogue');
@define('USERCONF_EMAIL', 'O seu endereço de e-mail');
@define('USERCONF_EMAIL_DESC', 'O seu endereço de e-mail pessoal');
@define('USERCONF_SENDCOMMENTS', 'Enviar notificação dos comentários?');
@define('USERCONF_SENDCOMMENTS_DESC', 'Quer receber e-mails quando forem enviados comentários à cerca dos seus artigos?');
@define('USERCONF_SENDTRACKBACKS', 'Enviar notificação de trackbacks?');
@define('USERCONF_SENDTRACKBACKS_DESC', 'Quer receber e-mails quando trackbacks forem enviados para os seus artigos?');
@define('USERCONF_ALLOWPUBLISH', 'Direitos: Publicação de artigos?');
@define('USERCONF_ALLOWPUBLISH_DESC', 'Este utilizador está autorizado a publicar artigos?');
@define('USERCONF_DARKMODE', 'Styx Theme Dark Mode');
@define('SUCCESS', 'Sucesso');
@define('POWERED_BY_SHOW_TEXT', 'Exibir "Serendipity" como texto');
@define('POWERED_BY_SHOW_TEXT_DESC', 'Exibirá o "Serendipity Weblog" como texto');
@define('POWERED_BY_SHOW_IMAGE', 'Exibir "Serendipity" como um logotipo');
@define('POWERED_BY_SHOW_IMAGE_DESC', 'Exibe o logotipo do Serendipity');

@define('SETTINGS_SAVED_AT', 'As novas configurações foram salvas às %s');
@define('PLUGIN_ITEM_DISPLAY', 'Aonde é que o item deve ser mostrado?');
@define('PLUGIN_ITEM_DISPLAY_EXTENDED', 'Apenas artigo extendido');
@define('PLUGIN_ITEM_DISPLAY_OVERVIEW', 'Página de visão geral apenas');
@define('PLUGIN_ITEM_DISPLAY_BOTH', 'Todas as vezes');
@define('RSS_IMPORT_CATEGORY', 'Utilize essa categoria para artigos que não encontrem uma categoria no Serendipity');
@define('ERROR_UNKNOWN_NOUPLOAD', 'Ocorreu um erro desconhecido, o arquivo não foi transferido. Talvez o tamanho do arquivo seja maior que o permitido pelo seu servidor. Verifique com o seu servidor de internet, ou edite o php.ini para permitir transferência de arquivos de maior tamanho.');
@define('COMMENTS_WILL_BE_MODERATED', 'Comentários enviados estarão sujeitos a moderação antes de serem exibidos.');
@define('YOU_HAVE_THESE_OPTIONS', 'As seguintes opções estão disponíveis');
@define('THIS_COMMENT_NEEDS_REVIEW', 'Atenção: Esse comentário precisa de aprovação antes de ser exibido.');
@define('DELETE_COMMENT', 'Excluir comentário');
@define('APPROVE_COMMENT', 'Aprovar comentário');
@define('REQUIRES_REVIEW', 'Requer revisão');
@define('COMMENT_APPROVED', 'Comentário #%s foi aprovado corretamente');
@define('COMMENT_DELETED', 'Comentário #%s foi excluído corretamente');
@define('VIEW', 'Exibir');
@define('COMMENT_ALREADY_APPROVED', 'Comentário #%s parece já ter sido aprovado');
@define('COMMENT_EDITED', 'O comentário selecionado foi editado');
@define('HIDE', 'Ocultar');
@define('VIEW_EXTENDED_ENTRY', 'Continuar lendo "%s"');
@define('TRACKBACK_SPECIFIC_ON_CLICK', 'This link is not active. It contains a copyable trackback URI to manually send ping- & trackbacks to this entry for older Blogs; Eg. (still valid) via the provided entry field of the serendipity_event_trackback plugin. Serendipity and other Blog systems nowadays recognize the trackback URL automatically by the article URL. The trackback URI for your Sender entry link therefore is as follows:');
@define('THIS_TRACKBACK_NEEDS_REVIEW', 'Atenção: Esse trackback precisa de aprovação antes que seja exibido');
@define('DELETE_TRACKBACK', 'Excluir trackback');
@define('APPROVE_TRACKBACK', 'Aprovar trackback');
@define('TRACKBACK_APPROVED', 'O trackback #%s foi aprovado corretamente');
@define('TRACKBACK_DELETED', 'O trackback #%s foi excluído corretamente');
@define('COMMENTS_MODERATE', 'Comentários & trackbacks para este artigo requerem moderação');
@define('PLUGIN_SUPERUSER_HTTPS', 'Usar https para login');
@define('PLUGIN_SUPERUSER_HTTPS_DESC', 'Permitir que o link de login aponte para uma link https. O seu servidor web necessita de suportar esta opção!');
@define('INSTALL_SHOW_EXTERNAL_LINKS', 'Tornar clicáveis os links externos?');
@define('INSTALL_SHOW_EXTERNAL_LINKS_DESC', '"não": Links externos não marcados (Maiores saídas, Maiores referências, Comentários de usuários) não são exibidos como puro texto para evitar spam do Google (recomendado). "sim": Links externos não marcados são exibidos como links. Podem ser sobrescritos pela configuração da barra lateral!');
@define('PAGE_BROWSE_COMMENTS', 'Página %s de %s, totalizando %s comentários');
@define('FILTERS', 'Filtros');
@define('FIND_ENTRIES', 'Encontrar artigos');
@define('FIND_COMMENTS', 'Encontrar comentários');
@define('FIND_MEDIA', 'Encontrar multimédia');
@define('FILTER_DIRECTORY', 'Directoria');
@define('SORT_BY', 'Ordenar por');
@define('TRACKBACK_COULD_NOT_CONNECT', 'Nenhum trackback enviado: Não foi possível abrir conexão para %s na porta %d');
@define('MEDIA', 'Média');
@define('MEDIA_LIBRARY', 'Biblioteca de multimédia');
@define('ADD_MEDIA_PICTELEMENT', 'Use &lt;picture&gt; element');
@define('ADD_MEDIA', 'Adicionar média');
@define('ENTER_MEDIA_URL', 'Introduza a URL de um ficheiro para ir buscá-lo:');
@define('ENTER_MEDIA_UPLOAD', 'Seleccione o ficheiro que deseja transferir:');
@define('SAVE_FILE_AS', 'Guardar ficheiro como:');
@define('STORE_IN_DIRECTORY', 'Guardar na seguinte directoria: ');
@define('MEDIA_RENAME', 'Renomear este ficheiro');
@define('IMAGE_RESIZE', 'Redimensionar esta imagem');
@define('MEDIA_DELETE', 'Apagar este ficheiro');
@define('FILES_PER_PAGE', 'Ficheiros por página');
@define('CLICK_FILE_TO_INSERT', 'Clique no ficheiro que deseja inserir:');
@define('SELECT_FILE', 'Seleccione arquivo para inserir');
@define('MEDIA_FULLSIZE', 'Tamanho total');
@define('CALENDAR_BOW_DESC', 'Dia da semana que deve ser considerado o início da semana. O padrão é segunda-feira');
@define('SUPERUSER', 'Administração do weblog');
@define('ALLOWS_YOU_BLAHBLAH', 'Fornece um link para a administração do weblog na barra lateral ');
@define('CALENDAR', 'Calendário');
@define('SUPERUSER_OPEN_ADMIN', 'Abrir Administração');
@define('SUPERUSER_OPEN_LOGIN', 'Abrir painel de login');
@define('INVERT_SELECTIONS', 'Inverter Seleção');
@define('COMMENTS_DELETE_CONFIRM', 'Tem a certeza de que deseja excluir os comentários selecionados?');
@define('COMMENT_DELETE_CONFIRM', 'Tem a certeza de que deseja excluir o comentário #%d, escrito por %s?');
@define('DELETE_SELECTED_COMMENTS', 'Excluir comentários seleccionados');
@define('VIEW_COMMENT', 'Exibir comentário');
@define('VIEW_ENTRY', 'Exibir artigo');
@define('DELETE_FILE_FAIL', 'NNão foi possível deletar o arquivo <b>%s</b>');
@define('DELETE_THUMBNAIL', 'Excluir a miniatura da imagem entitulada <b>%s</b>');
@define('DELETE_FILE', 'Excluir o campo entitulado <b>%s</b>');
@define('ABOUT_TO_DELETE_FILE', 'Você está prestes a apagar <b>%s</b><br>Se estiver utilizando esse arquivo em algum dos seus artigos, vai resultar em ligações ou imagens perdidas<br>Deseja realmente prosseguir com a exclusão?<br><br>');
@define('TRACKBACK_SENDING', 'Enviando trackback para o URL %s...');
@define('TRACKBACK_SENT', 'Trackback enviada com sucesso');
@define('TRACKBACK_FAILED', 'Trackback falhou: %s');
@define('TRACKBACK_NOT_FOUND', 'Nenhum URI de trackback foi encontrado.');
@define('TRACKBACK_URI_MISMATCH', 'A URI de trackback descoberta não com é semelhante ao URL alvo.');
@define('TRACKBACK_CHECKING', 'Verificando <u>%s</u> para possíveis trackbacks...');
@define('TRACKBACK_NO_DATA', 'O alvo não contém dados');
@define('TRACKBACK_SIZE', 'URL alvo excedeu o tamanho máximo de %s bytes para um arquivo.');
@define('COMMENTS_VIEWMODE_THREADED', 'Discussão');
@define('COMMENTS_VIEWMODE_LINEAR', 'Sequencial');
@define('DISPLAY_COMMENTS_AS', 'Exibir comentários como');
@define('CATEGORY_DELETED_ARTICLES_MOVED', 'Categoria #%s excluída. Os artigos antigos foram movidos para a categoria #%s');
@define('INSTALL_TOP_AS_LINKS', 'Exibir maiores saídas/referências como links?');
@define('INSTALL_TOP_AS_LINKS_DESC', '"não": Saídas e Referências são exibidas como puro texto para prevenir spam do Google. "sim": Saídas e Refrências são exibidas como links. "padrão": Usa o valor definido na configuração global (recomendado).');
@define('ADD_MEDIA_BLAHBLAH', '<b>Adicionar um ficheiro para o seu repositório de multimédia:</b><p>Aqui você pode transferir ficheiros de multimédia, ou oriente-me para obtê-lo noutro sítio da web! Se não possuir uma imagem apropriada, <a href="https://images.google.com" rel="noopener" target="_blank">procure no Google</a> alguma imagem que tenha relação com o assunto, os resultados são geralmente úteis e divertidos :)</p><p><b>Selecione o método:</b></p><br>');
@define('COMMENTS_FILTER_SHOW', 'Mostrar');
@define('COMMENTS_FILTER_ALL', 'Todos');
@define('COMMENTS_FILTER_APPROVED_ONLY', 'Só aprovados'); // Verify
@define('COMMENTS_FILTER_HIDDEN_ONLY', 'Only hidden');
@define('COMMENTS_FILTER_APPROVAL_ONLY', 'Only pending');
@define('COMMENTS_FILTER_CONFIRM_ONLY', 'Only confirmable');
@define('COMMENTS_FILTER_NEED_APPROVAL', 'Aprovação pendente');
@define('COMMENTS_FILTER_NEED_CONFIRM', 'Pending confirmation');
@define('RSS_IMPORT_BODYONLY', 'Pôr todo o texto importado na secção de "body" ("corpo") e não dividir para a secção "extended entry" ("entrada estendida").'); // Verify
@define('SYNDICATION_PLUGIN_FULLFEED', 'Mostrar artigos completos com corpo estendido dentro do RSS feed');
@define('MT_DATA_FILE', 'Ficheiro de dados Movable Type');
@define('FORCE', 'Forçar');
@define('CREATE_AUTHOR', 'Criar autor \'%s\'.');
@define('CREATE_CATEGORY', 'Criar categoria \'%s\'.');
@define('MYSQL_REQUIRED', 'Tem que ter a extensão MySQL para poder executar esta acção.');
@define('COULDNT_CONNECT', 'Foi impossível ligar à base de dados MySQL: %s.');
@define('COULDNT_SELECT_DB', 'Foi impossível seleccionar base de dados: %s.');
@define('COULDNT_SELECT_USER_INFO', 'Foi impossível seleccionar a informação sobre o utilizador: %s.');
@define('COULDNT_SELECT_CATEGORY_INFO', 'Não foi possível seleccionar a informação de categorias: %s.');
@define('COULDNT_SELECT_ENTRY_INFO', 'Não foi possível seleccionar a informação da entrada: %s.');
@define('COULDNT_SELECT_COMMENT_INFO', 'Não foi possível seleccionar a informação de comentários: %s.');
@define('WEEK', 'Semana');
@define('WEEKS', 'Semanas');
@define('MONTHS', 'Meses');
@define('DAYS', 'Dias');
@define('ARCHIVE_FREQUENCY', 'Frequência de item de calendário');
@define('ARCHIVE_FREQUENCY_DESC', 'O intervalo de calendário entre cada item da lista');
@define('ARCHIVE_COUNT', 'Número de itens nesta lista');
@define('ARCHIVE_COUNT_DESC', 'O número total de meses, semanas ou dias para mostrar');
@define('BELOW_IS_A_LIST_OF_INSTALLED_PLUGINS', 'Abaixo encontra uma lista dos plugins instalados');
@define('SIDEBAR_PLUGIN', 'plugin da barra lateral');
@define('EVENT_PLUGIN', 'plugin de evento');
@define('CLICK_HERE_TO_INSTALL_PLUGIN', 'Clique aqui para instalar um novo %s');
@define('VERSION', 'Versão');
@define('INSTALL', 'Instalar');
@define('ALREADY_INSTALLED', 'Já instalado');
@define('SELECT_A_PLUGIN_TO_ADD', 'Seleccione o plugin que pretende instalar');
@define('INSTALL_OFFSET', 'Offset temporal no servidor');
@define('INSTALL_OFFSET_ON_SERVER_TIME', 'Base offset on server timezone?');
@define('INSTALL_OFFSET_ON_SERVER_TIME_DESC', 'Offset entry times on server timezone or not. Select yes to base offset on server timezone and no to offset on GMT.');
@define('STICKY_POSTINGS', 'Postagens fixas');
@define('INSTALL_FETCHLIMIT', 'Entradas a mostrar na primeira página');
@define('INSTALL_FETCHLIMIT_DESC', 'Número de entradas a mostrar por cada página do página principal');
@define('IMPORT_ENTRIES', 'Importar dados');
@define('EXPORT_ENTRIES', 'Exportar entradas');
@define('IMPORT_WELCOME', 'Bem vindo ao utilitário de importação do Serendipity');
@define('IMPORT_WHAT_CAN', 'Aqui podemos importar entradas de outro software gestor de weblogs');
@define('IMPORT_SELECT', 'Por favor, seleccione o software de onde quer importar');
@define('IMPORT_PLEASE_ENTER', 'Por favor introduza os dados como pedido abaixo');
@define('IMPORT_NOW', 'Importar agora!');
@define('IMPORT_STARTING', 'Começando o procedimento de importação...');
@define('IMPORT_FAILED', 'Importação falhou');
@define('IMPORT_DONE', 'Importação completada com sucesso');
@define('IMPORT_WEBLOG_APP', 'Aplicação do Weblog');
@define('EXPORT_FEED', 'Exportar RSS feed completo'); // Verify
@define('IMPORT_STATUS', 'Status depois da importação');
@define('IMPORT_GENERIC_RSS', 'Importação de RSS genérica');
@define('ACTIVATE_AUTODISCOVERY', 'Enviar Trackbacks de ligações encontradas na entrada'); // Verify
@define('WELCOME_TO_ADMIN', 'Bem vindo à Suite Administrativa do Serendipity Styx.');
@define('PLEASE_ENTER_CREDENTIALS', 'Por favor, introduza as suas credenciais abaixo.');
@define('ADMIN_FOOTER_POWERED_BY', 'Equipado com Serendipity %s e PHP %s');
@define('INSTALL_USEGZIP', 'Use páginas comprimidas com gzip');
@define('INSTALL_USEGZIP_DESC', 'Para acelerar o acesso às páginas, podemos comprimi-las, desde que o navegador do visitante o suporte. Isto é recomendado');
@define('INSTALL_SHOWFUTURE', 'Mostre entradas futuras'); // Verify
@define('INSTALL_SHOWFUTURE_DESC', 'Se escolhido, isto mostrará todas as entradas futuras do seu blogue. Por omissão escondemos tais entradas e só são mostradas quando a data de publicação chega.');
@define('INSTALL_DBPERSISTENT', 'Usar conexões persistentes');
@define('INSTALL_DBPERSISTENT_DESC', 'Permitir a utilização de conexões permanentes à base de dados, ver leitura adicional em <a href="https://php.net/manual/features.persistent-connections.php" rel="noopener" target="_blank">aqui</a>. Isto normalmente não é recomendado.');
@define('NO_IMAGES_FOUND', 'Nenhuma das imagens foi encontrada');
@define('PERSONAL_SETTINGS', 'Preferências pessoais');
@define('REFERER', 'Referenciador');
@define('NOT_FOUND', 'Não encontrado');
@define('NOT_WRITABLE', 'Escrita negada');
@define('WRITABLE', 'Escrita permitida');
@define('PROBLEM_DIAGNOSTIC', 'Devido a um diagnóstico problemático, não se pode continuar a instalação sem que os erros acima sejam corrigidos');
@define('SELECT_INSTALLATION_TYPE', 'Seleccione que tipo de instalação que deseja efectuar');
@define('WELCOME_TO_INSTALLATION', 'Bem vindo à Instalação do Serendipity Styx');
@define('FIRST_WE_TAKE_A_LOOK', 'Primeiro examinamos a sua instalação corrente e tentamos diagnosticar quaisquer problemas de compatibilidade');
@define('ERRORS_ARE_DISPLAYED_IN', 'Erros são mostrados a %s, recomendações a %s e sucesso a %s');
@define('RED', 'vermelho');
@define('YELLOW', 'amarelo');
@define('GREEN', 'verde');
@define('PRE_INSTALLATION_REPORT', 'Relatório de pré-instalação do Serendipity v%s');
@define('RECOMMENDED', 'Recomendado');
@define('ACTUAL', 'Actual'); // Verify
@define('PHPINI_CONFIGURATION', 'configuração em php.ini');
@define('PHP_INSTALLATION', 'instalação de PHP installation');
@define('THEY_DO', 'eles fazem');
@define('THEY_DONT', 'eles não');
@define('SIMPLE_INSTALLATION', 'Instalação simples');
@define('EXPERT_INSTALLATION', 'Instalação para especialistas');
@define('COMPLETE_INSTALLATION', 'Instalação completa');
@define('WONT_INSTALL_DB_AGAIN', 'não instalaremos a base de dados novamente'); // Verify
@define('CHECK_DATABASE_EXISTS', 'Verificando se a base de dados ou se as tabelas já existem');
@define('CREATING_PRIMARY_AUTHOR', 'Criando autor primário \'%s\'');
@define('SETTING_DEFAULT_TEMPLATE', 'Definindo modelo por omissão'); // Verify
@define('INSTALLING_DEFAULT_PLUGINS', 'Instalando plugins por omissão'); // Verify
@define('SERENDIPITY_INSTALLED', 'Serendipity Styx foi instalado com sucesso');
@define('VISIT_BLOG_HERE', 'Visite o seu novo blogue aqui');
@define('THANK_YOU_FOR_CHOOSING', 'Obrigado por escolher Serendipity Styx');
@define('ERROR_DETECTED_IN_INSTALL', 'Erro detectado na instalação');
@define('OPERATING_SYSTEM', 'Sistema Operativo');
@define('WEBSERVER_SAPI', 'Servidor web SAPI');
@define('IMAGE_ROTATE_LEFT', 'Rodar a imagem 90º no sentido directo');
@define('IMAGE_ROTATE_RIGHT', 'Rodar a imagem 90º no sentido retrógado');
@define('TEMPLATE_SET', '\'%s\' foi definido como o seu modelo activo');
@define('SEARCH_ERROR', 'A função de busca não se comportou como esperado. Nota para o Administrador deste blogue: isto pode acontecer por falta de chaves no índice na sua base de dados. Em sistemas de MySQL, a sua conta de utilizador da base de dados precisa de privilégios para executar o seguinte comando: <pre>CREATE FULLTEXT INDEX entry_idx on %sentries (title,body,extended)</pre> O erro exacto reportado pela base de dados foi: <pre>%s</pre>'); // Verify
@define('EDIT_THIS_CAT', 'Editar "%s"');
@define('CATEGORY_REMAINING', 'Apague esta categoria e mova as entradas para esta outra');
@define('CATEGORY_INDEX', 'Abaixo encontra uma listagem de categorias disponíveis para as suas entradas');
@define('NO_CATEGORIES', 'Sem categorias'); // Verify
@define('RESET_DATE', 'Reinicializar a data');
@define('RESET_DATE_DESC', 'Clique aqui para reinicializar a data para o valor corrente');
@define('PROBLEM_PERMISSIONS_HOWTO', 'Permissões podem ser corrigidas na directoria problemática com o comando: `<em>%s</em>`, ou usando um programa de FTP'); // Verify
@define('WARNING_TEMPLATE_DEPRECATED', 'Aviso: o seu modelo corrente usa um método de padrões obsoleto, fica avisado para actualizar se possível');
@define('ENTRY_PUBLISHED_FUTURE', 'Esta entrada ainda não foi publicada.');
@define('ENTRIES_BY', 'Entradas por %s');
@define('PREVIOUS', 'Anterior');
@define('NEXT', 'Seguinte');
@define('APPROVE', 'Aprovar');
@define('CATEGORY_ALREADY_EXIST', 'Uma categoria com o nome "%s" já existe');
@define('IMPORT_NOTES', 'Nota:');
@define('ERROR_FILE_FORBIDDEN', 'Não é permitido fazer upload de ficheiros com conteúdo activo'); // Verify
@define('ADMIN', 'Administração');
@define('ADMIN_FRONTPAGE', 'Primeira página');
@define('QUOTE', 'Citar'); // Verify
@define('IFRAME_SAVE', 'Serendipity está guardando a sua entrada, criando trackbacks e realizando possíveis chamadas de XML-RPC. Isto pode demorar um pouco...');
@define('IFRAME_SAVE_DRAFT', 'Um rascunho desta entrada foi guardado');
@define('IFRAME_PREVIEW', 'Serendipity está agora a criar uma previsão da sua entrada...');
@define('IFRAME_WARNING', 'O seu navegador não suporta o conceito de iframes. Por favor abra o seu ficheiro serendipity_config.inc.php e defina a variável $serendipity[\'use_iframe\'] como FALSE.'); // Verify
@define('NONE', 'nenhum');
@define('USERCONF_CAT_DEFAULT_NEW_ENTRY', 'Características por omissão de novas entradas');
@define('UPGRADE', 'Actualizar');
@define('UPGRADE_TO_VERSION', '<b>Actualizar para a versão:</b> %s');
@define('DELETE_DIRECTORY', 'Apagar directoria');
@define('DELETE_DIRECTORY_DESC', 'Está prestes a apagar o conteúdo duma directoria que contém ficheiros de multimédia que podem ser usados por outras das suas entradas.'); // Verify
@define('FORCE_DELETE', 'Apagar TODOS os ficheiros nesta directoria, incluindo os desconhecidos pelo Serendipity');
@define('CREATE_DIRECTORY', 'Criar directoria');
@define('CREATE_NEW_DIRECTORY', 'Criar nova directoria');
@define('CREATE_DIRECTORY_DESC', 'Aqui pode criar uma nova directoria aonde colocar ficheiros multimédia. Escolha o nome para a nova directoria e seleccione se necessário a directoria onde aquela é colocada.'); // Verify
@define('BASE_DIRECTORY', 'Directoria base');
@define('USERLEVEL_EDITOR_DESC', 'Editor');
@define('USERLEVEL_CHIEF_DESC', 'Editor Chefe');
@define('USERLEVEL_ADMIN_DESC', 'Administrador');
@define('USERCONF_USERLEVEL', 'Nível de acesso');
@define('USERCONF_USERLEVEL_DESC', 'Este nível é usado para determinar que tipo de acesso ao blogue tem este utilizador. Os privilégios dos utilizadores são tratados pelos membros do grupo!');
@define('USER_SELF_INFO', 'Conectado como %s (%s)'); // Verify
@define('USER_ALERT', 'Userinfo');
@define('USER_PERMISSION_NOTIFIER_DRAFT_MODE', 'You have not yet been granted the right to publish your entries directly. Until sufficient trust is built, inform your assigned editor-in-chief that your entry is ready for publication and approval.');
@define('USER_PERMISSION_NOTIFIER_RESET', 'In case of temporary revocation of rights, please clarify the reasons in a friendly personal conversation.');
@define('ADMIN_ENTRIES', 'Entradas');
@define('RECHECK_INSTALLATION', 'Volte a verificar a instalação');
@define('IMAGICK_EXEC_ERROR', 'Impossível executar: "%s", erro: %s, variável devolvida: %d');
@define('INSTALL_OFFSET_DESC', 'Introduza a diferença horária entre o seu servicor (corrente: %clock%) e o fuso horário pretendido');
@define('UNMET_REQUIREMENTS', 'Requisitos falharam: %s');
@define('CHARSET', 'Codificação de caracteres');
@define('AUTOLANG', 'Por omissão usar a linguagem do navegador do visitante');
@define('AUTOLANG_DESC', 'Se escolhido, isto usará a linguagem do navegador do utilizador para determinar a linguagem por omissão da sua entrada e a linguagem da interface.');
@define('INSTALL_AUTODETECT_URL', 'Autodetectar HTTP-Host utilizado');
@define('INSTALL_AUTODETECT_URL_DESC', 'Se definido como "true", o Serendipity assegurará que o HTTP Host que é usado pelo seu visitante é usado como definição de BaseURL. Permitir isto torna possível usar vários nomes de domínio para o seu blogue Serendipity, e usar o domínio para todas as ligações subsequentes.'); // Verify
@define('CONVERT_HTMLENTITIES', 'Tentar converter as entidades HTML?');
@define('EMPTY_SETTING', 'Não especificou um valor válido para "%s"!');
@define('USERCONF_REALNAME', 'Nome verdadeiro');
@define('USERCONF_REALNAME_DESC', 'O nome completo do autor. Este é o nome visto pelos leitores');
@define('HOTLINK_DONE', 'Ficheiro "%s" hotlinked.<br>Internal name: \'%s\'. Feito.'); // Verify
@define('ENTER_MEDIA_URL_METHOD', 'Método de obtenção:');
@define('ADD_MEDIA_BLAHBLAH_NOTE', 'Nota: Se escolher fazer um hotlink para  o servidor, assegure-se que tem permissão para tal ou que o servidor é seu. Hotlinks permitem usar imagens sem as guardar localmente.'); // Verify
@define('MEDIA_HOTLINKED', 'hotlinked');
@define('FETCH_METHOD_IMAGE', 'Download da imagem para o seu servidor');
@define('FETCH_METHOD_HOTLINK', 'Hotlink para servidor'); // Verify
@define('DELETE_HOTLINK_FILE', 'Apagado o ficheiro hotlinked intitulado <b>%s</b>'); // Verify
@define('SYNDICATION_PLUGIN_SHOW_MAIL', 'Mostrar endereços de e-mail?');
@define('IMAGE_MORE_INPUT', 'Adicionar imagens');
@define('BACKEND_TITLE', 'Informação adicional no écrã de configuração de plugins');
@define('BACKEND_TITLE_FOR_NUGGET', 'Aqui pode definir um pedaço de texto personalizado que é mostrado no écrã de configuração de plugins juntamente com a descrição do plugin. Se tiver múltiplos stacked plugins / HTML nuggets com um título por preencher, isto permite distinguir os plugins entre si.');
@define('CATEGORIES_ALLOW_SELECT', 'Permitir aos visitantes mostrar múltiplas categorias simultaneamente?');
@define('CATEGORIES_ALLOW_SELECT_DESC', 'Se esta opção for activada, uma caixa de marcação será posta ao lado de cada categoria no plugin da barra lateral. Os utilizadores podem marcar estas caixas e depois ver as entradas correspondendo à sua selecção.'); // Verify
@define('PAGE_BROWSE_PLUGINS', 'Página %s de %s, totalizando %s plugins.');
@define('INSTALL_CAT_PERMALINKS', 'Links Pernamentes');
@define('INSTALL_CAT_PERMALINKS_DESC', 'Define vários padrões de URLs para definir ligações permanentes no seu blogue. É sugerido que use as escolhas por omissão; caso contrário, deverá tentar usar o valor da %id% sempre que possível para evitar que o Serendipity pergunte à base de dados pela URL alvo.'); // Verify
@define('INSTALL_PERMALINK', 'Estrutura de introdução do URL de Links Pernamentes'); // Verify
@define('INSTALL_PERMALINK_DESC', 'Aqui pode definir a estrura relativa de URLs do seu URL base até onde as entradas estão disponíveis. Pode usar as variáveis %id%, %title%, %day%, %month%, %year% e quaisquer outros caracteres..'); // Verify
@define('INSTALL_PERMALINK_AUTHOR', 'Estrutura de URL de Links Pernamentes do Autor'); // Verify
@define('INSTALL_PERMALINK_AUTHOR_DESC', 'Aqui pode definir a estrura relativa dos URLs do seu URL base até onde entradas para certos autores ficam disponíveis. Pode usar as variáveis %id%, %realname%, %username%, %email% e quaisquer outros caracteres.'); // Verify
@define('INSTALL_PERMALINK_CATEGORY', 'Estrutura do URL para Links Pernamentes por Categoria'); // Verify
@define('INSTALL_PERMALINK_CATEGORY_DESC', 'Aqui pode definir a estrura relativa dos URLs do seu URL base até onde entradas para certas categorias ficam disponíveis. Pode usar as variáveis %id%, %name%, %parentname%, %description% e quaisquer outros caracteres.'); // Verify
@define('INSTALL_PERMALINK_FEEDCATEGORY', 'Estrutura do URL para Links Permanentes por Categoria de RSS-Feed'); // Verify
@define('INSTALL_PERMALINK_FEEDCATEGORY_DESC', 'Aqui pode definir a estrutura relativa dos URLs d seu URL base até onde entradas para certas categorias de RSS-Feed ficam disponíveis. Pode usar as variáveis %id%, %name%, %description% e quaisquer outros caracteres.'); // Verify
@define('INSTALL_PERMALINK_FEEDAUTHOR', 'Permalink RSS-Feed Author URL structure');
@define('INSTALL_PERMALINK_FEEDAUTHOR_DESC', 'Here you can define the relative URL structure beginning from your base URL to where RSS-feeds from specific users may be viewed. You can use the variables %id%, %realname%, %username%, %email% and any other characters.');
@define('INSTALL_PERMALINK_ID_WARNING', 'If you remove the essential %id% variable, Serendipity cannot create an exact relationship. This has effects on various accesses and subsequent processes and is not recommended without your own responsibility!');
@define('INSTALL_PERMALINK_ARCHIVESPATH', 'Caminho para arquivos');
@define('INSTALL_PERMALINK_ARCHIVEPATH', 'Caminho para arquivo');
@define('INSTALL_PERMALINK_CATEGORIESPATH', 'Caminho para categorias');
@define('INSTALL_PERMALINK_AUTHORSPATH', 'Path to authors');
@define('INSTALL_PERMALINK_UNSUBSCRIBEPATH', 'Caminho para desistir da subscrição de comentários');
@define('INSTALL_PERMALINK_DELETEPATH', 'Caminho para apagar comentários');
@define('INSTALL_PERMALINK_APPROVEPATH', 'Caminho para aprovar comentários');
@define('INSTALL_PERMALINK_FEEDSPATH', 'Caminho para RSS Feeds');
@define('INSTALL_PERMALINK_PLUGINPATH', 'Caminho para plugin único'); //Verify
@define('INSTALL_PERMALINK_ADMINPATH', 'Caminho para Administração');
@define('INSTALL_PERMALINK_SEARCHPATH', 'Caminho para a pesquisa'); //Verify
@define('INSTALL_CAL', 'Género de calendário');
@define('INSTALL_CAL_DESC', 'Escolha o formato de calendário desejado');
@define('REPLY', 'Responder');
@define('USERCONF_GROUPS', 'Inscrições em grupos');
@define('USERCONF_GROUPS_DESC', 'Este utilizador é membro dos seguintes grupos. Inscrições múltiplas são possíveis.');
@define('GROUPCONF_GROUPS', 'Selectable members of this group');
@define('MANAGE_GROUPS', 'Gestão de grupos');
@define('DELETED_GROUP', 'O grupo #%d \'%s\' foi apagado.');
@define('CREATED_GROUP', 'Um novo grupo #%d \'%s\' foi criado');
@define('MODIFIED_GROUP', 'As propriedades do grupo \'%s\' foram mudadas');
@define('GROUP', 'Grupo');
@define('CREATE_NEW_GROUP', 'Criar novo grupo');
@define('DELETE_GROUP', 'Está prestes a apagar o grupo #%d \'%s\'. Tem a certeza?');
@define('SYNDICATION_PLUGIN_FEEDBURNERID', 'ID do FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_DESC', 'O ID do feed que deseja publicar');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG', 'Imagem do FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG_DESC', 'Nome da imagem a mostrar (ou deixe em branco para contador), localizada em feedburner.com, ex: fbapix.gif');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE', 'Título do FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE_DESC', 'Título (se pretendido) a mostrar ao lado da imagem');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT', 'Texto da imagem do FeedBurner');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT_DESC', 'Texto (se pretendido) a mostrar quando se pairao cursor sobre a imagem');
@define('SEARCH_TOO_SHORT', 'O texto a procurar deverá ter mais que 3 caracteres. Pode usar * como sufixo, por exemplo s9y*, para forçar a pesquisa por palavras mais pequenas.');
@define('INSTALL_DBPORT', 'Porto da base de dados'); //Verify
@define('INSTALL_DBPORT_DESC', 'Porto usado para ligar ao servidor da base de dados');
@define('PLUGIN_GROUP_FRONTEND_EXTERNAL_SERVICES', 'Frontend: Serviços Externos');
@define('PLUGIN_GROUP_FRONTEND_FEATURES', 'Frontend: Características');
@define('PLUGIN_GROUP_FRONTEND_FULL_MODS', 'Frontend: Modificações ');
@define('PLUGIN_GROUP_FRONTEND_VIEWS', 'Frontend: Vistas');
@define('PLUGIN_GROUP_FRONTEND_ENTRY_RELATED', 'Frontend: Relacionado com Entradas');
@define('PLUGIN_GROUP_BACKEND_EDITOR', 'Backend: Editor');
@define('PLUGIN_GROUP_BACKEND_USERMANAGEMENT', 'Backend: Gestão de Utilizadores');
@define('PLUGIN_GROUP_BACKEND_METAINFORMATION', 'Backend: Meta informação');
@define('PLUGIN_GROUP_BACKEND_TEMPLATES', 'Backend: Modelos');
@define('PLUGIN_GROUP_BACKEND_FEATURES', 'Backend: Características');
@define('PLUGIN_GROUP_BACKEND_MAINTAIN', 'Backend: Maintenance');
@define('PLUGIN_GROUP_BACKEND_DASHBOARD', 'Backend: Startpage');
@define('PLUGIN_GROUP_BACKEND_ADMIN', ADMIN); // is constant, no quotes, no translate!
@define('PLUGIN_GROUP_IMAGES', 'Imagens');
@define('PLUGIN_GROUP_ANTISPAM', 'Anti-Spam');
@define('PLUGIN_GROUP_MARKUP', 'Código');
@define('PLUGIN_GROUP_STATISTICS', 'Estatísticas');

 // GROUP PERMISSIONS   no translate first part until ':', since config variable!
@define('PERMISSION_PERSONALCONFIGURATION', 'personalConfiguration: Aceder à configuração pessoal');
@define('PERMISSION_PERSONALCONFIGURATIONUSERLEVEL', 'personalConfigurationUserlevel: Modificar níveis de utilizador');
@define('PERMISSION_PERSONALCONFIGURATIONNOCREATE', 'personalConfigurationNoCreate: Mudar "proibir criação de entradas"');
@define('PERMISSION_PERSONALCONFIGURATIONRIGHTPUBLISH', 'personalConfigurationRightPublish: Mudar direito de publicar entradas');
@define('PERMISSION_SITECONFIGURATION', 'siteConfiguration: Aceder à configuração de sistema');
@define('PERMISSION_SITEAUTOUPGRADES', 'siteAutoUpgrades: Access system autoupgrades');
@define('PERMISSION_BLOGCONFIGURATION', 'blogConfiguration: Aceder à configuração blogo-cêntrica');
@define('PERMISSION_ADMINENTRIES', 'adminEntries: Administrar entradas');
@define('PERMISSION_ADMINENTRIESMAINTAINOTHERS', 'adminEntriesMaintainOthers: Administrar entradas de outros utilizadores');
@define('PERMISSION_ADMINIMPORT', 'adminImport: Importar entradas');
@define('PERMISSION_ADMINCATEGORIES', 'adminCategories: Administrar categorias');
@define('PERMISSION_ADMINCATEGORIESMAINTAINOTHERS', 'adminCategoriesMaintainOthers: Administrar outras categorias dos utilizadores');
@define('PERMISSION_ADMINCATEGORIESDELETE', 'adminCategoriesDelete: Apagar categorias');
@define('PERMISSION_ADMINUSERS', 'adminUsers: Administrar utilizadores');
@define('PERMISSION_ADMINUSERSDELETE', 'adminUsersDelete: Apagar utilizadores');
@define('PERMISSION_ADMINUSERSEDITUSERLEVEL', 'adminUsersEditUserlevel: Mudar nível de utilizador');
@define('PERMISSION_ADMINUSERSMAINTAINSAME', 'adminUsersMaintainSame: Administrar utilizadores do(s) seu(s) grupo(s)');
@define('PERMISSION_ADMINUSERSMAINTAINOTHERS', 'adminUsersMaintainOthers: Administrar utilizadores que não pertencem ao(s) seu(s) grupo(s)');
@define('PERMISSION_ADMINUSERSCREATENEW', 'adminUsersCreateNew: Criar novos utilizadores');
@define('PERMISSION_ADMINUSERSGROUPS', 'adminUsersGroups: Administrar grupos de utilizadores');
@define('PERMISSION_ADMINPLUGINS', 'adminPlugins: Administrar plugins');
@define('PERMISSION_ADMINPLUGINSMAINTAINOTHERS', 'adminPluginsMaintainOthers: Administrar plugins de outros utilizadores');
@define('PERMISSION_ADMINIMAGES', 'adminImages: Administrar ficheiros de multimédia');
@define('PERMISSION_ADMINIMAGESDIRECTORIES', 'adminImagesDirectories: Administrate directorias de multimédia');
@define('PERMISSION_ADMINIMAGESADD', 'adminImagesAdd: Juntar novos ficheiros de multimédia');
@define('PERMISSION_ADMINIMAGESDELETE', 'adminImagesDelete: Apagar ficheiros de multimédia');
@define('PERMISSION_ADMINIMAGESMAINTAINOTHERS', 'adminImagesMaintainOthers: Administrar ficheiros de multimédia de outros utilizadores');
@define('PERMISSION_ADMINIMAGESVIEW', 'adminImagesView: Ver ficheiros de multimédia');
@define('PERMISSION_ADMINIMAGESSYNC', 'adminImagesSync: Sincronizar thumbnails');
@define('PERMISSION_ADMINIMAGESVIEWOTHERS', 'adminImagesViewOthers: Ver ficheiros de multimédia de outros utilizadores');
@define('PERMISSION_ADMINCOMMENTS', 'adminComments: Administrar comentários');
@define('PERMISSION_ADMINTEMPLATES', 'adminTemplates: Administrar modelos');

@define('GROUP_ADMIN_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights, might implement security risks. There are at least 3 permission flags [<em>adminPluginsMaintainOthers</em>, <em>adminUsersMaintainOthers</em> and <em>siteConfiguration</em>] which should stick to the ADMINISTRATOR <b>only</b>! Otherwise, vital conditions of your blog are endangered. Compare and understand what are the main differences between you, the ADMIN, and between "Editors in CHIEF" and normal "USERs". The [<em>siteAutoUpgrades</em>] permission flag is for a special cased and assigned CHIEF only. Read in the ChangeLog, the Styx Sites Help Center or the german Book on how to use it!');
@define('GROUP_CHIEF_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights to normal USERs, might implement security risks. You should deeply check which permission flag should be allowed/removed, compared to a standard USER! Otherwise, vital conditions of certain areas are endangered. Compare and understand what are the main differences between you, the "Editor in CHIEF" and normal "USERs". Read in the Styx Sites Help Center or the german Book for more information!');

@define('INSTALL_BLOG_EMAIL', 'Endereço de E-mail do Blogue');
@define('INSTALL_BLOG_EMAIL_DESC', 'Isto configura o endereço de E-mail que é usado na linha de "From" de e-mail que segue para o exterior. Certifique-se que isto é um endereço de e-mail que é reconhecido pelo servidor de mail configurado no seu servidor - muitos servidores de mail rejeitam mensagens com um endereço de "From"" desconhecido.');
@define('CATEGORIES_PARENT_BASE', 'Mostre só categorias abaixo...');
@define('CATEGORIES_PARENT_BASE_DESC', 'Pode escolher uma categoria antecessora para que só as categorias descendentes sejam vistas.');
@define('CATEGORIES_HIDE_PARALLEL', 'Esconder categorias que não fazem parte da árvore de categorias');
@define('CATEGORIES_HIDE_PARALLEL_DESC', 'Se quiser esconder categorias que fazem parte de uma árvore de categorias distinta, é preciso autorizar isto. This feature made most sense in the past, when used in conjunction with a "multi-Blog" like system using the "Properties/Templates of categories" plugin. However, this is no longer the case, since this plugin in its version greater than/equal to v.1.50 can calculate hidden categories independently and better. So you should only use this option if you have a specific use case outside of said categorytemplates plugin.');
@define('CHARSET_NATIVE', 'Nativo');
@define('INSTALL_CHARSET', 'Escolha de codificação de caracteres');
@define('INSTALL_CHARSET_DESC', 'Aqui pode optar entre codificações de caracteres UTF-8 ou nativas (ISO, EUC, ...). Algumas línguas só têm traduções UTF-8 de forma que mudar a codificação para "Nativo" não produzirá qualquer efeito. Sugere-se UTF-8 em instalações novas. Não altere esta definição se já criou entradas com caracteres especiais - isso pode causar corrupção de caracteres. Certifique-se que lê mais sobre este assunto em https://ophian.github.io/hc/en/i18n.html.');
@define('CALENDAR_ENABLE_EXTERNAL_EVENTS', 'Accionar conexão ao Plugin API');
@define('CALENDAR_EXTEVENT_DESC', 'Se accionado, os plugins podem conectar ao calendário para mostrar os seus eventos destacados. Só accione se instalou plugins que precisam disto, caso contrário o único efeito é diminuir a performance.');
@define('XMLRPC_NO_LONGER_BUNDLED', 'A Interface da API de XML-RPC para Serendipity já não é incluída devido a questões de segurança correntes com esta API e ao facto de ser pouco usada. Assim precisa de instalar o Plugin de XML-RPC para usar a API de XML-RPC. A URL a usar nas suas aplicações NÃO mudará - assim que instalar o plugin, poderá de novo usar a API.');
@define('PERM_READ', 'Permissão de leitura');
@define('PERM_WRITE', 'Permissão de escrita');
@define('PERM_DENIED', 'Permissão negada.');
@define('INSTALL_ACL', 'Aplicar permissões de leitura às categorias');
@define('INSTALL_ACL_DESC', 'Se accionado, as preferências de permissões de grupos de utilizadores que definir para categorias serão aplicadas quando utilizadores que fizeram login virem o seu blogue. Se inactivo, as permissões de leitura das categorias NÃO serão aplicadas, mas o efeito positivo é uma ligeira velocidade adicional no seu blogue. Assim se não precisar de permissões de leitura multi-utilizador para o seu blogue, inactive esta preferência.');
@define('PLUGIN_API_VALIDATE_ERROR', 'Sintaxe de configuração errada para a opção "%s". Precisa de conteúdo do tipo "%s".');
@define('PLUGIN_API_GENERIC_SUBOPTION_DESC', '<b>ATTENTION</b>: Certain options open or close pending suboptions [+] only after submission sets. Also, certain options can deactivate already set options or reset them to the default value, so that in case of a reconsideration a new setting or activation might be necessary.');
@define('USERCONF_CHECK_PASSWORD', 'Senha antiga');
@define('USERCONF_CHECK_PASSWORD_DESC', 'Se mudar a senha no campo acima, precisa de inserir a senha corrente neste campo.');
@define('USERCONF_CHECK_PASSWORD_DESC_ADDNOTE', 'Use carefully, since any following permissible backend action will force you to a new login afterwards - so only usable once, per Login-Session!');
@define('USERCONF_CHECK_PASSWORD_ERROR', 'Não especificou a senha antiga correctamente, e não está autorizado a mudar a nova senha. As suas preferências não foram guardadas.');
@define('ERROR_XSRF', 'O seu navegador não enviou uma sequência válida de HTTP-Referrer. Isto pode ter sido causado por um proxy ou navegador mal configurado ou por um Cross Site Request Forgery (XSRF) dirigido a si. A acção que solicitou não pôde ser completada.');

@define('AUTHORS', 'Autores');
@define('AUTHORS_ALLOW_SELECT', 'Permitir aos visitantes ver múltiplos autores simultaneamente?');
@define('AUTHORS_ALLOW_SELECT_DESC', 'Se esta opção estiver escolhida, uma marca será posta junto a cada autor no plugin de barra lateral. Os utilizadores podem alterar essas marcas para ver as entradas correspondentes à sua selecção.');
@define('AUTHOR_PLUGIN_DESC', 'Mostra uma lista de autores');
@define('CATEGORY_PLUGIN_TEMPLATE', 'Activar Smarty-Templates?');
@define('CATEGORY_PLUGIN_TEMPLATE_DESC', 'Se esta opção estiver activada, o plugin usará propriedades de Smarty-Templating para mostrar a lista de categorias. Se activar isto, pode mudar a formatação via o ficheiro modelo "plugin_categories.tpl". A activação desta opção terá um impacto na performance, de maneira que se não precisar de fazer adaptações, deixe-a inactiva.');
@define('CATEGORY_PLUGIN_SHOWCOUNT', 'Mostrar número de entradas por categoria?');
@define('AUTHORS_SHOW_ARTICLE_COUNT', 'Mostrar número de artigos ao lado do nome nome do autor?');
@define('AUTHORS_SHOW_ARTICLE_COUNT_DESC', 'Se esta opção for activada, o número de artigos deste autor será mostrado entre parêntesis junto ao nome do Autor.');
@define('CUSTOM_ADMIN_INTERFACE', 'Interface Administrativa ad hoc disponível');
@define('COMMENT_NOT_ADDED', 'Os seus comentários não foram adicionados, porque ou comentários para este artigo não estão autorizados, ou introduziu dados inválidos, ou os seus comentários foram interceptados por medidas anti-spam. ');
@define('INSTALL_TRACKREF', 'Activar localização do referenciador?');
@define('INSTALL_TRACKREF_DESC', 'A activação da localização do referenciador permite mostrar que sítios se referem aos seus artigos. Hoje em dia esta possibilidade é abusada para inserção de spam, de maneira que pode desligá-la se quiser.');
@define('CATEGORIES_HIDE_PARENT', 'Esconder a categoria mãe seleccionada?');
@define('CATEGORIES_HIDE_PARENT_DESC', 'Se restringir a listagem de categorias a uma categoria específica, por omissão verá a categoria mãe na listagem de saída. Se inactivar esta opção, o nome da categoria mãe não será mostrado.');
@define('WARNING_NO_GROUPS_SELECTED', 'Aviso: Não seleccionou afiliações em grupos. Isto encerrou a sua gestão de grupos, e as suas afiliações em grupos não foram alteradas.');
@define('INSTALL_RSSFETCHLIMIT', 'Entradas a mostrar nos Feeds');  // Verify
@define('INSTALL_RSSFETCHLIMIT_DESC', 'Número de entradas a mostrar por página do RSS Feed.'); // Verify
@define('INSTALL_DB_UTF8', 'Activar conversão da codificação de caracteres na base de dados');
@define('INSTALL_DB_UTF8_DESC', 'Envia um comando MySQL "SET NAMES" para indicar a codificação de caracteres requerida para a base de dados. Ligue ou desligue, se vir caracteres estranhos no seu blogue.');
@define('ONTHEFLYSYNCH', 'Activar a sincronização de multimédia instantânea');
@define('ONTHEFLYSYNCH_DESC', 'Se activado, o Serendipity comparará a base de dados de multimédia com os ficheiros guardados no seu servidor e sincronizará a base de dados com o conteúdo da directoria. This is - especially due to the additional variation formats - a rather time-consuming monitoring instrument and can increasingly slow down a growing media library, since each call of the same must permanently run through all(!) files, check and re-evaluate, including the resulting necessary changes. But since the latter happens correspondingly often, this step becomes accordingly shorter. Otherwise use the first two "Media library: Rebuild Thumbs" actions in the maintenance section from time to time, which also include a final synchronization! So a "Yes" is recommended here if you either often work around directly in the file system of the media library yourself, use this option only temporarily or do not notice any particular slowdown, or are a developer/tester with correspondingly many false/positive results.');
@define('USERCONF_CHECK_USERNAME_ERROR', 'O nome do utilizador não pode ser deixado em branco.');
@define('FURTHER_LINKS', 'Ligações adicionais');
@define('FURTHER_LINKS_S9Y', 'Sítio do Serendipity');
@define('FURTHER_LINKS_S9Y_DOCS', 'Documentação do Serendipity');
@define('FURTHER_LINKS_S9Y_BLOG', 'Blogue Oficial');
@define('FURTHER_LINKS_S9Y_FORUMS', 'Fóruns');
@define('FURTHER_LINKS_S9Y_SPARTACUS', 'Spartacus');
@define('COMMENT_IS_DELETED', '(Comentário removido)');

@define('CURRENT_AUTHOR', 'Autor corrente');

@define('WORD_NEW', 'Novo');
@define('SHOW_MEDIA_TOOLBAR', 'Mostrar barra dentro do seleccionador de media-popup?');
@define('MEDIA_KEYWORDS', 'Palavras-chave para a Media');
@define('MEDIA_KEYWORDS_DESC', 'Entre uma listagem, de palavras separadas por ";" através das quais deseja pré-definir, via palavras-chave, os seus itens de media.');
@define('MEDIA_EXIF', 'Importar imagens EXIF/JPEG');
@define('MEDIA_EXIF_DESC', 'Uma vez activado, as imagens EXIF/JPEG existentes serão separadas e arquivadas na base de dados para exposição na galeria de media.');
@define('MEDIA_PROP', 'Propriedades da Media');
@define('MEDIA_PROP_STATUS', 'This Form values "alt", "comment"s and "title" as public media properties have not been saved yet, OR equal the default. Currently, an image title-attribute is auto-build by the files realname!');
@define('MEDIA_CREATEVARS', 'Add additional image variations');

@define('GO_ADD_PROPERTIES', 'Ir & introduzir propriedades');
@define('MEDIA_PROPERTY_DPI', 'DPI (PPP)');
@define('MEDIA_PROPERTY_COPYRIGHT', 'Direitos de Autor');
@define('MEDIA_PROPERTY_COMMENT1', 'Comentário Público');
@define('MEDIA_PROPERTY_COMMENT2', 'Comentário Interno');
@define('MEDIA_PROPERTY_TITLE', 'Título');
@define('MEDIA_PROP_DESC', 'Entre uma listagem separada por ";" nos campos de propriedade, relativa ao modo como deseja definir cada um dos seus ficheiros Media');
@define('MEDIA_PROP_MULTIDESC', '(Você pode colocar ":MULTI" após algum item para indicar que o mesmo irá conter texto longo em vez de apenas alguns caracteres)');

@define('STYLE_OPTIONS_NONE', 'Este tema/estilo não tem opções específicas. Para visualizar como o seu modelo (template) pode expecificar opções, leia a Documentação Técnica disponível em "https://ophian.github.io/hc/en/templating.html#docs-theme-options" acerca de "Configuration of Theme options".');
@define('STYLE_OPTIONS', 'Opções de Tema/Estilo');

@define('PLUGIN_AVAILABLE_COUNT', 'Total: %d plugins.');

@define('SYNDICATION_RFC2616', 'Activar obediência estrita ao RFC2616 RSS-Feed');
@define('SYNDICATION_RFC2616_DESC', 'NÃO forçar o RFC2616 significa que todos os "Conditional GETs" pelo Serendipity irão devolver as últimas entradas modificadas deste o seu último pedido. Uma vez colocada a configuração como "false", os seus visitantes irão obter todos os artigos no seu último pedido, o que é um bom resultado. No entanto, alguns Agentes tal como Planet irão agir de forma estranha, se tal acontecer, violando também o RFC2616. Se colocar esta opção como "TRUE", você irá cumprir aquele RFC, mas os leitores do seu RSS Feed talvez percam alguns itens durante as férias. De qualquer forma, ou adora Agredadores como o Planet, ou fere os leitores actuais do seu Blogue. Se você está a enfrentar queixas de ambos os lados, poderá alterar esta opção.');
@define('MEDIA_PROPERTY_DATE', 'Data Associada');
@define('MEDIA_PROPERTY_RUN_LENGTH', 'Comprimento');
@define('FILENAME_REASSIGNED', 'Associa um novo ficheiro automaticamente: %s');
@define('MEDIA_UPLOAD_SIZE', 'Máximo do tamanho do ficheiro carregado');
@define('MEDIA_UPLOAD_SIZE_DESC', 'Introduza o tamanho máximo para upload de um ficheiro em bytes. Esta configuração poderá ser reescrita pelas definições incluídas no servidor via o ficheiro php.ini: upload_max_filesize, post_max_size, max_input_time em todos os precedentes acima desta opção. Um string vazio significa que apenas serão usado os limites definidos no servidor.');
@define('MEDIA_UPLOAD_SIZEERROR', 'Erro: Você não pode fazer upload de ficheiros maiores que %s bytes!');
@define('MEDIA_UPLOAD_MAXWIDTH', 'Máximo de largura de imagens no upload');
@define('MEDIA_UPLOAD_MAXWIDTH_DESC', 'Entre o máximo de largura permitido no upload em pixels.');
@define('MEDIA_UPLOAD_MAXHEIGHT', 'Máximo de altura de imagens no upload');
@define('MEDIA_UPLOAD_MAXHEIGHT_DESC', 'Entre o máximo de altura permitido por upload em pixels.');
@define('MEDIA_UPLOAD_DIMERROR', 'Error: One setting prevents to upload image files larger than %s x %s pixels! Check your Configuration section: "%s" settings. You may want to additionally activate the "%s"-Option to make this work.');

@define('MEDIA_TARGET', 'Alvo para esta ligação');
@define('MEDIA_TARGET_JS', 'Janela de Popup (via JavaScript, tamanho adaptável)');
@define('MEDIA_ENTRY', 'Entrada isolada');
@define('MEDIA_TARGET_BLANK', 'Janela de Popup (via target=_blank)');

@define('MEDIA_DYN_RESIZE', 'Permitir redimencionamento dinãmico de imagens?');
@define('MEDIA_DYN_RESIZE_DESC', 'Se activado, o serendipity_admin_image_selector.php file pode retornar as imagens em qualquer tamanho pretendido via a variante GET. Os resultados são colocados em cache, podendo criar um grande conjunto de ficheiros se fizer uso extensivo desta possibilidade.');

@define('MEDIA_DIRECTORY_MOVED', 'O Directório tal como os ficheiros foram movidos com sucesso para %s');
@define('MEDIA_DIRECTORY_MOVE_ERROR', 'O Directório tal como os ficheiros não puderam ser movidos para %s!');
#@define('MEDIA_DIRECTORY_MOVE_ENTRY', 'Em bases de dados distintas de MySQL não é possível intervir através de cada artigo para substituir o directório antigo dos URLs com um novo directório. Será necessário editar as suas entradas manualmente para corrigir novos URLs. Você pode ainda repôr o seu antigo directório onde ele se encontrava, se isso for muito incómodo para si.');
@define('MEDIA_DIRECTORY_MOVE_ENTRIES', 'Moveu-se o URL do directório alterado para estas %s entradas..');
@define('MEDIA_FILE_RENAME_ENTRY', 'The filename was changed in %s entries.');
@define('PLUGIN_ACTIVE', 'Activo');
@define('PLUGIN_INACTIVE', 'Inactivo');

@define('INSTALL_PERMALINK_COMMENTSPATH', 'Caminho para comentários');
@define('PERM_SET_CHILD', 'Configure as mesmas permissões para todos os subdirectórios recursivamente');
@define('PERMISSION_FORBIDDEN_PLUGINS', 'Plugins proibidos');
@define('PERMISSION_FORBIDDEN_HOOKS', 'Eventos proibidos');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE', 'Activar Plugin ACL para grupos?');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE_DESC', 'Se a opção "Plugin ACL para grupos" está activada na configuração, você pode especificar que grupos estão autorizados a executar certos plugins/eventos.');
@define('PERMISSION_READ_WRITE_ACL_DESC', 'By default, the read/write permissions are set to "0", i.e. "All authors". However, if you set them as an administrator, for example to Standard editor, equal to "1", you can no longer change back afterwards, since you have withdrawn the right yourself. So make sure to always include higher-ranking user groups if you want them to continue to have access to it.');

@define('DELETE_SELECTED_ENTRIES', 'Eliminar entradas seleccionadas');
@define('PLUGIN_AUTHORS_MINCOUNT', 'Mostar apenas Autores com um mínimo de X artigos');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET', 'Bookmarklet');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET_DESC', 'Marque esta ligação e use-a em qualquer página em que quiser blogar para aceder rapidamente ao seu blogue Serendipity.');
@define('IMPORT_WP_PAGES', 'Ir também buscar ficheiros anexados e páginas estáticas como entradas de blog normais?');
@define('USERCONF_CREATE', 'Disable user / forbid activity?');
@define('USERCONF_CREATE_DESC', 'If selected, the user will not have any editing or creation possibilities on the blog anymore. When logging in to the backend, he cannot do anything else apart from logging out and viewing his personal configuration.');
@define('CATEGORY_HIDE_SUB', 'Hide postings made to sub-categories?');
@define('CATEGORY_HIDE_SUB_DESC', 'By default, when you browse a category also entries of any subcategory are displayed. If this option is turned on, only postings of the currently selected category are displayed.');
@define('PINGBACK_SENDING', 'Sending pingback to URI %s...');
@define('PINGBACK_SENT', 'Pingback successful');
@define('PINGBACK_FAILED', 'Pingback failed: %s');
@define('PINGBACK_NOT_FOUND', 'No pingback-URI found.');
@define('CATEGORY_PLUGIN_HIDEZEROCOUNT', 'Hide archives link when no entries were made in that timespan (requires counting entries)');
@define('RSS_IMPORT_WPXRSS', 'WordPress eXtended RSS import, requires PHP5 and might take up much memory');
@define('SET_TO_MODERATED', 'Moderate');
@define('COMMENT_MODERATED', 'Comment #%s has successfully been set as moderated');
@define('CENTER', 'center');
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
@define('INSTALL_BACKENDPOPUP_DESC', 'Do you want to use popup windows for some backend functionality? When disabled (default), inline modal dialogs will be used for e.g. the category selector and media library. On the other hand this popup-window option only works for some elements, like the media library and some plugins. Others, like categories, will show up embedded.');
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
@define('BULKMOVE_INFO_DESC', 'You can select multiple files to bulk-move them to a new location. <strong>Note:</strong> This action takes effect immediately without any further demand. All checked files will be physically moved, and referring Blog entries are rewritten to point to the new location. The entrychange is also supported by staticpage versions up from v.4.52.');
@define('FIRST_PAGE', 'First Page');
@define('LAST_PAGE', 'Last Page');
@define('MEDIA_PROPERTIES_DONE', 'Properties of #%d changed.');
@define('DIRECTORY_INFO', 'Directory info');
@define('DIRECTORY_INFO_DESC', 'Directories reflect their physical folder directory name. If you want to change or move directories which contain items, you have two choices. Either create the directory or subdirectory you want, then move the items to the new directory via the media library and afterwards, delete the empty old directory there. Or completely change the whole old directory via the edit directory button below and rename it to whatever you like (existing subdir/ + newname). This will move all directories and items and change referring Blog entries.');
@define('MEDIA_RESIZE_EXISTS', 'File dimensions already exist!');
@define('USE_CACHE', 'Enable caching');
@define('USE_CACHE_DESC', 'Enables an internal cache to not repeat specific database queries. This reduces the load on servers with medium to high traffic and improves page load time.');
@define('CONFIG_PERMALINK_PATH_DESC', 'Please note that you have to use a prefix so that Serendipity can properly map the URL to the proper action. You may change the prefix to any unique name, but not remove it. This applies to all path prefix definitions.');

@define('HIDE_SUBDIR_FILES', 'Hide Files of Subdirectories');

@define('UPDATE_NOTIFICATION_URL', 'Update RELEASE-file URL');
@define('UPDATE_NOTIFICATION_URL_DESC', 'This is Styx! Do not change, if not applying a different RELEASE file location for custom core downloads in combination with the Serendipity Autoupdate plugin. The origin Serendipity default value to apply here would then be: "https://raw.githubusercontent.com/s9y/Serendipity/master/docs/RELEASE". A here provided URL points to a file containing the current released Serendipity stable and beta version numbers per line, eg. "stable:5.3.0".');

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

@define('MEDIA_GALLERY_SELECTION', 'This particular selection for media galleries only shows directory images of the same level. It does not contain a statement to also display the images of sub folders, as you might be used to. The number of possible preview images that can be displayed at the same time is limited to <b>48 items</b>. Restructure your media library accordingly!<br>This media gallery directory selection only shows thumbnails (optionally configurable linking to the big picture). If your preview images do not meet the standard width of 400px, and are much smaller than the defined gallery format of 260px for the per row option, it is possible that you get into display problems in this selection as well as afterwards in the frontend entry.<br>The <b>order</b> of displayed gallery items is not configurable here and can only be changed afterwards within the displayed source code of your editors dropping textarea window. If you there use the WYSIWYG-Editor, please do <strong>not</strong> simply use the internal <em>drag & drop</em> feature of the WYSIWYG-mode (editor) window, since that may destroy or at least mess up the gallery selection for your entry. Though <b>it is</b> possible to use the drag & drop feature, you then need to check the dragged & dropped image moves in source afterwards to be correctly placed within the gallery block container.');

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
@define('HTML_COMMENTS_DESC', 'If the rich text editor (WYSIWYG) option in personal preferences is set true, you may additionally allow tag-restricted HTML comments and "pre/code" tag parts displayed in backend and frontend pages, but edited by Editor in backend only. Keep in mind: This options liberates old comments to display their content. So better check them up before (!), that you don\'t have accidentally approved spoofed content in your database stored comments. Otherwise, rich text editor comments are also suitable to get rid of annoying bot program spammers. This option currently only works completely with the standard "Pure" theme and is therefore only recommended there!');

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
@define('ENABLEAVIF_DESC', 'Image AVIF variations can be very demanding on resources, since a lot of ram and CPU/GPU cores are needed to encode images into the AV1 format. Mass uploads and mass conversions (see "Maintenance") are therefore not recommended. Learn to handle on some examples before you generally allow to keep it enabled. PHP 8.1 still lacks a crucial build-in feature to read size information from AVIF files using the usual methods. For the time being, this also means that the image functions of the media library "Resize this image" and "Rotate image 90 degrees" cannot be used for all formats when using AVIF, since each of these actions affects the original image as well as its variations. PHP 8.2 solves this issue by adding the missing feature.');

