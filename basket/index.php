<?
	session_start();
	$hide = false;

	if (isset($_SESSION["login"])) {
		$sname = $_SESSION["surname"];
		$name = $_SESSION["name"];
		$login = $_SESSION["login"];
		$hide = true;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Себет - Zoo</title>
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
		<div class="grid" id="grid"></div>
		<div class="button">
			<a href="../order/" class="btn-main">Тапсырысқа өту</a>
		</div>
	</main>
	<script>
		// if (document.getElementById("title").textContent.trim() == "Тапсырыс жіберілді") {	localStorage.clear(); }

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

	</script>
</body>
</html>