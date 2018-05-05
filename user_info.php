<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
require_once 'page.class.php';//把page.css在静态页面中引用2.在php文件中引入page.class.php
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=127.0.0.1;dbname=buy2", "root", "root");
$db->exec("set names utf8");
 if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){
 	//登陆成功,页面信息展示
 	$user_name=$_SESSION['username'];//用户名
 	$user_id=$_SESSION['userid'];//用户编号
 	//根据id找性别
 	//echo "$user_name";
 	
 	//echo "$user_id";
 	
 	$sql="select * from userinfo where user_id='{$user_id}'";
 	$res=$db->query($sql);
 	if ($res){//如果成功
 		$arr=$res->fetchAll(PDO::FETCH_ASSOC);
		//print_r($arr);
     $user_sex=$arr[0]['user_sex'];
 	}else {
 		echo $sql;
 	}
 	
 	if (isset($_POST['act'])&& $_POST['act']=="userinfo"){
 		//有数据提交接收数据
 		$email=$_POST['email'];
 		$birth=$_POST['birth'];
 		$phone=$_POST['phone'];
 		$question=$_POST['question'];
 		$answer=$_POST['answer'];
 		$user_id=$_SESSION['userid'];
 		echo "$question";
        if ($email !="" && $birth!="" && $phone!="" && $question!="" && $answer!=""){
//         	echo "yes";
        $sql="update userinfo set user_email='{$email}',user_birthday='{$birth}',user_telphone='{$phone}',user_question='{$question}',user_answer='{$answer}' where user_id='{$user_id}'";
        $res=$db->exec($sql);
        if ($res){
        	echo "个人信息编辑成功。";
        }else {
        	echo "$sql";
        }
   }else {
        	echo "各项不能为空，请填写完整后提交。";
        }
 	}
 	$smarty->assign("arr",$arr);
 	$smarty->assign("user_name",$user_name);
 	$smarty->assign("user_sex",$user_sex);
 	$smarty->display("user_info.html");
 }else{
 	$smarty->display("login.html");
 }

?>