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

	$hasOrder = true;
	$message = "test";

	if ( isset($_POST["surname"]) || isset($_POST["name"]) ) {
		$sname = htmlspecialchars($_POST["surname"]); // Ненужные данные
		$name = htmlspecialchars($_POST["name"]); // Ненужные данные
		$phone = htmlspecialchars($_POST["phone"]);
		$address = htmlspecialchars($_POST["address"]);
		$hide = $_POST["products"];
		if (strlen($hide) > 1) {
			// insertOrderAI($link, $login, $phone, $address, $sum, $antis)
			insertOrder($connection, $login, $phone, $address, $hide);
			$hasOrder = true;
			$hideAll = true; // чтобы скрыть все и вывести сообщение
			$message = "Тапсырыс жіберілді";
		} else {
			$message = "Ешқандай тауар таңдалмаған";
			$hasOrder = false;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Тапсырыс - Zoo</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<header>
		<div class="item"><a href="../" class="main-link">Басты бет</a></div>
		<div class="item">
			<a href="../user/?login=<? echo $login; ?>" class="<? if (!$hide) echo "hide"; ?>">Профиль</a>
			<a href="../auth/?service=login" class="login <? if ($hide) echo "hide"; ?>">Кіру</a>
			<a href="../auth/?service=registration" class="reg <? if ($hide) echo "hide"; ?>">Тіркелу</a>
		</div>
	</header>
	<main>
		<div class="links flex <? if ($hide || !$hasOrder || $hideAll) echo "hide"; ?>">
			<div class="title">Тапсырыс беру үшін тіркеліңіз</div>
			<a href="../auth/?service=registration" class="first link">Тіркелу</a>
			<a href="../auth/?service=login" class="second link">Кіру</a>
		</div>
		<div class="order flex <? if (!$hide || !$hasOrder || $hideAll) echo "hide"; ?>">
			<form action="index.php" method="post" class="reg <? if ($hideReg) echo "hide"; ?>">
				<div class="row title">Тапсырыс беру үшін форма</div>
				<div class="row">
					<label for="login-reg">Фамилия</label>
					<input type="text" id="login-reg" name="surname" required>
				</div>
				<div class="row">
					<label for="password-reg">Аты</label>
					<input type="password" id="password-reg" name="name" required>
				</div>
				<div class="row">
					<label for="surname">Мекен-жай</label>
					<input type="text" id="surname" name="address" required>
				</div>
				<div class="row">
					<label for="name">Телефон</label>
					<input type="text" id="name" name="phone" required>
				</div>
				<div class="row">
					<input type="hidden" name="products" value="" id="products">
					<input type="submit" value="Тапсыру">
				</div>
			</form>
		</div>
		<div class="title <? if (!$hasOrder || $hideAll) echo "hide"; ?>">Сіздің таңдалған тауарларыңыз</div>
		<div class="title <? if ($hasOrder || $hideAll) echo "hide"; ?>">
			<? if ($hasOrder) echo $message; ?>
		</div>
		<div class="title <? if (!$hideAll) echo "hide"; ?>" id="title">
			<? echo $message; ?>
		</div>
		<div class="grid  <? if (!$hasOrder || $hideAll) echo "hide"; ?>" id="grid"></div>
	</main>
	<script>
		if (document.getElementById("title").className.trim() == "title") {	
			localStorage.clear(); 
		}

		var grid = document.getElementById("grid");
		// var formBuy = document.getElementById("buy");
		// var sumHidden = document.getElementById("sum");

		for (var i = 0; i < localStorage.length; i++) {
			var key = localStorage.key(i);
			var value = localStorage.getItem(key);

			var first = value.indexOf("+");

			var priceText = value.slice(first + 1);
			var nameText = value.slice(0, first);

			var item = createEl("item selected");
			item.id = key;

			var nameItem = createEl("name", nameText);
			var content = createEl("content");

			var price = createEl("price", priceText);
			var label = createLabel(key);

			content.appendChild(price);
			content.appendChild(label);

			item.appendChild(nameItem);
			item.appendChild(content);

			grid.appendChild(item);
		}

		checkProducts();

		function createLabel(id) {
			var label = document.createElement("label");
			var input = document.createElement("input");
			var span  = document.createElement("span");
			input.type = "checkbox";
			input.checked = true;
			input.id = "cb-" + id;
			input.className = "checkbox";
			span.className  = "list";
			var node = document.createTextNode("Таңдау");
			span.appendChild(node);
			input.addEventListener("change", function() {onChange(id);});
			label.appendChild(input);
			label.appendChild(span);
			return label;
		}

		function createEl(className, text) {
			if (text == undefined) text = "";
			var div = document.createElement("div");
			div.className = className;
			var node = document.createTextNode(text);
			div.appendChild(node);
			return div;
		}

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
			checkProducts();
		}

		function validate() {
			var surname = document.getElementById("surname");
			var name = document.getElementById("name");
			var phone = document.getElementById("phone");
			var address = document.getElementById("address");
			var message = "";

			if (surname.value.trim() == "") {message += "Фамилия жазылмаған. "}
			if (name.value.trim() == "") {message += "Аты жазылмаған. "}
			if (phone.value.trim() == "") {message += "Телефон нөмірі жазылмаған. "}
			if (address.value.trim() == "") {message += "Адрес жазылмаған. "}

			if (message.length > 0) {
				var error = document.getElementById("error-item");
				error.innerHTML = "<span>" + message + "</span>";
				return false;
			} else {
				return true;
			}
		}

		function checkProducts() {
			var text = "";
			var len = 0;
			for (var i = 0; i < localStorage.length; i++) {
				var key = localStorage.key(i);
				// if (grid == "grid-2") {
				// 	if (key.slice(0, 3) != "ai-") { continue; }
				// } else if (grid == "grid-3") {
				// 	if (key.slice(0, 3) != "oi-") { continue; }
				// } else {
				// 	if (key.slice(0, 3) != "el-") { continue; }
				// }

				var value = localStorage.getItem(key);
				var id = key;
				text += id + "+";
				len++;
			}
			if (len == 0) text = " ";
			document.getElementById("products").value = text;
		}

	</script>
</body>
</html>