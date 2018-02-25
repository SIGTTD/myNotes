<?php
	mysql_connect("127.0.0.1", "root", "") or die(mysql_error);   
	mysql_select_db("my-notes") or die(mysql_error());
	$checkPass = mysql_query("SELECT * FROM users WHERE userPassword='" . $_POST['userPassword'] . "'");
	$rowCheckPass = mysql_num_rows($checkPass);
	while($rowCheckPass = mysql_fetch_assoc($checkPass)){  
		$table['id'] = $rowCheckPass['id']; 
		$table['login'] = $rowCheckPass['userName']; 
		$table['password'] = $rowCheckPass['userPassword'];
	}
	if ($_POST['userPassword'] == $table['password']){
		$md5_password = md5($_POST['userPassword']);
		if ($_POST['userName'] == $table['login']){ 
			setcookie("userEnter", true);
			setcookie("userLogin", $_POST['userName']);
			header('Location: http://sigttd.ru:81/'); 
		}else{	
			setcookie("userLogin", "noEnter");
			setcookie("userEnter", false);
			header('Location: http://sigttd.ru:81/'); 
		}
	}
	else {
		setcookie("noMatch", true, time()+3);
		header('Location: http://sigttd.ru:81/');
	}
?>