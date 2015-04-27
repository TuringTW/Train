<?php 
	//登入檢查
	


	


	function connectDB()
	{
		$link = mysqli_connect('localhost','client','1qaz2wsx','train');
		mysqli_query($link,"SET NAMES 'UTF8'");
		if (empty($link)) {
			header('location:index.php?wrong=無法連線到資料庫');
			die();
		}
		return($link);
	}
	function powercheck($pow_require){
		if (isset($_SESSION['power'])) {
			$power = $_SESSION['power'];
			if ($power <= $pow_require) {
				
			}else{
				header('location:index.php');
			}
		}else{
			header('location:index.php');
		}
	}



 ?>