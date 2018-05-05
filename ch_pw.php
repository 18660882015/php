<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){
	//登陆成功,页面信息展示
	$user_name=$_SESSION['username'];//用户名
	$user_id=$_SESSION['userid'];//用户编号
	//根据id找性别
	$sql="select * from userinfo where user_id='{$user_id}'";
	$res=$db->query($sql);
	$arr=$res->fetchAll(PDO::FETCH_ASSOC);
    $user_passwd=$arr[0]['user_passwd'];

	//print_r($arr);
	//有数据提交接收数据
	//echo 111111;
	if (isset($_POST['act'])&& $_POST['act']=="newpasswd"){
		//有数据提交接收数据
		//echo 33331;
		$npwd=$_POST['npwd'];
		//echo $npwd;
		$user_id=$_SESSION['userid'];
		if ($npwd!=""){
			//echo 2222;
		
		$sql="update userinfo set user_passwd='{$npwd}' where user_id='{$user_id}'";
        $res=$db->exec($sql);
        if ($res){
        	echo "<script>alert('密码修改成功！')</script>";
        }else {
        	echo "$sql";
        }
		}else {
			echo "<script>alert('密码不能为空！')</script>";
		}
	}
	$smarty->assign('user_name',$user_name);
	$smarty->display("ch_pw.html");
}
else {
	$smarty->display("login.html");
}


?>