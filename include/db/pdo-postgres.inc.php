<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

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
    global $serendipity;

    $serendipity['dbConn']->beginTransaction();
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
    global $serendipity;

    if ($commit) {
        $serendipity['dbConn']->commit();
    } else {
        $serendipity['dbConn']->rollback();
    }
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
    return $col . " IN (" . implode(', ', $search_ids) . ")";
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

    $host = $port = '';
    if (strlen($serendipity['dbHost'])) {
        if (str_contains($serendipity['dbHost'], ':')) {
            $tmp = explode(':', $serendipity['dbHost']);
            $host = "host={$tmp[0]};";
            $port = "port={$tmp[1]};";
        } else {
            $host = "host={$serendipity['dbHost']};";
        }
    }

    try {
        $serendipity['dbConn'] = new PDO(
                                    sprintf(
                                        'pgsql:%sdbname=%s',
                                        "$host$port",
                                        $serendipity['dbName']
                                    ),
                                    $serendipity['dbUser'],
                                    $serendipity['dbPass']
                                );
    } catch (\PDOException $e) {
        $serendipity['dbConn'] = false;
    }

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
    global $serendipity;

    if ($string === null) {
        return null;
    }
    return substr($serendipity['dbConn']->quote((string) $string), 1, -1);
}

/**
 * Returns the option to a LIMIT SQL statement, because it varies across DB systems
 * Postgres return vice versa
 *
 * Args:
 *      - Number of the first row to return data from
 *      - Number of rows to return
 * Returns:
 *      - SQL string to pass to a LIMIT statement
 * @access public
 */
function serendipity_db_limit(int $start, int $offset) : string {
    return $offset . ', ' . $start;
}

/**
 * Return a LIMIT SQL option to the DB Layer as a full LIMIT statement
 *
 * Args:
 *      - SQL integer or string of a LIMIT option
 * Returns:
 *      - SQL string containing a full LIMIT statement
 * @access public
 */
function serendipity_db_limit_sql(int|string $limitstring) : string {
    $limit_split = explode(',', (string) $limitstring);
    if ($limit_split[0] > 0 && count($limit_split) > 1) {
        $limit = ' LIMIT ' . $limit_split[0] . ' OFFSET ' . $limit_split[1];
    } else {
        $limit = ' LIMIT ' . ($limit_split[1] ?? $limit_split[0]); // this ternary is for comments [1] vs entries [0]
    }
    return $limit;
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

    return $serendipity['dbSth']->rowCount();
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
    // it is unknown whether pg_affected_rows returns number of rows
    //  UPDATED or MATCHED on an UPDATE statement.
    return $serendipity['dbSth']->rowCount();
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
    // it is unknown whether pg_affected_rows returns number of rows
    //  UPDATED or MATCHED on an UPDATE statement.
    return $serendipity['dbSth']->rowCount();
}

/**
 * Returns the latest INSERT_ID of an SQL INSERT INTO command, for auto-increment columns
 * Mimics our other db return(s) of AUTO_INCREMENT INT or 0, instead of PD0 return types string|false
 *
 * Args:
 *      - Name of the table to get a INSERT ID for
 *      - Name of the column to get a INSERT ID for
 * Returns:
 *      - Value of the auto-increment column
 * @access public
 */
