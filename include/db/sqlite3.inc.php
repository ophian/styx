<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

// SQLite3 only fetches by assoc, we will emulate the other result types
define(SQLITE3_ASSOC, 0);
define(SQLITE3_NUM, 1);
define(SQLITE3_BOTH, 2);

/**
 * Tells the DB Layer to start a DB transaction.
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function serendipity_db_begin_transaction() : void {
    serendipity_db_query('begin transaction');
}

/**
 * Tells the DB Layer to end a DB transaction.
 *
 * Args:
 *      - If true, perform the query. If false, rollback.
 * Returns:
 *      -
 * @access public
 */
function serendipity_db_end_transaction(bool $commit) : void {
    if ($commit) {
        serendipity_db_query('commit transaction');
    } else {
        serendipity_db_query('rollback transaction');
    }
}

/**
 * Connect to the configured Database
 *
 * Args:
 *      -
 * Returns:
 *      - The resource connection handle
 * @access  public
 */
function serendipity_db_connect() : object {
    global $serendipity;

    if (isset($serendipity['dbConn'])) {
        return $serendipity['dbConn'];
    }

    // SQLite3 doesn't support persistent connections
    $serendipity['dbConn'] = sqlite3_open((defined('S9Y_DATA_PATH') ? S9Y_DATA_PATH : $serendipity['serendipityPath']) . $serendipity['dbName'] . '.db');

    return $serendipity['dbConn'];
}

/**
 * Stub
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function serendipity_db_reconnect() : void {
}

/**
 * Returns an escaped string, so that it can be safely included in a SQL string encapsulated within quotes, without allowing SQL injection.
 *
 * Args:
 *      - The input string
 * Returns:
 *      - The output string
 * @access  public
 */
function serendipity_db_escape_string(string|int|null $string) : ?string {
    static $search  = array("\x00", '%',   "'",   '\"');
    static $replace = array('%00',  '%25', "''", '\\\"');

    if ($string === null) {
        return null;
    }
    return str_replace($search, $replace, (string) $string);
}

/**
 * Returns the number of affected rows of a SQL query
 *
 * Args:
 *      -
 * Returns:
 *      - Number of affected rows
 * @access public
 */
function serendipity_db_affected_rows() : int {
    global $serendipity;

    return sqlite3_changes($serendipity['dbConn']);
}

/**
 * Returns the number of updated rows in a SQL query
 *
 * Args:
 *      -
 * Returns:
 *      - Number of updated rows
 * @access public
 */
function serendipity_db_updated_rows() : int {
    global $serendipity;
    // It is unknown whether sqlite returns rows MATCHED or rows UPDATED
    return sqlite3_changes($serendipity['dbConn']);
}

/**
 * Returns the number of matched rows in a SQL query
 *
 * Args:
 *      -
 * Returns:
 *      - Number of matched rows
 * @access public
 */
function serendipity_db_matched_rows() : int {
    global $serendipity;
    // It is unknown whether sqlite returns rows MATCHED or rows UPDATED
    return sqlite3_changes($serendipity['dbConn']);
}

/**
 * Returns the latest INSERT_ID of an SQL INSERT INTO command, for auto-increment columns
 *
 * Args:
 *      -
 * Returns:
 *      - Value of the auto-increment column
 * @access public
 */
function serendipity_db_insert_id() : int {
    global $serendipity;

    return sqlite3_last_insert_rowid($serendipity['dbConn']);
}

/**
 * Parse result arrays into expected format for further operations
 *
 * SQLite does not support to return "e.entryid" within a $row['entryid'] return.
 * So this function manually iterates through all result rows and rewrites 'X.yyyy' to 'yyyy'.
 * Yeah. This sucks. Don't tell me!
 *
 * Args:
 *      - The row resource handle
 *      - Bitmask to tell whether to fetch numerical/associative arrays
 * Returns:
 *      - Proper array containing the resource results
 * @access private
 */
