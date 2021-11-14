<?php

/**
 *  @version
 *  @author Ivan Cenov jwalker@hotmail.bg
 */

@define('PLUGIN_EVENT_ENTRYPROPERTIES_TITLE', '������������ �������� �� ����������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DESC', '(��������, �� �������� ��������, sticky ��������)');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS', '��������� �� ���� ������� ���� sticky');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS', '���������� ����� �� �� ����� ��');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE', '����');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS', '��-��������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC', '������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE', '�������� �� ���������� ?');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC', '��� � ���������, �������� ������ �� �������� �� ���� ���������� ��� ������� �����. ���������� �� ������� ������������������, �� �� ������ ����������� �� ������� �������. If you use the rich-text editor (wysiwyg) a cache is actually useless, unless you use many plugins that further change the output markup.');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE', '�������� �� ������ ��������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT', '�������� �� ���������� ����� �������� ...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO', '�������� �� �������� �� %d �� %d');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING', '�������� �� ������� #%d, <em>%s</em>...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHED', '��������� � �������.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE', '���������� �������.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED', '���������� � ����������.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL', ' (���� %d ��������)...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE', '��������� �� �� �� ����� �� �������� �������� � � ���������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS', '���������� �� �������-���������� ����������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC', '������ � ����������, ��� ������ �� ���������� ��� ������������� ����� �� ���� ���������� �� ������ �� ����������. ���� ����� ��� ������ ������� ����� ������������������ �� �����. ��������� �, ���� ��� �������� �� � ����������.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS', '���������� �� ����������-�������� ����������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC', '������ � ����������, ��� ������ �� ���������� ��� ���������� ����������� �� ���� ���������� �� ������ �� ����������. ���� ����� ��� ������ ������� ����� ������������������ �� �����. ��������� �, ���� ��� �������� �� � ����������.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS', '���������� �� RSS ������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS_DESC', '��� ����� \'��\' ������������ �� ���� ������ ���� �� ���� �������� � RSS ������.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS', '������������ ������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1', '�������������� ������ ����� �� �� �������� � ������� �� ������ ���� �� �����, ������ ������ �� �� �� ��������. �� ���� ������������ ����� ���� entries.tpl � ��������� Smarty ������ ������� �� ���������� �������: {$entry.properties.ep_MyCustomField} �� �������� �����. �� ������ �� ��������� �������� \'ep_\' ����� ����� �� ��������. ');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2', '��� ������ �� �������� ������ �� ����� �� ������, ��������� ��� �������, ����� �� ���������� �� ��������� �� HTML �� ����� ������. � ������� �� ��������� ���� �������� ����� � ����� (�� � ��������� �������). ��������: \'Customfield1\', \'Customfield2\'. ' . PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1);
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3', '�������� �� ���������� ������������ ������ ���� �� ���� ���������� � <a href="%s" target="_blank" rel="noopener" title="' . PLUGIN_EVENT_ENTRYPROPERTIES_TITLE . '">��������������</a> �� ��������� serendipity_event_entryproperties.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_DISABLE_MARKUP', '��������� �������� ������������ �� ���� ������:');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS', '���������� �� ��������� ������� � ������ �����');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS_DESC', '��� � ���������, �� ����� ��������� ������������ SQL ������ �� ������� ������, ������ ������ � ������� ������, �� �� ����� ���������� �� �������� ��������. ��� �� � �����, ��������� �� ���� ������� �� ������� ������������������.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE', '����� �� ����������� �� ������');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE_DESC', '��� ������ �� �������� ��� �������� (������) � � ����� ��� �� ����� �������� �� ����� �� ������� �� �����������.');

