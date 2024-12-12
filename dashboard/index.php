<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  Triple Tree Customer Portal                                                              **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.3-tree.com                                                                 **
	**                                                                                           **
	**  Copyright 2008-15 (C) Triple Tree                                                        **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**  Project Manager:                                                                         **
	**                                                                                           **
	**      Name  :  Muhammad Tahir Shahzad                                                      **
	**      Email :  mtahirshahzad@hotmail.com                                                   **
	**      Phone :  +92 333 456 0482                                                            **
	**      URL   :  http://www.mtshahzad.com                                                    **
	**                                                                                           **
	**  ***************************************************************************************  **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	**                                                                                           **
	***********************************************************************************************
	\*********************************************************************************************/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Triple Tree Customer Portal</title>

  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
  <meta http-equiv="Content-Language" content="en-us" />
  <meta name="description" content="Triple Tree Customer Portal" />
  <meta name="keywords" content="Triple Tree Customer Portal" />

  <meta name="revisit-after" content="1 Weeks" />
  <meta name="distribution" content="global" />
  <meta name="robots" content="all" />
  <meta name="rating" content="general" />
  <meta http-equiv="imagetoolbar" content="no" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="Tue, 01 Jan 2009 12:12:12 GMT" />
  <meta http-equiv="Cache-Control" content="no-cache" />

  <meta name="copyright" content="Matrix Sourcing" />
  <meta name="author" content="Muhammad Tahir Shahzad" />
  <link rev="made" href="mailto:mtahirshahzad@hotmail.com" />

  <base href="http://portal.3-tree.com/" />

  <link rel="Shortcut Icon" href="images/icons/favicon.ico" type="image/icon" />
  <link rel="icon" href="images/icons/favicon.ico" type="image/icon" />

  <script type="text/javascript" src="scripts/jquery.js"></script>

  <style type="text/css">
  <!--
  	html, body { height:100; overflow:hidden; }
  -->
  </style>
</head>



<body style="margin:0px; background:#ffffff;">

<iframe id="Frame" style="width:100%; height:1000px;" width="100%" height="100%" frameborder="0" src="dashboard/dashboard.php"></iframe>

<script type="text/javascript">
<!--
	var iCounter = 0;

	$(function( )
	{
		setInterval(function( )
		{
			if ((iCounter % 3) == 0)
				$("#Frame").attr("src", "dashboard/reports.php");

			else if ((iCounter % 3) == 1)
				$("#Frame").attr("src", "dashboard/protoware.php");

			else if ((iCounter % 3) == 2)
				$("#Frame").attr("src", "dashboard/dashboard.php");

			iCounter ++;
		}, 30000);
	});
-->
</script>


</body>
</html>
