<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';

$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
if(isset($_POST['act'])&& $_POST['act']=="login")
{
	//有数据提交，接收数据
	$user_name=$_POST['username'];
	$user_passwd=$_POST['passwd'];
	//问题：需要增加用户名和密码是否为空的判断
	//根据用户名找密码。看匹配不匹配，搜的到id？
	$sql="select user_id from userinfo where user_name='{$user_name}' and user_passwd='{$user_passwd}'";
    $res=$db->query($sql);//执行sql语句
//     $res->setFetchMode(PDO::FETCH_ASSOC);
    $res->setFetchMode(PDO::FETCH_ASSOC);
    //设置返回类型（关联数组）
    $arr=$res->fetchAll();//返回所有数据
    $user_id=$arr[0]['user_id'];//存入session
 
    //print_r($arr);$res->fetchAll()
//      print_r("$arr") ;
    if(empty($arr)){
//     	echo "用户名和密码不匹配，请重新输入！";

     $_SESSION['islogin']=0;//代表登陆失败
    echo "<script> alert('用户名和密码不匹配，请重新输入！');  </script>";
    }else {
    	//数组非空，成功登陆，匹配
    	//变量分配
    	$_SESSION['islogin']=1;//状态位1表示成功登陆
    	
    	$_SESSION['username']=$user_name;//保存在session中以便其他页面也可以调用
    	$_SESSION['userid']=$user_id;
    	$smarty->assign("islogin",$_SESSION['islogin']);
    	$smarty->assign("username",$_SESSION['username']);//将成功登陆的用户名分配到跳转模板
    	//$smarty->display("index.html");
    	header("location:index.php");
    	exit();
    }
}


$smarty->display("login.html");
?>