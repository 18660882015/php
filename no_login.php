<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';

$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");

$smarty->display("no_login.html");
?>