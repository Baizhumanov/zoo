<?
	require_once '../database.php';
	require_once '../functions.php';

	session_start();
	$hide = false;

	if (isset($_SESSION["login"])) {
		$sname = $_SESSION["surname"];
		$name = $_SESSION["name"];
		$login = $_SESSION["login"];
		$hide = true;
	}

	$products = getProducts($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Товары - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Басты бет</a></div>
		<div class="item">
			<a href="../basket/">Себет</a>
			<a href="../user/?login=<? echo $login; ?>" class="<? if (!$hide) echo "hide"; ?>">Профиль</a>
			<a href="../auth/?service=login" class="login <? if ($hide) echo "hide"; ?>">Кіру</a>
			<a href="../auth/?service=registration" class="reg <? if ($hide) echo "hide"; ?>">Тіркелу</a>
		</div>
	</header>
	<main>
		<div class="title">Тауарлар</div>
		<div class="grid">
			<? for ($i = 0; $i < count($products); $i++): ?>
			<div class="item" id="<? echo $products[$i]["id"]; ?>">
				<div class="image tac">
					<img src="images/<? echo $products[$i]["image"]; ?>" height="160">
				</div>
				<div class="name"><? echo $products[$i]["name"]; ?></div>
				<div class="content">
					<div class="price">Бағасы: <? echo $products[$i]["price"]; ?></div>
					<div class="weight">Салмағы: <? echo $products[$i]["weight"]; ?></div>
					<label>
						<input type="checkbox" id="<? echo "cb-".$products[$i]["id"] ?>" onchange="onChange(<? echo $products[$i]["id"] ?>)" class="checkbox">
						<span class="list">Таңдау</span>
					</label>
				</div>
			</div>
			<? endfor; ?>
		</div>
		<div class="button">
			<a href="../order/" class="btn-main">Тапсырысқа өту</a>
		</div>
	</main>
	<script>
		onLoad();
		function onChange(id) {
			var chechBox = document.getElementById("cb-" + id);
			var card  = document.getElementById(id);
			var name  = card.getElementsByClassName("name")[0].textContent;
			var price = card.getElementsByClassName("price")[0].textContent;
			if (chechBox.checked) {
				card.className = "item selected";
				localStorage.setItem(id, name + "+" + price);
			} else {
				card.className = "item";
				localStorage.removeItem(id);
			}
		}

		function onLoad() {
			var checkboxs = document.getElementsByClassName("checkbox");
			for (var i = 0; i < checkboxs.length; i++) {
				checkboxs[i].checked = false;
			}
			var grid = document.getElementsByClassName("grid")[0];
			var items = grid.childNodes;

			for (var i = 0; i < items.length; i++) {
				for (var j = 0; j < localStorage.length; j++) {
					var key = localStorage.key(j);
					if (items[i].id == key) {
						var chechBox = items[i].getElementsByClassName("checkbox");
						chechBox[0].checked = true;
						items[i].className = "item selected";
					}
				}
			}
		}
	</script>
</body>
</html>