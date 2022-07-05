<!DOCTYPE html>
<?php 
session_start();
?>

<HTML>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./bbs.css">
	<title>BBS</title>
</head>
<body>
<div class = "header">
<div class = "header-left"><h1>掲示板</h1></div>

<?php if (file_exists($_SESSION['file_name']) && !filesize($_SESSION['file_name'])==0) {
	?>
<div class = "header-right">
	<form method="get" action="datawrite.php">
		<select name='order'>
			<option value='new_order'>新しい順</option>
			<option value='old_order' 
			<?php if($_SESSION['order']=='selected_old'){?>selected<?php }?>>古い順</option>
		</select>

		
	<?php for($page_num = 1; $page_num <= $_SESSION['page_max'] ;$page_num++){ 
			echo "~".$page_num*10;?>
		<input type="radio" name='page_num' value=<?php echo $page_num;?>
		<?php if($_SESSION['now_page'] == $page_num){?>checked<?php }?>>
		&nbsp;
	<?php }?>
	 	<button type='submit' name='action' value='send'>移動</button>
	</form>

	<form method="post" action="datawrite.php" style="display: inline">
		<select name='delete_index'>
		<?php
		for($i=1; $i<=count($_SESSION['data']); $i++){?>
			<option value=<?php echo $i;?>>
			<?php echo $i; ?></option>
		<?php }?>				
		</select>
	 	<button type='submit' name='action' value='send'>削除</button>
	</form>
	<form method="post" action="datawrite.php" style="display: inline">
		<input type="text" name="search" maxlength=600>
		<button type='submit' name='action' value='send'>検索</button>
	</form>
	
	</div>
<?php 
}?>
</div>

<div class="contents">
<!--　掲示板  -->
	<?php
	if (file_exists($_SESSION['file_name']) && !filesize($_SESSION['file_name'])==0) {
	echo "<hr>";
		foreach ($_SESSION['display_data'] as $value) {
		$timestamp = $value['time'];
	?>

	<p><?php echo $value['index'];?>:<?php echo $value['name'];?></p>
	<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
	<br>
	<p><?php echo $value['message'];?></p>

	<hr>

	<p><?php 
		if(!($value['reply']['index']==0)){
			$timestamp = $value['reply']['time'];?>
	<div class="reply">		
		<p>※<?php 
				echo $value['reply']['index'];?>:<?php echo $value['reply']['name'];?></p>
		<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
		<br>
		<p><?php 	echo $value['reply']['message']?></p>
	</div>
	<hr>
	<?php 
		}
	}?>
</div>

<hr>
<?php 
}
?>
<div class ="write">
	<form method="post" action="datawrite.php">
	<?php if (file_exists($_SESSION['file_name']) && !filesize($_SESSION['file_name'])==0) {
	?>
		<input type="checkbox" name="rep_checked">
		<select name='reply_to'>
			<?php
			for($i=1; $i<=count($_SESSION['data']); $i++){?>
				<option value=<?php echo $i;?>>
				<?php echo $i; ?></option>
			<?php }?>				
		</select>
		返信->チェックして番号を選択
		<br>
	<?php 
	}?>
		お名前:
		<input type="text" name="name" maxlength=30><br>
		書き込み:<br>
		<textarea name="message" rows="5" cols="80"  maxlength=600></textarea><br />
		<input type="file" name="upfile" accept="image/*" size="30" /><br>
  		<button type='submit' name='action' value='send'>送信</button>
	</form>

</div>

</body>
</HTML>