function serendipity_db_insert_id(string $table = '', string $id = '') : int {
    global $serendipity;

    if (empty($table) || empty($id)) {
        // BC - will/should never be called with empty parameters!
        $return = $serendipity['dbConn']->lastInsertId();
        return is_string($return) ? (int) $return : 0;
    } else {
        $query = "SELECT currval('{$serendipity['dbPrefix']}{$table}_{$id}_seq'::text) AS {$id}";
        $res = $serendipity['dbConn']->prepare($query);
        $res->execute();
        foreach($res->fetchAll(PDO::FETCH_ASSOC) AS $row) {
            return (int) $row[$id] ?? 0;
        }
        $return = $serendipity['dbConn']->lastInsertId();
        return is_string($return) ? (int) $return : 0;
    }
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
                        'assoc' => PDO::FETCH_ASSOC,
                        'num'   => PDO::FETCH_NUM,
                        'both'  => PDO::FETCH_BOTH,
                        'true'  => true,
                        'false' => false
    );

    if (!$expectError && ($reportErr || !$serendipity['production'])) {
        $serendipity['dbConn']->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Backport to PHP 8.0+ behaviour
        $serendipity['dbSth'] = $serendipity['dbConn']->prepare($sql);
    } else {
        try {
            $serendipity['dbConn']->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT ); // PHP 8.0: PDO: Default error mode set to exceptions. Previously used silent.
            $serendipity['dbSth'] = $serendipity['dbConn']->prepare($sql);
        } catch(\Throwable $e) {
            return $type_map['false'];
        }
    }

    if (!$serendipity['dbSth']) {
        if (!$expectError && !$serendipity['production']) {
            $tsql = htmlspecialchars($sql);
            print "<span class=\"msg_error\">Error in $tsql</span>\n";
            print '<span class="msg_error">' . $serendipity['dbConn']->errorInfo() . "</span>\n";
            if (function_exists('debug_backtrace') && $reportErr == true) {
                // highlight_string() in mean of '<pre></pre>' equivalent, not in mean of php code highlight...
                highlight_string(var_export(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4), true));
                // if you need the "object" Index filled use
                // highlight_string(var_export(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 4), true));
            }
            print "<pre>$sql</pre>\n";
        }
        return $type_map['false'];
    }

    $serendipity['dbSth']->execute();

    if ($serendipity['dbSth'] === true) {
        return $type_map['true'];
    }

    $result_type = $type_map[$result_type];

    $n = 0;

    $rows = array();
    foreach($serendipity['dbSth']->fetchAll($result_type) AS $row) {
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
    if (count($rows) == 0) {
        if ($single) {
            return $type_map['false'];
        }
        return $type_map['true'];
    }
    if (count($rows) == 1 && $single) {
        return $rows[0];
    }
    return $rows;
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
    static $search  = array('{AUTOINCREMENT}', '{PRIMARY}', '{UNSIGNED}',
        '{FULLTEXT}', '{BOOLEAN}', 'int(1)', 'int(2)', 'int(10)', 'int(11)', 'int(4)', '{UTF_8}', '{TEXT}');
    static $replace = array('SERIAL', 'primary key', '', '', 'BOOLEAN NOT NULL', 'int2', 'int2',
        'int4', 'int4', 'int4', '', 'text');

    if (stristr($query, '{FULLTEXT_MYSQL}')) {
        return true;
    }

    $query = trim(str_replace($search, $replace, $query));

    if (str_starts_with($query, '@')) {
        // Errors are expected to happen (like duplicate index creation)
        return serendipity_db_query(substr($query, 1), expectError: true);
    } else {
        return serendipity_db_query($query);
    }
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

    if (!in_array('pgsql', PDO::getAvailableDrivers())) {
        $errs[] = 'PDO_PGSQL driver not avialable';
        return false;
    }

    try {
        $serendipity['dbConn'] = new PDO(
                                   sprintf(
                                     'pgsql:%sdbname=%s',
                                     strlen($hash['dbHost']) ? ('host=' . $hash['dbHost'] . ';') : '',
                                     $hash['dbName']
                                   ),
                                   $hash['dbUser'],
                                   $hash['dbPass']
                                 );
    } catch (\PDOException $e) {
        $errs[] = 'Could not connect to database; check your settings.';
        return false;
    }

    if (!$serendipity['dbConn']) {
        $errs[] = 'Could not connect to database; check your settings.';
        return false;
    }

    return true;
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
    return '(' . str_replace(', ', '||', $string) . ')';
}

/* vim: set sts=4 ts=4 expandtab : */
