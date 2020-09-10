<?
	require_once '../database.php';
	require_once '../functions.php';

	$start = true;
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
			ini_set('session.gc_maxlifetime', 10800);
			session_start();
			$start = false;
			$_SESSION["login"] = $login;
			$_SESSION["surname"] = $surname;
			$_SESSION["name"] = $name;
			$_SESSION["type"] = "user";
		}
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
				ini_set('session.gc_maxlifetime', 10800);
				session_start();
				$start = false;
				$_SESSION["login"] = $login;
				$_SESSION["surname"] = $arrayUsers[0]["surname"];
				$_SESSION["name"] = $arrayUsers[0]["name"];
				$_SESSION["type"] = $arrayUsers[0]["type"];

				if ($arrayUsers[0]["type"] == "admin") {
					$_SESSION["type"] = "admin";
					header('Location: ../admin/');
				}
			} else {
				$message = "Логин немесе құпиясөз дұрыс терілмеген";
			}
		} else {
			$message = "Логин немесе құпиясөз дұрыс терілмеген";
		}
	}

	// Если нажата кнопка  Записаться
	if (isset($_POST["app"])) {
		$login = $_POST["loginHidden"];
		$date = $_POST["date"];
		$times = explode('_', $_POST["time"]);
		$phone = $_POST["phone"];
		insertApp($connection, $login, $date, $times[0], $times[1], $phone);
		$hideModule = true;
		$showMessage = true;
		$message = "Запись успешно отправлена. Дождитесь звонка администратора";
	}

	if ($start) { session_start(); }
	$hideReg = true;
	$hideLog = true;
	$hideUser = true;
	$get = $_GET["action"];

	if (isset($_SESSION["login"])) {
		$sname = $_SESSION["surname"];
		$name = $_SESSION["name"];
		$login = $_SESSION["login"];
		$hideUser = false;
	} else {
		if ($get == "reg") {$hideReg = false;} else {$hideLog = false;}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Запись - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Zoo Clinica</a></div>
	</header>
	<main class="flex">
		<div class="module <? if ($hideModule) echo "hide"; ?>">
			<form action="index.php" method="post" class="log <? if ($hideLog) echo "hide"; ?>">
				<div class="row title">Войдите в систему для записи</div>
				<div class="row">
					<label for="login">Логин</label>
					<input type="text" id="login" name="login">
				</div>
				<div class="row">
					<label for="password">Құпиясөз</label>
					<input type="password" id="password" name="password">
				</div>
				<div class="row">
					<input type="submit" value="Кіру">
				</div>
				<div class="row">
					Вас нет в системе? <a href="index.php?action=reg">Тіркеліңіз</a>
				</div>
			</form>
			<form action="index.php" method="post" class="reg <? if ($hideReg) echo "hide"; ?>">
				<div class="row title">Зарегайтесь в системе для записи</div>
				<div class="row">
					<label for="login-reg">Логин</label>
					<input type="text" id="login-reg" name="login-reg" required>
				</div>
				<div class="row">
					<label for="password-reg">Құпиясөз</label>
					<input type="password" id="password-reg" name="password-reg" required>
				</div>
				<div class="row">
					<label for="surname">Фамилия</label>
					<input type="text" id="surname" name="surname" required>
				</div>
				<div class="row">
					<label for="name">Аты</label>
					<input type="text" id="name" name="name" required>
				</div>
				<div class="row">
					<input type="submit" value="Тіркелу">
				</div>
				<div class="row">
					Вы уже зареганы? <a href="index.php?action=reg">Войдите</a>
				</div>
			</form>
			<div class="user <? if ($hideUser) echo "hide"; ?>">
				<div class="row">
					<div class="title">Ваши данные</div>
				</div>
				<div class="row">
					<span class="param">Фамилия: </span> <? echo $sname; ?>
				</div>
				<div class="row">
					<span class="param">Аты: </span> <? echo $name; ?>
				</div>
				<hr>
				<div class="last-row">Запись через другой аккаунт? <br><a href="">Аккаунт ауыстыру</a></div>
			</div>
			<div class="error <? if (strlen($message) == 0) echo "hide"; ?> "><? echo $message; ?></div>
		</div>
		<div class="module <? if ($hideModule) echo "hide"; ?>">
			<form action="index.php" method="post" class="data">
				<input type="hidden" name="loginHidden" value="<? echo $login; ?>">
				<div class="row">
					<div class="title">Запись</div>
				</div>
				<div class="row flex">
					<div class="input">
						<label for="date">Дата</label>
						<input type="date" name="date" id="date" value="2020-05-01" min="2020-01-01" max="2024-12-31">
					</div>
					<div class="input">
						<label for="phone">Телефон</label>
						<input type="text" id="phone" name="phone" required>
					</div>
				</div>
				<div class="row">
					<div class="grid">
						<div class="item">
							<label>
								<input type="radio" name="time" value="09:00:00_09:30:00" checked>
								<span class="list">09:00-09:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="09:30:00_10:00:00">
								<span class="list">09:30-10:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="10:00:00_10:30:00">
								<span class="list">10:00-10:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="10:30:00_11:00:00">
								<span class="list">10:30-11:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="11:00:00_11:30:00">
								<span class="list">11:00-11:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="11:30:00_12:00:00">
								<span class="list">11:30-12:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="12:00:00_12:30:00">
								<span class="list">12:00-12:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="12:30:00_13:00:00">
								<span class="list">12:30-13:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="14:00:00_14:30:00">
								<span class="list">14:00-14:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="14:30:00_15:00:00">
								<span class="list">14:30-15:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="15:00:00_15:30:00">
								<span class="list">15:00-15:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="15:30:00_16:00:00">
								<span class="list">15:30-16:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="16:00:00_16:30:00">
								<span class="list">16:00-16:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="16:30:00_17:00:00">
								<span class="list">16:30-17:00</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="17:00:00_17:30:00">
								<span class="list">17:00-17:30</span>
							</label>
						</div>
						<div class="item">
							<label>
								<input type="radio" name="time" value="17:30:00_18:00:00">
								<span class="list">17:30-18:00</span>
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<input type="submit" value="Оставить заявку" name="app">
				</div>
			</form>
		</div>
		<div class="message <? if (!$showMessage) echo "hide"; ?>"><? echo $message; ?></div>
	</main>
</body>
</html>