<?php
date_default_timezone_set('Asia/Kolkata');
if(isset($_REQUEST['action']) && $_REQUEST['action']=='REGISTER')
{
	$response=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$name=(isset($_REQUEST['name']))?trim(mysql_real_escape_string($_REQUEST['name'])):'NA';
	$mobile=(isset($_REQUEST['mobile']))?trim(mysql_real_escape_string($_REQUEST['mobile'])):'NA';
	$email=(isset($_REQUEST['email']))?trim(mysql_real_escape_string($_REQUEST['email'])):'NA';
	$gcmid=(isset($_REQUEST['gcmid']))?trim(mysql_real_escape_string($_REQUEST['gcmid'])):'';
	
	if($name!='NA' && $mobile!='NA' && $email!='NA')
	{
		if(!$t->validMobile($mobile) && !$t->validEmail($email))
		{
			$response[]=array("code"=>"FAIL","message"=>"Please enter valid mobile, email.");
		}
		else if($t->is_exists_int('e_users','mobile',$mobile) || $t->is_exists_var('e_users','email',$email))
		{
			$response[]=array("code"=>"FAIL","message"=>"This mobile OR email already registered with us.");
		}
		else
		{
			$pass=$t->add_new_client($name,$mobile,$email,$gcmid);
			if(!empty($pass))
			{
				$cid=$t->get_val_from_int('e_users','mobile',$mobile,'id');
				$t->verify_user_mobile($cid);	//Verify Mobile On First Login
				$t->generateSession($cid,$pass);	//Generate and record session to avoid multiple login
				$response[]=array("code"=>"PASS","message"=>"Thank you!!! You registered successfully with us.","username"=>$mobile,"password"=>$pass);
			}
			else
			{
				$response[]=array("code"=>"FAIL","message"=>"SORRY!!! Unable to register yourself.");
			}		
		}
	}
	else
	$response[]=array("code"=>"FAIL","message"=>"Please enter name, mobile, email.");	
	
	$k=json_encode($response);	
	echo $k;
}
?>