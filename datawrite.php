<?php

session_start();

$_SESSION['file_name'] = 'data.txt';

//data.txt作成
if(!file_exists($_SESSION['file_name'])){
	touch($_SESSION['file_name']);
}

//書き込みが無いときには行われない処理
if(!(filesize($_SESSION['file_name']) == 0)){
	//data.txtが空じゃなければ、unserializeデータを生成
	$data = unserialize(file_get_contents($_SESSION['file_name']));
	$_SESSION['data'] = $data;
}

if($_SERVER["REQUEST_METHOD"] != "POST"){
	// ブラウザからHTMLページを要求された場合
	//遷移ボタンを生成するのに必要なページ数を計算
	if(count($data) % 10 == 0){
		$_SESSION['page_max'] = count($data) / 10;
	}else{
		$_SESSION['page_max'] = (int)((count($data)) / 10 + 1);
	}
	
	//指定されたページへ遷移
	if (@$_GET['page_num']) {
		$data = array_slice($data,$_GET['page_num']*10-10,10);
		$_SESSION['now_page'] = $_GET['page_num'];
	}if(count($data)<=10){
		//もしこれが無ければ
		//投稿が９件のとき、次のelse文中にある array_slicez()の引数が、
		//array_slice($data,9-10,10)となり、1件だけが表示されてしまうので、ここで弾いている。
		$_SESSION['now_page'] = $_SESSION['page_max'];
		
	}else{
		//最新10件を取得
		$data = array_slice($data,count($data)-10,10);
		$_SESSION['now_page'] = $_SESSION['page_max'];
	}
	
	if(@$_GET['order'] == 'old_order' || $_SESSION['order'] == 'selected_old'){
		//投稿を過去から順に表示する。
		$data = array_reverse($data);
		//ページが遷移しても、一度選択された表示順が記憶される
		$_SESSION['order'] = 'selected_old';
	}
	if(!isset($_SESSION['order']) || $_GET['order'] == 'new_order'){
		$_SESSION['order'] = 'unselected_old';
	}
	
	//表示データだけを格納して、bbs.phpに渡す。
	$data = array_reverse($data);
	$_SESSION['display_data'] = $data;	
	
	header('Location: ./bbs.php');
	exit;
	
}else{
	// フォームからPOSTによって要求された場合
	if (@$_POST['message']) {
		//投稿された時に呼び出される
		
		if(@$_POST['rep_checked']) {
			//返信投稿
			$reply_to = $_POST['reply_to'] ;
			$reply_to = $reply_to -1;
		
			if(@$_POST['name']){
				$rep_name = htmlspecialchars($_POST['name'],ENT_QUOTES);
			}else{
				$rep_name = "名無しさん";
			}
		
			$reply = array('index' => 1, 'time'=>time(),'name'=> $rep_name,'message' => nl2br(htmlspecialchars($_POST['message'],ENT_QUOTES)));
			$data[$reply_to]['reply'] = $reply;
		
			try{
				$serialized_data = serialize($data);
				file_put_contents($_SESSION['file_name'], $serialized_data);
			}catch(Exeption $e){
				echo "返信保存失敗やで";
			}
		
			header('Location: ./datawrite.php');
			exit;
		}
		
		if(@$_POST['name']){
			$name = htmlspecialchars($_POST['name'],ENT_QUOTES);
		}else{
			$name = "名無しさん";
		}
		
		if(filesize($_SESSION['file_name'])==0){
			$index = 1;
		}else{
			$index = $data[count($data)-1]['index'] + 1;
		}
		
		$reply = array('index' => "0", 'time' => '','name' => '','message' =>"");
		
		$data[] = array('index' => $index, 'time'=>time(),'name'=>$name,'message' => nl2br(htmlspecialchars($_POST['message'],ENT_QUOTES)),
				'reply' => $reply);
		try{
			$serialized_data = serialize($data);
			file_put_contents($_SESSION['file_name'], $serialized_data);
		}catch(Exeption $e){
			echo "データ保存失敗やで";
		}

		header('Location: ./datawrite.php');
		exit;
		
	}elseif(@$_POST['delete_index']){
		//削除された時に呼び出される
		try{
			$reply = array('index' => "0", 'time' => '','name' => '','message' =>"");
			$deleting_text[] = array('index' => $_POST['delete_index'], 'time'=>time(),'name'=> "削除太郎",'message' => "削除しました。",
			'reply' => $reply);
			array_splice($data, $_POST['delete_index']-1,1,$deleting_text);			
			$serialized_data = serialize($data);
			file_put_contents($_SESSION['file_name'], $serialized_data);
		}catch(Exeption $e){
			echo "データ削除失敗やで";
		}
		
		header('Location: ./datawrite.php');
		exit;
		
	}elseif(@$_POST['search']){
		$search_decision = 0;
		$search = htmlspecialchars($_POST['search'],ENT_QUOTES);

		foreach($data as $value){
			if (in_array($search, $value)) {
				$search_result[] = $value;
				$search_decision = 1;
			}
		}
		
		if($search_decision == 0){
			echo "該当する書き込みはありませんでした。(この検索は、完全一致でしか検索できません。)";
		}else{
			$_SESSION['display_data'] = $search_result;
			header('Location: ./bbs.php');
			exit;
		}
		
	}else{
		echo "書き込みが未入力です。<hr>";
	}

	
}
?>