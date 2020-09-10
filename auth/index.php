<?
	require_once '../database.php';
	require_once '../functions.php';

	session_start();
	if (isset($_SESSION["login"])) { header('Location: ../'); }
	if ($_GET["service"] == "login") {
		$title = "Кіру";
	} else {
		$title = "Тіркелу";
		$hide = true;
	}

	if (isset($_POST["login"]) || isset($_POST["password"])) {
		$message = "";
		$login = $_POST["login"];
		$password = $_POST["password"];

		$arrayUsers = getUser($connection, $_POST["login"]);
		if (count($arrayUsers) != 0) { 
			// если вообще есть такой логин
			$passFrom = $arrayUsers[0]["password"];
			if (password_verify($password, $passFrom)) {
				session_destroy();
				ini_set('session.gc_maxlifetime', 10800);
				session_start();
				$_SESSION["login"] = $login;
				$_SESSION["surname"] = $arrayUsers[0]["surname"];
				$_SESSION["name"] = $arrayUsers[0]["name"];
				$_SESSION["type"] = $arrayUsers[0]["type"];

				if ($arrayUsers[0]["type"] == "admin") {
					$_SESSION["type"] = "admin";
					header('Location: ../admin/');
				} else {
					header('Location: ../');
				}
			} else {
				$message = "Логин немесе құпиясөз дұрыс терілмеген";
			}
		} else {
			$message = "Логин немесе құпиясөз дұрыс терілмеген";
		}
	}

	if (isset($_POST["login-reg"]) || isset($_POST["password-reg"])) {
		$message = "";
		$login = htmlspecialchars($_POST["login-reg"]);
		$password = $_POST["password-reg"];
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$surname = htmlspecialchars($_POST["surname"]);
		$name = htmlspecialchars($_POST["name"]);

		$arrayUsers = getUser($connection, $_POST["login-reg"]);
		if (count($arrayUsers) > 0) {$message = "Осы логин тіркелген, басқа логин теріңіз";}
		else {insertUser($connection, $login, $hash, $surname, $name);}

		if ($message == "") {
			session_destroy();
			ini_set('session.gc_maxlifetime', 10800);
			session_start();
			$_SESSION["login"] = $login;
			$_SESSION["surname"] = $surname;
			$_SESSION["name"] = $name;
			$_SESSION["type"] = "user";
			header('Location: ../');
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Авторизация - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Zoo Clinica</a></div>
	</header>
	<main>
		<div class="title"><? echo $title; ?></div>
		<div class="form <? if ($hide) echo "hide"; ?>">
			<form action="index.php?service=login" method="post" onsubmit="return validate('first')">
				<div class="input">
					<label for="login">Логин</label>
					<input type="text" name="login" id="login">
				</div>
				<div class="input">
					<label for="password">Құпиясөз</label>
					<input type="password" name="password" id="password">
				</div>
				<div class="input submit">
					<input type="submit" value="Кіру">
					<div class="reg-link">
						Әлі аккаунт жоқ па? <a href="../auth/?service=registration">Тіркеліңіз</a>
					</div>
				</div>
				<div class="input error">
					<? echo "<span>".$message."</span>"; ?>
				</div>
			</form>
		</div>

		<div class="form reg <? if (!$hide) echo "hide"; ?>">
			<form action="index.php?service=registration" method="post" onsubmit="return validate('second')">
				<div class="grid">
					<div class="item">
						<div class="input">
							<label for="login-reg">Логин</label>
							<input type="text" id="login-reg" name="login-reg">
						</div>
					</div>
					<div class="item">
						<div class="input">
						<label for="password-reg">Құпиясөз</label>
						<input type="password" id="password-reg" name="password-reg">
					</div>
					</div>
					<div class="item">
						<div class="input">
							<label for="surname">Фамилия</label>
							<input type="text" id="surname" name="surname">
						</div>
					</div>
					<div class="item">
						<div class="input">
							<label for="login">Аты</label>
							<input type="text" id="name" name="name">
						</div>
					</div>
					<div class="item">
						<div class="input">
							<div class="reg-link">
							Сізде аккаунт бар ма?<br><a href="../auth/?service=login">Сіз жүйеге кіре аласыз</a>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="input">
							<input type="submit" value="Тіркелу" class="right">
						</div>
					</div>
					<div class="item" id="error-item">
						<? echo "<span>".$message."</span>"; ?>
					</div>
				</div>
			</form>
		</div>
	</main>
	<script>
		function validate(form) {
			if (form == "second") {
				var loginReg = document.getElementById("login-reg");
				var passwordReg = document.getElementById("password-reg");
				var surname = document.getElementById("surname");
				var name = document.getElementById("name");
				var message = "";

				if (loginReg.value.trim() == "") {message += "Логин жазылмаған. "}
				if (passwordReg.value.trim() == "") {message += "Құпиясөз жазылмаған. "}
				if (surname.value.trim() == "") {message += "Фамилия жазылмаған. "}
				if (name.value.trim() == "") {message += "Аты жазылмаған. "}

				if (message.length > 0) {
					var error = document.getElementById("error-item");
					error.innerHTML = "<span>" + message + "</span>";
					return false;
				} else {
					return true;
				}
			}
			if (form == "first") {
				var login = document.getElementById("login").value;
				var password = document.getElementById("password").value;
			}
		}
	</script>
</body>
</html>