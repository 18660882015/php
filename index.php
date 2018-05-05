<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
$smarty=new Smarty();

session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
//完善islogin 和username 在session中
$smarty->assign('islogin',$_SESSION['islogin']);
// if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){
// 	$user_name=$_SESSION['username'];
// 	$smarty->assign('username',$user_name);
		
// }

$sql="select * from goodinfo order by good_id desc limit 0,8";
// 拿出商品表的最后八条信息  id倒序排列
//$res=$db->query($sql);
$res=$db->query($sql);
if ($res){
$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
//print_r($arr_all);

}else {
	echo "$sql";
}
$smarty->assign('username',$_SESSION['username']);
$smarty->assign('arr_all',$arr_all);
$smarty->display("index.html");//显示模板
?>