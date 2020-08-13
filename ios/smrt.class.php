<?php

class smrt
{
	
	//Constructor
	public function __construct()
	{
		ini_set('max_execution_time',0);
		set_time_limit(0);
		session_start();							//Initiating Session	
		date_default_timezone_set('Asia/Kolkata');	//Setting Timezone	
		require_once('db.class.php');
		$d=new db();		
		$this->disable_old_sessions();		//Disabled old sessions
		$this->disable_expired_services();
		
	}

	function add_new_client($name,$mobile,$email,$gcmid)
	{
		date_default_timezone_set('Asia/Kolkata');	//Setting Timezone
		$ip=$_SERVER['REMOTE_ADDR'];	//REMOTE IP		
		
		
		if(!$this->is_exists_var('e_users','email',$email) && !$this->is_exists_int('e_users','mobile',$mobile))
		{
			$now=date('Y-m-d H:i:s');
			$sql="INSERT INTO e_users (name,mobile,email,gcmid,dt,ip) VALUES ('$name',$mobile,'$email','$gcmid','$now','$ip')";
			$r=mysqli_query($sql) or die(mysqli_connect_error());
			if($r)
			{
				$cid=mysqli_insert_id();
				
				//GENERATE PASSWORD
				$pass=$this->generate_password($cid);
				
				//INSERT INTO USER PROFILE
				$this->generate_profile($cid);
				
				//ENTRY INTO USER EMAIL/MOBILE VERIFICATION
				$t2="INSERT INTO e_user_verification (cid,dt,ip) VALUES ($cid,'$now','$ip')";
				mysqli_query($t2) OR die(mysqli_connect_error());
				
				//SEND EMAIL VERIFICATION CODE
				$this->send_email_verification_code($cid);
				
				//GENERATE FREE TRIAL
				//$this->generate_ft($cid);
				
				return $pass;
			}
		}
		else
		{
			return false;
		}
	}

	public function get_table_array($table)
	{
		$p=array();
		
		$sql="SELECT * FROM $table";
		$r=mysqli_query($sql) or die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			while($d=mysqli_fetch_assoc($r))
			{
				$p[]=$d;
			}
		}		
		return $p;
	}
	
	//FUNCTION TO GET TABLE ARRAY FROM INT
	public function get_table_array_int($table,$col,$val)
	{
		$p=array();
		$sql="SELECT * FROM $table WHERE $col=$val";
		$r=mysqli_query($sql) or die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			while($d=mysqli_fetch_assoc($r))
			{
				$p[]=$d;
			}
		}		
		return $p;
	}
	
	//FUNCTION TO GET TABLE ARRAY FROM VAR
	public function get_table_array_var($table,$col,$val)
	{
		$p=array();
		
		$sql="SELECT * FROM $table WHERE $col='$val'";
		$r=mysqli_query($sql) or die(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			while($d=mysqli_fetch_assoc($r))
			{
				$p[]=$d;
			}
		}		
		return $p;
	}

	public function get_end_date($sdate)
	{
		$edate='';
		$d=strtoupper(date('l',strtotime($sdate)));		
		
		switch($d)
		{
			case 'MONDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'TUESDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'WEDNESDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'THURSDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'FRIDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'SATURDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
			
			case 'SUNDAY':
			$edate=date('Y-m-d',strtotime("+180 day",strtotime($sdate)));
			break;
		}
		return $edate;
	}
	
	//FUNCTION TO GET END DATE
	public function get_start_date($sdate)
	{
		$edate='';
		$d=strtoupper(date('l',strtotime($sdate)));		
		
		switch($d)
		{
			case 'MONDAY':
			$edate=$sdate;
			break;
			
			case 'TUESDAY':
			$edate=$sdate;
			break;
			
			case 'WEDNESDAY':
			$edate=$sdate;
			break;
			
			case 'THURSDAY':
			$edate=$sdate;
			break;
			
			case 'FRIDAY':
			$edate=$sdate;
			break;
			
			case 'SATURDAY':
			$edate=date('Y-m-d',strtotime("+2 day",strtotime($sdate)));
			break;
			
			case 'SUNDAY':
			$edate=date('Y-m-d',strtotime("+1 day",strtotime($sdate)));
			break;
		}
		return $edate;
	}
	
	public function get_active_platform_sid($sid)
	{
		$q=array();
		
		$sql="SELECT * FROM e_user_service_platform_records WHERE sid=$sid";
		$r=mysqli_query($sql) OR DIE(mysqli_connect_error());
		if(mysqli_num_rows($r)>0)
		{
			while($d=mysqli_fetch_array($r))
			{
				$q[]=$d['platform_id'];
			}
		}
		
		return $q;
	}
}
?>