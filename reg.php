<?php
	mysql_connect("127.0.0.1", "root", "") or die(mysql_error);   
	mysql_select_db("my-notes") or die(mysql_error());
	$newUserName = $_POST['newUserName'];
	$find_id_sql = mysql_query("SELECT * FROM users ORDER BY id ASC");
	$find_id_row = mysql_num_rows($find_id_sql);
	$find_names = mysql_query("SELECT * FROM users WHERE userName='".$newUserName."'");
	$gotName = mysql_fetch_assoc($find_names);
	if($newUserName == $gotName['userName']){
		setcookie("userExists", true, time()+3);
		header('Location: http://sigttd.ru:81/');
	}
	else {
		if ($_POST['newUserName'] && $_POST['newUserMail'] && ($_POST['newUserPassword'] == $_POST['newUserPasswordCheck'])) {
			$newUserName = $_POST['newUserName'];
			$newUserPassword = $_POST['newUserPassword'];
			$newUserMail = $_POST['newUserMail'];
			$newUserSQL = "INSERT INTO users(userName, userPassword, mail) VALUES('" . $newUserName . "', '" . $newUserPassword . "', '" . $newUserMail . "')";
			$newTableSQL = "CREATE TABLE ".$newUserName." (id INT(11) AUTO_INCREMENT, content TEXT, date TEXT, class VARCHAR(50), PRIMARY KEY (id))";
			mysql_query($newUserSQL) or die(mysql_error());
			mysql_query($newTableSQL) or die(mysql_error());
			$to  = $_POST['newUserMail']; 
			$subject = "Registration on My Notes"; 
			$message = "Hallo, thank you for joining! ^_^ \r\nYour login: ".$newUserName.".\r\nYour password: ".$newUserPassword.".\r\nSincerely, My Notes service.";
			$headers  = "Content-type: text/html; charset=utf-8 \r\nFrom: От кого письмо <webmaster@mynotes.ru>\r\nReply-To: webmaster@mynotes.ru\r\nX-Mailer: PHP/".phpversion(); 
			if (mail($newUserMail, $subject, $headers)) {
				setcookie("userEnter", true);
				setcookie("userLogin", $newUserName);
				header('Location: http://sigttd.ru:81/');
			}
			else {
				echo "mail err";
			}
		}
		else {
			setcookie("noMatchReg", true, time()+3);
			header('Location: http://sigttd.ru:81/');
		}
	}
?>