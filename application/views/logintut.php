<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
</head>
<body>
<?php 
if($error){
	$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">'.$errormsg.'</div>');
	$buttondis = 'disabled="disabled"';
	
}
else
{
	$buttondis = ' ';
}

 ?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
		<?php $attributes = array("name" => "loginform");
			echo form_open("login", $attributes);?>
			<legend>Login</legend>
			<div class="form-group">
				<label for="name">Username</label>
				<input class="form-control" name="email" placeholder="Enter Nickname" type="text" autocomplete="off"/>
				<span class="text-danger"><?php echo form_error('email'); ?></span>
			</div>
			<div class="form-group">
				<label for="name">Password</label>
				<input class="form-control" name="password" placeholder="Password" type="password" autocomplete="off" />
				<span class="text-danger"><?php echo form_error('password'); ?></span>
			</div>
			<div class="form-group">
				<button name="loginbtn" type="submit" class="btn btn-info"<?php echo $buttondis; ?>>Login</button>
				<button name="cancel" type="reset" class="btn btn-info">Cancel</button>
			</div>
		<?php echo form_close(); ?>
		<?php echo $this->session->flashdata('msg'); ?>
		</div>
	</div>
	
</div>

</body>
</html>
