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
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/custom.js"></script>

<script type="text/javascript">

$(document).ready(function(){

     $("form[name='add_outlet']").validate({
    
    rules: {
       
      outlet_id: "required",
      password: "required",
      outlet_name: "required",
      address: "required",
      city :"required",
      zip :"required",
      country: "required",
      logni: "required",
      lati:"required",
      fb_page_id: "required"
    },
     
    messages: {
      outlet_id: "Please enter outlet id (Which will be your login id!)",
      password: "Please enter your password",
      outlet_name: "Please enter your outlet name",
      address: "Please enter address",
      city : "Please enter city",
      country: "Please enter country",
      logni:"Please enter your outlet longitude",
      lati:"Please enter your outlet latitude",
      fb_page_id: "Please enter your facebook page id or page url" 
    },
    
    submitHandler: function(form) {
      form.submit();
    }
  });
});

</script>

</head>

<body>
    
<!-- leftmenu starts here -->
<div class="leftmenu">
    
    <ul>
        <li><a href="<?php echo base_url();?>admin/dashboard/add_outlets" class="active">Add Outlet</a></li>
        <li><a href="<?php echo base_url();?>admin/dashboard/add_story">Add Story</a></li>
    </ul>
    
</div>  
<!-- leftmenu ends here -->    
    
<!-- right container starts here -->
<div class="right-container">
<div class="form-container">
    <form name="add_outlet" method="post" enctype="multipart/form-data" action="<?php echo base_url();?>admin/dashboard/add_outlets">
        <div class="input-box">
            <input type="text" name="outlet_id" value="" placeholder="Outlet Id(username).." class="input">
        </div>
        <div class="input-box">
            <input type="password" name="password" value="" placeholder="Password.." class="input">
        </div>
        <div class="input-box">
            <input type="text" name="outlet_name" value="" placeholder="Outlet Name" class="input">
        </div>
        <div class="input-box">
            <input type="text" name="address" value="" placeholder="Address" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="city" value="" placeholder="City" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="zip" value="" placeholder="Zip" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="country" value="" placeholder="Country" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="logni" value="" placeholder="Longitude" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="lati" value="" placeholder="Latitude" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="fb_page_id" value="" placeholder="FB Page ID" class="input">
        </div>
        
        <div class="clear space30"></div>
        
        <input type="file" class="button" name="logo"> 
        <div class="clear space30"></div>
        <input type="submit" name="submit" value="DONE" class="button">
    </form>
</div>    
</div>    
<!-- right container ends here -->    
   
</body>    
</html>    