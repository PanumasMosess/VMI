<?
$curr_datetime = date('Y-m-d H:i:s');
$curr_datetime = date('Y-m-d H:i:s', strtotime($curr_datetime));

//full day
//$fullDayStart = date('Y-m-d H:i:s', strtotime("2022-01-30 08:00:00"));
//$fullDayStop = date('Y-m-d H:i:s', strtotime("2022-01-31 07:59:59"));

//shift
$DayStart = date('Y-m-d H:i:s', strtotime("2022-01-30 08:00:00"));
$DayStop = date('Y-m-d H:i:s', strtotime("2022-01-30 19:59:59"));
   
if(($curr_datetime >= $DayStart) && ($curr_datetime <= $DayStop))
{
	echo "DAY";
}
else
{
	echo "NIGHT";
}
?>