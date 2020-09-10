<?
	session_start();
	if (isset($_SESSION["login"])) {
		$sname = $_SESSION["surname"];
		$name = $_SESSION["name"];
		$login = $_SESSION["login"];
		$hide = true;
	}
	// echo "<pre>";
	// echo $_SESSION;
	// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Клиника Zoo</title>
	<link rel="stylesheet" href="style.css">
	<!-- <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet"> -->
</head>
<body>
	<header>
		<div class="head flex">
			<div class="name">Zoo Clinica</div>
			<div class="items flex">
				<div class="item"><a href="appointment/?action=log">Запись</a></div>
				<div class="item"><a href="products/">Товары</a></div>
				<div class="item"><a href="">Услуги</a></div>
				<div class="item <? if ($hide) echo "hide"; ?>">
					<a href="auth/?service=login">Кіру</a>
				</div>
				<div class="item button <? if ($hide) echo "hide"; ?>">
					<a href="auth/?service=registration" class="btn-main">Тіркелу</a>
				</div>
				<?if ($hide) { for ($i=0; $i < 1; $i++): ?>
					<div class="item">
						<a href="user/?login=<? echo $login; ?>">
						<? echo $sname." ".$name; ?>
						</a>
					</div>
				<? endfor; } ?>
			</div>
		</div>
	</header>
	<main>
		<section class="main flex">
			<div class="item flex">
				<div class="title">Запишитесь на прием прямо сейчас!</div>
				<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, aut veritatis nesciunt officia sed ut doloribus! Quasi nemo quos ducimus, earum facilis suscipit repellat vitae ipsum accusamus, recusandae enim amet.</div>
				<div class="links flex">
					<a href="appointment/?action=log" class="first btn-main">Записаться</a>
					<a href="" class="second btn-main">Что-то другое</a>
				</div>
			</div>
			<div class="item">
				<img src="images/bgg1.jpg" alt="" height="400">
			</div>
		</section>
		<section class="info">
			<div class="item title">Как это работает?</div>

			<div class="cards flex">
				<div class="card">
					<div class="icon"></div>
					<div class="title">Жүйеге тіркеліңіз немесе кіріңіз</div>
					<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
				</div>
				<div class="card">
					<div class="icon"></div>
					<div class="title">Қолайлы уақытты таңдаңыз</div>
					<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
				</div>
				<div class="card">
					<div class="icon"></div>
					<div class="title">Подтвердите ваш выбор</div>
					<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
				</div>
				<div class="card">
					<div class="icon"></div>
					<div class="title">Дождитесь администратора</div>
					<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
				</div>
			</div>
		</section>
		<section class="services">
			<div class="title">Наши услуги</div>
			<div class="list">
				<div class="item flex">
					<div class="number flex">1</div>
					<div class="text"><b>Терапия</b>. Lorem ipsum dolor sit amet, consectetur adipisicing andae.</div>
				</div>
				<div class="item flex">
					<div class="number flex">2</div>
					<div class="text"><b>Хирургия</b>. Lorem ipsum dolor sit amet, consectetur adipisicing 				</div>
				</div>
				<div class="item flex">
					<div class="number flex">3</div>
					<div class="text"><b>Травматология</b>. Lorem ipsum dolor sit amet, consectetur amus, voluptatum unde!</div>
				</div>
				<div class="item flex">
					<div class="number flex">4</div>
					<div class="text"><b>Офтальмология</b>. Lorem ipsum dolor sit amet, consectetur  nihil.</div>
				</div>
				<div class="item flex">
					<div class="number flex">5</div>
					<div class="text"><b>Вакцинация</b>. Lorem ipsum dolor sit amet, consectetur adipisicing </div>
				</div>
				<div class="item flex">
					<div class="number flex">6</div>
					<div class="text"><b>Рентгенологиялық диагностика</b>. Lorem ipsum dolor sit amet, e vel provident.</div>
				</div>
				<div class="button-item">
					<a href="" class="btn-main">Все услуги</a>
				</div>
			</div>
		</section>
		<section class="products flex">
			<div class="item">
				<img src="images/bgg3.jpg" alt="" height="340">
			</div>
			<div class="item flex">
				<div class="title">Наши товары</div>
				<div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci ullam vero, exercitationem commodi doloribus pariatur iure natus hic! Error veritatis voluptates temporibus vitae hic consequuntur unde alias nisi sunt impedit!</div>
				<div class="link">
					<a href="products/" class="btn-main">Все товары</a>
				</div>
			</div>
		</section>
		<section class="auth flex">
			<div class="title">Авторизация</div>
			<form action="">
				<div class="grid">
					<div class="row">
						<div class="item"><label for="login">Логин</label></div>
						<div class="item"><input type="text" id="login"></div>
					</div>
					<div class="row">
						<div class="item"><label for="password">Құпиясөз</label></div>
						<div class="item"><input type="password" id="password"></div>
					</div>
					<div class="row">
						<input type="submit" value="Кіру">
					</div>
					<div class="row no-margin">
						<div class="text">Если вас нет в системе, то вы можете <a href="auth/?service=registration">зарегаться</a></div>
					</div>
				</div>
			</form>
		</section>
	</main>
	<footer></footer>
</body>
</html>