<?php
/*
	Задача 1:
	користувач задає масив даних: дата та курс долара (до 20 точок).
	Дати можуть бути непослідовними та різними.
	Програма має відповісти на питання:
	який курс долара був чи буде на потрібну дату (також вводиться користувачем та може не співпадати з введеними попередньо),
	та вивести весь графік для наочності.
*/
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	$kursMax  = 20;
	$kursList = array();
	for ($key=0;$key<$kursMax;$key++) {
		$kursList[$key] = array(
			'date' => '',
			'kurs' => '',
		);
	}
	
	$currentDate = !empty($_POST['currentDate']) ? $_POST['currentDate'] : '';
	$action      = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';

	if ($action==='fill') {
		/* заполнить данные случайно */
		foreach ($kursList as $key => &$kursValue) {
			$kursValue['date'] = date('Y-m-d',time()-rand(1,100)*86400);
			$kursValue['kurs'] = rand(30,42);
		}
	}

	$result    = '';
	$graphList = [];

	if ($action==='send') {
		/* заполнить данные тем, что указал пользователь */
		$newDates = !empty($_POST['date']) ? $_POST['date'] : [];
		$newKurs = !empty($_POST['kurs']) ? $_POST['kurs'] : [];
		
		if ($newDates && is_array($newDates)) {
			foreach ($kursList as $key => &$kursValue) {
				$kursValue['date'] = !empty($newDates[$key]) ? $newDates[$key] : '';
				$kursValue['kurs'] = !empty($newKurs[$key]) ? (float)$newKurs[$key] : '';
				if (!$kursValue['kurs']) {
					$kursValue['kurs'] = '';
				}
			}
		}
		
		/* И собственно само вычисление курса валют на заданную дату */
		if ($currentDate) {
			$maxDate = '';
			$maxKurs = '';
			foreach ($kursList as $key => $kursValue) {
				if ($kursValue['kurs']>0)
				{
					if ($kursValue['date']>'' && $kursValue['date'] <= $currentDate) {
						if (!$maxDate || $maxDate <= $kursValue['date']) {
							$maxDate = $kursValue['date'];
							$maxKurs = $kursValue['kurs'];
						}
					}
					$graphList[$kursValue['date']] = $kursValue['kurs'];
				}
			}
			if ($maxDate) {
				$result = 'На '.$currentDate.' курс дорiвнював '.$maxKurs;
				/* сортируем для графика по дате */
				ksort($graphList);
			}
			else {
				$result = 'Відомостей про курс у цей день немає';
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Задача 1</title>
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="/include/datepicker/datepicker.css">
</head>
<body>
	<div class="container">
		<h1>Задача 1: користувач задає масив даних: дата та курс долара (до 20 точок). Дати можуть бути непослідовними та різними. Програма має відповісти на питання: який курс долара був чи буде на потрібну дату (також вводиться користувачем та може не співпадати з введеними попередньо), та вивести весь графік для наочності.</h1>
		<form class="forma" method="post" action="/task-1.php">
			<input type="hidden" name="action" value="send">

			<div class="forma__current date-current">
				<label class="lb lb--row" for="currentDate">Дата запроса:</label>
				<input class="input date-select" type="text" name="currentDate" id="currentDate" value="<?php echo htmlspecialchars($currentDate); ?>">
			</div>

			<div class="forma__dates dates">
<?php
	foreach ($kursList as $key => $value):
?>
				<div class="dates__item">
					<label class="dates__label lb lb--row" for="date-<?php echo $key ?>">Дата #<?php echo $key+1; ?></label>
					<input class="dates__input input date-select" type="text" name="date[<?php echo $key ?>]" id="date-<?php echo $key ?>" value="<?php echo htmlspecialchars($value['date']); ?>">
					<input class="dates__input input" type="text" name="kurs[<?php echo $key ?>]" id="date-<?php echo $key ?>" value="<?php echo htmlspecialchars($value['kurs']); ?>">
				</div>
<?php
	endforeach;
?>
			</div>
			
			<div class="forma__buttons">
				<button class="btn">Відправити</button>
				<a class="btn" href="/task-1.php?action=fill">Заповнити випадково</a>
			</div>
		</form>
		
<?php
	if ($result) {
		echo '<div class="forma__results">'.$result.'</div>';
	}
?>

<?php
	if ($graphList):
?>
		<script src="https://www.gstatic.com/charts/loader.js"></script>
		<script>
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		function drawChart() {
			const data = google.visualization.arrayToDataTable([
				['date', 'kurs'],
<?php
			foreach ($graphList as $graphDate => $graphKurs) {
				echo '["'.substr($graphDate,5).'", '.$graphKurs.'],';
			}
?>
			]);
			const options = {
				title: 'Графік курсу валют за вказаними точками',
				hAxis: {title: 'Введені дати'},
				vAxis: {title: 'Курси'},
				legend: 'none'
			};
			const chart = new google.visualization.ScatterChart(document.getElementById('chart'));
			chart.draw(data, options);
		}
		</script>
		<div id="chart" style="width: 100%; height: 500px;"></div>
	</div>
<?php
	endif;
?>
	<script src="/include/jquery.min.js"></script>
	<script src="/include/datepicker/datepicker.js"></script>
	<script src="/include/main.js"></script>
</body>
</html>