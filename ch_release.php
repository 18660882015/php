<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
require_once 'page.class.php';//把page.css在静态页面中引用2.在php文件中引入page.class.php
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
$_SESSION['islogin']=1;

   $good_id=$_GET['good_id'];
//    echo $good_id;
   $sql="select * from goodinfo where good_id='{$good_id}'";
   $res=$db->query($sql);
   $arr=$res->fetchAll(PDO::FETCH_ASSOC);
   $smarty->assign("arr",$arr);
// if (isset($_POST['act'])&&$_POST['act']=="update_good"){
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
	$good_time=time();
	if($good_name !="" && $good_price!="" && $good_picture!=""){
		$houzhui=substr($good_picture['name'], -3,3);
		if ($houzhui=="gif" || $houzhui=="png" || $houzhui=="jpg" || $houzhui=="JPG"){

			if ($good_picture['error']==1){
					echo "文件太大，请压缩后再上传！";
				}elseif ($good_picture['error']==2){
					echo "文件太大，请压缩后再上传！";
				}elseif ($good_picture['error']==3){
					echo "网络不稳定，请重新上传！";
				}elseif ($good_picture['error']==4){
					echo "文件为空，请选择后再上传";
				}elseif ($good_picture['error']==0){
				
		
				$src=$good_picture['tmp_name'];
				$dest="./public/images/goodimg/{$good_picture['name']}";
				if (file_exists($dest)){    
					$a=substr($dest,0,strlen($dest)-4);
					$a=$a."(1)";
					$a=$a.substr($dest,-4,4);
				}
				$bool=move_uploaded_file($src,$dest);
				if ($bool){
				
					$sql="update goodinfo set good_name='{$good_name}',good_price='{$good_price}',good_desc='{$good_desc}',
					good_bigtype='{$good_bigtype}',good_smalltype='{$good_smalltype}',good_trading_province='{$good_trading_province}',
					good_trading_city='{$good_trading_city}',good_attn_phone='{$good_attn_phone}',
					good_attn_name='{$good_attn_name}',good_time='{$good_time}',good_picture='{$dest}'
					where good_id='{$good_id}'";
					$res=$db->exec($sql);
					if ($res==1)
					{      
					header("location:al_release.php");
					//$smarty->display("al_release.html");
					exit();
					}
					else
					{
					echo "<script> alert('sql执行失败');</script>";
					}
					}else {
						echo "图片上传失败，请联系管理员!<br>";
					}
					}
					}else {
						echo "图片格式不正确，请重新选择!";
					}
					
					}
						
$smarty->display("ch_release.html");
								
					

?>
										
						
						
				