<?php
/* Error Checking */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* Includes HTML DOM Framework for scraping */
include('scrape_html_dom.php');

/**
 * Scrapes BCIT Website for listings. Only used
 * for yearly updates on links and clases.
 *
 * @param   url:    String - Link to listings page.
 */
function scrape_website_links($url) {
  $html = file_get_html($url);

  foreach ($html->find('a') as $element) {
    if (strpos($element->href, '../details/') !== false) {
      echo 'http://www.bcit.ca/its/labs' . str_replace("..", "", "$element->href") . '<br>';
    }
  }
}

/**
 * Gets HTML Content from link so it can
 * be used for scraping/modifiying.
 *
 * @param   url:    String - Link to listings page.
 * @return  html:   String - HTML page conent.
 */
function scrape_website($url) {
  $html = file_get_html($url);
  return $html;
}

/**
 * Finds number of rows, or class spots for
 * the current class day.
 *
 * @param   html:           String - Page content.
 * @param   current_day:    String - Current day to check.
 * @return  i:              Int - Number of rows/classes that day.
 */
function get_rows_count($html, $current_day) {
  for ($i = 0; $i < 10; $i++) {
    if ($html->find(".timeTable", 0)->children(0)->children($current_day)->children($i) == null) {
      return $i;
      break;
    }
  }
}

/**
 * Gets HTML Content from link so it can
 * be used for scraping/modifiying.
 *
 * @param   html:           String - Page content.
 * @param   current_day:    String - Current day to check.
 * @return  string:         Current day, or error if none exists.
 */
function get_day_name($html, $current_day) {

  if ($html->find(".timeTable", 0)->children(0)->children($current_day)->children(0)->plaintext != null) {
    return $html->find(".timeTable", 0)->children(0)->children($current_day)->children(0)->plaintext;
  } else {
    return "Error: Current Day Does Not Exist.";
  }

}

/**
 * Gets HTML Content from link so it can
 * be used for scraping/modifiying.
 *
 * @param   html:           String - Page content.
 * @param   current_day:    String - Current day to check.
 * @return  styling:        Array - Row height and row colour.
 */
function get_row_data($html, $current_day, $index) {
  $str = $html->find(".timeTable", 0)->children(0)->children($current_day)->children($index)->style;
  $str = str_replace("height:", "", $str);
  $str = str_replace("background-color:", "", $str);
  $str = str_replace(";", "", $str);
  $str = str_replace("px", "", $str);
  $str = trim($str);
  $styling = explode(" ", $str);
  return $styling;
}

/**
 * Enter in Pixel data, and converts to
 * actual time of the class/row.
 * TODO: Function needs to be fixed/optimized.
 *
 * @param   styling:     Array - Row height and row colour.
 * @return  time:        Time of class
 */
function get_row_time($styling) {
  $time = (round(($styling[0]) * 60 / 43));
  return $time;
}

/**
 * TODO: Add Documentation To Functions Below
 */
function print_day($day) {
  echo '<b>' . $day . '</b><br />';
}

function print_current_row($index) {
  echo 'Class #' . $index . '<br />'; // Prints current day.
}

function print_time($time) {
  echo 'Class Time Length: ' . $time . ' ~ mins<br />';
}

function get_class_name($html, $current_day, $index) {
  echo $html->find(".timeTable", 0)->children(0)->children($current_day)->children($index)->plaintext;
}

function check_class_avail($html, $current_day, $index, $colour) {

  // Checks if classroom is free/empty/closed.
  if ($html->find(".timeTable", 0)->children(0)->children($current_day)->children($index)->plaintext == null) {
    if (strcmp($colour, '#990000') !== 0) {
      echo 'Classroom Empty';
    } else {
      echo 'Classroom Is Closed.';
    }
  } else {
    get_class_name($html, $current_day, $index);
  }

}

?>
