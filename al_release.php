<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
require_once 'page.class.php';//把page.css在静态页面中引用2.在php文件中引入page.class.php
$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");

if (isset($_SESSION['islogin']) && $_SESSION['islogin']==1){
	//商品删除处理
	if (isset($_GET['act']) && $_GET['act']=="delete"){
		//代码
       // echo "shanchu";
     $good_id=$_GET['good_id'];//商品的编号
     $sql = "delete  from goodinfo where good_id='{$good_id}'";
     $res=$db->exec($sql);
     if ($res){
     	echo "<script>alert('删除成功！')</script>";
     }else {
     	echo "<script>alert('删除失败！')</script>";
     }
     
	}
	  //商品的修改。和新增一样update
	if (isset($_GET['act']) && $_GET['act']=="update"){
        $good_id=$_GET['good_id'];        
     $sql="select * from goodinfo where good_id='{$good_id}'";
     $res=$db->query($sql);
     $all=$res->fetchAll(PDO::FETCH_ASSOC);
     if ($res){
		$smarty->assign('all',$all);
     	$smarty->display("ch_release.php");
     	exit();
     }
	}
	
	
	//商品列表的展示处理
	
	//当前用户已经登陆
	$user_name=$_SESSION['username'];
	$sql="select user_id from userinfo where user_name='{$user_name}'";
	$res=$db->query($sql);
// 	$res->setFetchMode(PDO::FETCH_ASSOC);
// 	$arr=$res->fetchAll();
    $arr=$res->fetchAll(PDO::FETCH_ASSOC);
	$user_id=$arr[0]['user_id'];
	//根据用户名找用户编号
	$sql="select * from goodinfo where user_id='{$user_id}'";
	$res=$db->query($sql);
	$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
//     print_r($arr_all);
//分页的逻辑处理
    $showrow=3;//每页显示的条数
    $curpage=empty($_GET['page']) ?1 :$_GET['page'];//三位运算
    $total=$res->rowCount(PDO::FETCH_ASSOC);//总条数
    $url = "?page={page}";//分页的地址
    
    if (!empty($_GET['page']) && $total != 0 && $curpage>ceil($total/$showrow)){
    	$curpage=ceil($total/$showrow);//当前页数大于最后一页则显示最后一页
    }
    //获取每一页的数据
    $sql .=" limit ".($curpage-1)*$showrow.",{$showrow}";//限制每页的显示
//     echo $sql;
     $res=$db->query($sql);
     if ($res){
     	//执行成功才设置
     	$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
     }else {
     	echo $sql;
     }
     
     if ($total>$showrow){//记录数大于每页展示的条数才显示分页
     	$page=new page($total,$showrow,$curpage,$url,2);
     	$showpage=$page->myde_write();//分页输出
     }
     $smarty->assign("showpage",$showpage);
    $smarty->assign('arr_all',$arr_all);
    $smarty->display("al_release.html");
}else {
	$smarty->display("no_login.html");
}




?>