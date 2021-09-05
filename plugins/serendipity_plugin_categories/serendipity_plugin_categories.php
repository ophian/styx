<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_categories extends serendipity_plugin
{
    var $title = CATEGORIES;

    function introspect(&$propbag) {
        global $serendipity;

        $propbag->add('name',        CATEGORIES);
        $propbag->add('description', CATEGORY_PLUGIN_DESC);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team');
        $propbag->add('version',       '2.16');
        $propbag->add('configuration', array('title', 'authorid', 'parent_base', 'hide_parent', 'image', 'sort_order', 'sort_method', 'allow_select', 'hide_parallel', 'show_count', 'show_all', 'smarty'));
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {

            case 'title':
                $propbag->add('type',        'string');
                $propbag->add('name',        TITLE);
                $propbag->add('description', TITLE_FOR_NUGGET);
                $propbag->add('default',     CATEGORIES);
                break;

            case 'authorid':
                $row_authors = serendipity_db_query("SELECT realname, authorid FROM {$serendipity['dbPrefix']}authors");
                $authors     = array('all' => ALL_AUTHORS, 'login' => CURRENT_AUTHOR);
                if (is_array($row_authors)) {
                    foreach($row_authors AS $row) {
                        $authors[$row['authorid']] = $row['realname'];
                    }
                }

                $propbag->add('type',         'select');
                $propbag->add('name',         CATEGORIES_TO_FETCH);
                $propbag->add('description',  CATEGORIES_TO_FETCH_DESC);
                $propbag->add('select_values', $authors);
                $propbag->add('default',     'all');
                break;

            case 'parent_base':
                $categories = array('all' => ALL_CATEGORIES);
                $cats       = serendipity_fetchCategories();

                if (is_array($cats)) {
                    $cats = serendipity_walkRecursive($cats, 'categoryid', 'parentid', VIEWMODE_THREADED);
                    foreach($cats AS $cat) {
                        $categories[$cat['categoryid']] = str_repeat(' . ', $cat['depth']) . $cat['category_name'];
                    }
                }

                $propbag->add('type',         'select');
                $propbag->add('name',         CATEGORIES_PARENT_BASE);
                $propbag->add('description',  CATEGORIES_PARENT_BASE_DESC);
                $propbag->add('select_values', $categories);
                $propbag->add('default',      'all');
                break;

            case 'hide_parent':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         CATEGORIES_HIDE_PARENT);
                $propbag->add('description',  CATEGORIES_HIDE_PARENT_DESC);
                $propbag->add('default',      'false');
                break;

            case 'hide_parallel':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         CATEGORIES_HIDE_PARALLEL);
                $propbag->add('description',  CATEGORIES_HIDE_PARALLEL_DESC);
                $propbag->add('default',      'false');
                break;

            case 'allow_select':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         CATEGORIES_ALLOW_SELECT);
                $propbag->add('description',  CATEGORIES_ALLOW_SELECT_DESC);
                $propbag->add('default',      'false');
                break;

            case 'sort_order':
                $select = array();
                $select['category_name']        = CATEGORY;
                $select['category_description'] = DESCRIPTION;
                $select['categoryid']           = 'ID';
                $select['none']                 = NONE;
                $propbag->add('type',         'select');
                $propbag->add('name',         SORT_ORDER . ' (field)');
                $propbag->add('description',  '');
                $propbag->add('select_values', $select);
                $propbag->add('default',     'category_name');
                break;

            case 'sort_method':
                $select = array();
                $select['ASC'] = SORT_ORDER_ASC;
                $select['DESC'] = SORT_ORDER_DESC;
                $propbag->add('type',         'select');
                $propbag->add('name',         SORT_ORDER . ' (method)');
                $propbag->add('description',  '');
                $propbag->add('select_values', $select);
                $propbag->add('default',     'ASC');
                break;

            case 'image':
                $propbag->add('type',         'string');
                $propbag->add('name',         XML_IMAGE_TO_DISPLAY);
                $propbag->add('description',  XML_IMAGE_TO_DISPLAY_DESC);
                $propbag->add('default',     serendipity_getTemplateFile('img/xml.gif', 'serendipityHTTPPath', true));
                break;

            case 'smarty':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        CATEGORY_PLUGIN_TEMPLATE);
                $propbag->add('description', CATEGORY_PLUGIN_TEMPLATE_DESC);
                $propbag->add('default',     'false');
                break;

            case 'show_count':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        CATEGORY_PLUGIN_SHOWCOUNT);
                $propbag->add('description', '');
                $propbag->add('default',     'false');
                break;

            case 'show_all':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        CATEGORY_PLUGIN_SHOWALL);
                $propbag->add('description', CATEGORY_PLUGIN_SHOWALL_DESC);
                $propbag->add('default',     'false');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->get_config('title', CATEGORIES);
        $smarty = serendipity_db_bool($this->get_config('smarty', 'false'));

        $which_category = $this->get_config('authorid', 'all');
        $sort = $this->get_config('sort_order', 'category_name');
        if ($sort == 'none') {
            $sort = '';
        } else {
            $sort .= ' ' . $this->get_config('sort_method', 'ASC');
        }
        $is_form = serendipity_db_bool($this->get_config('allow_select', 'false'));
        if ($which_category === "login") {
            $which_category = (int)$serendipity['authorid'];
            if ($which_category === 0) {
                $which_category = -1; // Set to -1 for anonymous authors to get a proper match.
            }
        }

        $wcat = empty($which_category) ? 'all' : $which_category;
        $categories = serendipity_fetchCategories($wcat, '', $sort, 'read');

        $cat_count = array();
        if (serendipity_db_bool($this->get_config('show_count', 'false'))) {
            $cat_sql = "SELECT c.categoryid, c.category_name, count(e.id) AS postings
                          FROM {$serendipity['dbPrefix']}entrycat ec,
                               {$serendipity['dbPrefix']}category c,
                               {$serendipity['dbPrefix']}entries e
                         WHERE ec.categoryid = c.categoryid
                           AND ec.entryid = e.id
                           AND e.isdraft = 'false'
                               " . (!serendipity_db_bool($serendipity['showFutureEntries']) ? " AND e.timestamp  <= " . serendipity_db_time() : '') . "
                      GROUP BY c.categoryid, c.category_name
                      ORDER BY postings DESC";
            $category_rows = serendipity_db_query($cat_sql);
            if (is_array($category_rows)) {
                foreach($category_rows AS $cat) {
                    $cat_count[$cat['categoryid']] = $cat['postings'];
                }
            }
        }

        // empty $categories returns 1
        $html = '';

        if (!$smarty && $is_form) {
            $html .= '<form action="' . $serendipity['baseURL'] . $serendipity['indexFile'] . '?frontpage" method="post">'."\n";
        }
        if (!$smarty) {
            $html .= '<ul id="serendipity_categories_list" class="plainList">'."\n";
        }

        $image = $this->get_config('image', serendipity_getTemplateFile('img/xml.gif', 'serendipityHTTPPath', true));
        $image = (($image == "'none'" || $image == 'none') ? '' : $image);

        $use_parent  = $this->get_config('parent_base', 'all');
        $hide_parent = serendipity_db_bool($this->get_config('hide_parent', 'false'));
        $parentdepth = 0;

        $hide_parallel = serendipity_db_bool($this->get_config('hide_parallel', 'false'));
        $hidedepth     = 0;

        if (is_array($categories) && count($categories)) {
            $categories = serendipity_walkRecursive($categories, 'categoryid', 'parentid', VIEWMODE_THREADED);
            foreach ($categories AS $cid => $cat) {
                // Hide parents not wanted
                if ($use_parent && $use_parent != 'all') {
                    if ($parentdepth == 0 && $cat['parentid'] != $use_parent && $cat['categoryid'] != $use_parent) {
                        unset($categories[$cid]);
                        continue;
                    } else {
                        if ($hide_parent && $cat['categoryid'] == $use_parent) {
                            unset($categories[$cid]);
                            continue;
                        }

                        if ($cat['depth'] < $parentdepth) {
                            $parentdepth = 0;
                            unset($categories[$cid]);
                            continue;
                        }

                        if ($parentdepth == 0) {
                            $parentdepth = $cat['depth'];
                        }
                    }
                }

                // Hide parents outside of our tree
                if ($hide_parallel && !empty($serendipity['GET']['category'])) {
                    if ($hidedepth == 0 && $cat['parentid'] != $serendipity['GET']['category'] && $cat['categoryid'] != $serendipity['GET']['category']) {
                        unset($categories[$cid]);
                        continue;
                    } else {
                        if ($cat['depth'] < $hidedepth) {
                            $hidedepth = 0;
                            unset($categories[$cid]);
                            continue;
                        }

                        if ($hidedepth == 0) {
                            $hidedepth = $cat['depth'];
                        }
                    }
                }

                $categories[$cid]['feedCategoryURL'] = serendipity_feedCategoryURL($cat, 'serendipityHTTPPath');
                $categories[$cid]['categoryURL']     = serendipity_categoryURL($cat, 'serendipityHTTPPath');
                $categories[$cid]['paddingPx']       = $cat['depth']*6;
                $categories[$cid]['catdepth']        = $cat['depth'];

                if (!empty($cat_count[$cat['categoryid']])) {
                    $categories[$cid]['true_category_name'] = $cat['category_name'];
                    $categories[$cid]['category_name'] .= ' (' . $cat_count[$cat['categoryid']] . ')';
                    $categories[$cid]['article_count'] = $cat_count[$cat['categoryid']];
                }

                if (!$smarty) {
                    $html .= '<li class="category_depth' . $cat['depth'] . ' category_' . $cat['categoryid'] . '">';

                    if ($is_form) {
                        $html .= '<input type="checkbox" name="serendipity[multiCat][]" value="' . $cat['categoryid'] . '" />';
                    }

                    if (!empty($image)) {
                        $html .= '<a class="serendipity_xml_icon" href="'. $categories[$cid]['feedCategoryURL'] .'"><img src="'. $image .'" alt="XML" /></a> ';
                    }
                    $html .= '<a href="'. $categories[$cid]['categoryURL'] .'" title="'. serendipity_specialchars($cat['category_description']) .'" style="padding-left: '. $categories[$cid]['paddingPx'] .'px">'. serendipity_specialchars($categories[$cid]['category_name']) .'</a>';
                    $html .= '</li>' . "\n";
                }
            }
        }

        if (!$smarty) {
            $html .= "</ul>\n";
        }

        if (isset($categories) && $categories == 1) {
            $html = '';
        }

        if (!$smarty && $is_form) {
            $html .= '<div class="category_submit">'."\n";
            $html .= isset($serendipity['GET']['category'])
                ? '<input type="submit" name="serendipity[isMultiCat]" value="' . RESET_FILTERS . '" />'
                : '<input type="submit" name="serendipity[isMultiCat]" value="' . GO . '" />';
            $html .= "</div>\n";
        }

        if (!$smarty && serendipity_db_bool($this->get_config('show_all', 'false'))) {
            $html .= sprintf(
                '<div class="category_link_all"><a href="%s" title="%s">%s</a></div>'."\n",
                $serendipity['serendipityHTTPPath'] . $serendipity['indexFile'] . '?frontpage',
                ALL_CATEGORIES,
                ALL_CATEGORIES
            );
        }

        if (!$smarty && $is_form) {
            $html .= "</form>\n";
        }

        if (!$smarty) {
            echo $html;
        } else {
            $plugin_categories_data = array(
                'is_form'           => $is_form,
                'show_all'          => serendipity_db_bool($this->get_config('show_all', 'false')),
                'category_image'    => $image,
                'form_url'          => $serendipity['baseURL'] . $serendipity['indexFile'] . '?frontpage',
                'categories'        => is_array($categories) ? $categories : array()
            );
            $serendipity['smarty']->assign($plugin_categories_data);
            echo serendipity_smarty_fetch('CATEGORIES', 'plugin_categories.tpl');
        }
    }

}

?>