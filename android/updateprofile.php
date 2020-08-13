<?php
if(isset($_REQUEST['action']) && $_REQUEST['action']=='UPDATEPROFILE')
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
				$cid=$t->get_val_from_int('e_users','mobile',$user,'id');
				$add1=isset($_REQUEST['addr1'])?trim(mysql_real_escape_string($_REQUEST['addr1'])):'';
				$add2=isset($_REQUEST['addr2'])?trim(mysql_real_escape_string($_REQUEST['addr2'])):'';
				$city=isset($_REQUEST['city'])?trim(mysql_real_escape_string($_REQUEST['city'])):'';
				$pincode=isset($_REQUEST['pincode'])?trim(mysql_real_escape_string($_REQUEST['pincode'])):'';
				$state=isset($_REQUEST['state'])?trim(mysql_real_escape_string($_REQUEST['state'])):'';
				
				if(!empty($add1) && !empty($add2) && !empty($city) && !empty($pincode) && !empty($state))
				{				
					if($t->is_exists_int('e_users','id',$cid))
					{
						$ip=$_SERVER['REMOTE_ADDR'];
						
						$sql="UPDATE e_user_profile SET address1='$add1',address2='$add2',city='$city',state='$state',pincode=$pincode,dt=now(),ip='$ip' WHERE $cid=$cid";
						$m=mysql_query($sql) OR die(mysql_error());
						if($m)
						{
							$response[]=array("code"=>"PASS","message"=>"profile updated successfully.");	
						}
					}
				}
				else
				{
					$response[]=array("code"=>"FAIL","message"=>"please enter required parameter to update user profile.");				
				}
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