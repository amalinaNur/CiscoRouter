<?php require_once('Connections/Connection.php'); ?>
<?php require_once('Connections/Connection.php'); 
?>
<?php
require_once('PHPTelnet.php');
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
<title>Main Menu</title>
<link href="menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="media/jquery00.js"></script>
<script type="text/javascript" src="media/jquery1.js"></script>
<style type="text/css">
.font-mm {
	font-size: medium;
	text-align: center;
	font-weight: normal;
	text-transform: none;
	line-height: 30px;
}
</style>
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
        <li><h4 align="center">Hi <?php echo ucfirst($row_getSession['User_Name']); ?></h4>
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
        <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
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
<td align="center">
      <div class="scroll">
       <div id="txt" align="left"></div>
        <h1 align="center">Cisco Router Admin</h1>
        <p align="center"> 
          <span class="font-mm">Welcome to the Cisco Router Admin System. Cisco Router Admin System is developed as an alternative way to configure Cisco 2800 Series Router without using command-line. This system is developed especially for the beginners who are the first time to configure Cisco Router. This system is compatible for Cisco 2800 Series Router only. Cisco Router Admin provide a basic configuration where the hostname of the router and interface address of the router can be changed. Besides that, Cisco Router Admin provides Telnet configuration to change the Telnet password and can view the configuration of the router. 
          </span>
       </p>
        <br />      
        <p align="center"> 
          <span class="font-mm">Before you start to configure, you need to prepare the requirements needed which are:
        <br style="display:list-item" />1. Laptop or workstation.
        <br />2. Console Cable.
        <br />3. Straight through cable.
        <br />4. Cisco 2800 Series router.
          </span>
        </p>
        <br />
        <p align="justify">
          <span class="font-mm">Now, you have to follow these guidelines to avoid any error happened. Here are the steps that you need to follow, especially for beginners:</span></p>
          
          <p align="justify">
        <span class="font-mm">1. Connect the console cable to the serial port of your laptop or workstation and connect the connector of console cable to the console port of the router.
        <br/>2. Next, connect the straight through cable to the any Fast Ethernet port that you want to use on the router and on the laptop too. Here is how your network environment looks alike. 
        <br />
          </span></p>
          <img src="imagePSM/network architecture.png" width="60%" height="40%" alt="Network setup" />
          
          <br /><br />
          
          <p align="center"> 
          <span class="font-mm">After that, you need to open the connection using hyperterminal and this software tools need to be downloaded first. Then, these steps need to be followed after download and install the tools:</span></p>
          
          <p align="center">
        <br style="display:list-item" />1. Open the HyperTerminal and the "Connection Description" window will appear. Enter the name of the connection and then click "Ok".<br />
        <br /><img src="imagePSM/hyperterminal.PNG" width="30%" height="10%" alt="1" /><br />
        <br />2. The "Connect To" window will appear and select directly to COM10.<br />
        <br /><img src="imagePSM/hyperterminal 1.PNG" width="30%" height="10%" alt="2" /><br />
        <br />3. On the "COM10 Properties" window, change the default settings to this values and click "OK" to start the connection. <br />
        <br /><img src="imagePSM/hyperterminal 2.PNG" width="30%" height="10%" alt="3" /><br />
        <br />4. After that, a blinking cursor is shown in the HyperTerminal window and it displays an Access Verification for user to access the Cisco 2801 Router. If user successfully access the router, the connection also successful and router is ready to start configure. <br />
        <br /><img src="imagePSM/hyperterminal 3.PNG" width="30%" height="10%" alt="4" />          
        </p>
        <p>Now, you can start configure the router. </p>
      </div>
    </td>
  </tr>
  <tr>
    <td align="center" bgcolor="#000099" /td>
    <td height="34" align="center" bgcolor="#000099" /td>
</table>
</body>
</html>
<?php
mysql_free_result($getSession);
?>