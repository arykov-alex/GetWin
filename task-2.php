<?php
/*
	Задача 2:
	користувач вводить дві фрази в два окремих поля.
	Система повинна у відсотках визначити на скільки вони схожі за змістом.
	Саме за змістом, так як слова кожної з фраз можуть бути різними.
*/
	error_reporting(E_ALL);
	ini_set('display_errors',1);
	
	$string1 = !empty($_POST['string1']) ? $_POST['string1'] : '';
	$string2 = !empty($_POST['string2']) ? $_POST['string2'] : '';
	
	$action  = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';

	$result  = '';

	if ( $action==='compare' ) {
		/* заполнить данные тем, что указал пользователь */
		if ( $string1>'' && $string2>'' ) {
			if ( $string1 === $string2 ) {
				$result = 'Ці два рядки ідентичні';
			}
			else {
				similar_text($string1,$string2,$percent);
				$result = 'Схожість цих рядків = '.sprintf('%0.2f',$percent).'%';
				
				$speak1 = metaphone($string1,20);
				$speak2 = metaphone($string2,20);
				if ( $speak1 === $speak2 && $speak1>'' ) {
					$result .= ', ці рядки вимовляються однаково';
				}
			}
		}
		else {
			$result = 'Ви не вказали тексти для порівняння';
		}
	}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Задача 2</title>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<div class="container">
		<h1>Задача 2: користувач вводить дві фрази в два окремих поля. Система повинна у відсотках визначити на скільки вони схожі за змістом. Саме за змістом, так як слова кожної з фраз можуть бути різними.</h1>
		<form class="forma" method="post" action="/task-2.php">
			<input type="hidden" name="action" value="compare">

			<div class="forma__current">
				<label class="lb lb--row" for="string-1">Строка 1:</label>
				<input class="input input--long" type="text" name="string1" id="string-1" value="<?php echo htmlspecialchars($string1); ?>">
			</div>

			<div class="forma__current">
				<label class="lb lb--row" for="string-2">Строка 2:</label>
				<input class="input input--long" type="text" name="string2" id="string-2" value="<?php echo htmlspecialchars($string2); ?>">
			</div>

			<div class="forma__buttons">
				<button class="btn">Відправити</button>
			</div>
		</form>
<?php
	if ($result) {
		echo '<div class="forma__results">'.$result.'</div>';
	}
?>
</body>
</html>