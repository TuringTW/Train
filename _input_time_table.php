<?php 
	require_once('system/utility.php');
	set_time_limit(0);
	$link = connectDB('train');

    if(isset($_POST['newsubmit'])){
       $uploadExcel = $_FILES['xmlfile']['tmp_name'];
        $name = $_FILES['xmlfile']['name'];

        move_uploaded_file($uploadExcel, $name);  
        $doc = new DOMDocument();
        $doc->load( "$name" );      
        $readXml = $doc->getElementsByTagName('TrainInfo');


        // echo "<pre>";
        // print_r($readXml);
        // echo "</pre>";
        
        

        foreach ($readXml as $key => $value) {
        	

        	
        	$train_id = $value->getAttribute('Train');
        	
			$carclass = $value->getAttribute('CarClass');
			switch ($carclass) {
				case 'thsr':
					$carclass = '1'; 
					break;
				case '1100':
					$carclass = '2';    	//自強
					break;   				// Tze-Chiang Limited Express 
				case '1101':
					$carclass = '2';		//自強
					break;   				// Tze-Chiang Limited Express 
				case '1102':
					$carclass = '2';	//太魯閣號
					break;   				// Tze-Chiang Limited Express(Tarko) 
				case '1107':
					$carclass = '2';	//普悠瑪號
					break;   				// Tze-Chiang Limited Express(Puyuma) 
				case '1110':
					$carclass = '3';		//莒光
					break;   				// Chu-Kuang Express 
				case '1120':
					$carclass = '4';		//復興
					break;   				// Fu-Hsing Semi Express 
				case '1130':
					$carclass = '5';		//電車
					break;   				// Electric Multiple Unit 
				case '1131':
					$carclass = '6';	//區間車
					break;   				// Local Train 
				case '1132':
					$carclass = '5';	//區間快
					break;   				// Fast Local Train 
				case '1140':
					$carclass = '5';	//普快車
					break;   				// Ordinary train 
				case '1141':
					$carclass = '6';	//柴快車
					break;   				// Disel Rail Car 
				case '1150':
					$carclass = '7';	//柴油車
					break;   				// na
				default:
					$carclass = '8';
					break;
			}

			$line = $value->getAttribute('Line'); //儲存方式：0（不經過山海線）,1(山),2(海)
			$linedir = $value->getAttribute('LineDir'); //(0=順時針，1=逆時針)
			$overnightstn = $value->getAttribute('OverNightStn'); //0為不跨日，有資料代表為跨夜車，ETime為次日時間。
			$cripple = $value->getAttribute('Cripple'); //儲存方式：殘障座位(Y/N)
			$package = $value->getAttribute('Package'); //儲存方式：辦理托運(Y/N)
			$dinning = $value->getAttribute('Dinning'); //儲存方式：餐車(Y/N)
			$type = $value->getAttribute('Type'); //0：常態列車1：臨時2：團體列車3春節加開車
			$breastFeed = $value->getAttribute('BreastFeed'); //儲存方式：設有哺(集)乳室 (Y/N)
			$note = $value->getAttribute('Note'); //列車說明，如「每日行駛。」、「民國96年2月17,19,22,26日行駛。民國96年3月1日行駛。」「逢週一至四,日行駛。」

			$sql = "INSERT INTO  `train`.`train` (
					
					`train_id` ,
					`class` ,
					`note` ,
					`linedir`
					)
					VALUES (
					  '$train_id',  '$carclass',  '$note',  '$linedir'
					);";
			$result = mysqli_query($link,$sql);

        	foreach ($value->getElementsByTagName('TimeInfo') as $key => $timeinfo) {
        		$station_id =  $timeinfo -> getAttribute('Station');
        		$deptime =  $timeinfo -> getAttribute('DEPTime');
        		$arrtime =  $timeinfo -> getAttribute('ARRTime');
        		$order =  $timeinfo -> getAttribute('Order');
        		if ($arrtime < "03:00") {
        			$arrtime = "2015-03-18 ".$arrtime;
        		}else{
        			$arrtime = "2015-03-17 ".$arrtime;
        		}
        		if ($deptime < "03:00") {
        			$deptime = "2015-03-18 ".$deptime;
        		}else{
        			$deptime = "2015-03-17 ".$deptime;
        		}
        		$sql = "INSERT INTO  `train`.`timetable` (
										`train_id` ,
										`station_id` ,
										`arrtime` ,
										`deptime` ,
										`order`
										)
										VALUES (
										'$train_id',  '$station_id',  '$arrtime',  '$deptime',  '$order'
										);";
				$result = mysqli_query($link,$sql);
        		// echo "<pre>";
		        // print_r($timeinfo);
		        // echo "</pre>";
        	}

        }

        unlink($name);

        
   }
   header('location:index.php');

 ?>