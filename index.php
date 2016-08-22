<?php 
echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<body>";

$start = '22:00';
$end = '08:00';
getTime($start, $end);

function getTime($start, $end) {	
	echo 'Starting time: ' . $start . '<br/>';
	echo 'Finishing time: ' . $end . '<br/>';
	
	$dayTimeStart = '06:00';
	$nightTimeStart = '22:00';
	$dayStart = 6;
	$nightStart = 22;
	
	$startHours = explode(':', $start);
	$startHour = $startHours[0];
	$endHours = explode(':', $end);
	$endHour = $endHours[0];
	
	if($startHour > $dayStart && $startHour < $nightStart && $endHour > $dayStart && $endHour <= $nightStart) {
		calculate_when_both_days($start, $end);
	} else if ($startHour >= $dayStart && $startHour <= $nightStart && $endHour <= $dayStart) {
		calculate_start_day_end_night($start, $nightTimeStart, $startHour, $end);
	} else if($startHour >= $dayStart && $startHour <= $nightStart && $endHour >= $nightStart && $endHour <= 24) {
		calculate_start_day_end_night_special($start, $nightTimeStart, $end);
	} else if($startHour <= $dayStart && $endHour <= $dayStart) {
		calculate_start_night_end_night($start, $end);
	} else if($startHour < $dayStart && $endHour > $dayStart) {
		calculate_start_night_end_day($dayTimeStart, $end, $start, $dayTimeStart);
	} else if($startHour >= $nightStart && $endHour >= $dayStart) {
		calculate_start_night_end_day_special($dayTimeStart, $end, $startHour);
	}
}

function calculate_when_both_days($start, $end) {
	$result = calculate_interval($start, $end);
	
	if($result < 0) {
		$result = 24 + $result;
	}
	
	echo 'day hours ' . $result . ' hours<br/>';
}

function calculate_start_day_end_night($start, $nightTimeStart, $startHour, $end) {
	$result = calculate_interval($start, $nightTimeStart);
		
	if ($result > 0) {
		echo 'day hours ' . $result . ' hours<br/>';
	}
	
	$toMidnight = calculate_to_midnight($startHour);
	$result = calculate_interval('00:00', $end) + $toMidnight;
	
	if ($result > 0) {
		echo 'night hours ' . $result . ' hours<br/>';
	}
}

function calculate_start_day_end_night_special($start, $nightTimeStart, $end) {
	$result = calculate_interval($start, $nightTimeStart);
	echo 'day hours ' . $result . ' hours<br/>';
	$result = calculate_interval($nightTimeStart, $end);
	echo 'night hours ' . $result . ' hours<br/>';
}

function calculate_start_night_end_night($start, $end) {
	$result = calculate_interval($start, $end);
	echo 'night hours ' . $result . ' hours<br/>';
}

function calculate_start_night_end_day($dayTimeStart, $end, $start, $dayTimeStart) {
	$result = calculate_interval($dayTimeStart, $end);
	echo 'day hours ' . $result . ' hours<br/>';
	$result = calculate_interval($start, $dayTimeStart);
	echo 'night hours ' . $result . ' hours<br/>';
}

function calculate_interval($startTime, $endTime) {
	$from_time = strtotime($startTime);
	$to_time = strtotime($endTime);
	
	$result = round($to_time - $from_time) / 3600;
	return $result;
}

function calculate_start_night_end_day_special($dayTimeStart, $end, $startHour) {
	$result = calculate_interval($dayTimeStart, $end);
	echo 'day hours ' . $result . ' hours<br/>';
	$toMidnight = calculate_to_midnight($startHour);
	$result = calculate_interval('00:00', $dayTimeStart) + $toMidnight;
	echo 'night hours ' . $result . ' hours<br/>';
}

function calculate_to_midnight($startHour) {
	$toMidnight = 0;
	
	switch($startHour) {
		case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15:
		case 16: case 17: case 18: case 19: case 20: case 21: case 22:
			$toMidnight = 2;
			break;
		case 23:
			$toMidnight = 1;
			break;
	}
		
	return $toMidnight;
}

echo "</body>";
echo "</html>";
?>
