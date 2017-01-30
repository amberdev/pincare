<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<title>Pincare</title>
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/layout.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/custom.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>

<script type="text/javascript">

$(document).ready(function(){

     $("form[name='manage_pins']").validate({
    
    rules: {
       
      all_outlets: "required",
      chk_per_day: "required",
      amnt_per_day: "required"
    },
     
    messages: {
      all_outlets: "Select Outlets!!!",
      chk_per_day: "Enter Check-Ins per day",
      amnt_per_day: "Enter Amount per day"
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
        <li><a href="#" class="active">Manage Pins</a></li>
        <li><a href="#">Edit Info</a></li>
    </ul>
    
</div>  
<!-- leftmenu ends here -->    
    
<!-- right container starts here -->
<div class="right-container">
<div class="manage-pins-sec">
    <form name="manage_pins" action="<?php echo base_url();?>admin/dashboard/manage_pins" method="post">
    <div class="manage-pin-state">
        <button type="button" name="" value="">Active <img src="images/tick-icon.png" alt=""></button>
        <div class="clear space10"></div>
        <button type="button" name="" value="">Off</button>
    </div>
    <div class="clear space50"></div>
   
    <div class="input-box">
            <select name="all_outlets" >
                <option value="">Select One</option>
                <?php if(!empty($outlets)):
                        foreach($outlets as $out):?>
                        <option value=<?php echo $out['id'];?>><?php echo $out['outlet_name'];?></option>
                        <?php endforeach;?> 
                <?php endif; ?>
            </select>
        </div>

    <div class="manage-pins-field">
        <label>Check-Ins Per Day</label>
        <input type="text" name="chk_per_day" value="" class="input">
    </div>
    
    <div class="clear space20"></div>
    
     <div class="manage-pins-field">
        <label>Amount per Check-Ins</label>
        <input type="text" name="amnt_per_day" value="" class="input">
    </div>
    
    <div class="clear space50"></div>
    
    <button type="submit" name="update" value="udpate" class="button update-btn">Update</button>
    </form>
</div>    
</div>    
<!-- right container ends here -->    
   
</body>    
</html>    