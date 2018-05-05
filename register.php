<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
session_start();
$smarty=new Smarty();
//第三方操作类链接
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");

$sql = "set names utf8";
$db->exec($sql);//设置字符集编码
//$db->exec("set names utf8");
if(isset($_POST['act'])&& $_POST['act']=="adduser"){
	//确定有注册信息提交
	$user_name = $_POST['username'];//用户名
	$password = $_POST['password'];//密码
	$user_sex=$_POST['sex'];//性别
//密码是否一致，一致
//验证码的一致性
    $fcode =$_POST['checkcode'];//用户输入的验证码
    if ($fcode==$_SESSION['scode']){
    	//验证码正确。成功注册-》信息存库
    	$sql="insert into userinfo(user_name,user_passwd,user_sex)
    			values ('{$user_name}','{$password}','{$user_sex}')";
    	$res=$db->exec($sql); //如果insert成功int(1)，失败false
    	if ($res){
    		//sql成功，进入代码段,直接跳转到登录页面
    		$smarty->display("login.html");
    		exit();
    	}
    	else {
//     		echo $sql;
    		echo "sql执行失败，请联系管理员。";
    		//不成功则提示失败
            }
    }else {
    	//验证码有误
    	echo "验证码错误，请重新输入！";
    }
	
}
$smarty->display("register.html");
?>
