<?php

if(isset($_POST['email'], $_POST['p']) || !login_check($mysqli) ) { 
   $email = $_POST['email'];
   $password = $_POST['p']; // The hashed password.
   if(login($email, $password, $mysqli) == true) {
      // Login success
      //echo 'Success: You have been logged in!';
      echo '<meta http-equiv="refresh" content="0;URL=http://photos.rosie.fr/">';
   } else {
	   ?>
	    <script type="text/javascript" src="js/sha512.js"></script>
		<script type="text/javascript" src="js/form.js"></script>
		<?php
		if(isset($_GET['error'])) { 
		   //echo 'Error Logging In!';
		}
		?>
		<div id="login">
			<form action="" method="post" name="login_form" class="shadow">
			   Login: <input type="text" name="email" /><br />
			   Mot de passe: <input type="password" name="password" id="password"/><br />
			   <br />
			   <input type="submit" value="OK" />
			</form>
		</div>
		<!-- formhash(this.form, this.form.password); -->
	 <?php  
	   exit(0);
   }
} else { 
   // The correct POST variables were not sent to this page.
   //echo 'Invalid Request';
}

