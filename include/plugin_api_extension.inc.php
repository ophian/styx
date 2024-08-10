<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_api_extension extends serendipity_plugin_api
{
    /**
     * Prepare a given one dimension array for reordering
     *
     * Args:
     *      - the array
     *      - the key of the parent id
     * Returns:
     *      - the final array with two new keys: 'up' and 'down'
     * @access public
     * @author Falk Doering
     */
    function prepareReorder(iterable $array, string $parent_id = 'parent_id') : iterable
    {
        if (is_array($array)) {
            for ($i = 0, $ii = count($array); $i < $ii; $i++) {
                $array[$i]['down'] = $array[$i]['down'] ?? false;
                $array[$i]['up']   = $array[$i]['up'] ?? false;
                for ($j = ($i + 1); $j < $ii; $j++) {
                    if ($array[$j][$parent_id] == $array[$i][$parent_id]) {
                        $array[$i]['down'] = true;
                        $array[$j]['up'] = true;
                    }
                }
            }

            return $array;
        }

        return $array;
    }

    /**
     * Prepare a given one dimension array for deleting
     *
     * Args:
     *      - the array
     *      - the key of the main id
     *      - the key of the parent id
     * Returns:
     *      - the final array with one new keys: 'delete' with true or false
     * @access public
     * @author Falk Doering
     */
    function prepareDelete(iterable $array, string $this_id = 'id', string $parent_id = 'parent_id') : iterable
    {
        if (is_array($array)) {
            for ($i = 0, $ii = count($array); $i < $ii; $i++) {
                if (isset($array[$i+1]) && ($array[$i+1][$parent_id] == $array[$i][$this_id])) {
                   $array[$i]['delete'] = false;
               } else {
                   $array[$i]['delete'] = true;
               }
            }
        }

        return $array;
    }

    /**
     * Update table for re-ordering
     *
     * Args:
     *      - Name of the table
     *      - The direction ('up' or 'down')
     *      - The update array
     *      - The array containing the where clause
     * Returns:
     *      - boolean
     * @access public
     * @author Falk Doering
     */
    function doReorder(string $table, string $moveto, iterable $update_array, iterable $where_array) : bool
    {
        global $serendipity;

        if (is_array($update_array) && is_array($where_array)) {
            $where = '';
            foreach($where_array AS $key => $value) {
                if (strlen($where)) {
                    $where .= ' AND ';
                }
                $where .= $key . ' = ' . $value;
            }
            $q = 'SELECT '.implode(', ', array_keys($update_array)).'
                    FROM '. $serendipity['dbPrefix'] . $table .'
                   WHERE '.$where;
            $old = serendipity_db_query($q, true, 'assoc');

            if (is_array($old)) {
                $where = array();
                $update = array();
                switch ($moveto) {
                    case 'up':
                        foreach($update_array AS $key => $value) {
                            if ($value) {
                                $where[$key] = ($old[$key] - 1);
                                $update[$key] = $old[$key];
                                $update_1[$key] = ($old[$key] - 1);
                            } else {
                                $where[$key] = $old[$key];
                            }
                        }
                        break;
                    case 'down':
                        foreach($update_array AS $key => $value) {
                            if ($value) {
                                $where[$key] = ($old[$key] + 1);
                                $update[$key] = $old[$key];
                                $update_1[$key] = ($old[$key] + 1);
                            } else {
                                $where[$key] = $old[$key];
                            }
                        }
                        break;
                    default:
                        return false;
                }
                serendipity_db_update($table, $where, $update);
                serendipity_db_update($table, $where_array, $update_1);

                return true;
            }
        }

        return false;
    }

    /**
     * Check if a string is a valid email
     *
     * Args:
     *      - The email string
     * Returns:
     *      - True is valid email, False else
     * @access public
     * @author Falk Doering
     *
     */
    function isEmail(string $email) : bool
    {
        $preg = '/^[a-zA-Z0-9](([_\.-][a-zA-Z0-9]+)*)@([a-zA-Z0-9]+)(([\.-]?[a-zA-Z0-9]+)*)\.([a-zA-Z]{2,6})|localhost$/';
        return (preg_match($preg, $email) != 0);
    }

}