function serendipity_db_sqlite_fetch_array(iterable|bool $row, int $type = SQLITE3_BOTH) : iterable {
    static $search  = array('%00',  '%25');
    static $replace = array("\x00", '%');

    $row = sqlite3_fetch_array($res);
    if (!is_array($row)) {
        return $row;
    }

    /* strip any slashes, correct fieldname */
    foreach($row AS $i => $v) {
        if (is_null($v)) continue;
        // TODO: If a query of the format 'SELECT a.id, b.text FROM table' is used,
        //       the sqlite extension will give us key indizes 'a.id' and 'b.text'
        //       instead of just 'id' and 'text' like in mysql/postgresql extension.
        //       To fix that, we use a preg-regex; but that is quite performance costy.
        //       Either we always need to use 'SELECT a.id AS id, b.text AS text' in query,
        //       or the sqlite extension may get fixed. :-)
        $row[preg_replace('@^.+\.(.*)@', '\1', (string) $i)] = str_replace($search, $replace, (string) $v);
    }

    if ($type == SQLITE3_NUM)
        $frow = array();
    else
        $frow = $row;

    if ($type != SQLITE3_ASSOC) {
        $i = 0;
        foreach($row AS $k => $v) {
            $frow[$i] = $v;
            $i++;
        }
    }

    return $frow;
}

/**
 * Assemble and return SQL condition for a "IN (...)" clause
 *
 * Args:
 *      - The table column name
 *      - A referenced array of values to search for in the "IN (...)" clause
 *      - A condition of how to associate the different input values of the $search_ids parameter
 * Returns:
 *      - This resulting SQL string
 * @access public
 */
function serendipity_db_in_sql(string $col, iterable &$search_ids, string $type = ' OR ') : string {
    $sql = array();
    if (!is_array($search_ids)) {
        return false;
    }

    foreach($search_ids AS $id) {
        $sql[] = $col . ' = ' . $id;
    }

    $cond = '(' . implode($type, $sql) . ')';
    return $cond;
}

/**
 * Perform a DB Layer SQL query.
 *
 * This function returns values depending on the input parameters and the result of the query.
 * It can return:
 *   FALSE or a STRING if there was an error (depends on $expectError),
 *   TRUE if the query succeeded but did not generate any rows
 *   ARRAY of field values if it returned a single row and $single is true
 *   ARRAY of array of field values if it returned row(s) [stacked array]
 *
 * Args:
 *      - SQL query to execute
 *      - Toggle whether the expected result is a single row (TRUE) or multiple rows (FALSE). This affects whether the returned array is 1 or 2 dimensional!
 *      - Result type of the array indexing. Can be one of "assoc" (associative), "num" (numerical), "both" (numerical and associative, default)
 *      - If true, errors will be reported. If false, errors will be ignored.
 *      - A possible array key name, so that you can control the multi-dimensional mapping of an array by the key column
 *      - A possible array field name, so that you can control the multi-dimensional mapping of an array by the key column and the field value.
 *      - If true, the executed SQL error is known to fail, and should be disregarded (errors can be ignored on DUPLICATE INDEX queries and the likes)
 * Returns:
 *      - Returns the result of the SQL query, depending on the input parameters
 * @access public
 */
function &serendipity_db_query(string $sql, bool $single = false, string $result_type = "both", bool $reportErr = false, string|bool $assocKey = false, string|bool $assocVal = false, bool $expectError = false) : string|bool|iterable {
    global $serendipity;
    $type_map = array(
                        'assoc' => SQLITE3_ASSOC,
                        'num'   => SQLITE3_NUM,
                        'both'  => SQLITE3_BOTH,
                        'true'  => true,
                        'false' => false
    );
    static $debug = false;

    if ($debug) {
        // Open file and write directly. In case of crashes, the pointer needs to be killed.
        $fp = @fopen('sqlite.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] SQLITE QUERY: ' . $sql . "\n\n");
        fclose($fp);
    }

    if ($reportErr && !$expectError) {
        $res = sqlite3_query($serendipity['dbConn'], $sql);
    } else {
        $res = @sqlite3_query($serendipity['dbConn'], $sql);
    }

    if (!$res) {
        if (!$expectError && !$serendipity['production']) {
            var_dump($res);
            var_dump(htmlspecialchars($sql));
            $msg = "problem with query";
            return $msg;
        }
        if ($debug) {
            $fp = @fopen('sqlite.log', 'a');
            fwrite($fp, '[' . date('d.m.Y H:i') . '] [ERROR] ' . "\n\n");
            fclose($fp);
        }

        return $type_map['false'];
    }

    if ($res === true) {
        return $type_map['true'];
    }

    $rows = array();

    while (($row = serendipity_db_sqlite_fetch_array($res, $type_map[$result_type]))) {
        if (!empty($assocKey)) {
            // You can fetch a key-associated array via the two function parameters assocKey and assocVal
            if (empty($assocVal)) {
                $rows[$row[$assocKey]] = $row;
            } else {
                $rows[$row[$assocKey]] = $row[$assocVal];
            }
        } else {
            $rows[] = $row;
        }
    }

    if ($debug) {
        $fp = @fopen('sqlite.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] SQLITE RESULT: ' . print_r($rows, true). "\n\n");
        fclose($fp);
    }

    if ($single && count($rows) == 1) {
        return $rows[0];
    }

    if (count($rows) == 0) {
        if ($single)
            return $type_map['false'];
        return $type_map['true'];
    }

    return $rows;
}

