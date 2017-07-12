<?php
   define('DB_SERVER', 'PUT_YOURDOMAIN_HERE.COM:3306');
   define('DB_USERNAME', 'PUT_DB_USERNAME_HERE');
   define('DB_PASSWORD', 'PUT_DB_PASSWORD_HERE');
   define('DB_DATABASE', 'PUT_DATABASE_NAME_HERE');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   
   $date_time = $_GET['date_time']; 
   $gpu_name = $_GET['gpu_name']; 
   $gpu_temp = $_GET['gpu_temp']; 
   $gpu_power = $_GET['gpu_power']; 
   $gpu_speed = $_GET['gpu_speed']; 
   $gpu_efficient = $_GET['gpu_efficient']; 
   
   if (!$db || empty($date_time) || empty($gpu_temp) || empty($gpu_name) || empty($gpu_power) || empty($gpu_speed) || empty($gpu_efficient) ){
	   echo "null" . PHP_EOL;
	   exit;
   }
   
  $sql = "INSERT INTO gpulog (DATE_TIME, GPU_NAME, GPU_TEMP, GPU_POWER, GPU_SPEED, GPU_EFFICIENT) VALUES
			('".$date_time."','".$gpu_name."','".$gpu_temp."','".$gpu_power."','".$gpu_speed."','".$gpu_efficient."')";

  if ( mysqli_query($db,$sql)){
	  echo "GPU stats updated successfully";
	} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
	}
	mysqli_close($db);
?>