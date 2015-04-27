<!-- 86sec from 1008 to 1001 at 23:10 -->
<!-- 
台北 	台北 	1008
板橋	板橋	1011		
桃園 	X		7001
新竹 	六家	2204				
台中	新烏日	1242
嘉義 	X		7002
台南 	沙崙	5102		
高雄 	新左營	1242



 -->
<?php 
	require_once('system/utility.php');
	set_time_limit(0);
	$link = connectDB('train');

	//計時開始
	$stime = time();

	$settime = date('G:i:s');
	$tostation = '1001';
	$fromstation = '1008';
	if (isset($_GET['newsubmit'])) {
		$tostation = $_GET['tostation'];
		$fromstation = $_GET['fromstation'];
		$settime = $_GET['strtime'];
		$strtime = '2014-10-25 '.$settime; 
		
		
		//基本宣告
		$route = array();
		$allroute = array();
		$failroute = array();	
		//基本變數
		$searchresult = 3; 	//理想結果數量
		$waithour = 5;		//幾小時內必須到達目的地
		$countR = 1;
		//搜尋迴圈
		echo "<pre>";
		stationfindtrain($route,$strtime,$fromstation);
		echo "</pre>";
		foreach ($allroute as $key => $value) {
			foreach ($value[1] as $key1 => $ttable) {
				$sql = "SELECT `station`.`name` as `sname` , `train`.`class` from `timetable` LEFT JOIN `train` on `train`.`train_id` = `timetable`.`train_id` LEFT JOIN `station` on `station`.`station_id` = `timetable`.`station_id` where `timetable`.`ttable_id` = '".$ttable['ttable_id']."'";
				$result = mysqli_query($link,$sql);
				$row = mysql_fetch_assoc($result);
				switch ($row['class']) {
					case '1':
						$row['class'] = '高鐵';    	//
						break;
					case '2':
						$row['class'] = '自強';    	//
						break;   				// Tze-Chiang Limited Express 
					case '3':
						$row['class'] = '莒光';		//
						break;   				// Chu-Kuang Express 
					case '4':
						$row['class'] = '復興';		//
						break;   				// Fu-Hsing Semi Express 
					case '5':
						$row['class'] = '電車';		//
						break;   				// Electric Multiple Unit 
					case '6':
						$row['class'] = '區間車';	//
						break;   				// Local Train 
					case '7':
						$row['class'] = '柴油車';	//
						break;   				// na
					case '8':
						$row['class'] = '其他';
						break;	
				}
				
				array_splice($allroute[$key][1][$key1], 0, 0, $row);


			}
		}
		//計時結束
		echo "start:".date('h:i:s',$stime)."<br>";
		echo "stop:".date('h:i:s',time())."<br>";
		echo "consume:".((time()-$stime))."<br>";
		echo "Total Query:".($countR*2)."次<br>";
		
	} 

	

	function stationfindtrain($route,$arrtime,$fromstation){
		global $allroute;
		global $searchresult;
		global $waithour;
		global $strtime;
		global $link;
		global $tostation;
		$grade = 0;
		$total_transfer = 0;

		if ($tostation == $fromstation) {
			
		}else if(count($route)<2){	
			$sql = "SELECT  `timetable`.`ttable_id`,`timetable`.`train_id`,`timetable`.`order`,`timetable`.`deptime`,`timetable`.`arrtime` ,`timetable`.`station_id` from `timetable` LEFT JOIN `train` on `train`.`train_id` = `timetable`.`train_id` where `timetable`.`station_id` = '$fromstation' and TO_SECONDS(`deptime`)-TO_SECONDS('$arrtime')>0  and TO_SECONDS(`deptime`)-TO_SECONDS('".date('Y-m-d G:i:s',strtotime($strtime." +$waithour hours"))."') <0 and `train`.`train_id` NOT IN (".join(',',trainp($route)).") order by `train`.`class`,`deptime`";
			if (!empty($allroute)) {
				if (count($allroute)>$searchresult&&$allroute[$searchresult][0]['grade']<$grade) {
					$sql = "";
				}
			}
			$result = mysqli_query($link,$sql);
			if ($result) {
				while ($row=mysqli_fetch_assoc($result)) {
					array_push($route, $row);

					trainfindstation($route,$row['train_id'],$row['order']);
					array_pop($route);
				}
			}
		}
	}
	function trainfindstation($route,$train_id,$order){
		global $stime,$countR;
		global $searchresult;
		global $waithour;
		global $strtime;
		global $link;
		$pre_station = array_flatten($route,'station_id');
		$sql = "SELECT  `timetable`.`ttable_id`,`timetable`.`train_id`,`timetable`.`deptime`,`timetable`.`arrtime` ,`timetable`.`station_id` from `timetable` LEFT JOIN `station` on `station`.`station_id` = `timetable`.`station_id` where `train_id` = '$train_id' && `order` > '$order' and TO_SECONDS(`arrtime`)-TO_SECONDS('".date('Y-m-d G:i:s',strtotime($strtime." +$waithour hours"))."') <0 and `timetable`.`station_id` NOT IN (".join(',',$pre_station).") order by `station`.`class` DESC,`arrtime` ";
		echo $countR.'&#9;&#9;'.(time()-$stime).'&#9;&#9;'.join('->',$pre_station),'<br>';
		$countR++;
		//echo "$sql<br>";
		// die($sql);
		$result = mysqli_query($link,$sql);
		if ($result) {
			while ($row=mysqli_fetch_assoc($result)) {
				array_push($route, $row);
				stationfindtrain($route,$row['arrtime'],$row['station_id']);
				array_pop($route);
			}
		}else{
			//array_push($failroute, $route);
		}
	}
	function sort_route($route){
		global $allroute;
		$result = trainfl($route[1]);
		if ($result!=-2) {
			if ($result>-1) {
				array_splice($allroute, $result, 1);
			}
			$i=0;
			while ($i<count($allroute)) {
				if ($allroute[$i][0]['grade']>$route[0]['grade']) {
					break;
				}
				$i++;
			}
			$temp = array($route);
			array_splice($allroute, $i, 0, $temp);
		}
	}
	function array_flatten($array,$col){
		$temp = array();
		for ($i=0; $i < count($array); $i+=2) { 
			array_push($temp, $array[$i][$col]);
		}
		return $temp;
	}
