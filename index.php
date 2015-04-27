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
	<title>Train</title>
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
		table{
			vertical-align: middle;
			text-align: center;
		}

	</style>
	
</head>
<?php require_once('_show_index_new.php'); ?>
<body>
	<form method="GET" action="index.php">
		<div class="container">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-1"><input class="form-control" type="text" name="fromstation" value="<?=$fromstation?>"></div>
				<div class="col-md-1"><input class="form-control" type="text" name="tostation"  value="<?=$tostation?>"></div>
				<div class="col-md-2"><input class="form-control" type="text" name="strtime"  value="<?=$settime?>"></div>
				<div class="col-md-1"><button type="submit"  name="newsubmit" class="btn btn-primary">提交</button></div>
				<div class="col-md-3"></div>
			</div>
			<hr>

			<?php if (isset($_GET['newsubmit'])) {
				foreach ($allroute as $key => $route) { ?>
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							<div class="panel-heading">
								
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>">
									<table class="" style="text-align:center;vertical-align:middle;width:100%">
										<tr>
											<td style="width:2%;" rowspan="2" style="width:3%;"><?=$key+1?></td>
											<td style="width:10%;"><?=$route[1][0][0]?></td>
											<td style="width:10%;"><?=date('G:i',strtotime($route[1][0]['deptime']))?></td>
											<td style="width:10%;" rowspan="2"><?=$route[0]['travel_time']/60?>分鐘</td>
											<td style="width:3%;" rowspan="2"><?=$route[0]['total_transfer']?>次</td>
											<td style="width:3%;" rowspan="2"><?=0?>元</td>
										</tr>
										<tr>
											
											<td><?=$route[1][count($route[1])-1][0]?></td>
											<td><?=date('G:i',strtotime($route[1][count($route[1])-1]['arrtime']))?></td>
											
										</tr>
									</table>
								</a>
								
							</div>
							<div id="collapse<?=$key?>" class="panel-collapse collapse">
								<div class="panel-body">
									
									<table class="table table-stripe">
										<?php foreach ($route[1] as $key => $value): ?>
											<tr>
												<td><?=$value['0']?></td>
												<?php if ($key%2==0): ?>
													<td rowspan="2" style="vertical-align:middle"><?=$value['train_id']?>次</td>
													<td rowspan="2" style="vertical-align:middle"><?=$value[1]?></td>
												<?php endif ?>
												
												<td><?=date('m-d G:i',strtotime($value['arrtime']))?></td>
												<td><?=date('m-d G:i',strtotime($value['deptime']))?></td>
												<?php if ($key%2==0): ?>
													<td rowspan="2" style="vertical-align:middle"><?=0?>元</td>
												<?php endif ?>
											</tr>
										<?php endforeach ?>	
									</table>
									
								</div>
							</div>
						</div>
					</div>

					
				<?php } ?>
			<?php } ?>
		</div>
		
	</form>
</body>
<script src="style/js/bootstrap.min.js"></script>
<script src="style/js/jquery-2.1.0.js"></script>
<script src="style/js/bootstrap.js"></script>
<script src="style/js/jquery-ui.js"></script>
<script src="style/js/bootstrap-fileupload.js"></script>

</html>


