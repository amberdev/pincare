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

     $("form[name='add_story']").validate({
    
    rules: {
       
      story: "required",
      title: "required",
      imageurl: "required",
      description: "required"
    },
     
    messages: {
      story: "Please select story image!!!",
      title: "Please provide title.",
      imageurl: "Please enter image url which will be share on facebook",
      description: "Please enter description"
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
        <li><a href="<?php echo base_url();?>admin/dashboard/add_outlets">Add Outlet</a></li>
        <li><a href="<?php echo base_url();?>admin/dashboard/add_story" class="active">Add Story</a></li>
    </ul>
    
</div>  
<!-- leftmenu ends here -->    
    
<!-- right container starts here -->
<div class="right-container">
<div class="add-story-container">
    <form name="add_story" action="<?php echo base_url();?>admin/dashboard/add_story" method="post" enctype="multipart/form-data">

        <button type="submit" name="add" value="add_stroy" class="button">Add Story</button>
        <div class="clear space40"></div>
        

        


        <div class="input-box">
            <input type="file" class="button" name="story" id="story"> 
            <!-- <button type="button" name="" value="" class="delete-btn"></button> -->
        </div>


        <div class="input-box">
            <input type="text" name="title" value="" placeholder="Story Title" class="input">
        </div>

        <div class="input-box">
            <input type="text" name="imageurl" value="" placeholder="image-url" class="input">
        </div>

        <div class="input-box">
            <textarea placeholder="Description" cols="50" rows="20" name="description"></textarea>
        </div>


        
    </form> 
</div>        
</div>    
<!-- right container ends here -->    
   
</body>    
</html>    