// 排除ABA ACA的重複現象
	function trainp($route){
		global $allroute;
		$temp = array(0);
		if (!empty($allroute)) {
			foreach ($allroute as $key => $value) {
				if (count($value[1])>=count($route)+2) {
					$i=0;
					$nexts = 0;
					while($i<count($route)){
						if ($route[$i]['train_id']==$value[1][$i]['train_id']) {
							if (isset($value[1][$i+2]['train_id'])) {
								$nexts = $value[1][$i+2]['train_id'];
							}
							
						}else{
							$nexts = 0;
						}
						$i=$i+2;
					}
					array_push($temp, $nexts);
				}
			}
		}
		for ($i=0; $i < count($route); $i+=2) { 
			array_push($temp, $route[$i]['train_id']);
		}
		return $temp;
	}
	// 
	function trainfl($route){
		global $allroute;
		$temp=-1;
		// 檢查ACA ABA的重複現象
		foreach ($allroute as $key => $value) {
			if ($value[1][count($value[1])-1]['train_id']==$route[count($route)-1]['train_id']) {
				$travel_time = strtotime($route[count($route)-1]['arrtime'])-strtotime($route[0]['deptime']);
				$temp = -2;
				if ($travel_time<$value[0]['travel_time']||count($route)<$value[0]['total_transfer']) {
					$temp = $key;
					break;
				}
			}
		}
		return $temp;
	}
 ?>