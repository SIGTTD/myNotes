<?php
	mysql_connect("127.0.0.1", "root", "") or die(mysql_error);   
	mysql_select_db("my-notes") or die(mysql_error());
	$userName = $_COOKIE['userLogin'];
	for ($i = 0; $i < 10000; $i++) {
		if (isset($_POST['deletePost'.$i])) {
			$deletePost = $_POST['deletePost'.$i];
			$deletePostSQL = "DELETE FROM ".$userName." WHERE id = $i";
			mysql_query($deletePostSQL);
			header('Location: http://sigttd.ru:81/#postsHdr');
		}
		if (isset($_POST['editPost'])) {
			$editPost = $_POST['editPost'.$i];
			$editedPost = $_POST['editedPost'.$i]; 
			$editPostSQL = "UPDATE ".$userName." SET content = '" . $editedPost . "' WHERE id = $i";
			mysql_query($editPostSQL);
			header('Location: http://sigttd.ru:81/#editedPost');
		}
	}
	
?>
