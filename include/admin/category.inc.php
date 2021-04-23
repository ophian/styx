<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('adminCategories')) {
    return;
}

$admin_category = (!serendipity_checkPermission('adminCategoriesMaintainOthers') ? 'AND (authorid = 0 OR authorid = ' . (int)$serendipity['authorid'] . ')' : '');
$data = array();
$data['closed'] = false;

/* KEEP in mind:
    IMHO, there is a difference between ACL write permissions, which always additionally plays with authorid = 0 (all),
          to EITHER allow writing entries to a category OR writing to change the category itself for a least-privileged user.
    Since ACL - including authorid 0 - cannot distinguish this difference of permission, you either have it both or not.
    So the ACL perms have to be combined with serendipity_checkPermission() checks to restrict/allow ACTIONS, see 'edit' action.
    Thus we allow the 'edit' or 'delete' buttons to show up for non-privileged users.
*/

/* Add a new category */
if (isset($_POST['SAVE']) && serendipity_checkFormToken()) {
    $name = $serendipity['POST']['cat']['name'];
    $data['post_save'] = true;
    $desc = $serendipity['POST']['cat']['description'];
    if (is_array($serendipity['POST']['cat']['write_authors']) && in_array(0, $serendipity['POST']['cat']['write_authors'])) {
        $authorid = 0;
    } else {
        $authorid = $serendipity['authorid'];
    }

    $icon     = $serendipity['POST']['cat']['icon'];
    $parentid = (isset($serendipity['POST']['cat']['parent_cat']) && is_numeric($serendipity['POST']['cat']['parent_cat'])) ? $serendipity['POST']['cat']['parent_cat'] : 0;

    if ($parentid > 0 && $serendipity['GET']['adminAction'] == 'newSub'
    && false === (serendipity_checkPermission('adminCategoriesMaintainOthers') && serendipity_ACLCheck($serendipity['authorid'], $serendipity['GET']['cid'], 'category', 'write'))) {
        // non-privileged users shall not be able to add sub-categories to categories they do not have permissions to maintain
        $data['post_save'] = false;
        echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . PERM_DENIED . ' ' . sprintf(UNMET_REQUIREMENTS, '"' . trim(explode(':', PERMISSION_ADMINCATEGORIESMAINTAINOTHERS)[1])) . "\".</span>\n";
    }
    if ($data['post_save'] === true && ($serendipity['GET']['adminAction'] == 'new' || $serendipity['GET']['adminAction'] == 'newSub')) {
        // only continue, if the category-name does not already exists, as user have no means to distinguish between them
        $r = serendipity_db_query("SELECT category_name FROM {$serendipity['dbPrefix']}category WHERE category_name = '". serendipity_db_escape_string($name)."'");
        if (is_array($r) && is_array($r[0])) {
            $data['error_name'] = true;
            $data['category_name'] = $name;
        } else {
            $data['new'] = true;
            $catid = serendipity_addCategory($name, $desc, $authorid, $icon, $parentid);
            serendipity_ACLGrant($catid, 'category', 'read', $serendipity['POST']['cat']['read_authors']);
            serendipity_ACLGrant($catid, 'category', 'write', $serendipity['POST']['cat']['write_authors']);
        }
    } elseif ($serendipity['GET']['adminAction'] == 'edit') {
        $data['edit'] = true;
        if (false === (serendipity_checkPermission('adminCategoriesMaintainOthers') && serendipity_ACLCheck($serendipity['authorid'], $serendipity['GET']['cid'], 'category', 'write'))) {
            $data['editPermission'] = false;
            $data['failedperm'] = sprintf(UNMET_REQUIREMENTS, '"' . trim(explode(':', PERMISSION_ADMINCATEGORIESMAINTAINOTHERS)[1])) . '"';
        } else {
            /* Check to make sure parent is not a child of self */
            $r = serendipity_db_query("SELECT categoryid FROM {$serendipity['dbPrefix']}category c
                                        WHERE c.categoryid = ". (int)$parentid ."
                                          AND c.category_left BETWEEN " . implode(' AND ', serendipity_fetchCategoryRange((int)$serendipity['GET']['cid'])));
            if (is_array($r)) {
                $r = serendipity_db_query("SELECT category_name FROM {$serendipity['dbPrefix']}category
                                            WHERE categoryid = ". (int)$parentid);
                $data['subcat'] = sprintf(ALREADY_SUBCATEGORY, serendipity_specialchars($r[0]['category_name']), serendipity_specialchars($name));
            } else {
                $_sort_order = $serendipity['POST']['cat']['sort_order'] ?? 0;
                $_hide_sub   = $serendipity['POST']['cat']['hide_sub']   ?? 0;
                serendipity_updateCategory($serendipity['GET']['cid'], $name, $desc, $authorid, $icon, $parentid, $_sort_order, $_hide_sub, $admin_category);
                serendipity_ACLGrant($serendipity['GET']['cid'], 'category', 'read', $serendipity['POST']['cat']['read_authors']);
                serendipity_ACLGrant($serendipity['GET']['cid'], 'category', 'write', $serendipity['POST']['cat']['write_authors']);
            }
        }
    }

    serendipity_rebuildCategoryTree();
    $serendipity['GET']['adminAction'] = 'view';
}

/* Delete a category */
if ($serendipity['GET']['adminAction'] == 'doDelete' && serendipity_checkFormToken()) {
    $data['doDelete'] = true;
    if ($serendipity['GET']['cid'] != 0) {
        $remaining_cat = (int)$serendipity['POST']['cat']['remaining_catid'];
        $category_ranges = serendipity_fetchCategoryRange((int)$serendipity['GET']['cid']);
        $category_range  = implode(' AND ', $category_ranges);
        if (in_array($serendipity['dbType'], ['postgres', 'pdo-postgres', 'sqlite', 'sqlite3', 'sqlite3oo', 'pdo-sqlite'])) {
            $query = "UPDATE {$serendipity['dbPrefix']}entrycat
                        SET categoryid={$remaining_cat} WHERE entryid IN
                        (
                          SELECT DISTINCT(e.id) FROM {$serendipity['dbPrefix']}entries e,
                            {$serendipity['dbPrefix']}category c,
                            {$serendipity['dbPrefix']}entrycat ec
                           WHERE e.id=ec.entryid AND c.categoryid=ec.categoryid
                             AND c.category_left BETWEEN {$category_range}
                            {$admin_category}
                        )";
        } else {
            $query = "UPDATE {$serendipity['dbPrefix']}entries e,
                        {$serendipity['dbPrefix']}entrycat ec,
                        {$serendipity['dbPrefix']}category c
                      SET ec.categoryid={$remaining_cat}
                        WHERE e.id = ec.entryid
                          AND c.categoryid = ec.categoryid
                          AND c.category_left BETWEEN {$category_range}
                          {$admin_category}";
        }

        serendipity_db_query($query);
        if (serendipity_deleteCategory($category_range, $admin_category) ) {

            foreach($category_ranges AS $cid) {
                if (serendipity_ACLCheck($serendipity['authorid'], $cid, 'category', 'write')) {
                    serendipity_ACLGrant($cid, 'category', 'read', array());
                    serendipity_ACLGrant($cid, 'category', 'write', array());
                }
            }
            $data['deleteSuccess'] = true;
            $data['remaining_cat'] = $remaining_cat;
            $data['cid'] = (int)$serendipity['GET']['cid'];
            $serendipity['GET']['adminAction'] = 'view';
        }
    } else {
        $data['deleteSuccess'] = false;
    }
}

if ($serendipity['GET']['adminAction'] == 'delete') {
    $data['delete'] = true;
    $this_cat = serendipity_fetchCategoryInfo($serendipity['GET']['cid']);
    $data['deletePermission'] = false;
    if (
       (serendipity_checkPermission('adminCategoriesDelete') && serendipity_checkPermission('adminCategoriesMaintainOthers'))
    || (serendipity_checkPermission('adminCategoriesDelete') && ($serendipity['authorid'] == $this_cat['authorid'] || $this_cat['authorid'] == '0'))
    || (serendipity_checkPermission('adminCategoriesDelete') && serendipity_ACLCheck($serendipity['authorid'], $serendipity['GET']['cid'], 'category', 'write'))) {
        $data['deletePermission'] = true;
        $data['cid'] = (int)$serendipity['GET']['cid'];
        $data['formToken'] = serendipity_setFormToken();
        $data['categoryName'] = $this_cat['category_name'];
        $cats = serendipity_fetchCategories('all');
        $data['cats'] = array();
        /* TODO, show dropdown as nested categories */
        foreach($cats AS $cat_data) {
            if ($cat_data['categoryid'] != $serendipity['GET']['cid'] && (serendipity_checkPermission('adminCategoriesMaintainOthers') || $cat_data['authorid'] == '0' || $cat_data['authorid'] == $serendipity['authorid'])) {
                $data['cats'][] = $cat_data;
            }
        }
    } else {
        echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . PERM_DENIED . ' ' . sprintf(UNMET_REQUIREMENTS, '"' . trim(explode(':', PERMISSION_ADMINCATEGORIESMAINTAINOTHERS)[1])) . "\".</span>\n";
    }
}

if ($serendipity['GET']['adminAction'] == 'edit' || $serendipity['GET']['adminAction'] == 'new' || $serendipity['GET']['adminAction'] == 'newSub') {
    if ($serendipity['GET']['adminAction'] == 'edit') {
        $data['edit'] = true;
        $data['closed'] = true;
        $cid = (int)$serendipity['GET']['cid'];
        $this_cat = serendipity_fetchCategoryInfo($cid);
        $data['category_name'] = $this_cat['category_name'];
        $save = SAVE;
        $read_groups  = serendipity_ACLGet($cid, 'category', 'read');
        $write_groups = serendipity_ACLGet($cid, 'category', 'write');
    } else {
        $data['new'] = true;
        $cid = false;
        $this_cat = array();
        echo '<h2>'. CREATE_NEW_CAT .'</h2>';
        $save = CREATE;
        $read_groups  = array(0 => 0);
        $write_groups = array(0 => 0);
    }

    if ($serendipity['GET']['adminAction'] == 'newSub') {
        $data['new'] = true;
        $data['newSub'] = true;
        $this_cat['parentid'] = (int)$serendipity['GET']['cid'];
    }
    $data['cid'] = $cid;
    $data['this_cat'] = $this_cat;
    $data['save'] = $save;

    $groups = serendipity_getAllGroups();
    $data['groups'] = $groups;
    $data['read_groups'] = $read_groups;
    $data['write_groups'] = $write_groups;

    $data['formToken'] = serendipity_setFormToken();
    $data['cat'] = $this_cat;
    if (!is_array($this_cat) || (isset($this_cat['authorid']) && $this_cat['authorid'] == '0') || isset($read_groups[0])) {
        $data['selectAllReadAuthors'] = true;
    } else {
        $data['selectAllReadAuthors'] = false;
    }
    if (!is_array($this_cat) || (isset($this_cat['authorid']) && $this_cat['authorid'] == '0') || isset($write_groups[0])) {
        $data['selectAllWriteAuthors'] = true;
    } else {
        $data['selectAllWriteAuthors'] = false;
    }

    $categories = serendipity_fetchCategories('all');
    $categories = serendipity_walkRecursive($categories, 'categoryid', 'parentid', VIEWMODE_THREADED);
    $data['categories'] = $categories;
    // hook content as var to category.inc.tpl, to place inside the form
    ob_start();
    serendipity_plugin_api::hook_event('backend_category_showForm', $cid, $this_cat);
    $data['category_showForm'] = ob_get_contents();
    ob_end_clean();
}

if ($serendipity['GET']['adminAction'] == 'view') {
    if (empty($admin_category)) {
        $cats = serendipity_fetchCategories('all');
    } else {
        $cats = serendipity_fetchCategories(null, null, null, 'write'); // $serendipity['authorid'] is added in there - only use per given parameter, when current user is different to meant user!!
    }
    $data['view'] = true;
    $data['viewCats'] = $cats;

    if (is_array($cats)) {
        $categories = serendipity_walkRecursive($cats, 'categoryid', 'parentid', VIEWMODE_THREADED);
        $data['viewCategories'] = $categories;
        $data['threadedCat'] = in_array(1, array_column($categories, 'depth'));
        $cnums = serendipity_getTotalCount('entriesbycat');
        $data['catentries'] = array_column((is_array($cnums) ? array_values($cnums) : []), 'num', 'cat');
        $enumnocat = serendipity_getTotalCount('entriesnocat');
        $data['entriesnocat'] = array_column((is_array($enumnocat) ? array_values($enumnocat) : []), 'num', 'cat');
        $data['entriesbyauthor'] = serendipity_checkPermission('siteConfiguration')
                                    ? GROUP . ': <span class="icon-users admin" title="' . USERLEVEL_ADMIN_DESC . '" aria-hidden="true"></span> ++'
                                    : (
                                        (serendipity_checkPermission('adminEntriesMaintainOthers') && serendipity_checkPermission('adminCategoriesMaintainOthers'))
                                        ? GROUP . ': <span class="icon-users chief" title="' . USERLEVEL_CHIEF_DESC . '" aria-hidden="true"></span> +'
                                        : AUTHOR . ': ' .serendipity_specialchars($serendipity['serendipityUser'])
                                    );
    }
}
// avoid errors on empty categories for non-issets
if (!isset($data['this_cat']['category_name'])) {
    $data['this_cat']['category_name'] = null;
}
if (!isset($data['this_cat']['category_description'])) {
    $data['this_cat']['category_description'] = null;
}
if (!isset($data['this_cat']['category_icon'])) {
    $data['this_cat']['category_icon'] = null;
}

echo serendipity_smarty_showTemplate('admin/category.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
