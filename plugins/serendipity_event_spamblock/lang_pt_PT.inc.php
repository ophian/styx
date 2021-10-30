<?php

/**
 *  @version  
 *  @file 
 *  @author Joao P Matos <jmatos@math.ist.utl.pt>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Protec��o anti Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Oferece uma imensidade de possibilidades para proteger o seu blogue contra Spam nos coment�rios. Esta � a espinha dorsal das medidas Anti-Spam. N�o remover!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Protec��o contra o Spam: mensagem n�o v�lida.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Protec��o contra o Spam: n�o pode juntar um coment�rio suplementar num intervalo t�o curto.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_RBL', 'Protec��o contra o Spam: o endere�o IP do computador que usa para escrever o coment�rio est� listado como um rel� aberto.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_SURBL', 'Protec��o contra o Spam: o seu coment�rio cont�m um endere�o listado no SURBL.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Este blogue est� em modo "Bloqueio urgente de coment�rios", pelo que se agradece que tente mais tarde.');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'N�o autorizar a duplica��o de coment�rios');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'N�o autorizar os utilizadores a juntar coment�rios que t�m o mesmo conte�do dum coment�rio existente.');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Bloqueio de urg�ncia de coment�rios');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Permite-lhe inactivar temporariamente os coment�rios de todos os artigos. Pr�tico se o seu blogue est� sob um ataque de Spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'Intervalo de bloqueio de endere�o IP');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'S� autorizar um endere�o IP a submeter coment�rios cada n minutos. Pr�tico para evitar um dil�vio de coment�rios.');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL', 'Recusar coment�rios por lista negra');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL_DESC', 'Si active, cette option permet de refuser les commentaires ventant d\'h�tes list�s dans les RBLs (listes noires). Notez que cela peut avoir un effet sur les utilisateurs derri�re un proxy ou dont le fournisseur internet est sur liste noire.');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL', 'Recusar coment�rios usando SURBL');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL_DESC', 'Recusa coment�rios contendo liga��es para m�quinas listadas na base de dados <a href="http://www.surbl.org">SURBL</a>');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST', 'RBLs a contactar');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST_DESC', 'Bloqueia os coment�rios com base nas listas RBL definidas aqui.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Activar os captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'For�a os utilizadores a introduzir um texto mostrado por uma imagem gerada automaticamente para evitar que sistemas automatizados possam adicionar coment�rios. � de notar que isto causa problemas a pessoas com defici�ncias visuais. To avoid having to use Captchas at all, try out the extending Serendipity Spamblog Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Para evitar o spam por robots automatizados (spambots), agradecemos que introduza os caracteres que v� abaixo no campo de formul�rio para esse efeito. Certifique-se que o seu navegador gere e aceita cookies, caso contr�rio o seu coment�rio n�o poder� ser registado.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Introduza o texto que est� a ver no campo!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Introduza o texto da imagem anti-spam acima: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'N�o introduziu correctamente o texto da imagem anti-spam. Por favor corrija o seu c�digo verificando de novo a imagem.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Os captchas n�o est�o dispon�veis no seu servidor. � preciso que a GDLib e as bibliotecas freetype estejam compiladas na sua instala��o de PHP.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Captchas autom�ticos depois de X dias');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Os captchas podem ser activados automaticamente depois de um certo n�mero de dias para cada artigo. Para activ�-los sempre, introduza um 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Modera��o autom�tica depois de X dias');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'A modera��o dos coment�rios pode ser activada automaticamente depois de um certo n�mero de dias ap�s a publica��o de um artigo. Para n�o utilizar a modera��o autom�tica, introduza um 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Modera��o autom�tica depois de X liga��es');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'A modera��o dos coment�rios pode ser activada automaticamente se o n�mero de liga��es contidos num coment�rio ultrapassa um n�mero estabelecido. Para n�o utilizar esta fun��o, introduza um 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Recusa autom�tica para al�m de X liga��es');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Um coment�rio pode ser recusado automaticamente se o n�mero de liga��es ultrapassa um certo n�mero. Para n�o usar esta fun��o, introduza 0.');
@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Devido a certas condi��es, o seu coment�rio est� sujeito a modera��o pelo autor do blogue antes de ser publicado.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Cor de fundo do captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Introduza valores RGB: 0,255,255');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Ficheiro de log');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'A informa��o sobre os coment�rios recusados/moderados pode ser registada num ficheiro de log, indique uma localiza��o para esse ficheiro se quiser usar esta fun��o.');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Bloqueio de urg�ncia de coment�rios');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Duplica��o de coment�rio');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'Bloqueio por IP');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_RBL', 'Bloqueio por RBL');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_SURBL', 'Bloqueio por SURBL');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Captcha inv�lido (Introduzido: %s, V�lido: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Modera��o autom�tica depois de X dias');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'M�ximo de liga��es');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'demasiadas liga��es');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Mascarar os endere�os de email');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Masque les adresses Emqil des utilisateurs qui ont �crit des commentaires');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Les adresses Email ne sont pas affich�es, et sont seulement utilis�es pour la communication.');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Escolha um m�todo para os logs');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Os logs de coment�rios recusados podem ter como suporte um ficheiro de texto, ou uma base de dados.');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Ficheiro (ver a op��o \'Ficheiro de log\')');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Base de dados');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'N�o usar logs');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Gest�o dos coment�rios por interface');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'Define como o Serendipity gere os coment�rios feitos pela interface (retroliga��es, coment�rios WFW:commentAPI). Se seleccionar "modera��o", estes coment�rios estar�o sempre sujeitos a modera��o. Com "recusar", n�o s�o autorizados. Com "nenhum", estes coment�rios ser�o geridos como coment�rios tradicionais.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'modera��o');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'recusa');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'nenhum');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Filtragem de palavras chave');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Marca todos os coment�rios contendo as palavras chave definidas como Spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Filtragem por palavras chave para as liga��es');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Marca todos os coment�rios cujas liga��es cont�m palavras chave consideradas como definidoras de Spam. As express�es regulares s�o autorizadas, separe as palavras chave por ponto e v�rgula (;).');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Filtragem por nome de autor');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', 'Marca todos os coment�rios cujo nome de autor cont�m palavras chave consideradas como indicadoras de Spam. As express�es regulares s�o autorizadas, separe as palavras chave por ponto e v�rgula (;).');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Endere�o de email inv�lido');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'Verificar endere�os de email?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Campos de coment�rio obrigat�rios');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Introduza uma lista de campos de preenchimento obrigat�rio quando um utilizador submete coment�rios. Separe os diversos campos com uma v�rgula ",". As chaves dispon�veis s�o: nome, email, url, replyTo, coment�rio');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'N�o especificou o campo %s!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Configura��o de m�todos Anti-Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Bloquear este autor via plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Bloquear esta URL via plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Desbloquear este autor via plugin Spamblock');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Desbloquear esta URL via plugin Spamblock');

