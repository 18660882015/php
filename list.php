<?php
header("content-type:text/html;charset=utf-8");
require_once './libs/Smarty.class.php';
require_once 'page.class.php';

$smarty=new Smarty();
session_start();
$db=new PDO("mysql:host=localhost;dbname=buy2", "root", "root");
$db->exec("set names utf8");
//接收大小分类变量，首先判断是否有变量传过来，有就接收，没有就赋值为空
if (isset($_GET['bigtype'])){
	$bigtype=$_GET['bigtype'];
	
}else {
	$bigtype=="";
}
if (isset($_GET['smalltype'])){
	$smalltype=$_GET['smalltype'];

}else {
	$smalltype=="";
}
//根据是否传递变量，执行不同的sql
if ($bigtype!="" && $smalltype==""){
     $sql="select * from goodinfo where good_bigtype='{$bigtype}' order by good_id desc";
}elseif ($smalltype!="" && $bigtype==""){
     $sql="select * from goodinfo where good_smalltype='{$smalltype}' order by good_id desc";
}elseif ($smalltype!="" && $bigtype!=""){
	 $sql="select * from goodinfo where good_smalltype='{$smalltype}' and good_bigtype='{$bigtype}' order by good_id desc";
}else {
     $sql="select * from goodinfo order by good_id desc";
}
$res=$db->query($sql);//只有query才会设置if￥res
if ($res){
	$arr_all=$res->fetchAll(PDO::FETCH_ASSOC);
}else {
	echo $sql;
}
//分页
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
$smarty->display("list.html");
?>