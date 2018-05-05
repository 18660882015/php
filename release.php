<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");

if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){//判断当前用户是否登陆成功，成功继续处理
// 	echo 1111111;
// 	$smarty->display("release.html");
	//判断是否是商品发布的表单提交的信息
	if (isset($_POST['act']) && $_POST['act']=="add_good"){
// 		echo       22222111;
		//接收数据
		$good_name=$_POST['good_name'];
		$good_price=$_POST['good_price'];
		$good_desc=$_POST['good_desc'];
		$good_bigtype=$_POST['good_bigtype'];
		$good_smalltype=$_POST['good_smalltype'];
		$good_picture=$_FILES['good_picture'];
		$good_trading_province=$_POST['good_trading_province'];
		$good_trading_city=$_POST['good_trading_city'];
		$good_attn_phone=$_POST['good_attn_phone'];
		$good_attn_name=$_POST['good_attn_name'];
		$good_time=time();//商品的发布时间
		if ($good_name !="" && $good_price!="" && $good_picture!=""){
// 			echo "yes";
			//print_r($good_picture);//上传的图片信息
			//对图片进行处理（文件上传）
			
			
			$good_picture= $_FILES['good_picture'];
// 			var_dump($good_picture);//查看上传图片的信息
			$houzhui=substr($good_picture['name'], -3,3);//获取上传图片的后缀名，substr截取文件后缀
			if ($houzhui=="gif" || $houzhui=="png" || $houzhui=="jpg" || $houzhui=="JPG"){
				//图片的验证及处理
				if ($good_picture['error']==1){
					echo "文件太大，请压缩后再上传！";
				}elseif ($good_picture['error']==2){
					echo "文件太大，请压缩后再上传！";
				}elseif ($good_picture['error']==3){
					echo "网络不稳定，请重新上传！";
				}elseif ($good_picture['error']==4){
					echo "文件为空，请选择后再上传";
				}elseif ($good_picture['error']==0){
					//$good_picture['error]为0才能继续处理
					$src=$good_picture['tmp_name'];
					$dest="./public/images/goodimg/{$good_picture['name']}";
					if (file_exists($dest)){//判断文件或目录是否存在
						$a=substr($dest,0,strlen($dest)-4);
						$a=$a."(1)";
						$a=$a.substr($dest,-4,4);
					}
					$bool=move_uploaded_file($src,$dest);//将上传的文件移动到新位置
					if ($bool){
						//$user_id
						$user_name=$_SESSION['username'];
						$sql="select user_id from userinfo where user_name='{$user_name}'";
						$res=$db->query($sql);
						$res->setFetchMode(PDO::FETCH_ASSOC);
						$arr=$res->fetchAll();
						$user_id=$arr[0]['user_id'];
						
						//成功，开始数据存库=================================
			           $sql="insert into goodinfo(good_name,good_price,good_desc,good_bigtype,good_smalltype,good_trading_province,good_trading_city,good_attn_phone,good_attn_name,good_time,good_picture,user_id)
			           		values ('{$good_name}','{$good_price}','{$good_desc}','{$good_bigtype}','{$good_smalltype}','{$good_trading_province}','{$good_trading_city}','{$good_attn_phone}','{$good_attn_name}','{$good_time}','{$dest}','{$user_id}')";
					//echo 
// 					$sql
					$res=$db->exec($sql);
					if($res){
						//成功
					
// 						$arr=$res->fetchAll(PDO::FETCH_ASSOC);
// 						$user_id=$arr[0]['user_id'];
// 						//根据用户名找用户编号
//完善
						$sql="select * from goodinfo where user_id='{$user_id}'";
						$res=$db->query($sql);
						$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
// 						print_r($arr_all);
						$smarty->assign('arr_all',$arr_all);
						$smarty->display("al_release.html");
						exit();
					}else {
						echo "<script>alert('sql执行失败，请联系管理员。')</script>";
					}
				}else {
						echo "图片上传失败，请联系管理员!<br>";
					}
				}
			}else {
				echo "图片格式不正确，请重新选择!";
			}
			
	}else {
			echo "商品名称/价格/图片不能为空！";
		}
		
		
	}
// 	$smarty->display("release.html");
$smarty->display("release.html");
}else {
	$smarty->display("login.html");
}


?>


