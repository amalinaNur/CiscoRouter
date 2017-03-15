<?php require_once('Connections/Connection.php'); ?>
<?php require_once('Connections/Connection.php');
require_once('PHPTelnet.php'); ?>
<?php
//Initialise the session
session_start();

if (isset($_SESSION['host']))
{
	//Destroy the whole session
	$_SESSION = array();
	session_destroy();
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
if (isset($_SESSION['MM_Username'])) {
  $colname_getSession = $_SESSION['MM_Username'];
}
mysql_select_db($database_Connection, $Connection);
$query_getSession = sprintf("SELECT * FROM user_registration WHERE User_Name = %s", GetSQLValueString($colname_getSession, "text"));
$getSession = mysql_query($query_getSession, $Connection) or die(mysql_error());
$row_getSession = mysql_fetch_assoc($getSession);
$totalRows_getSession = mysql_num_rows($getSession);
?>
<?php
//session_start();
session_cache_expire(20);
$inactive = 600; //10 mins in sec

$_SESSION['login_time'] = time();	      

if(!isset($_SESSION['MM_Username']) || time() - $_SESSION['login_time'] > inactive) {
	header("Location: login.php");
	
}
else {
	$now = time();
	
	if($now > $_SESSION['expire']) {
		session_destroy();
		header("Location: login.php");
	}
}

?>
<?php
// define variables and set to empty values
$hostErr = $portErr = $passErr = "";
$host = $port = $pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  if (empty($_POST["host"])) 
  {
    $hostErr = "IP Addreess is required";
  } 
  else 
  {
    $host = test_input($_POST["host"]);
  }
  
  if (empty($_POST["port"])) 
  {
    $portErr = "Port Telnet is required";
  } 
  else 
  {
    $port = test_input($_POST["port"]);
  }
  
  if (empty($_POST["pass"])) 
  {
    $passErr = "Password is required";
  } 
  else 
  {
    $pass = test_input($_POST["pass"]);
  }
}
function test_input($data) 
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta http-equiv="refresh" content="60" />
<title>Telnet Router</title>
<script type="text/javascript" src="media/jquery00.js"></script>
<script type="text/javascript" src="media/jquery1.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>
<body onload="startTime()">
<table width="1085" border="1" align="center">
  <tr>
    <td width="1075" height="152" bgcolor="#000099"><img src="imagePSM/Cisco-Logo-Sml1.jpg" alt="" width="252" height="148" align="left" /></td>
  </tr>
  <tr>
    <td align="center">
    <div id="txt" align="left"></div>
    <p>&nbsp;</p>
    <h1>Cisco Router Admin</h1>
    <h3>Hi <?php echo ucfirst($row_getSession['User_Name']); ?></h3>
    <form action="<?php echo htmlspecialchars("main_menu.php");?>" METHOD="POST" name="telnet_router">
      <label>IP Address Router: </label>
      <span id="sprytextfield1">
      <input name="host" type="text" autofocus="autofocus" maxlength="15" value="<?php echo $host;?>" />
      <span class="textfieldRequiredMsg">IP adress router is required.</span></span><br/>
    <br/>
    <label>Port: </label>
    <span id="sprytextfield2">
    <input name="port" type="text" maxlength="2" value="<?php echo $port;?>" />
    <span class="textfieldRequiredMsg">Telnet port is required.</span></span><br/>
    <br/>
    <label>Password: </label>
    <span id="sprypassword1">
    <input name="pass" type="password" maxlength="10" value="<?php echo $pass;?>" />
    <span class="passwordValidMsg">Connection Error !<span class="passwordRequiredMsg">Password is required.</span></span></span><br/>
    <br/>
    
    <input type="submit" name="submit" value="Telnet" /> <a href="<?php echo $logoutAction ?>">Log out</a>
</form>
        <br />
        <br />
    	<br />
        <br />
    	<br />
      </div></td>
  </tr>
    <td height="34" align="center" bgcolor="#000099" /td>
    
</table>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysql_free_result($getSession);
?>
