<?php
if(isset($_REQUEST['action']) && $_REQUEST['action']=='RECOVER')
{
	$response=array();
	
	require_once('../t.class.php');
	$t=new t();
	
	$u=trim(mysql_real_escape_string($_REQUEST['username']));
	
	if($t->validMobile($u))
	{
		if($t->is_exists_int('e_users','mobile',$u))
		{
			if($t->reset_password($u))
			{
				$email=$t->get_val_from_int('e_users','mobile',$u,'email');
				$response[]=array("code"=>"PASS","message"=>convert_email($email));
			}
			else
			$response[]=array("code"=>"FAIL","message"=>"error occurred to change your password.");
		}
		else
		{
			$response[]=array("code"=>"FAIL","message"=>"this username not found in our database.");
		}
	}
	else
	{
		$response[]=array("code"=>"FAIL","message"=>"invalid username.");
	}		
	$k=json_encode($response);	
	echo $k;
}

function convert_email($email)
{
	$em = explode("@",$email);
	$name = $em[0];
	$len = strlen($name);
	$showLen = floor($len/2);
	$str_arr = str_split($name);
	for($ii=$showLen;$ii<$len;$ii++){
		$str_arr[$ii] = '*';
	}
	$em[0] = implode('',$str_arr); 
	$new_name = implode('@',$em);
	return $new_name;
}
?>