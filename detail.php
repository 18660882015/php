<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
$_SESSION['islogin']=1;


    $good_id=$_GET['good_id'];//商品的编号
//     echo $good_id;
    $sql = "select * from goodinfo where good_id='{$good_id}'";
//     echo $sql;
      $res=$db->query($sql);
      if ($res) {
      	  $arr=$res->fetchAll(PDO::FETCH_ASSOC);
          
      }else {
      	echo "失败";
      }
    
      $smarty->assign("arr",$arr);
$smarty->display("detail.html");
?>