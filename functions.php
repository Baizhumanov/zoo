<?
	function get_main($link, $table_name) {
		$sql = "SELECT * FROM $table_name";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getProducts($link) {
		$sql = "SELECT * FROM products";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getUser($link, $user) {
		$sql = "SELECT `login`, `password`, `surname`, `name`, `type` FROM users WHERE login = '".$user."'";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function insertUser($link, $login, $pass, $sname, $name) {
		$sql = "INSERT INTO users (login, password, surname, name, type) VALUES (\"$login\", \"$pass\", \"$sname\", \"$name\", \"user\")";
		$result = mysqli_query($link, $sql);
	}

	function insertApp($link, $login, $date, $startTime, $endTime, $phone) {
		$arrayUserId = getUserId($link, $login);
		$id = $arrayUserId[0]["id"];

		$sql = "INSERT INTO app (user_id, `date`, start_time, end_time, phone) VALUES (\"$id\", \"$date\", \"$startTime\", \"$endTime\", \"$phone\")";
		$result = mysqli_query($link, $sql);
	}

	function getUserId($link, $login) {
		$sql = "SELECT `id` FROM users WHERE login = '".$login."'";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getUserApps($link, $login) {
		$arrayUserId = getUserId($link, $login);
		$id = $arrayUserId[0]["id"];

		$sql = "SELECT * FROM app WHERE user_id = '".$id."' ORDER BY `date` DESC";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getUserOrders($link, $login) {
		$arrayUserId = getUserId($link, $login);
		$id = $arrayUserId[0]["id"];

		$sql = "SELECT * FROM orders WHERE id_user = '".$id."' ORDER BY `date` DESC";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getOrderParts($link, $id) {
		$sql = "SELECT `parts`.`name`, `ordersparts`.`count` FROM ordersparts INNER JOIN parts ON `ordersparts`.`part_id` = `parts`.`id` WHERE order_id = '".$id."'";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getApps($link) {
		$sql = "SELECT app.id, users.surname, users.name, app.phone, app.start_time, app.end_time, app.`date` FROM app INNER JOIN users ON app.`user_id` = `users`.`id`";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function updateApp($link, $id, $date, $start_time, $end_time, $phone) {
		$sql = "UPDATE app SET `date` = \"$date\", start_time = \"$start_time\", end_time = \"$end_time\", phone = \"$phone\" WHERE id = '".$id."'";
		$result = mysqli_query($link, $sql);
	}

	function deleteApp($link, $id) {
		$sql = "DELETE FROM app WHERE id = '".$id."'";
		$result = mysqli_query($link, $sql);
	}

	function deleteOrder($link, $id) {
		$sql = "DELETE FROM orders WHERE id = '".$id."'";
		$result = mysqli_query($link, $sql);
		$sql = "DELETE FROM ordersproducts WHERE order_id = '".$id."'";
		$result = mysqli_query($link, $sql);
	}

	function insertOrder($link, $login, $phone, $address, $products) {
		$arrayUserId = getUserId($link, $login);
		$id = $arrayUserId[0]["id"];

		$arraySum = getSum($link, $products);
		$sum = $arraySum[0]["SUM(price)"];
		//echo var_dump(getSum($link, $products));

		$date = date("Y-m-d");
		$sql = "INSERT INTO orders (id_user, phone, address, `date`, `sum`, status) VALUES (\"$id\", \"$phone\", \"$address\", \"$date\", \"$sum\", \"Тапсырыс\")";
		$result = mysqli_query($link, $sql);

		$order_id = mysqli_insert_id($link);
		$arr = explode('+', $products); // 13_
		// substr_count($parts, '+');
		for ($i = 0; $i < count($arr) - 1; $i++) { 
			//$indexOfSep = strpos($arr[$i], '_');
			//$product_id = substr($arr[$i], 0, strlen($arr[$i]) - $indexOfSep); 
			//$count = substr($arr[$i], $indexOfSep + 1);
			$product_id = $arr[$i];
			$sql = "INSERT INTO ordersproducts (order_id, product_id) VALUES ($order_id, $product_id)";
			$result = mysqli_query($link, $sql);
		}
	}

	function getSum($link, $products) {
		$arr = explode('+', $products);
		$list = "";
		for ($i = 0; $i < count($arr) - 1; $i++) {
			$product_id = $arr[$i];
			$list .= (string)$product_id;
			if ($i == count($arr) - 1) $list .= ", ";
		}
		$sql = "SELECT SUM(price) FROM products WHERE id IN ($list)"; // (1, 4, 6)
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}

	function getOrderProducts($link, $id) {
		$sql = "SELECT `products`.`name`, `products`.`price`, `products`.`weight` FROM ordersproducts INNER JOIN products ON `ordersproducts`.`product_id` = `products`.`id` WHERE order_id = '".$id."'";
		$result = mysqli_query($link, $sql);
		$main = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $main;
	}
?>