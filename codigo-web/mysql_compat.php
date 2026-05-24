<?php

global $MYSQLI_CONN;
date_default_timezone_set('America/Caracas');

/*
|--------------------------------------------------------------------------
| mysql_connect
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_connect')) {

    function mysql_connect($host, $user, $pass)
    {
        global $MYSQLI_CONN;

        $MYSQLI_CONN = mysqli_connect($host, $user, $pass);

        return $MYSQLI_CONN;
    }
}

/*
|--------------------------------------------------------------------------
| mysql_select_db
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_select_db')) {

    function mysql_select_db($database, $conn = null)
    {
        global $MYSQLI_CONN;

        $conn = $conn ?: $MYSQLI_CONN;

        return mysqli_select_db($conn, $database);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_query
|--------------------------------------------------------------------------
*/

function mysql_query($query, $conn = null)
{
    global $MYSQLI_CONN;

    if ($conn === null) {
        $conn = $MYSQLI_CONN;
    }

    if (!$conn) {

        die(
            'No existe conexión mysqli activa'
        );
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {

        die(
            'MYSQL ERROR: '
            . mysqli_error($conn)
            . '<br><br>QUERY: '
            . $query
        );
    }

    return $result;
}

/*
|--------------------------------------------------------------------------
| mysql_fetch_array
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_fetch_array')) {

    function mysql_fetch_array($result, $mode = MYSQLI_BOTH)
    {
        return mysqli_fetch_array($result, $mode);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_fetch_assoc
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_fetch_assoc')) {

    function mysql_fetch_assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_num_rows
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_num_rows')) {

    function mysql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_real_escape_string
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_real_escape_string')) {

    function mysql_real_escape_string($string, $conn = null)
    {
        global $MYSQLI_CONN;

        $conn = $conn ?: $MYSQLI_CONN;

        return mysqli_real_escape_string($conn, $string);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_error
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_error')) {

    function mysql_error($conn = null)
    {
        global $MYSQLI_CONN;

        $conn = $conn ?: $MYSQLI_CONN;

        return mysqli_error($conn);
    }
}

/*
|--------------------------------------------------------------------------
| mysql_close
|--------------------------------------------------------------------------
*/

if (!function_exists('mysql_close')) {

    function mysql_close($conn = null)
    {
        global $MYSQLI_CONN;

        $conn = $conn ?: $MYSQLI_CONN;

        return mysqli_close($conn);
    }
}


if (!function_exists('mysql_data_seek')) {

    function mysql_data_seek($result, $row_number)
    {
        if (!$result instanceof mysqli_result) {

            die(
                'mysql_data_seek(): resultado inválido'
            );
        }

        return mysqli_data_seek(
            $result,
            $row_number
        );
    }
}


if (!function_exists('mysql_num_fields')) {

    function mysql_num_fields($result)
    {
        if (!$result instanceof mysqli_result) {

            die(
                'mysql_num_fields(): resultado inválido'
            );
        }

        return mysqli_num_fields($result);
    }
}
