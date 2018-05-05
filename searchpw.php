<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';

$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");

if(isset($_POST['act'])&&$_POST['act']=="searchpw")
{//确定有注册信息提交
$username=$_POST['username'];
$question=$_POST['question'];
$pwkey=$_POST['pwkey'];
$newpwd=$_POST['newpwd'];
if($pwkey!="" && $newpwd!="")
{
	//echo 1;
	$sql="update userinfo set user_password='{$newpwd}'
	where user_name='{$username}'and user_answer='{$pwkey}'";
	$res=$db->exec($sql);
	if ($res){
	echo "<script>alert('修改成功')</script>";
  	 	//header("location:login.php");
	$smarty->display("login.html");
	exit();
  	 }
  	 else {
  	 echo  "<script>alert('找回密码失败')</script>";
	}
	}
		 
	}
//   else {
	//   	echo "<script>alert('找回密码失败')</script>";
	//   }
	 

$smarty->display("searchpw.html");
?>
