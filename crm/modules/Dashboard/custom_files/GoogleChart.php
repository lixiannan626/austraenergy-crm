<?php
/*This file contains interfaces which work with Google Chart APIs
 *
 *Commenced on Sep. 1st 2011.
 *
 *
 *
 *
 *
 *
 */
 

function drawColumnChart($metadata, $data, $div, $width, $height, $title, $options)
{
	//initialise $width and $height
	if($width == 0)
		$width = 450;
	if($height == 0)
		$height = 300;
	if($options == "")
		$options = "is3D: false";
	echo '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
	';
	//Meta Data
	foreach($metadata as $key=>$value)
	{
		echo 'data.addColumn("'.$value.'","'.$key.'");
		';
	}
	echo 'data.addRows('.count($data).');
	';
	//Real Data
	for($i = 0; $i < count($data); $i++)
	{
		$row = $data[$i];
		echo "data.setValue($i, 0, '$row[0]');
			";
		for($j = 1; $j < count($row); $j++)
		{
			echo "data.setValue($i, $j, $row[$j]);
			";
		}
	}
	echo 'var chart = new google.visualization.ColumnChart(document.getElementById("'.$div.'"));
	';
	echo 'chart.draw(data, {title: "'.$title.'", width: '.$width.', height: '.$height.','.$options.'});
	';
	echo '
	}
	</script>';
	echo '<div class="pie_chart" id="'.$div.'"></div>';
}

///
///$data is an associative array
///
function drawPieChartA($data, $div_container, $width, $height, $title, $options)
{
	//initialise $width and $height
	if($width == 0)
		$width = 400;
	if($height == 0)
		$height = 300;
	if($options == "")
		$options = "is3D: false";
	
	echo '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
		data.addColumn("string","String");
		data.addColumn("number","Number");
		data.addRows([
		';
		end($data);
		$last_key = key($data);
		foreach($data as $key=>$value)
		{
			if($last_key != $key)
				echo '["'.$key.'",'.$value.'],
				';
			else
				echo '["'.$key.'",'.$value.']
				';
		}
	echo '
		]);
		';
	echo 'var chart = new google.visualization.PieChart(document.getElementById("'.$div_container.'"));
	';
	echo 'chart.draw(data, {title: "'.$title.'", width: '.$width.', height: '.$height.','.$options.'});
	';
	echo '
	}
	</script>';
	echo '<div class="pie_chart" id="'.$div_container.'"></div>';
}
///
///$data is a two dimensional array
///
function drawPieChart($data,$div_container, $width, $height, $title, $options)
{
	//initialise $width and $height
	if($width == 0)
		$width = 400;
	if($height == 0)
		$height = 300;
	if($options == "")
		$options = "is3D: false";
	
	echo '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
		data.addColumn("string","String");
		data.addColumn("number","Number");
		data.addRows('.count($data).');
		';
		for($i = 0; $i < count($data); $i++)
		{
			$temp = $data[$i];
			echo 'data.setValue('.$i.', 0, "'.$temp[0].'");
			';
			echo 'data.setValue('.$i.', 1, '.$temp[1].');
			';
		}
	echo 'var chart = new google.visualization.PieChart(document.getElementById("'.$div_container.'"));
	';
	echo 'chart.draw(data, {title: "'.$title.'",width: '.$width.', height: '.$height.','.$option.'});
	';
	echo '
	}
	</script>';
	echo '<div class="pie_chart" id="'.$div_container.'"></div>';
	
		
}


function OneToTwo($array)
{
	$result = array();
	foreach($array as $key=>$value)
	{
		$temp = array($key, $value);
		$result[count($result)] = $temp;
	}
	return $result;
}

?>