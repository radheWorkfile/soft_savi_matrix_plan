<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php echo $description;?>">
	<meta name="keywords" content="<?php echo $keywords;?>">
	<meta name="author" content="AMB LIFE">
	<link rel="shortcut icon" href="<?php echo base_url()?>uploads/favicon.png" type="image/x-icon">
	<!-- SITE TITLE -->
	<title><?php echo $title;?> | <?php echo config_item('company_name');?> </title>			


	<?php $this->load->view('theme/include/css'); ?>
</head>

<body>	
	<?php $this->load->view('theme/include/header'); ?>





    <!-- main page section start here -->
    <?php if (trim($layout) == "") { ?>
        <div style="margin: 10%">
            <h2 align="center">Welcome to <?php echo config_item('company_name') ?></h2>
            <div align="center">Please click above to login or sign up</div>
        </div>
    <?php } else {
        include_once(APPPATH . "views/theme/" . $layout);
    } ?>
    <!-- main page section start end -->


	<?php $this->load->view('theme/include/footer'); ?>
	<?php $this->load->view('theme/include/js_2'); ?>
	<?php $this->load->view('theme/include/js'); ?>
	
</body>

</html>