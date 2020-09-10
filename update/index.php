<?
	require_once '../database.php';
	require_once '../functions.php';

	session_start();

	// Если бы нажата кнопка изменить
	if (isset($_POST["set"])) {
		$id = $_POST["idHidden"];
		$login = $_POST["loginHidden"];
		$date = $_POST["date"];
		$times = explode('_', $_POST["time"]);
		$phone = $_POST["phone"];
		updateApp($connection, $id, $date, $times[0], $times[1], $phone);
		// Переадресация если админ
		if ($_SESSION["type"] == "admin") {
			header('Location: ../admin/');
		} else {
			header('Location: ../user/?login='.$_SESSION["login"]);
		}
		die();
	}

	// Проверка на авторизаицю
	if (isset($_SESSION["login"])) {
		$sname = $_SESSION["surname"];
		$name = $_SESSION["name"];
		$login = $_SESSION["login"];
	} else { header('Location: ../'); }

	// Если нажата кнопка изменить в странице user.php
	if (isset($_POST["open"])) {
		$times = $_POST["time"];
		$date = $_POST["date"];
		$phone = $_POST["phone"];
		$id = $_POST["id"];
	} else { header('Location: ../'); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Изменение записи - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Zoo Clinica</a></div>
	</header>
	<main class="flex">
		<div class="module">
			<div class="user">
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
		</div>
		<div class="module">
			<form action="index.php" method="post" class="data">
				<input type="hidden" name="idHidden" value="<? echo $id; ?>">
				<input type="hidden" name="loginHidden" value="<? echo $login; ?>">
				<div class="row">
					<div class="title">Изменение записи</div>
				</div>
				<div class="row flex">
					<div class="input">
						<label for="date">Дата</label>
						<input type="date" name="date" id="date" value="<? echo $date; ?>" min="2020-01-01" max="2024-12-31">
					</div>
					<div class="input">
						<label for="phone">Телефон</label>
						<input type="text" id="phone" name="phone" value="<? echo $phone; ?>" required>
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
					<input type="submit" value="Изменить заявку" name="set">
				</div>
			</form>
		</div>
	</main>
</body>
</html>