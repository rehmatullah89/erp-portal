<?
	/*********************************************************************************************\
	***********************************************************************************************
	**                                                                                           **
	**  MATRIX Customer Portal                                                                   **
	**  Version 2.0                                                                              **
	**                                                                                           **
	**  http://portal.apparelco.com                                                              **
	**                                                                                           **
	**  Copyright 2008-15 (C) Matrix Sourcing                                                    **
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
		  <div id="Profile">
			<h1 class="green">
			  <img id="ProfileIcon" src="images/icons/show.jpg" width="25" height="25" alt="" title="" align="right" style="margin:8px 7px 0px 0px; cursor:pointer;" onclick="toggleProfile( );" />
			  <b>My Profile</b>
			</h1>

			<div id="ProfileDetails" class="block" style="display:none;">
			  <div class="blockBottom">
				<div class="blockTop">
<?
	@include($sBaseDir."includes/profile.php");
?>
				</div>
			  </div>
			</div>
		  </div>
