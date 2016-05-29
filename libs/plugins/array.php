<?php

/*********************************************************************

 Functions for Array

*********************************************************************/

/**
 * キーにプレフィックスを付与
 *
 * @param  array   $array
 * @param  string  $prefix
 * @return array
 */
function array_key_prefix($array, $prefix = '')
{
    $result = array();
    foreach ($array as $key => $value) {
        $result[$prefix . $key] = $value;
    }

    return $result;
}

/**
 * キーにサフィックスを付与
 *
 * @param  array   $array
 * @param  string  $suffix
 * @return array
 */
function array_key_suffix($array, $suffix = '')
{
    $result = array();
    foreach ($array as $key => $value) {
        $result[$key . $suffix] = $value;
    }

    return $result;
}
