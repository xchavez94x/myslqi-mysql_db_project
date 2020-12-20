<?php

const HOST = "localhost";
const USER = "root";
const PASSWORD = "";
const DB = "l07";

$db = mysqli_connect(HOST,USER,PASSWORD);
if (!$db) {
	exit('WRONG CONNECTION');
}
if(!mysqli_select_db($db, DB)) {
	exit(DB);
}
//mysqli_query('SET NAMES utf8');
mysqli_set_charset($db, "utf8") or die('Не установлена кодировка!');