<?php
	define('DB_SERVER', 'PUT_YOURDOMAIN_HERE.COM:3306');
   define('DB_USERNAME', 'PUT_DB_USERNAME_HERE');
   define('DB_PASSWORD', 'PUT_DB_PASSWORD_HERE');
   define('DB_DATABASE', 'PUT_DATABASE_NAME_HERE');
   define('WEB_LINK','PUT_YOURDOMAIN_HERE.COM/PATH_TO_PHP_FILE/viewchart.php?hour=');
   $hour = $_GET['hour'];
	if ($hour == null){
		$hour = 0.5;
	}
	$tominute = $hour*60;
   $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   $sql = sprintf("SELECT 
(SELECT g.DATE_TIME) AS RECORD_TIME, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU0_TEMP, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU1_TEMP, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU2_TEMP, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU3_TEMP, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU4_TEMP, 
IFNULL((SELECT gpulog.GPU_TEMP FROM gpulog WHERE gpulog.GPU_NAME='%s' AND gpulog.DATE_TIME = g.DATE_TIME LIMIT 1),0) AS GPU5_TEMP  
FROM gpulog g 
WHERE g.DATE_TIME > DATE_SUB(NOW(), INTERVAL %d MINUTE)
GROUP BY RECORD_TIME 
ORDER BY RECORD_TIME DESC",
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU0"),
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU1"), 
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU2"),
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU3"),
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU4"),
 mysql_real_escape_string("GeForce GTX 1080 Ti GPU5"),
 $tominute);
$result = mysqli_query($conn,$sql);

if($result === FALSE) {
    echo mysqli_errno($result) .": ". mysqli_error($result) ."/n";
    die(mysqli_error());
}
    $i = 0; //iteration counter - start at 0

    $totalRows = mysqli_num_rows($result); // we need this to know when to change the output
    $targetRows = $totalRows - 1; //row indies start from 0, not 1.

    foreach ($result as $row){ 

	
        if ($targetRows == $i) {
            $temp = "[new Date('".$row['RECORD_TIME']."'),".($row['GPU0_TEMP']).",".($row['GPU1_TEMP']).",".($row['GPU2_TEMP']).",".($row['GPU3_TEMP']).",".($row['GPU4_TEMP']).",".($row['GPU5_TEMP'])."]". PHP_EOL;
            } else {
            $temp = "[new Date('".$row['RECORD_TIME']."'),".($row['GPU0_TEMP']).",".($row['GPU1_TEMP']).",".($row['GPU2_TEMP']).",".($row['GPU3_TEMP']).",".($row['GPU4_TEMP']).",".($row['GPU5_TEMP'])."],". PHP_EOL;
            }
        $i = $i + 1; 
        $rows[] = $temp; 
    }

 $table = $rows;
 $data = implode($table); //format the table as a single string, with line returns
?>
<html>
  <head>
	<meta charset="UTF-8">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  	<meta http-equiv="refresh" content="30" >
  </head>
  <body>
	<b>Select period </b><select id="dynamic_select" name="Period" >
	  <option value="0.5"  <?php if ($hour == 0.5) echo "selected=\"selected\""; ?> >30 mins</option>
	  <option value="1"    <?php if ($hour == 1)   echo "selected=\"selected\""; ?> >1 hour</option>
	  <option value="2"    <?php if ($hour == 2)   echo "selected=\"selected\""; ?> >2 hours</option>
	  <option value="6"    <?php if ($hour == 6)   echo "selected=\"selected\""; ?> >6 hours</option>
	  <option value="12"   <?php if ($hour == 12)  echo "selected=\"selected\""; ?> >12 hours</option>
	  <option value="24"   <?php if ($hour == 24)  echo "selected=\"selected\""; ?> >1 day</option>
	</select>
	
    <div id="chart" style="width: 1200px; height: 500px"></div>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart(){
        var data = new google.visualization.DataTable();
            data.addColumn('datetime','Time'); //data.addColumn('timeofday','Time'); 
            data.addColumn('number','GPU0_TEMP');
            data.addColumn('number','GPU1_TEMP');
            data.addColumn('number','GPU2_TEMP');
            data.addColumn('number','GPU3_TEMP');
			data.addColumn('number','GPU4_TEMP');
            data.addColumn('number','GPU5_TEMP');

            data.addRows([              
                <?php echo $data; ?> //dump the result into here, as it's correctly formatted   
            ]);

        var options = {
            title: 'Mining Rig Temperatures (<?php if ($hour == 24) echo "1 day"; elseif ($hour == 0.5) echo "30 mins"; elseif ($hour == 1) echo "1 hour"; else echo $hour." hours";?>)',
			curveType: 'function',
            legend: { position: 'bottom' },
			explorer: { axis: 'horizontal' },
            width: 1600,
            height: 600,
            hAxis: { format: 'HH:mm MMMM dd' },
			vAxis: {format:'#ºC'}
        }; 

		var chart = new google.visualization.LineChart(document.getElementById('chart'));
        chart.draw(data, options);    
      }
	  
    </script>
	
	

	<script>
		$(function(){
		  // bind change event to select
		  $('#dynamic_select').on('change', function () {
			  var url = $(this).val(); // get selected value
			  if (url) { // require a URL
				  window.location = "<?php echo WEB_LINK; ?>" + url; // redirect
			  }
			  return false;
		  });
		});
	</script>
  </body>
</html>