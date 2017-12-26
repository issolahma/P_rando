<!-- 
Login Bootstrap Snippet
https://bootsnipp.com/snippets/M3MPP
-->

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>RandoLibre</title>
 
  <link rel="stylesheet" href="resources/css/login.css">
  <link rel="stylesheet" href="resources/css/global.css">
</head>
<body>
<!-- DEBUG -->
<?php 
print_r($_SESSION);
echo $login." ";
echo $password." ".$result;
?>
<!-- DEBUG -->

    <script src="https://use.typekit.net/ayg4pcz.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>

    <div class="container">
    <h1 class="welcome text-center">RandoLibre</h1>
        <div class="card card-container">
        <h2 class='login_title text-center'>Login</h2>
        <hr>
				<!-- Print error message -->
				<?php
    				if(isset($text)){echo "<p class='redMsg'>".$text."</p>";}   
    			?>
    
    			<!-- Login form -->
            <form class="form-signin" method="post" action="index.php?controller=login&action=login">
                <span id="reauth-email" class="reauth-email"></span>
                <p class="input_title">Login</p>
                <input type="text" id="login" name="login" class="login_box" placeholder="login" required autofocus>
                <p class="input_title">Password</p>
                <input type="password" id="password" name="password" class="login_box" placeholder="password" required>
                <button class="btn btn-lg btn-primary" type="submit">Login</button>
            </form><!-- /form -->
        </div><!-- /card-container -->
    </div><!-- /container -->

</body>
</html>