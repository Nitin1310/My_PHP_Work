<?php

if(isset($_REQUEST['action']) && $_REQUEST['action']=='LOGIN')
{
	$response=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$user=(isset($_REQUEST['username']))?trim(mysql_real_escape_string($_REQUEST['username'])):'NA';
	$pass=(isset($_REQUEST['password']))?trim(mysql_real_escape_string($_REQUEST['password'])):'NA';
	$gcmid=(isset($_REQUEST['gcmid']))?trim(mysql_real_escape_string($_REQUEST['gcmid'])):'';
			
	if($user=='NA' || $pass=='NA')
	{
		$response[]=array("code"=>"FAIL","message"=>"please enter username,password.");		
	}
	else
	{
		if($t->validMobile($user))
		{
			if($t->validLogin($user,$pass,$gcmid))
			{
				$cid=$t->get_val_from_int('e_users','mobile',$user,'id');
				if($t->checking_ft($cid))	//If free trial never ever done, do it
				{
					$t->generate_ft($cid);						
				}
				$t->verify_user_mobile($cid);	//Verify Mobile On First Login
				$t->generateSession($cid,$pass);	//Generate and record session to avoid multiple login
				if(!empty($gcmid))
				//Update GCM ID
				mysql_query("UPDATE e_users SET gcmid='$gcmid' WHERE id=$cid") OR DIE(mysql_error());
				
				$response[]=array("code"=>"PASS","message"=>"valid username password.");	
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