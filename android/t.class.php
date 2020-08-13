<?php
class t
{
	//Constructor
	public function __construct()
	{
		session_start();							//Initiating Session
		date_default_timezone_set('Asia/Kolkata');	//Setting Timezone
		require_once('db.class.php');
		$d=new db();		
		$this->disable_old_sessions();		//Disabled old sessions
		$this->disable_expired_services();
	}
	
	//Destructor
	public function __destruct(){}
	
	//For Valid Mobile Number
	public function validMobile($s)
	{
		if(preg_match('#^[7-9]([0-9]){9}$#',$s))
		return true;
		else
		return false;
	}
	
	//FUNCTION TO GET BG
	public function getbg($s)
	{
		$today=date('Y-m-d');
		
		$d="SELECT * FROM callcolor WHERE edate='$today' AND sname='$s' LIMIT 0,1";
		$r=mysql_query($d) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			$k=mysql_fetch_array($r);
			return $k['color'];
		}
		else
		return '#FFFFFF';
	}
	
	//FUNCTION TO VALID EMAIL
	function validEmail($email) 
	{		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)===false)
		return true;
		else
		return false;
	}
	
	//FUNCTION TO GET VAL FROM INT
	function get_val_from_int($table,$col,$val,$req)
	{
		$sql="SELECT * FROM $table WHERE $col=$val";
		$r=mysql_query($sql) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			$d=mysql_fetch_array($r);
			return $d[$req];
		}
		else
		return 0;
		mysql_close($r);
	}
	
	//FUNCTION TO GET VAL FROM INT
	function get_val_from_var($table,$col,$val,$req)
	{
		$sql="SELECT * FROM $table WHERE $col='$val'";
		$r=mysql_query($sql) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			$d=mysql_fetch_array($r);
			return $d[$req];
		}
		else
		return 0;
		mysql_close($r);
	}
	
	//FUNCTION TO CHECK IS INT EXISTS IN TABLE
	function is_exists_int($table,$col,$val)
	{
		$sql="SELECT * FROM $table WHERE $col=$val";
		$r=mysql_query($sql) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
		mysql_close($r);
	}
	
	//FUNCTION TO CHECK IS VAR EXISTS IN TABLE
	function is_exists_var($table,$col,$val)
	{
		$sql="SELECT * FROM $table WHERE $col='$val'";
		$r=mysql_query($sql) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
		mysql_close($r);
	}
	
	//FUNCTION TO ADD NEW USER
	function add_new_client($name,$mobile,$email,$gcmid)
	{
		echo $gcmid;
		echo $name;
		echo $mobile;
		echo $email;
		
		$ip=$_SERVER['REMOTE_ADDR'];	//REMOTE IP
		
		if(!$this->is_exists_var('e_users','email',$email) && !$this->is_exists_int('e_users','mobile',$mobile))
		{
			$sql="INSERT INTO e_users (name,mobile,email,gcmid,dt,ip) VALUES ('$name',$mobile,'$email','$gcmid',now(),'$ip')";
			$r=mysql_query($sql) or die(mysql_error());
			if($r)
			{
				$cid=mysql_insert_id();
				
				//GENERATE PASSWORD
				$this->generate_password($cid);
				
				//INSERT INTO USER PROFILE
				$this->generate_profile($cid);
				
				//ENTRY INTO USER EMAIL/MOBILE VERIFICATION
				$t2="INSERT INTO e_user_verification (cid,dt,ip) VALUES ($cid,now(),'$ip')";
				mysql_query($t2) OR die(mysql_error());
				
				//SEND EMAIL VERIFICATION CODE
				$this->send_email_verification_code($cid);
				
				//GENERATE FREE TRIAL
				//$this->generate_ft($cid);
				
				//return true;
			}
		}
		else
		{
			//return false;
		}
	}
	
	//FUNCTION TO SEND SMS
	public function send_sms($n,$msg)
	{
		$msg.="\r\n\r\nLiveTips Algo Services\r\nLiveTips.in";
		$url='http://alerts.sinfini.com/api/web2sms.php?workingkey=79546846t34350082811&sender=LiveTp&to='.$n.'&message='.urlencode($msg);			
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);
	}
	
	//FUNCTION TO RECORD SMS LOG
	public function record_sms_log($cid,$sms_t,$type)
	{
		$ip=$_SERVER['REMOTE_ADDR'];	//REMOTE IP
		$sql="INSERT INTO e_sms_logs (cid,sms_text,sms_type,dt,ip) VALUES ($cid,'$sms_t','$type',now(),'$ip')";
		mysql_query($sql) or die(mysql_error());
	}
	
	//FUNCTION TO GENERATE PASSWORD
	public function generate_password($cid)
	{
		$ip=$_SERVER['REMOTE_ADDR'];	//REMOTE IP
		
		if(!$this->is_exists_int('e_user_login','cid',$cid))
		{
			$random=rand(999999,000000);
			$mobile=$this->get_val_from_int('e_users','id',$cid,'mobile');
			
				
			$t1="INSERT INTO e_user_login (cid,passw,dt,ip) VALUES ($cid,PASSWORD('$random'),now(),'$ip')";
			$r1=mysql_query($t1) OR die(mysql_error());
			if($r1)
			{
				//SEND VERIFICATION EMAIL & USERNAME/PASSWORD THROUGH SMS
				$sms_t="Thanks for registration.\r\nYour Login Detail:\r\nUsername: $mobile\r\nPassword: $random\r\nPlease verify your email also to receive call on email.";
				$this->send_sms($mobile,$sms_t);	//Sending SMS
				$this->record_sms_log($cid,$sms_t,'NEW REGISTRATION'); //Record SMS Log
			}			
		}
	}
	
	//FUNCTION TO SEND EMAIL VERIFICATION CODE
	public function send_email_verification_code($cid)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		if($this->is_exists_int('e_email_code','cid',$cid))
		{
			$status=$this->get_val_from_int('e_email_code','cid',$cid,'status');
			if($status==0)	//NOT VERIFIED
			{
				$vcode=$this->get_val_from_int('e_email_code','cid',$cid,'email_vcode');
				$email=$this->get_val_from_int('e_users','id',$cid,'email');
				$name=$this->get_val_from_int('e_users','id',$cid,'name');
				
				$this->send_verification_mail($vcode,$email,$name);
				
				$t="UPDATE e_email_code SET sent_on=now(),dt=now(),ip='$ip' WHERE cid=$cid";
				mysql_query($t) or die(mysql_error());
			}
		}
		else if(!$this->is_exists_int('e_email_code','cid',$cid))
		{
			$vcode=base64_encode(md5(rand(99999999,00000000)));
			$email=$this->get_val_from_int('e_users','id',$cid,'email');
			$name=$this->get_val_from_int('e_users','id',$cid,'name');
				
			$this->send_verification_mail($vcode,$email,$name);
			$t="INSERT INTO e_email_code (cid,email_vcode,sent_on,dt,ip) VALUES ($cid,'$vcode',now(),now(),'$ip')";
			mysql_query($t) or die(mysql_error());
		}
	}
	
	//FUNCTION TO SEND VERIFICATION MAIL
	public function send_verification_mail($vcode,$email,$name)
	{
		$name=ucwords($name);
		require_once 'mandrill/Mandrill.php';
		$mandrill = new Mandrill('4uuh0UMZtO_p0vgxRMnKLw');
		$message = new stdClass();
		$message->html ="<!doctype html>
							<html>
								<head>
									<title>LiveTips Verification Email</title>
								</head>
								<body style='BACKGROUND: #f90; MARGIN: 0px auto; WIDTH: 650px'>
								<DIV style='TEXT-ALIGN:center;border-bottom:2px solid #fff;'><IMG src='http://www.LiveTips.biz/images/LiveTips.gif'> </DIV>
								
								<div style='padding:25px;text-align:justify; color:#fff; font-family:Calibri;'>
									<h2>Dear $name,</h2>
									<h3>Welcome to LiveTips Market Research,<br>To Verify your email address kindly click on the given link</h3>
									
									<div style='padding:5px; text-align:center; background:#fff;'>
										<a href='http://www.LiveTips.in/verify.php?email=$email&verificationcode=$vcode' target='_blank'>CLICK HERE TO VERIFY YOUR EMAIL</a><br><br>
										<small style='color:#ff0000;align:left;'>*To activate your Free Trial kindly login once using given username/password sent through SMS<br>If SMS not received, Kindly call on given numbers.</small>
									</div>
								</div>
								
								
								
								<div style='text-align:center;border-top:2px solid #fff;'>
									<div>
										<a href='https://plus.google.com/117912351834993026978' target=_new><IMG height=35 src='http://rayhiltz.com/wp-content/uploads/2014/05/gplus-icon.png' width=35 ;></A>
										<A href='https://twitter.com/LiveTps' target=_new><IMG height=35 src='http://getfansinstantly.com/images/twitter_image.png' width=35 ;></A> <A href='https://www.facebook.com/pages/LiveTips-Market-Research-Pvt-Ltd/117639511621534' target=_new><IMG height=35 src='http://www.engagesciences.com/images/facebook.png' width=35 ;></A>
										<a href='#'><IMG src='http://markusmenzel.de/android/media/kunena/category_images/29_off_childsmall.gif'></A>
									</div>
									<div style='FONT-SIZE: 14px; FONT-FAMILY: Calibri'>
										<a href='http://www.livetips.biz' target=_new>LiveTips</a> | 										
										<a href='http://www.livetips.biz/terms-use.php' target=_new>Terms of Use</a> | 
										<a href='http://www.livetips.biz/privacy-policy.php' target=_new>Privacy Policy</a> <br>
										<b style='COLOR: #090909'>(Call : 0751-6002502,6005560,6002500 | Mob.:+91-8982418000 &amp; 7415178000)</b>
									</div>
								</div>
								</body>
							</html>";
		$message->text ="";
		$message->subject = "Verification mail";
		$message->from_email = "verify@livetips.in";
		$message->from_name  = "LiveTips Market Research";
		$message->to = array(array("email"=>$email));
		$message->track_opens = true;

		$response = $mandrill->messages->send($message);
	}
	
	//FUNCTION TO GET TABLE ARRAY
	public function get_table_array($table)
	{
		$p=array();
		
		$sql="SELECT * FROM $table";
		$r=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_assoc($r))
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
		$r=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_assoc($r))
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
		$r=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_assoc($r))
			{
				$p[]=$d;
			}
		}		
		return $p;
	}
	
	//FUNCTION TO GENERATE FREE TRIAL
	public function generate_ft($cid)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$product_id=$this->get_table_array('e_products');		
		
		$t=date('Y-m-d');
		$today=$this->get_start_date(date($t));					//Start Date
		$edate=$this->get_end_date($today);						//End Date
		
		//echo "Start Date : $today<br>";
		//echo "End Date : $edate<br>";
		
		$lid=array();	//Service Record ID	
		$wpid=array();	//Working Product ID
		$ptid=array(2,6);	//Working Platform ID
		
		if(!empty($product_id))
		{
			foreach($product_id AS $i)
			{
				if($i['status']==1)
				{
					$wpid[]=$i['id'];
				}
			}
		}
		
		if(!empty($wpid))
		{
			foreach($wpid AS $j)
			{
				$t="SELECT * FROM e_user_service_records WHERE cid=$cid AND product_id=$j AND payment_id=0 AND start_date='$today' AND end_date='$edate'";
				$q=mysql_query($t) OR die(mysql_error());
				if(mysql_num_rows($q)>0)
				{
					//If Already exists, Do Nothing
				}
				else
				{
					//Generate Free Trial
					$service_type=1;	//FREE
					$t="INSERT INTO e_user_service_records (cid,product_id,payment_id,service_type,start_date,end_date,dt,ip) VALUES ($cid,$j,0,$service_type,'$today','$edate',now(),'$ip')";
					$r=mysql_query($t) OR die(mysql_error());
					if($r)
					{
						$lid[]=mysql_insert_id();
						//echo "Trial Done for ".mysql_insert_id();
					}
				}
			}
		}
		
		//echo '<pre>';
			//print_r($lid);
		//echo '</pre>';
		
		if(!empty($lid))
		{
			foreach($lid AS $l)
			{
				foreach($ptid AS $i)
				{
					$t="SELECT * FROM e_user_service_platform_records WHERE sid=$l AND platform_id=$i";
					$m=mysql_query($t) or die(mysql_error());
					if(mysql_num_rows($m)>0)
					{
						//Already exists do nothing						
					}
					else
					{
						$b="INSERT INTO e_user_service_platform_records (sid,platform_id,dt,ip,status) VALUES ($l,$i,now(),'$ip',1)";
						if(mysql_query($b) or die(mysql_error()))
						{
							//echo "Trial Done for $i | $l";
						}
					}
				}
			}
		}
	}
	
	//FUNCTION TO GET END DATE
	public function get_end_date($sdate)
	{
		$edate='';
		$d=strtoupper(date('l',strtotime($sdate)));		
		
		switch($d)
		{
			case 'MONDAY':
			$edate=date('Y-m-d',strtotime("+1 day",strtotime($sdate)));
			break;
			
			case 'TUESDAY':
			$edate=date('Y-m-d',strtotime("+1 day",strtotime($sdate)));
			break;
			
			case 'WEDNESDAY':
			$edate=date('Y-m-d',strtotime("+1 day",strtotime($sdate)));
			break;
			
			case 'THURSDAY':
			$edate=date('Y-m-d',strtotime("+1 day",strtotime($sdate)));
			break;
			
			case 'FRIDAY':
			$edate=date('Y-m-d',strtotime("+3 day",strtotime($sdate)));
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
	
	//FUNCTION FOR ALERT BOX
	public function alert_box($redirect, $msg)
	{
		if($redirect!='')
		{
			if($msg!='')
			{
				echo ("<SCRIPT LANGUAGE='JavaScript'>
        				window.alert('$msg')
						window.location.href='$redirect'
						</SCRIPT>");
			}
			else
			{
				header('location:'.$redirect);
			}
		}
	}
	
	//Function to check Valid Login
	function validLogin($user,$pass,$gcmid)
	{
		echo $user.$pass,$gcmid;
		if($this->validMobile($user))
		{
			$cid=$this->get_val_from_int('e_users','mobile',$user,'id');
			
			$sql="SELECT * FROM e_user_login WHERE cid=$cid AND passw=PASSWORD('$pass') AND status=1";
			$m=mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($m)>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	//Function to verify user mobile
	public function verify_user_mobile($cid)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$t="UPDATE e_user_verification SET mv_dt=now(),mv_ip='$ip' WHERE cid=$cid";
		mysql_query($t) OR die(mysql_error());
	}
	
	//Function to generate session_cache_expire
	public function generateSession($cid,$pass)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		$today=date('Y-m-d');
		$passw=$this->encrypt_session($pass);
		$s=$cid.','.$passw.','.$today;
		$se=$this->encrypt_session($s);
		
		$t1="SELECT * FROM e_user_login_records WHERE cid=$cid AND session_id='$se' AND session_date='$today' AND status=1";
		$r1=mysql_query($t1) OR die(mysql_error());
		if(mysql_num_rows($r1)>1)
		{
			$n="UPDATE e_user_login_records SET status=0 WHERE cid=$cid AND session_id='$se' AND session_date='$today'";
			mysql_query($n);
		}
		else
		{
			$t2="INSERT INTO e_user_login_records (cid,session_id,session_date,dt,ip,status) VALUES ($cid,'$se','$today',now(),'$ip',1)";
			if(mysql_query($t2) OR die(mysql_error()))
			{
				return $se;
			}
			
		}
	}
	
	//Function to encrypt session
	public function encrypt_session($s)
	{
		return base64_encode(base64_encode(base64_encode(base64_encode(base64_encode($s)))));
	}
	
	//Function to decrypt session
	public function decrypt_session($s)
	{
		return base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($s)))));
	}
	
	//Check Session Variable
	public function checkSession()
	{
		if(isset($_SESSION['key']) && $_SESSION['key']!='')
		{
			$key=$_SESSION['key'];
			$m=$this->decrypt_session($key);
			$i=explode(',',$m);
			$cid=$i[0];
			$pass=$this->decrypt_session($i[1]);
			$user=$this->get_val_from_int('e_users','id',$cid,'mobile');
			$status=$this->get_val_from_int('e_user_login_records','cid',$cid,'status');
			
			if($this->validLogin($user,$pass))
			{				
				if($this->checkSession1($cid))
				{
					return true;
					//Do Nothing
				}
				else
				{
					if(basename($_SERVER['PHP_SELF'])!='login.php')
					$this->alert_box('login.php','Multiple session found.');
				}				
			}
			else
			{
				if(basename($_SERVER['PHP_SELF'])!='login.php')
				$this->alert_box('login.php','Invalid username/password.');
			}
		}
		else
		{
			if(basename($_SERVER['PHP_SELF'])!='login.php')
			$this->alert_box('login.php','Session error!!!.');
		}
	}
	
	//Function check multiple sessions
	public function checkSession1($cid)
	{
		$today=date('Y-m-d');
		$t="SELECT * FROM e_user_login_records WHERE cid=$cid AND session_date='$today' AND status=1";
		$m=mysql_query($t) OR die(mysql_error());
		$row=mysql_num_rows($m);
		if($row==1)
		{			
			return true;
		}
		else if($row>1)
		{
			$t1="UPDATE e_user_login_records SET status=0 WHERE cid=$cid";
			if(mysql_query($t1))
			{
				return false;
			}
		}
		else if($row==0)
		{
			return false;
		}
		
	}
	
	//Disable old sessions
	private function disable_old_sessions()
	{
		$today=date('Y-m-d');
		$sql="UPDATE e_user_login_records SET status=0 WHERE session_date!='$today'";
		mysql_query($sql) OR DIE(mysql_error());
	}
	
	//Disable old sessions
	private function disable_expired_services()
	{
		$today=date('Y-m-d');
		$sql="UPDATE e_user_service_records SET status=0 WHERE end_date<'$today'";
		mysql_query($sql) OR DIE(mysql_error());
	}
	
	//FUNCTION TO GET WORKING NUMBERS FROM PID
	public function get_working_numbers_product_id($pid)
	{
		$n=$cid=$lid=$l=array();
		
		$platform=1;	//SMS
		
		//GETTING WORKING SERVICE ID FROM SERVICE RECORDS
		$t="SELECT * FROM e_user_service_records WHERE product_id=$pid AND status=1";
		$r=mysql_query($t) OR DIE(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_array($r))
			{
				$lid[]=$d['id'];
			}
		}
		
		//GETTING WORKING SERVICE ID FROM WORKING PLATFORM RECORDS
		if(!empty($lid))
		{
			$m=implode(',',$lid);
			$t="SELECT * FROM e_user_service_platform_records WHERE sid IN ($m) AND platform_id=$platform AND status=1";
			$r=mysql_query($t) OR DIE(mysql_error());
			if(mysql_num_rows($r)>0)
			{
				while($d=mysql_fetch_array($r))
				{
					$l[]=$d['sid'];
				}
			}
		}
		
		//GETTING CID FROM LID
		if(!empty($l))
		{
			foreach($l AS $j)
			{
				$cid[]=$this->get_val_from_int('e_user_service_records','id',$j,'cid');
			}
		}
		
		if(!empty($cid))
		{
			foreach ($cid AS $c)
			{
				$n[]=$this->get_val_from_int('e_users','id',$c,'mobile');
			}
		}
		return $n;		
	}
	
	//FUNCTION TO GET WORKING EMAILS
	public function get_working_email_product_id($pid)
	{
		$n=$cid=$lid=$l=array();
		
		$platform=6;	//SMS
		
		//GETTING WORKING SERVICE ID FROM SERVICE RECORDS
		$t="SELECT * FROM e_user_service_records WHERE product_id=$pid AND status=1";
		$r=mysql_query($t) OR DIE(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_array($r))
			{
				$lid[]=$d['id'];
			}
		}
		
		//GETTING WORKING SERVICE ID FROM WORKING PLATFORM RECORDS
		if(!empty($lid))
		{
			$m=implode(',',$lid);
			$t="SELECT * FROM e_user_service_platform_records WHERE sid IN ($m) AND platform_id=$platform AND status=1";
			$r=mysql_query($t) OR DIE(mysql_error());
			if(mysql_num_rows($r)>0)
			{
				while($d=mysql_fetch_array($r))
				{
					$l[]=$d['sid'];
				}
			}
		}
		
		//GETTING CID FROM LID
		if(!empty($l))
		{
			foreach($l AS $j)
			{
				$cid[]=$this->get_val_from_int('e_user_service_records','id',$j,'cid');
			}
		}
		
		if(!empty($cid))
		{
			foreach ($cid AS $c)
			{
				if($this->get_val_from_int('e_email_code','cid',$c,'status')==1)
				{
					$n[]=$this->get_val_from_int('e_users','id',$c,'email');
				}
			}
		}
		return $n;		
	}
	
	//FUNCTION TO SEND CALL EMAIL	
	public function send_call_mail($call,$email,$name,$script)
	{
		$name=ucwords($name);
		require_once 'mandrill/Mandrill.php';
		$mandrill = new Mandrill('4uuh0UMZtO_p0vgxRMnKLw');
		$message = new stdClass();
		$now=date('Y-m-d H:i:s');
		$message->html ="<!doctype html>
							<html>
								<head>
									<title>LiveTips Message Email</title>
								</head>
								<body style='BACKGROUND: #f90; MARGIN: 0px auto; WIDTH: 650px'>
								<DIV style='TEXT-ALIGN:center;border-bottom:2px solid #fff;'><IMG src='http://www.LiveTips.biz/images/LiveTips.gif'> </DIV>
								
								<div style='padding:25px;text-align:justify; color:#fff; font-family:Calibri;'>
									<h2>Dear $name,</h2>
									<h3>Here is the latest call in $script</h3>
									
									<div style='padding:5px; text-align:center; background:#fff; color:#000; font-size:16px;'>
										$call<br><br><b>$now</b>
									</div>
								</div>
								
								<div class='row' style='width:auto;text-align:center;padding:5px;background:#fff;'>		
									<table width='80%' align='center' class='table'>
									<tr>
										<td style='height:20px;width:100px;background:#478A47;color:#fff; text-align:center;'><b>Strong Up</b></td>
										<td style='height:20px;width:150px;background:#FF9933;color:#fff; text-align:center;'><b>Weak Up | Weak Down</b></td>
										<td style='height:20px;width:100px;background:#FF0000;color:#fff; text-align:center;'><b>Strong Down</b></td>
									</tr>
									</table>
								</div>		
								
								<div style='text-align:center;border-top:2px solid #fff;'>
									<div>
										<a href='https://plus.google.com/117912351834993026978' target=_new><IMG height=35 src='http://rayhiltz.com/wp-content/uploads/2014/05/gplus-icon.png' width=35 ;></A>
										<A href='https://twitter.com/LiveTps' target=_new><IMG height=35 src='http://getfansinstantly.com/images/twitter_image.png' width=35 ;></A> <A href='https://www.facebook.com/pages/LiveTips-Market-Research-Pvt-Ltd/117639511621534' target=_new><IMG height=35 src='http://www.engagesciences.com/images/facebook.png' width=35 ;></A>
										<a href='#'><IMG src='http://markusmenzel.de/android/media/kunena/category_images/29_off_childsmall.gif'></A>
									</div>
									<div style='FONT-SIZE: 14px; FONT-FAMILY: Calibri'>
										<a href='http://www.livetips.biz' target=_new>LiveTips</a> | 										
										<a href='http://www.livetips.biz/terms-use.php' target=_new>Terms of Use</a> | 
										<a href='http://www.livetips.biz/privacy-policy.php' target=_new>Privacy Policy</a> <br>
										<b style='COLOR: #090909'>(Call : 0751-6002502,6005560,6002500 | Mob.:+91-8982418000 &amp; 7415178000)</b>
									</div>
								</div>
								</body>
							</html>";
		$message->text ="";
		$message->subject = "$script Message - LiveTips";
		$message->from_email = "no-reply@livetips.in";
		$message->from_name  = "LiveTips Market Research";
		$message->to = array(array("email"=>$email));
		$message->track_opens = true;

		$response = $mandrill->messages->send($message);
	}
	
	//Function to change mobile number in start_date
	public function convert_mobile_to_star($m)
	{
		$str =$m;
		$len = strlen($str);
		$str1 = '';
		for($i=0; $i < $len; $i++) {
		   if($i<6) {
			   $str1 .= '*';
		   } else {
			   $str1 .= $str[$i];
		   }
		}
		return $str1;
	}
	
	//FUNCTION TO GET ACTIVE PLATFORM BY SID
	public function get_active_platform_sid($sid)
	{
		$q=array();
		
		$sql="SELECT * FROM e_user_service_platform_records WHERE sid=$sid";
		$r=mysql_query($sql) OR DIE(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_array($r))
			{
				$q[]=$d['platform_id'];
			}
		}
		
		return $q;
	}
	
	//Function to reset password
	public function reset_password($u)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		if($this->validMobile($u))
		{
			if($this->is_exists_int('e_users','mobile',$u))
			{
				$cid=$this->get_val_from_int('e_users','mobile',$u,'id');
				
				$p=rand(99999999,00000000);
				$t="UPDATE e_user_login SET passw=PASSWORD('$p'),dt=now(),ip='$ip' WHERE cid=$cid";
				if(mysql_query($t))
				{
					$sms_t="Password has been successfully changed.\r\nYour Login Detail:\r\nUsername: $u\r\nPassword: $p";
					$this->send_sms($u,$sms_t);	//Sending SMS
					$this->record_sms_log($cid,$sms_t,'PASSWORD CHANGED'); //Record SMS Log
				
					//CLOSE ALL ACTIVE SESSION.
					mysql_query("UPDATE e_user_login_records SET status=0 WHERE cid=$cid");
				}
				return true;
			}
			else
			return false;
		}
		else
		return false;
	}
	
	//Function to show Indian States
	public function indian_states()
	{
		$st=array(
		'Andhra Pradesh'=>'Andhra Pradesh',
		'Arunachal Pradesh'=>'Arunachal Pradesh',
		'Assam'=>'Assam',
		'Bihar'=>'Bihar',
		'Chhattisgarh'=>'Chhattisgarh',
		'Goa'=>'Goa',
		'Gujarat'=>'Gujarat',
		'Haryana'=>'Haryana',
		'Himachal Pradesh'=>'Himachal Pradesh',
		'Jammu and Kashmir'=>'Jammu and Kashmir',
		'Jharkhand'=>'Jharkhand',
		'Karnataka'=>'Karnataka',
		'Kerala'=>'Kerala',
		'Madhya Pradesh'=>'Madhya Pradesh',
		'Maharashtra'=>'Maharashtra',
		'Manipur'=>'Manipur',
		'Meghalaya'=>'Meghalaya',
		'Mizoram'=>'Mizoram',
		'Nagaland'=>'Nagaland',
		'Odisha'=>'Odisha',
		'Punjab'=>'Punjab',
		'Rajasthan'=>'Rajasthan',
		'Sikkim'=>'Sikkim',
		'Tamilnadu'=>'Tamilnadu',
		'Telangana'=>'Telangana',
		'Tripura'=>'Tripura',
		'Uttar Pradesh'=>'Uttar Pradesh',
		'Uttarakhand'=>'Uttarakhand',
		'West Bengal'=>'West Bengal'
		);
		return $st;
	}
	
	//Function to get working product ID from cid
	public function get_working_productid_cid($cid)
	{
		$result=array();
		
		$sql="SELECT * FROM e_user_service_records AS a, e_user_service_platform_records AS b WHERE a.id=b.sid AND b.platform_id=2 AND a.cid=$cid AND a.status=1 AND b.status=1";
		$r=mysql_query($sql) OR die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_array($r))
			{
				$result[]=$d['product_id'];
			}
		}
		
		return $result;
	}
	
	//Function to check Old FT
	public function checking_ft($cid)
	{
		if($this->is_exists_int('e_users','id',$cid))
		{
			$service_type=1;	//FREE TRIAL
			
			$d="SELECT * FROM e_user_service_records WHERE cid=$cid AND service_type=$service_type";
			$r=mysql_query($d) OR die(mysql_error());
			if(mysql_num_rows($r)>0)
			{
				return false;				
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	//Generate profile
	public function generate_profile($cid)
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		mysql_query("INSERT INTO e_user_profile (cid,dt,ip) VALUES ($cid,now(),'$ip')");		
	}
	
	//GET FT DETAILS
	public function get_ft_details($cid)
	{
		$result=array();
		
		$d="SELECT * FROM e_user_service_records WHERE cid=$cid AND service_type=1 GROUP BY start_date";
		$r=mysql_query($d) or die(mysql_error());
		if(mysql_num_rows($r)>0)
		{
			while($d=mysql_fetch_array($r))
			{
				$result[]=$this->get_val_from_int('e_service_type','id',$d['service_type'],'sr_type').','.$d['start_date'].','.$d['end_date'].','.$d['status'];
			}
		}
		mysql_free_result($r);
		
		return $result;
	}
	
	
	//Checking valid yyyy-mm-dd date
	function isValidDateyyyymmdd($date)
	{
		if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches))
		{
			if(checkdate($matches[2], $matches[3], $matches[1]))
			{
				return true;
			}
		}
	}
	
	//Function get remaining SMS
	public function sms_remain($cid)
	{
		$service_type=2;	//PAID
		$t=$this->get_table_array_int('e_user_service_records','service_type',$service_type);
		$p_id=array();
		$sms=$n_sms=0;
		
		if(!empty($t))
		{
			foreach ($t AS $i)
			{
				if($i['status']==1)
				{
					$p_id[]=$i['payment_id'];
				}
			}
		}
		
		if(!empty($p_id))
		{
			$p=array_unique($p_id);
			print_r($p);
			foreach($p AS $i)
			{
				$pid=$this->get_val_from_int('e_payment_records','id',$i,'package_id');
				$sms+=$this->get_val_from_int('e_packages','id',$pid,'p_sms');
			}
		}
		
		$s_sms=$this->get_table_array_int('e_sms_logs','cid',$cid);
		
		if(!empty($s_sms))
		{
			$j=0;
			foreach($s_sms AS $i)
			{
				if($i['sms_type']=='CALL')
				$j++;
			}
			$n_sms=$j;
		}
		
		echo "SMS : $sms<br>";
		echo "Sent SMS : $n_sms<br>";
		echo "Remain : $sms-$n_sms";
	}
}
?>