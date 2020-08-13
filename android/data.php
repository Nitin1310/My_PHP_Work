<?php
if(isset($_REQUEST['action']) && $_REQUEST['action']=='GETDATA')
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
				
					$t->generate_ft($cid);	
					$a_services=array();	//Available Service
					$sql=mysql_query("SELECT * FROM e_user_service_records WHERE cid=$cid") OR DIE(mysql_error());
					if(mysql_num_rows($sql)>0)
					{
						while($j=mysql_fetch_array($sql))
						{
							$a_services[]='\''.$t->get_val_from_int('e_products','id',$j['product_id'],'product_name').'\'';
						}
					}
					
					if(!empty($a_services))
					{
						$response=array_unique($a_services);
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
	
	$data=array();
	
	if(!empty($response))
	{
			$edate=date('Y-m-d');
			
			$sql=mysql_query("SELECT * FROM autocall WHERE edate='$edate' ORDER BY id desc") or die(mysql_error());
			if(mysql_num_rows($sql)>0)
			{
				while($j2h=mysql_fetch_assoc($sql))
				{
					$q=mysql_query("SELECT * FROM callcolor WHERE sname='$j2h[sname]' AND EDATE='$edate'") OR DIE(mysql_error());
					if(mysql_num_rows($q)>0)
					{
						$j2m=mysql_fetch_array($q);
						$j2h['color']=$j2m['color'];
					}					
					//array_push($j2h,array('color'=>$color));
					$data[]=$j2h;
					
				}
			}
	}
	
	$k=json_encode($data);
	
	echo $k;
}
?>