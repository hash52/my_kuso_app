<?php 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>検索結果</title>
</head>

<body>
検索結果
<hr>

<?php
	$file_name = 'data.txt';
	if (file_exists($file_name) && !filesize($file_name)==0) {
		foreach ($_SESSION['search_result'] as $value) {
			$timestamp = $value['time'];
?>
<p><?php echo $value['index'];?>:<?php echo $value['name'];?></p>
<p>投稿日時:<?php echo date("Y年m月d日 H時i分s秒",$timestamp);?></p>
<br>
<p><?php echo $value['message'];?></p><hr>
	
<?php
		}
	}
?>

</body>
 
</html>