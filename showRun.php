<?php require_once('Connections/Connection.php'); ?>
<?php require_once('Connections/Connection.php'); 
require_once('PHPTelnet.php');
?>
<?php
//Initialise the session
session_start();
if (!isset($_SESSION['host']))
{
$_SESSION['host'] = $_POST['host'];
$_SESSION['port'] = $_POST['port'];
$_SESSION['pass'] = $_POST['pass'];
}
?>
<?php
//initialize the session
//session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "login.php";
  if ($logoutGoTo) {
	session_destroy();
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_getSession = "-1";
if (isset($_GET['MM_Username'])) {
  $colname_getSession = $_GET['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_getSession = sprintf("SELECT * FROM user_registration WHERE User_Name = %s", GetSQLValueString($colname_getSession, "text"));
$getSession = mysql_query($query_getSession, $Connection) or die(mysql_error());
$row_getSession = mysql_fetch_assoc($getSession);
$totalRows_getSession = mysql_num_rows($getSession);

$colname_getSession = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getSession = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_getSession = sprintf("SELECT * FROM user_registration WHERE User_Name = %s", GetSQLValueString($colname_getSession, "text"));
$getSession = mysql_query($query_getSession, $Connection) or die(mysql_error());
$row_getSession = mysql_fetch_assoc($getSession);
?>
<?php
//session_start();
session_cache_expire(20);

if(!isset($_SESSION['MM_Username']))
{
	session_destroy();
	header("Location: login.php");
}
$_SESSION['login_time'] = time();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="60" />
<title>Show Configuration</title>
<link href="menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="media/jquery00.js"></script>
<script type="text/javascript" src="media/jquery1.js"></script>
<script>
<?php /*?>function mySave() 
{
	<?php $telnet = new PHPTelnet();
	  //$telnet->show_connect_error=0;
	  
	  // if the first argument to Connect is blank,
	  // PHPTelnet will connect to the local host via 127.0.0.1
	  $result = $telnet->Connect($host, $port, $pass);
	  
	  switch ($result) 
	  {
		case 0:
		  $telnet->DoCommand("enable", $out);
		  // NOTE: $result may contain newlines
		  
		  sleep(1);
	  
		  $telnet->DoCommand("$pass", $out);
		  
		  usleep(100000);
		  
		  $telnet->DoCommand("copy running-config startup-config startup-config", $out);
		  
		  sleep(1);
		  
		  $telnet->DoCommand("", $out);
		  echo $out."";
	  
		  usleep(100000);
		  
		  // say Disconnect(0); to break the connection without explicitly logging out
		  break;
	  }
	?>
}<?php */?>
</script>
</head>
<body onload="startTime()">
<table width="1085" border="1" align="center">
  <tr>
    <td width="252" height="148" bgcolor="#000099"><a href="main_menu.php"><img src="imagePSM/Cisco-Logo-Sml1.jpg" alt="" width="252" height="148" align="left" /></a></td>    
    <td width="833" bgcolor="#000099">
    </td>
  </tr>
  <tr>
    <td height="100" align="left" bgcolor="beige">
    <ul class="nav">
        <li>
        <h4 align="center">Hi <?php echo ucfirst($row_getSession['User_Name']); ?></h4>
        </li>
        <li><a href="hostname.php">Hostname</a>
        </li>
        <li><a href="default_router.php">Default IP Address Router</a>
        </li>
        <li><a href="telnet_router.php">Telnet Configuration</a>
        </li>
        <li><a href="showRun.php">Show Configuration</a>
        </li>
        <li><a href="showipintbr.php">Show IP Interface Fast Ethernet </a>
        </li>
        <li><a href="<?php echo $logoutAction ?>">Logout</a>
        </li>
        <h4 align="center" style="font-size:16px">
		<?php
        
        $telnet = new PHPTelnet();
        //$telnet->show_connect_error=0;
        $result = $telnet->Connect($host, $port, $pass);
        
		if($telnet == false)
        {
            echo("Status: Can't connect");
        }
        else
        {
            echo("Status: Successfully Connected !");
            $connected = true;
        }
        ?>
        </h4>
     </ul>
    </td>

<td valign="top" bgcolor="#FFFFFF">
      <div id="txt"></div>
      <h1 align="center">Cisco Router Admin</h1> 
      <input type="hidden" value="<?php echo $hostname;
	  $telnet = new PHPTelnet();
  //$telnet->show_connect_error=0;
  
  // if the first argument to Connect is blank,
  // PHPTelnet will connect to the local host via 127.0.0.1
  $result = $telnet->Connect($host, $port, $pass);
  
  switch ($result) {
  case 0:
	$telnet->DoCommand("enable", $out);
	// NOTE: $result may contain newlines
	
	sleep(1);

	$telnet->DoCommand("$pass", $out);
	
	usleep(100000);
	
	$telnet->DoCommand("configure terminal", $out);
	
	sleep(1);
	
	$telnet->DoCommand("", $out);
	echo $out."";

    usleep(100000);
	
	$telnet->DoCommand("hostname $hostname", $out);
    echo $out."";

    sleep(1);
	
	$telnet->DoCommand("exit", $out);
	echo $out."\n";
	
	usleep(100000);	
	
	// say Disconnect(0); to break the connection without explicitly logging out
	break;
  case 1:
	echo '[PHP Telnet] Connect failed: Unable to open network connection';
	break;
  case 2:
	echo '[PHP Telnet] Connect failed: Unknown host';
	break;
  case 3:
	echo '[PHP Telnet] Connect failed: Login failed';
	break;
  case 4:
	echo '[PHP Telnet] Connect failed: Your PHP version does not support PHP Telnet';
	break;
}?>" />
	  
      <input type="hidden" value="<?php /*echo $intFastEth;
	  echo $host;
	  echo $subneMask;
	  
	  $telnet = new PHPTelnet();
  $telnet->show_connect_error=0;*/
  
  // if the first argument to Connect is blank,
  // PHPTelnet will connect to the local host via 127.0.0.1
  /*$result = $telnet->Connect($host, $port, $pass);
  
  switch ($result) {
  case 0:
	$telnet->DoCommand("enable", $out);
	// NOTE: $result may contain newlines
	
	sleep(1);

	$telnet->DoCommand("$pass", $out);
	
	usleep(100000);
	
	$telnet->DoCommand("configure terminal", $out);
	
	sleep(1);
	
	$telnet->DoCommand("", $out);
	echo $out."";

    usleep(100000);
	
	$telnet->DoCommand("interface fastEthernet $intFastEth", $out);
	echo $out."";

    usleep(100000);
	
	$telnet->DoCommand("ip address $host $subnetMask", $out);
    echo $out."";

    sleep(1);
	
	$telnet->DoCommand("no shutdown", $out);
	echo $out."\n";
	
	usleep(100000);
	
	$telnet->DoCommand("exit", $out);
	echo $out."\n";
	
	sleep(1);
	
	$telnet->DoCommand("exit", $out);
	echo $out."\n";
	
	usleep(100000);*/
	
	// say Disconnect(0); to break the connection without explicitly logging out
	/*break;
  case 1:
	echo '[PHP Telnet] Connect failed: Unable to open network connection';
	break;
  case 2:
	echo '[PHP Telnet] Connect failed: Unknown host';
	break;
  case 3:
	echo '[PHP Telnet] Connect failed: Login failed';
	break;
  case 4:
	echo '[PHP Telnet] Connect failed: Your PHP version does not support PHP Telnet';
	break;
}*/?>" />

      <textarea name="command_prompt" wrap="on" readonly="readonly" class="boxShow"><?php
  
  $telnet = new PHPTelnet();
  //$telnet->show_connect_error=0;
  
  // if the first argument to Connect is blank,
  // PHPTelnet will connect to the local host via 127.0.0.1
  $result = $telnet->Connect($host, $port, $pass);
	
  switch ($result) {
  case 0:
	$telnet->DoCommand("enable", $out);
	// NOTE: $result may contain newlines
	//echo $out."\n";
	
	sleep(1);

	$telnet->DoCommand("$pass", $out);
	echo $out."\n";
	
	usleep(100000);
	
	$telnet->DoCommand("show run", $out);
	echo $out."\n";
		
	sleep(1);
	
	/*$telnet->DoCommand("", $out);
    echo $out."\n";

    usleep(100000);*/
	
	// say Disconnect(0); to break the connection without explicitly logging out
	break;
  case 1:
	echo '[PHP Telnet] Connect failed: Unable to open network connection';
	break;
  case 2:
	echo '[PHP Telnet] Connect failed: Unknown host';
	break;
  case 3:
	echo '[PHP Telnet] Connect failed: Login failed';
	break;
  case 4:
	echo '[PHP Telnet] Connect failed: Your PHP version does not support PHP Telnet';
	break;
}
?>
      </textarea>
      <input name="submit" type="submit" value="Save Configuration"  class="btn" />
    </td>
  </tr>
  <tr>
    <td align="center" bgcolor="#000099" /td>
    <td height="34" align="center" bgcolor="#000099" /td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($getSession);
?>
