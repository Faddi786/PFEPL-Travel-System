<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session
}
?>

<nav>
	<input type="checkbox" id="check">
	<label for="check" class="checkbtn">
		<i class="fas fa-bars"></i>
	</label>
	<label class="logo">
		<img src="<?php echo SITE_URL ?>/static/logo/logo2.png" style="border-radius:10px" alt="Logo" width="70px" height="65px">
	</label>
	<ul>
		<li><a href="<?php echo SITE_URL ?>records.php">Home</a></li>
		<!-- <li><a href="<admin/site/">Sites</a></li> -->
		<?php
		if ($_SESSION['role'] != 2) {
		?>
			<li><a href="<?php echo SITE_URL ?>admin/user">Employee</a></li>
			<li><a href="<?php echo SITE_URL ?>admin/project/">Project</a></li>
			<li><a href="<?php echo SITE_URL ?>admin/vehicle/">Vehicle</a></li>
		<?php
		}
		?>
		<li><a href="<?php echo SITE_URL ?>report.php">Vehicle Reports</a></li>
		<li><a href="<?php echo SITE_URL ?>auth.php?action=logout" style="text-decoration:none; width:fit-content; color:#777773">↩️Logout</a></li>
	</ul>
</nav>