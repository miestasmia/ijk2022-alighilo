<?php
function coal (...$arr) {
	foreach ($arr as $x) {
		if ($x) {
			return $x;
		}
	}
	return null;
};

function format_eur ($eur) {
    return number_format($eur, 2, ',', '.') . '€';
}

/**
 * Applies the callback to the elements of the given arrays, then merge all results to one array
 *
 * @param callable $callback Callback function to run for each element in each array.
 * @param array $array1 An array to run through the callback function.
 * @param array $array,... Variable list of array arguments to run through the callback function.
 * @return array Returns an array containing all the elements of array1 after
 * applying the callback function to each one, casting them to arrays and merging together.
 */
function array_flatmap()
{
    $args = func_get_args();
    $mapped = array_map(function ($a) {
        return (array)$a;
    }, call_user_func_array('array_map', $args));

    return count($mapped) === 0 ? array() : call_user_func_array('array_merge', $mapped);
}
