<?php
	session_start();
	if ($_POST['exit']){		
		setcookie("userLogin","noEnter",time()-1);
		setcookie("userEnter",false,time()-1);
		header('Location: http://sigttd.ru:81/');
	}
	$userName = $_COOKIE['userLogin'];
	$filename = $userName."_Notes.txt";
	if ($_POST['saveFile']) {
		if (file_exists($filename)) {
			header("Cache-Control: must-revalidate");
			header("Pragma: public");
			header("Content-Length: " .(string)(filesize($filename)) );
			header("Content-Type: application/octet-stream");
			header('Content-Disposition: attachment; filename=my_notes.txt');
			header("Content-Transfer-Encoding: binary");
			readfile($filename);
			unlink($filename);			
		}
		else {
			echo "<h1>FILE NOT FOUND!</h1>";
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My notes</title>
		<link href="style.css" rel="stylesheet">
		<link rel="shortcut icon" href="icon.png" type="image/png">
	</head>
	<body>
		<script src="script.js" async></script>
		<div id="logo" style="position: absolute; top: 15px; left: 3%;">
			<a href="/"><img src="logoText.png" width="250px" height="125px"></a>
		</div>		
		<div id="pageHeader">
			<ul>
				<li id="reviews"><a href='http://sigttd.ru:81/?class=reviews'>Рецензии</a>
					<ul>
						<li id="films"><a href='http://sigttd.ru:81/?class=films'>Фильмы</a></li>
						<li id="tvShows"><a href='http://sigttd.ru:81/?class=tvshows'>Сериалы</a></li>
						<li id="books"><a href='http://sigttd.ru:81/?class=books'>Книги</a></li>
					</ul>
				</li>
				<li id="thoughts"><a href='http://sigttd.ru:81/?class=thoughts'>Рассуждения</a></li>
				<li id="basic"><a href='http://sigttd.ru:81/'>Общее</a></li>
			</ul>
		</div>
		<?php 
			if ($_COOKIE['userEnter'] == 0) {
				$userForm = "
					<div id='loginForm'>
						<h2>Войти</h2>
						<form method='post' action='session.php'>
							<input type='text' name='userName' placeholder='Ваш логин'>
							<input type='password' name='userPassword' placeholder='Ваш пароль'>
							<input type='submit' name='enter' value='Войти'>
						</form>
					</div>
					<div id='regForm'>
						<h2 id='h2reg'>Регистрация</h2>
						<form method='post' action='reg.php'>
							<input type='text' name='newUserName' minlength='3' maxlength='31' placeholder='Ваш логин' required>
							<input type='password' name='newUserPassword' minlength='5' maxlength='31' placeholder='Ваш пароль' required>
							<input type='password' name='newUserPasswordCheck' minlength='5' maxlength='31' placeholder='Подтвердите пароль' required>
							<input type='email' name='newUserMail' maxlength='31' placeholder='Ваша почта' required>
							<input type='submit' name='register' value='Отправить'>
						</form>
					</div>";
			} 
			else {
				$userForm ='
					<div id="loginForm" style="border: none; background: none;">
						<form method="post">
							<h2 style="margin-bottom: 50px;">Здравствуйте, <i>'. $_COOKIE['userLogin'] .'</i>!</h2>
							<input type="submit" name="saveFile" value="Сохранить в файл">
							<input type="submit" name="exit" value="Выйти">
						</form>
					</div>';				
			}
			echo $userForm;
			if ($_COOKIE['noMatch'] == 1) {
				echo "<span style='display: block;position: fixed;top: 325px;right: 5%;width: 200px;height: 20px;color: #f77;font-size:14px'>Неверное имя пользователя или пароль!</span>";
			}
			if ($_COOKIE['noMatchReg'] == 1) {
				echo "<span style='display: block;position: fixed;top: 700px;right: 5%;width: 200px;height: 20px;color: #f77;font-size:14px'>Не все поля заполнены или пароли не совпали!</span>";
			}
			if ($_COOKIE['userExists'] == 1) {
				echo "<span style='display: block;position: fixed;top: 700px;right: 5%;width: 200px;height: 20px;color: #f77;font-size:14px'>Имя пользователя уже занято!</span>";
			}
		?>
		<div id="note">
			<form method="post">
				<textarea name="userPost" cols="50" rows="10" placeholder="Напишите что-нибудь"></textarea>
				<input type="submit" value="Сохранить">
			</form>
		</div>
		<div id="recentPosts">
				<?php
					$db = mysql_connect ('127.0.0.1', 'root', '');
					mysql_select_db("my-notes") or die(mysql_error());
					$userName = $_COOKIE['userLogin'];
					$postClass = $_GET['class'];
					if (isset($_POST['userPost'])) {		
						if ($_COOKIE['userEnter'] != 0) {
							$dateNtimeNow = getdate();
							if ($dateNtimeNow["mday"] < 10) {
								$dateNtimeNow["mday"] = "0" . $dateNtimeNow["mday"];
							}
							if ($dateNtimeNow["hours"] < 10) {
								$dateNtimeNow["hours"] = "0" . $dateNtimeNow["hours"];
							}
							if ($dateNtimeNow["minutes"] < 10) {
								$dateNtimeNow["minutes"] = "0" . $dateNtimeNow["minutes"];
							}
							$dateNtime = $dateNtimeNow["mday"] . ", " . $dateNtimeNow["month"] . ", " . $dateNtimeNow["hours"] . ":" . $dateNtimeNow["minutes"];						
							$postPre = $_POST['userPost'];
							$post = mysql_real_escape_string($postPre);			/* get back - stripslashes() */
							$strSQL = "INSERT INTO ".$userName."(content, date, class) VALUES('" . $post . "', '" . $dateNtime . "', '" . $postClass . "')";
							mysql_query($strSQL) or die(mysql_error());
						}
						else {
							echo "<span style='display: block;width: 250px;height: 20px; margin: 50px auto;color: #f77;font-size:16px'>Войдите, чтобы сохранить заметку</span>";
						}
					}
					if ($_COOKIE['userEnter'] != 0) {
						echo "<h2 id='postsHdr'>Последние записи</h2><div>";
						if (isset($_GET['class'])) {
							file_put_contents($filename, "", LOCK_EX);
							for($i = 300; $i >= 0; $i--) {													
								$resultDB = mysql_query("SELECT * FROM ".$userName." WHERE id = $i") or die(mysql_error());
								$dab = mysql_fetch_assoc($resultDB);
								if (($dab["id"] != 0) && ($dab["class"] == $postClass)) {
									echo "<div>id: " . $dab["id"] . "; content: 
										<br>
										<form id='editForm' method='post' action='edit.php'>
											<p id='postContent'>" .  htmlspecialchars($dab["content"]) . "</p>
											<textarea id='editedPost' name='editedPost".$dab['id']."'>" .  htmlspecialchars($dab["content"]) . "</textarea>
											<div>
												<input type='submit' name='editPost".$dab['id']."' value='Изменить'>
												<input id='deletePost' type='submit' name='deletePost".$dab['id']."' value='Удалить'>
												<span class='postMeta'>" . $dab["date"] . "</span>
											</div>
										</form>
									</div>";
									?><script>editClickOn();</script><?php
									file_put_contents($filename, htmlspecialchars($dab["content"]) . "\r\n\r\n" . $dab["date"] . "\r\n\r\n-------------------------------\r\n\r\n", FILE_APPEND | LOCK_EX);
								}
							}
						}
						else {
							file_put_contents($filename, "", LOCK_EX);
							for($i = 300; $i >= 0; $i--) {												
								$resultDB = mysql_query("SELECT * FROM ".$userName." WHERE id = $i") or die(mysql_error());
								$dab = mysql_fetch_assoc($resultDB);
								if ($dab["id"] != 0) {
									echo "<div>id: " . $dab["id"] . "; content: 
										<br>
										<form id='editForm' method='post' action='edit.php'>
											<p id='postContent' onclick='
												var postView = document.getEementById(\"postContent\");
												postView.style.visibility = \"hidden\";
												alert(233);
											'>" .  htmlspecialchars($dab["content"]) . "</p>
											<textarea id='editedPost' name='editedPost".$dab['id']."'>" .  htmlspecialchars($dab["content"]) . "</textarea>
											<div>
												<input type='submit' name='editPost".$dab['id']."' value='Изменить'>
												<input id='deletePost' type='submit' name='deletePost".$dab['id']."' value='Удалить'>
												<span class='postMeta'>" . $dab["date"] . "</span>
											</div>
										</form>
									</div>";
									file_put_contents($filename, htmlspecialchars($dab["content"]) . "\r\n\r\n" . $dab["date"] . "\r\n\r\n-------------------------------\r\n\r\n", FILE_APPEND | LOCK_EX);
								}
							}
						}
						echo "</div>";
					}
				?>
		</div>
		<img id="hintImg" src="hintOn.png" width="75" height="75" alt="hintOn">
		<div id="hintBlock">
			<img src="hintOff.png" width="75" height="75" alt="HintOff">
			<h2>Подсказка</h2>
			<p>Выберите раздел меню и оставьте заметку. Каждая заметка сохраняется на сервере. Вы можете также сохранить все заметки в файл.</p>
			<p>Есть вопросы? <a href="mailto:sigttd@ya.ru"><b>sigttd@ya.ru</b></a></p>
		</div>
	</body>
</html>