<?php
if(isset($_REQUEST['action']) && $_REQUEST['action']=='GETPROFILE')
{
	/*
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	*/
	
	$response=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$user=(isset($_REQUEST['username']))?trim(mysql_real_escape_string($_REQUEST['username'])):'NA';
	$pass=(isset($_REQUEST['password']))?trim(mysql_real_escape_string($_REQUEST['password'])):'NA';
			
	if($user=='NA' || $pass=='NA')
	{
		$response['data'][]=array("code"=>"FAIL","message"=>"please enter username,password.");		
	}
	else
	{
		if($t->validMobile($user))
		{
			if($t->validLogin($user,$pass))
			{
				$data=array();
				
				$cid=$t->get_val_from_int('e_users','mobile',$user,'id');
				
				//echo $cid;
				/*
				$sql=mysql_query("SELECT * FROM e_users AS a, e_user_profile AS b, e_user_verification AS c, e_payment_records AS d WHERE a.id=$cid") OR DIE(mysql_error());
				if(mysql_num_rows($sql)>0)
				{
					$d=mysql_fetch_assoc($sql);
					*/
					$data['name']=$t->get_val_from_int('e_users','id',$cid,'name');
					$data['mobile']=$t->get_val_from_int('e_users','id',$cid,'mobile');
					$data['email']=$t->get_val_from_int('e_users','id',$cid,'email');
					$data['mv_dt']=$t->get_val_from_int('e_user_verification','cid',$cid,'mv_dt');
					$data['ev_dt']=$t->get_val_from_int('e_user_verification','cid',$cid,'ev_dt');
					$data['add1']=$t->get_val_from_int('e_user_profile','cid',$cid,'address1');
					$data['add2']=$t->get_val_from_int('e_user_profile','cid',$cid,'address2');
					$data['city']=$t->get_val_from_int('e_user_profile','cid',$cid,'city');
					$data['state']=$t->get_val_from_int('e_user_profile','cid',$cid,'state');
					$data['pincode']=$t->get_val_from_int('e_user_profile','cid',$cid,'pincode');
					
					$package_id=$t->get_val_from_int('e_payment_records','cid',$cid,'package_id');
					$payment_mode_id=$t->get_val_from_int('e_payment_records','cid',$cid,'payment_mode_id');
					
					$data['p_pakacge']=$t->get_val_from_int('e_packages','id',$package_id,'p_name');
					$data['p_payment_mode']=$t->get_val_from_int('e_payment_modes','id',$payment_mode_id,'py_md_name');
					$data['p_amount']=$t->get_val_from_int('e_payment_records','cid',$cid,'amount');
					$data['p_date']=$t->get_val_from_int('e_payment_records','cid',$cid,'payment_date');
					
					$k2=$data;
				//}
				
				$k=$data1=array();
				
				$s="SELECT * FROM e_user_service_records WHERE cid=$cid GROUP BY start_date;";
				$r=mysql_query($s) OR die(mysql_error());
				if(mysql_num_rows($r)>0)
				{
					while($d1=mysql_fetch_array($r))					
					{
						$data1['code']="PASS";
						$data1['message']="SUCCESS";
						$data1['subscription_type']=$t->get_val_from_int('e_service_type','id',$d1['service_type'],'sr_type');
						$data1['start_date']=$d1['start_date'];
						$data1['end_date']=$d1['end_date'];
						if($d1['status']==0)
						$data1['status']='DeActive';
						if($d1['status']==1)
						$data1['status']='Active';
						
						$k['data'][]=$data1;
					}					
				}
			}
			else
			{
				$response['data'][]=array("code"=>"FAIL","message"=>"invalid username,password.");				
			}
		}		
		else
		{
			$response['data'][]=array("code"=>"FAIL","message"=>"Please enter valid username.");
		}
	}
	
	$data_f=array();
	
	if(!empty($k) || !empty($k2))
	{
		array_push($data_f,$k);
		array_push($data_f,$k2);
		//$data_f['data']=$k2.$k;
	}
	else
	{
		array_push($data_f,$response);
	}
	$k1=json_encode($data_f);
	
	/*
	echo '<pre>';
		print_r($data);
		print_r($k);
	echo '</pre>';
	*/
	echo $k1;	
}
?>