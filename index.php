<?php
/* Error Checking */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Core Program Functions
include('core.php');
$url = 'http://www.bcit.ca/its/labs/details/ne1-336'; // Current class URL.
$current_day = 1; // Current day to check for.

$html = scrape_website($url); // Gets website content.
$rows = get_rows_count($html, $current_day); // Gets number of rows.
$day_name = get_day_name($html, $current_day); // Gets day name.
print_day($day_name); // Prints day name.

// Prints Class number, time, and availability.
for ($index = 1; $index <= $rows - 1; $index++) {

  print_current_row($index);
  $styling = get_row_data($html, $current_day, $index);
  $time = get_row_time($styling);
  print_time($time);
  $colour = $styling[1];
  check_class_avail($html, $current_day, $index, $colour);
  echo '<br /><br />';

}


?>
