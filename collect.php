<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
require_once 'page.class.php';
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
// @$user_id=$_SESSION['userid'];//@屏蔽小错误

if (isset($_GET['act']) && $_GET['act']=="collect"){
	$good_id=$_GET['good_id'];
	$user_id=$_SESSION['userid'];
	//首先判断该商品是否是被收藏
$sql="select c_id from collection where user_id='{$user_id}' and good_id='{$good_id}'";
$res=$db->query($sql);
  //echo $res;
if ($res){
	$arr=$res->fetchAll(PDO::FETCH_ASSOC);
	if (empty($arr)){
		//该用户没有收藏过此商品
	    $sql="insert into collection (good_id,user_id) values('{$good_id}','{$user_id}')";
		$res=$db->exec($sql);
		//echo $res;
		echo "<script>alert('收藏成功！')</script>";
		}else {
			echo "<script>alert('您已收藏过此商品！')</script>";
		      }
}else{
		//该用户收藏过此商品
		echo "<script>alert('收藏失败！')</script>";
	 }
}else {
	echo $sql;
}

if (isset($_GET['act']) && $_GET['act']=="delete"){
	//代码
	$c_id=$_GET['c_id'];
	$sql = "delete  from collection where c_id='{$c_id}'";
	$res=$db->exec($sql);
	if ($res){
		echo "<script>alert('成功取消收藏！')</script>";
    }else {
		echo "<script>alert('sql执行失败！')</script>";
	}
}
//判断登陆用户才可访问
if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){
	$sql="select * from collection,goodinfo where collection.good_id=goodinfo.good_id and collection.user_id='{$user_id}'";
      $res=$db->query($sql);
      if ($res){
      	$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
      }else {
      	echo "<script>alert('sql执行失败。');</script>";
      }
      $smarty->assign("arr_all",$arr_all);
      $smarty->display("collect.html");
}else {
	echo "<script>alert('您未登录，请登陆！');window.location='login.php'</script>";//window.location 跳转到
}	
  


  //分页的逻辑处理
  $showrow=3;//每页显示的条数
  $curpage=empty($_GET['page']) ?1 :$_GET['page'];//三位运算
  $total=$res->rowCount(PDO::FETCH_ASSOC);//总条数
  $url = "?page={page}";//分页的地址
  
  if (!empty($_GET['page']) && $total != 0 && $curpage>ceil($total/$showrow)){
  	$curpage=ceil($total/$showrow);//当前页数大于最后一页则显示最后一页
  }
  //获取每一页的数据
  $user_id=$_SESSION['userid'];
  $sql .=" limit ".($curpage-1)*$showrow.",{$showrow}";//限制每页的显示
  //     echo $sql;
  $res=$db->query($sql);
  if ($res){
  	//执行成功才设置
  	$all=$res->fetchAll(PDO::FETCH_ASSOC);
  }else {
  	echo $sql;
  }
   
  if ($total>$showrow){     
  	//记录数大于每页展示的条数才显示分页
  	$page=new page($total,$showrow,$curpage,$url,2);
  	$showpage=$page->myde_write();//分页输出
  }
  $smarty->assign('showpage',$showpage);
  $smarty->assign('all',$all);

?>