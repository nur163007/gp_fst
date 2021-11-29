<?php

/*!
 * Check Memory and time usage by scripts
 * Added by: Hasan Masud
 * */

$big_array = array();

$start = microtime(TRUE);
for ($i = 0; $i < 1000000; $i++)
{
    $big_array[] = $i;
}
$end = microtime(TRUE);

echo 'After building the array.<br>';
print_mem($start, $end);

unset($big_array);

echo 'After unsetting the array.<br>';
print_mem($start = 0, $end = 0);


function print_mem($start, $end)
{
    /* Currently used memory */
    $mem_usage = memory_get_usage();

    /* Peak memory usage */
    $mem_peak = memory_get_peak_usage();

    echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
    echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
    if ($start) {
        echo '<pre>';
        echo 'Start Time: ' . unixToLocal($start) . '<br>';
        echo 'End Time: ' . unixToLocal($end) . '<br>';
        echo 'Time Taken: ' . ($end - $start) . ' Seconds' . '<br>';
        echo '</pre>';
    }
}

function unixToLocal($time){
    $timestamp = $time;
    $timezone = "Asia/Dhaka";
    $dt = new DateTime();
    $dt->setTimestamp($timestamp);
    $dt->setTimezone(new DateTimeZone($timezone));
    $datetime = $dt->format('Y-m-d H:i:s');
    return $datetime;
}

