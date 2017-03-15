<?php require_once('Connections/Connection.php'); ?>
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
?>
<?php
// *** Validate request to login to this site.
session_start();
session_cache_expire(20);
$inactive = 600; //10 mins in sec

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['User_Name'])) {
  $loginUsername=$_POST['User_Name'];
  $password=$_POST['User_Pwd'];
  $MM_fldUserAuthorization = "User_ID";
  $MM_redirectLoginSuccess = "telnet.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_Connection, $Connection);
  	
  $LoginRS__query=sprintf("SELECT User_Name, User_Pwd, User_ID FROM user_registration WHERE User_Name=%s AND User_Pwd=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $Connection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'User_ID');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
	$_SESSION['login_time'] = time();	      
    
	if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	$_SESSION['expire'] = $_SESSION['login_time'] + inactive;
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta http-equiv="refresh" content="60" />
<title>Cisco Router Admin</title>
<script type="text/javascript" src="media/jquery00.js"></script>
<script type="text/javascript" src="media/jquery1.js"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.size {
	font-size: medium;
}
</style>
</head>
<body onload="startTime()">
<table width="1085" border="1" align="center">
  <tr>
    <td width="1075" height="152" bgcolor="#000099"><img src="imagePSM/Cisco-Logo-Sml1.jpg" alt="" width="252" height="148" align="left" /></td>
  </tr>
  <tr>
    <td align="center">
    <div id="txt" align="left"></div>
    <h1>WELCOME TO CISCO ROUTER ADMIN</h1>
    <br/>
    <p><img src="imagePSM/54026-Router-Image.jpg" width="419" height="245" align="right" /></p>
    <br />
    <h2>Login to Cisco Router Admin</h2>
    <form ACTION="<?php echo $loginFormAction; ?>"  METHOD="POST"  name="login_form">
    <br />
    <label>Username: </label>
    <span id="sprytextfield1">
    <input name="User_Name" type="text" autofocus="autofocus" maxlength="20" />
    <span class="textfieldRequiredMsg">Username is required.</span></span><br/>
    <br /> 
    <label>Password: </label>
    <span id="sprypassword1">
    <input name="User_Pwd" type="password" maxlength="20" />
    <span class="passwordRequiredMsg">Password is required.</span></span><br/>
    <br/>
    <input name="submit" type="submit" value="Login" /> <a href="register.php">Register</a>
    </form>
    <br />
    <br />
    <br />
    </p>
    </td>
  <tr>
    <td height="34" align="center" bgcolor="#000099" /tr>
</table>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none");
</script>
</body>
</html>