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
		deleteOrder($connection, $id);
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
			$orders = getUserOrders($connection, $login);
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
			<a href="../user/?login=<? echo $login; ?>" >Ваши записи</a>
			<form action="index.php" method="post"><input type="submit" value="Шығу" name="exit"></form>
		</div>
	</header>
	<main>
		<div class="title">Сіздің тапсырыстарыңыз</div>
		<? for ($i = 0; $i < count($orders); $i++): ?>
			<div class="app flex">
				<div class="datas">
					<div class="data">
						<span class="params">Телефон: </span><? echo $orders[$i]["phone"]; ?>
					</div>
					<div class="data">
						<span class="params">Мекен-жай: </span><? echo $orders[$i]["address"]?>
					</div>
					<div class="data">
						<span class="params">Күні: </span><? echo $orders[$i]["date"]; ?>
					</div>
				</div>
				<? $products = getOrderProducts($connection, $orders[$i]["id"]); ?>
				<div class="products">
					<? for ($j = 0; $j < count($products); $j++): ?>
						<div class="item"><? echo $products[$j]["name"]." (".$products[$j]["weight"].") - ".$products[$j]["price"]."тг"; ?></div>
					<? endfor; ?>
				</div>
				<div class="manage flex">
					<form action="index.php?login=<? echo $login; ?>" method="post">
						<input type="hidden" name="id" value="<? echo $orders[$i]["id"]; ?>">
						<input type="submit" value="Отменить" class="delete" name="delete">
					</form>
				</div>
			</div>
		<? endfor; ?>
	</main>
</body>
</html>