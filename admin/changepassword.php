<?php include("session.php"); ?>
<?php include("../includes/connection.php"); ?>
<?php include("functions.php"); ?>
<?php if(!chkLogin()){
	redirectTo("index.php");
}?>
<?php 
	if(isset($_POST['submit'])){
		if(trim($_POST['oldPassword']) && trim($_POST['newPassword']) && trim($_POST['confirmPassword'])){
			$username=trim(mysqlPrep($_SESSION['admin']));
			$oldPassword=trim(mysqlPrep($_POST['oldPassword']));
			$hashOldPassword=sha1($oldPassword);
			$query="SELECT username FROM teamtwisteradmin WHERE username='{$username}' AND password='{$hashOldPassword}'";
			$set=mysql_query($query);
			confirmQuery($set);
			$num=mysql_num_rows($set);
			if($num){
				//proceed with changing the password
				$newPassword=trim(mysqlPrep($_POST['newPassword']));
				$confirmPassword=trim(mysqlPrep($_POST['confirmPassword']));
				if($newPassword==$confirmPassword){
					$hashNewPassword=sha1($newPassword);
					$insrt="UPDATE teamtwisteradmin SET password='{$hashNewPassword}' WHERE username='{$username}'";
					$optn=mysql_query($insrt);
					confirmQuery($optn);
					$chk=mysql_affected_rows();
					if($optn){
						$message="Your Password has been changed.";
					}
					else{
						$message="Unknown error. Please Try again or contact the web developer";
					}
				}
				else{
					$message="ERROR: new Password and confirm Password fields does not match";
				}	
			}
			else{
				$message="ERROR: Incorrect Password.";
			}
		}
		else{
			$message="ERROR: Make sure that all the fields are filled";
		}
	}
?>
<?php
	include_once("header.php");
?>
<div id="main">
	<div id="sidebar">
		<ul id="menu">
			<li class="item-1"><a href="index.php">Admin</a></li>
			<?php if(chkLogin()){
					echo "<li class=\"item-2\"><a href=\"logout.php\">Logout</a></li>";
					echo "<li class=\"item-3\"><a href=\"players.php\">Player Update</a></li>";
					echo "<li class=\"item-4\"><a href=\"playerentry.php\">Edit players</a></li>";
					echo "<li class=\"item-5\"><a href=\"changepassword.php\">Admin Acc Settings</a></li>";
				}
				else {
					echo "<li class=\"item-2\"><a href=\"\">Public Site</a></li>";	
				}
			?>
		</ul>
	</div><!-- // end #sidebar -->
	<div id="content">
		<p class="banner"><img src="../common/images/banner.png" alt="Banner" /></p>
		<div>
			<h2 class="title"><span>Change Password</span></h2>
			<div>
			<?php
				if(isset($message)){
				echo "<p>".htmlentities($message)."</p>";
				unset($message);
				}
			?>
			<table>
			<form name="changePassword" action="changepassword.php" method="post">
			<tr>
				<td>Old Password: </td><td><input type="password" name="oldPassword" value="" id="oldPassword"/></td>
			</tr>
			<tr>
				<td>New Password: </td><td><input type="password" name="newPassword" value="" id="newPassword"/></td>
			</tr>
			<tr>
				<td>Confirm Password: </td><td><input type="password" name="confirmPassword" value="" id="conPassword"/></td>
			</tr>
			<tr></tr>
			<tr>
				<td><input name="submit" type="submit" value="Change" onsubmit="return formValidation(this);"/></td>
			</tr>
			</form>
			</table>
			</div>
		</div>
	</div><!-- // end #content -->
	<div class="clear"></div>
</div><!-- // end #main -->
<?php
	include_once("footer.php");
	connectionClose($connection);
?>