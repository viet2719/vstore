<?php
$mysqli = new mysqli("localhost", "root", "", "web_mysqli");
// $mysqli = new mysqli("localhost", "id21635143_vstore", "Vv123456789@", "id21635143_vstore");
$mysqli->set_charset("utf8");


if ($mysqli->connect_errno) {
  echo "Kết nối MYSQLi lỗi: " . $mysqli->connect_error;
  exit();
}
