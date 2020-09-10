<?
	require_once '../database.php';
	require_once '../functions.php';
	session_start();

	// Если нажат кнопка выйти
	if (isset($_POST["exit"])) {
		session_destroy();
		header('Location: ../');
	}

	// Если была нажата кнопка удалить
	if (isset($_POST["delete"])) {
		$id = $_POST["id"];
		deleteApp($connection, $id);
	}

	// Переадресация если админ
	if ($_SESSION["type"] == "admin") {
		header('Location: ../admin/');
		die();
	}

	// проверка на авторизацию
	if (isset($_SESSION["login"])) {
		if ($_SESSION["login"] == $_GET["login"]) {
			$sname = $_SESSION["surname"];
			$name = $_SESSION["name"];
			$login = $_SESSION["login"];
			$apps = getUserApps($connection, $login);
		} else { header('Location: ../'); }
	} else { header('Location: ../'); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Профиль - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Басты бет</a></div>
		<div class="item">
			<a href="../user-orders/?login=<? echo $login; ?>" >Тапсырыстар</a>
			<form action="index.php" method="post"><input type="submit" value="Шығу" name="exit"></form>
		</div>
	</header>
	<main>
		<div class="title">Ваши записи</div>
		<? for ($i = 0; $i < count($apps); $i++): ?>
			<div class="app flex">
				<div class="datas">
					<div class="data">
						<span class="params">Дата: </span><? echo $apps[$i]["date"]; ?>
					</div>
					<div class="data">
						<span class="params">Уақыт: </span><? echo $apps[$i]["start_time"]." - ".$apps[$i]["end_time"]; ?>
					</div>
					<div class="data">
						<span class="params">Телефон: </span><? echo $apps[$i]["phone"]; ?>
					</div>
				</div>
				<div class="manage flex">
					<form action="../update/index.php" method="post">
						<input type="hidden" name="id" value="<? echo $apps[$i]["id"]; ?>">
						<input type="hidden" name="time" value="<? echo $apps[$i]["start_time"]."_".$apps[$i]["end_time"]; ?>">
						<input type="hidden" name="date" value="<? echo $apps[$i]["date"]; ?>">
						<input type="hidden" name="phone" value="<? echo $apps[$i]["phone"]; ?>">
						<input type="submit" value="Өзгерту" class="update" name="open">
					</form>
					<form action="index.php?login=<? echo $login; ?>" method="post">
						<input type="hidden" name="id" value="<? echo $apps[$i]["id"]; ?>">
						<input type="submit" value="Отменить" class="delete" name="delete">
					</form>
				</div>
			</div>
		<? endfor; ?>
	</main>
</body>
</html>