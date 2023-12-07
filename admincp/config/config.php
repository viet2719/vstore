<?php
$mysqli = new mysqli("localhost", "id21634502_vstore", "Vv123456789@", "id21634502_vstore");
$mysqli->set_charset("utf8");

// Check connection
if ($mysqli->connect_errno) {
  echo "Kết nối MYSQLi lỗi: " . $mysqli->connect_error;
  exit();
}
