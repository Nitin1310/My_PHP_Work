<?php
	date_default_timezone_set('Asia/Kolkata');
	
	$result=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$k=mysql_query("SELECT * FROM e_user_service_records WHERE status=1");
	
	if(mysql_num_rows($k)>0)
	{
		while($j=mysql_fetch_array($k))
		{
			$result[]=$j['id'];
		}
	}
	
	if(!empty($result))
	{
		$today=date('Y-m-d');
		
		foreach($result AS $i)
		{
			$sdate=$t->get_val_from_int('e_user_service_records','id',$i,'start_date');
			
			$expiry=date('Y-m-d',strtotime('+2 days',strtotime($sdate)));
			
			echo 'For : '.$i.' Start Date : '.$sdate.', Email Expiry Date : '.$expiry;
			
			if($expiry<$today)
			{
				$im=mysql_query("UPDATE e_user_service_platform_records SET status=0, dt=now() WHERE sid=$i AND platform_id=6") OR DIE(mysql_error());
				if($im)
					echo 'Email disabled for '.$i.'<br>';
			}
		}
	}
	
?>