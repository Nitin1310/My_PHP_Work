<?php
if(isset($_REQUEST['action']) && $_REQUEST['action']=='GETSERVICE')
{
	$response=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$user=(isset($_REQUEST['username']))?trim(mysql_real_escape_string($_REQUEST['username'])):'NA';
	$pass=(isset($_REQUEST['password']))?trim(mysql_real_escape_string($_REQUEST['password'])):'NA';
			
	if($user=='NA' || $pass=='NA')
	{
		$response[]=array("code"=>"FAIL","message"=>"please enter username,password.");		
	}
	else
	{
		if($t->validMobile($user))
		{
			if($t->validLogin($user,$pass))
			{
				$data=array();
				
				$cid=$t->get_val_from_int('e_users','mobile',$user,'id');
				
				$s="SELECT * FROM e_user_service_records WHERE cid=$cid GROUP BY start_date;";
				$r=mysql_query($s) OR die(mysql_error());
				if(mysql_num_rows($r)>0)
				{
					while($d1=mysql_fetch_array($r))					
					{
						$data['subscription_type']=$t->get_val_from_int('e_service_type','id',$d1['service_type'],'sr_type');
						$data['start_date']=$d1['start_date'];
						$data['end_date']=$d1['end_date'];
						if($d1['status']==0)
						$data['status']='DeActive';
						if($d1['status']==1)
						$data['status']='Active';
						
						$response[]=$data;
					}
				}
				mysql_free_result($r);
			}
			else
			{
				$response[]=array("code"=>"FAIL","message"=>"invalid username,password.");				
			}
		}		
		else
		{
			$response[]=array("code"=>"FAIL","message"=>"Please enter valid username.");
		}
	}
	
	$k=json_encode($response);
	
	echo $k;
}
?>