/**
 * Try to connect to the configured Database (during installation)
 *
 * Args:
 *      - The input configuration array, holding the connection info
 *      - A referenced array which holds the errors that might be encountered
 * Returns:
 *      - True on success, False on error
 * @access public
 */
function serendipity_db_probe(iterable $hash, iterable &$errs) : bool {
    global $serendipity;

    $dbName = $hash['sqlitedbName'] ?? $hash['dbName'];

    if (!function_exists('sqlite3_open')) {
        $errs[] = 'SQLite extension not installed. Run "pear install sqlite" on your webserver or contact your systems administrator regarding this problem.';
        return false;
    }

    if (defined('S9Y_DATA_PATH')) {
        // Shared installations!
        $dbfile = S9Y_DATA_PATH . $dbName . '.db';
    } else {
        $dbfile = $serendipity['serendipityPath'] . $dbName . '.db';
    }

    $serendipity['dbConn'] = sqlite3_open($dbfile);

    if ($serendipity['dbConn']) {
        return true;
    }

    $errs[] = "Unable to open \"$dbfile\" - check permissions (directory needs to be writeable for webserver)!";
    return false;
}

/**
 * Prepares a Serendipity query input to fully valid SQL. Replaces certain "template" variables.
 *
 * Args:
 *      - SQL query with template variables to convert
 * Returns:
 *      - SQL resource handle of the executed query
 * @access public
 */
function serendipity_db_schema_import(string $query) : string|bool|iterable {
    static $search  = array('{AUTOINCREMENT}', '{PRIMARY}', '{UNSIGNED}', '{FULLTEXT}', '{BOOLEAN}', '{UTF_8}', '{TEXT}');
    static $replace = array('INTEGER AUTOINCREMENT', 'PRIMARY KEY', '', '', 'BOOLEAN NOT NULL', '', 'LONGTEXT');

    if (stristr($query, '{FULLTEXT_MYSQL}')) {
        return true;
    }

    $query = trim(str_replace($search, $replace, $query));
    $query = str_replace('INTEGER AUTOINCREMENT PRIMARY KEY', 'INTEGER PRIMARY KEY AUTOINCREMENT', $query);

    if (str_starts_with($query, '@')) {
        // Errors are expected to happen (like duplicate index creation)
        return serendipity_db_query(substr($query, 1), expectError: true);
    } else {
        return serendipity_db_query($query);
    }
}

/**
 * Returns the option to a LIMIT SQL statement, because it varies across DB systems
 *
 * Args:
 *      - Number of the first row to return data from
 *      - Number of rows to return
 * Returns:
 *      - SQL string to pass to a LIMIT statement
 * @access public
 */
function serendipity_db_limit(int $start, int $offset) : string {
    return $start . ', ' . $offset;
}

/**
 * Return a LIMIT SQL option to the DB Layer as a full LIMIT statement
 *
 * Args:
 *      - SQL string of a LIMIT option
 * Returns:
 *      - SQL string containing a full LIMIT statement
 * @access public
 */
function serendipity_db_limit_sql(string $limitstring) : string {
    return ' LIMIT ' . $limitstring;
}

/**
 * Returns the SQL code used for concatenating strings
 *
 * Args:
 *      - Input string/column to concatenate
 * Returns:
 *      - SQL parameter
 * @access public
 */
function serendipity_db_concat(string $string) : string {
    return 'concat(' . $string . ')';
}

/* vim: set sts=4 ts=4 expandtab : */
