<!DOCTYPE html>
<?php 
session_start();
?>

<HTML>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>テストBBS</title>
</head>
<body>

<form method="post" action="datawrite.php">
	お名前:<br>
	<input type="text" name="name" ><br>
	書き込み:<br>
	<textarea name="message" rows="5"></textarea><br />
  	<button type='submit' name='action' value='send'>送信</button>
</form>
<hr>
<?php 
echo "配列\$dataの要素数は".count($_SESSION['data'])."<hr>";
?>

<?php 
/*
foreach ($_SESSION['data'] as $value){
		if(!($value['reply']['index']==0)){
			$timestamp = $value['reply']['time'];?>
		<p>※<?php echo $value['reply']['index'];?>:<?php echo $value['reply']['name'];?></p>
		<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
		<br>
		<p><?php echo $value['reply']['message']?></p><hr>
		<?php 
		}
}
*/
?>
<hr><hr>

<!--　掲示板  -->
<?php
	if (file_exists($_SESSION['file_name']) && !filesize($_SESSION['file_name'])==0) {
		foreach ($_SESSION['data'] as $value) {
			$timestamp = $value['time'];
?>
<p><?php echo $value['index'];?>:<?php echo $value['name'];?></p>
<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
<br>
<p><?php echo $value['message'];?></p><hr>

<p><?php 

		if(!($value['reply']['index']==0)){
			$timestamp = $value['reply']['time'];?>
		<p>※<?php echo $value['reply']['index'];?>:<?php echo $value['reply']['name'];?></p>
		<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
		<br>
		<p><?php echo $value['reply']['message']?></p><hr>
		<?php 
		}

?>


	
<?php
		}
	}
?>

<form method="get" action="datawrite.php">
	<select name='order'>
		<option value='new_order'>新しい順</option>
		<option value='old_order' 
		<?php if($_SESSION['order']=='selected_old'){?>selected<?php }?>>古い順</option>
	</select>
	ページ番号の選択
<?php for($page_num = 1; $page_num <= $_SESSION['page_max'] ;$page_num++){ 
		echo $page_num;?>
		<input type="radio" name='page_num' value=<?php echo $page_num;?>
		<?php if($_SESSION['now_page'] == $page_num){?>checked<?php }?>>
		&nbsp;
		
<?php }
?>
 	<button type='submit' name='action' value='send'>移動</button>
</form>

<a href='#'>10件←</a>
<a href='#'>→10件</a>

<form method="post" action="datawrite.php">
	<input type="text" name="search">
	<button type='submit' name='action' value='send'>検索</button>
</form>

<?php if(!filesize($_SESSION['file_name'])==0){?>
<form method="post" action="datawrite.php">
	<select name='delete_index'>
	<?php
	for($i=1; $i<=count($_SESSION['data']); $i++){?>
		<option value=<?php echo $i;?>>
		<?php echo $i; ?></option>
	<?php }?>				
	</select>
	 <button type='submit' name='action' value='send'>削除</button>
	</form>
<?php }?>

<hr>
返信
<form method="post" action="datawrite.php">
	<select name='reply_to'>
	<?php
	for($i=1; $i<=count($_SESSION['data']); $i++){?>
		<option value=<?php echo $i;?>>
		<?php echo $i; ?></option>
	<?php }?>				
	</select>
	お名前:<br>
	<input type="text" name="rep_name" ><br>
	書き込み:<br>
	<textarea name="rep_message" rows="5"></textarea><br />
  	<button type='submit' name='reply_done'>返信</button>
</form>
	
	<?php 
	$data = $_SESSION['data'];
	print_r($data[1]['message']);?>
	<hr>
	<?php 
	print_r($_SESSION['data']);
	?>

</body>
</HTML>