<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (!empty($serendipity['dbType']) && include(S9Y_INCLUDE_PATH . "include/db/{$serendipity['dbType']}.inc.php")) {
    define('S9Y_DB_INCLUDED', true);
}

/**
 * Perform a query to update the data of a certain table row
 *
 * You can pass the tablename and an array of keys to select the row,
 * and an array of values to UPDATE in the DB table.
 *
 * @access public
 * @param  string   Name of the DB table
 * @param  array    Input array that controls the "WHERE" condition part. Pass it an associative array like array('key1' => 'value1', 'key2' => 'value2') to get a statement like "WHERE key1 = value1 AND key2 = value2". Escaping is done automatically in this function.
 * @param  array    Input array that controls the "SET" condition part. Pass it an associative array like array('key1' => 'value1', 'key2' => 'value2') to get a statement like "SET key1 = value1, key2 = value2". Escaping is done automatically in this function.
 * @param  string   What do do with the SQL query (execute, display)
 * @return array    Returns the result of the SQL query
 */
function serendipity_db_update($table, $keys, $values, $action = 'execute') {
    global $serendipity;

    $set = '';

    foreach($values AS $k => $v) {
        if (strlen($set))
            $set .= ', ';
        $set .= $k . '=\'' . serendipity_db_escape_string($v) . '\'';
    }

    $where = '';
    foreach($keys AS $k => $v) {
        if (strlen($where))
            $where .= ' AND ';
        $where .= $k . '=\'' . serendipity_db_escape_string($v) . '\'';
    }

    if (strlen($where)) {
        $where = " WHERE $where";
    }

    $q = "UPDATE {$serendipity['dbPrefix']}$table SET $set $where";
    if ($action == 'execute') {
        return serendipity_db_query($q);
    } else {
        return $q;
    }
}

/**
 * Perform a query to insert an associative array into a specific SQL table
 *
 * You can pass a tablename and an array of input data to insert into an array.
 *
 * @access  public
 * @param   string      Name of the SQL table
 * @param   array       Associative array of keys/values to insert into the table. Escaping is done automatically.
 * @param  string   What do do with the SQL query (execute, display)
 * @return array    Returns the result of the SQL query
 */
function serendipity_db_insert($table, $values, $action = 'execute') {
    global $serendipity;

    $names = implode(',', array_keys($values));

    $vals = '';
    foreach($values AS $k => $v) {
        if (strlen($vals)) {
            $vals .= ', ';
        }
        // See db::serendipity_set_config_var() mentioned issue that arrays were build for the web as strings and only highly late PHP versions as of PHP 8.2 are able to type cast to INTs.
        if ($table == 'config' && $k === array_key_last($values) && is_int($v)) {
            $vals .= "$v"; // it is an integer field and PHP below 8.2 will throw a MYSQLI ERROR EXCEPTION which cannot be suppressed by @, since then the value is not stored!
        } else {
            $vals .= '\'' . serendipity_db_escape_string($v) . '\'';
        }
    }

    $q = "INSERT INTO {$serendipity['dbPrefix']}$table ($names) values ($vals)";

    if ($action == 'execute') {
        return serendipity_db_query($q);
    } else {
        return $q;
    }
}

/**
 * Check whether an input value corresponds to a TRUE/FALSE option in the SQL database.
 *
 * Because older DBs could not store TRUE/FALSE values to be restored into a PHP variable,
 * this function tries to detect what the return code of a SQL column is, and convert it
 * to a PHP native boolean.
 *
 * Values that will be recognized as TRUE are 'true', 't' and '1'.
 *
 * @access public
 * @param  string   input value to compare
 * @return boolean  boolean conversion of the input value
 */
function serendipity_db_bool($val) {
    if (($val === true) || ($val == 'true') || ($val == 't') || ($val == '1'))
        return true;
    #elseif (($val === false || $val == 'false' || $val == 'f'))
    else
        return false;
}

/**
 * Return a SQL statement for a time interval or timestamp, specific to certain SQL backends
 *
 * @access public
 * @param   string  Indicate whether to return a timestamp, or an Interval
 * @param   int     The interval one might want to use, if Interval return was selected
 * @return  string  SQL statement
 */
function serendipity_db_get_interval($val, $ival = 900) {
    global $serendipity;

    switch($serendipity['dbType']) {
        case 'sqlite':
        case 'sqlite3':
        case 'sqlite3oo':
        case 'pdo-sqlite':
            $interval = $ival;
            $ts       = time();
            break;

        case 'pdo-postgres':
        case 'postgres':
            $interval = "interval '$ival'";
            $ts       = 'NOW()';
            break;

        case 'mysql':
        case 'mysqli':
        default:
            $interval = $ival;
            $ts       = 'NOW()';
            break;
    }

    switch($val) {
        case 'interval':
            return $interval;

        default:
        case 'ts':
            return $ts;
    }
}

/**
 * Operates on an array to prepare it for SQL usage.
 *
 * @access public
 * @param   string Concatenation character
 * @param   array  Input array
 * @param   string How to convert (int: Only numbers, string: serendipity_db_escape_String)
 * @return  string Imploded string
 */
function serendipity_db_implode($string, &$array, $type = 'int') {
    $new_array = array();
    if (!is_array($array)) {
        return '';
    }

    foreach($array AS $idx => $key) {
        if ($type == 'int') {
            $new_array[$idx] = (int)$key;
        } else {
            $new_array[$idx] = serendipity_db_escape_string($key);
        }
    }

    $string = implode($string, $new_array);
    return $string;
}

/**
 * Operates on differing database command concepts to convert
 * the CURRENT or a DATE field value to a "UNIX TIMESTAMP" (UTC) for SQL SELECT usage
 *
 * @access public
 * @param   string Optional (joined) field name to work on (resulting to eg. '2021-12-05')
 *          - CURRENT or NOW by default
 * @param   boolean Cast to string (cts) for strictly typed operator comparisons with PostGreSQL against string/text fields
 *
 * @return  string String command by dbType to include to a query
 */
function serendipity_db_get_unixTimestamp(string $field = '', bool $cts = false) : string {
    global $serendipity;

    if ($serendipity['dbType'] == 'postgres' || $serendipity['dbType'] == 'pdo-postgres') {
        $field = empty($field) ? 'NOW()' : $field;
        if ($cts) {
            return "'EXTRACT(EPOCH FROM $field)'";
        } else {
            return "EXTRACT(EPOCH FROM $field)";
        }
    } elseif ($serendipity['dbType'] == 'sqlite' || $serendipity['dbType'] == 'sqlite3' || $serendipity['dbType'] == 'pdo-sqlite' || $serendipity['dbType'] == 'sqlite3oo') {
        $field = empty($field) ? "'now'" : $field; // OK
        return "STRFTIME('%s', $field)"; // change to UNIXEPOCH($field) w/ Styx 5 for SQLite >= 3.38.0 (2022-02-22) only
    } else {
        return "UNIX_TIMESTAMP($field)";
    }
}

/* vim: set sts=4 ts=4 expandtab : */
