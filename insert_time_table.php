<?php
	require_once('system/utility.php');

	header("Content-Type:text/html; charset=utf-8");
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="KEVIN">

	<link href="./style/css/bootstrap.css" rel="stylesheet">
	<link href="./style/css/bootstrap.min.css" rel="stylesheet">
	<link href="./style/css/bootstrap-fileupload.css" rel="stylesheet">
	<link href="./style/css/jquery-ui.css" rel="stylesheet">
	<title>train input</title>
	<style type="text/css">
		body{ 	padding-top: 70px;
				padding-bottom: 50px; 
			}
		.alert {
			margin-bottom: 0px;
		}
		.sidebar{
			
			padding-top: 60px;
				
		}

	</style>
	
</head>
<body>
	<form method="POST" action="_input_time_table.php" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-5"><input type="file" class="form-control" width="100%" name="file"></div>
			<div class="col-md-1"><button type="submit"  name="newsubmit" class="btn btn-primary">新增</button></div>
			<div class="col-md-3"></div>
		</div>
	</form>
</body>
</html>
			
		