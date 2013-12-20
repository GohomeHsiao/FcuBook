<?php
//隨時整理資料庫
//檢查書本
$sql = "SELECT * FROM book";
$tmp = mysql_query($sql, $link);
while($b_data = mysql_fetch_array($tmp)){	
	putenv("TZ=Asia/Taipei");
	$f_time = date("Y-m-d H:i:s");
	
	//檢查待確認書籍未繳書START
	if($b_data[book_state_no] == 1){		
		$datetime = explode(" ",$b_data[req_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);		
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+3, $date[0]);
		$t2 = time();	
		
		if($t2 > $t1){
			//$sql = "DELETE FROM book WHERE book_no = $b_data[book_no]";
			//mysql_query($sql, $link);
		
			//REMIND				
			//$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所登錄的$b_data[book_name]已經逾期未繳書，此筆交易已刪除。', '2', '$f_time', '$b_data[seller]')";
			//mysql_query($sql, $link);
		}		
	}
	//檢查待確認書籍未繳書END
	
	//檢查銷售中書籍是否滯銷START
	if($b_data[book_state_no] == 2){
		$datetime = explode(" ",$b_data[on_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+14, $date[0]);
		$t2 = time();
	
		$remain_seconds = $t1 - $t2;
		if($remain_seconds <= 0){
			$sql = "UPDATE book SET on_time = '0000-00-00 00:00:00', unsale_time = '$f_time', book_state_no = 5 WHERE book_no = $b_data[book_no]";
			mysql_query($sql, $link);	
		
			//REMIND				
			$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所販賣的$b_data[book_name]已經過期，請至聯合服務中心處理。', '2', '$f_time', '$b_data[seller]')";
			mysql_query($sql, $link);
		}
	}
	//檢查銷售中書籍是否滯銷END
	
	//檢查銷售中書籍是否惡意棄標START
	if($b_data[book_state_no] == 3 AND $b_data[trade_state] == 'n' AND $b_data[buyer] != ''){
		$datetime = explode(" ",$b_data[buy_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+3, $date[0]);
		$t2 = time();	
		
		if($t2 > $t1){
			$sql = "UPDATE book SET buy_time = '0000-00-00 00:00:00', buyer = '' WHERE book_no = $b_data[book_no]";
			mysql_query($sql, $link);
			
			$sql = "UPDATE member SET state_no = 2, punish_time = '$f_time' WHERE id = '$b_data[buyer]'";
			mysql_query($sql, $link);
		
			//REMIND				
			$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所購買的$b_data[book_name]已經逾期未付款，系統已將您停權三天。', '2', '$f_time', '$b_data[buyer]')";
			mysql_query($sql, $link);
		}
	}
	//檢查銷售中書籍是否惡意棄標END
	
	//檢查滯銷書START
	if($b_data[book_state_no] == 5){				
		//對滯銷書收取保管費START
		$datetime = explode(" ",$b_data[unsale_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);		
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+7, $date[0]);
		$t2 = time();				
		$next_time = getdate($t1);
		$next_time = $next_time[year].'-'.$next_time[mon].'-'.$next_time[mday].' '.$next_time[hours].':'.$next_time[minutes].':'.$next_time[seconds];
				
		if($t2 >= $t1){
			$sql = "UPDATE book SET b_storage = b_storage + 5, unsale_time = '$next_time' WHERE book_no = $b_data[book_no]";
			mysql_query($sql, $link);
		}
		//對滯銷書收取保管費END
		
		//檢查保管費是否超過其本身價值START
		if($b_data[b_storage] >= $b_data[new_price]){
			$sql = "UPDATE book SET finish_time = '$f_time', book_state_no = 6 WHERE book_no = $b_data[book_no]";
			mysql_query($sql, $link);
			
			//REMIND				
			$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您販賣的$b_data[book_name]所累積的保管費已經超過其本身價值，故已充公。', '2', '$f_time', '$b_data[seller]')";
			mysql_query($sql, $link);
		}
		//檢查保管費是否超過其本身價值END
	}
	//檢查滯銷書END
	
	//檢查超過三個月之交易完成AND取消登錄刪除START
	if($b_data[book_state_no] == 4 OR $b_data[book_state_no] == 6){				
		
		$datetime = explode(" ",$b_data[finish_time]);
		$date = explode("-",$datetime[0]);		
		$month_book = $date[1];
		if(date("Y") > $date[0]) 
			$month_now = date("m") + 12;
		else
			$month_now = date("m");
			
		if($month_now - $month_book > 3){
			$sql = "DELETE FROM book WHERE book_no = $b_data[book_no]";
			mysql_query($sql, $link);
		}
		
	}
	//檢查超過三個月之交易完成AND取消登錄END
}

//檢查會員懲罰
$sql = "SELECT * FROM member";
$tmp = mysql_query($sql, $link);
while($m_data = mysql_fetch_array($tmp)){
	if($m_data[state_no] == 2){ //停權三天
		$datetime = explode(" ",$m_data[punish_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+3, $date[0]);
		$t2 = time();
		if($t2 > $t1){
			$sql = "UPDATE member SET state_no = 1, punish_time = '0000-00-00 00:00:00' WHERE id = '$m_data[id]'";
			mysql_query($sql, $link);
			
			//REMIND				
			$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('系統已經恢復您的權限。', '1', '$f_time', '$m_data[id]')";
			mysql_query($sql, $link);
		}
	}
	else if($m_data[state_no] == 3){//停權七天
		$datetime = explode(" ",$m_data[punish_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+7, $date[0]);
		$t2 = time();
		if($t2 > $t1){
			$sql = "UPDATE member SET state_no = 1, punish_time = '0000-00-00 00:00:00' WHERE id = '$m_data[id]'";
			mysql_query($sql, $link);
			
			//REMIND				
			$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('系統已經恢復您的權限。', '1', '$f_time', '$m_data[id]')";
			mysql_query($sql, $link);
		}
	}
}
?>