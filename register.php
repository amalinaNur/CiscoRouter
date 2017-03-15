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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "register_form")) {
  $insertSQL = sprintf("INSERT INTO user_registration (User_Name, User_Email, User_Pwd) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['User_Name'], "text"),
                       GetSQLValueString($_POST['User_Email'], "text"),
                       GetSQLValueString($_POST['User_Pwd'], "text"));

  mysql_select_db($database_Connection, $Connection);
  $Result1 = mysql_query($insertSQL, $Connection) or die(mysql_error());

  $insertGoTo = "register_successful.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Connection, $Connection);
$query_User_Request = "SELECT * FROM user_registration";
$User_Request = mysql_query($query_User_Request, $Connection) or die(mysql_error());
$row_User_Request = mysql_fetch_assoc($User_Request);
$totalRows_User_Request = mysql_num_rows($User_Request);
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
    <h2>Registration of Cisco Router Admin</h2>
    <form action="<?php echo $editFormAction; ?>"  METHOD="POST"  name="register_form">
    <br />
    <label>Username: </label>
    <span id="sprytextfield1">
    <input name="User_Name" type="text" autofocus="autofocus" maxlength="20" />
    <span class="textfieldRequiredMsg">Username is required.</span></span><br/>
    <br /> 
    <label>Email: </label>
    <span id="sprytextfield1"><span id="sprytextfield2">
    <input name="User_Email" type="text" autofocus="autofocus" maxlength="20" />
    <span class="textfieldRequiredMsg">Email is required.</span></span><span class="textfieldRequiredMsg">Username is required.</span></span><br/>
    <br />
    <label>Password: </label>
    <span id="sprypassword1">
    <input name="User_Pwd" type="password" maxlength="20" />
    <span class="passwordRequiredMsg">Password is required.</span></span><br/>
    <br/>
    <input name="submit" type="submit" value="Register" />
    <input type="hidden" name="MM_insert" value="register_form" />
    </form>
    <br />
    <br />
    <br />
    </td>
  <tr>
    <td height="34" align="center" bgcolor="#000099" /tr>
</table>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>
</html>
<?php
mysql_free_result($User_Request);
?>
