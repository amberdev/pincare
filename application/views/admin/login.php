<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>Pincare</title>
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700|Roboto:400,400i" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/layout.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>

</head>

<body>
    
<!-- login starts here -->
<div class="login-box">

    <form name="admin_login" action="welcome/login" method="post">

        <h1>pincare <b>admin</b></h1>
        <div class="clear space30"></div>
        <div class="input-box">
            <input type="text" name="username" value="" placeholder="id..." class="input">
        </div>
        <div class="clear space20"></div>
        <div class="input-box">
            <input type="password" name="password" value="" placeholder="password..." class="input">
        </div>
        <div class="clear space50"></div>
        <input type="submit" name="submit" value="Login" class="button">

    </form>
</div>    
<!-- login ends here -->    
   
</body>    
</html>    