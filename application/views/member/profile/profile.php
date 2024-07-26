<?php
/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/
?>
<?php echo form_open_multipart() ?>
<div class="col-sm-6">
    <label class="text_white">My Name</label>
    <input type="text" class="form-control" name="my_name"
           value="<?php echo set_value('my_name', $this->session->name) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">My Phone No</label>
    <input type="text" class="form-control" name="my_phone"
           value="<?php echo set_value('my_phone', $my->phone) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">My Email Id</label>
    <input type="text" class="form-control" name="my_email"
           value="<?php echo set_value('my_email', $my->email) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Bank Name</label>
    <input type="text" class="form-control" name="bank_name"
           value="<?php echo set_value('bank_name', $data->bank_name) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Bank A/C No</label>
    <input type="text" class="form-control" name="bank_ac_no"
           value="<?php echo set_value('bank_ac_no', $data->bank_ac_no) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Bank IFSC Code</label>
    <input type="text" class="form-control" name="bank_ifsc"
           value="<?php echo set_value('bank_ifsc', $data->bank_ifsc) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Bank Branch Name</label>
    <input type="text" class="form-control" name="bank_branch"
           value="<?php echo set_value('bank_branch', $data->bank_branch) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Upload Passbook</label>
    <input type="file" class="form-control" name="pass_book"
           >
           <?php if($data->pass_book){?>
           <a href="<?php echo base_url('uploads/').$data->pass_book?>" target="_blank">Click Here</a>
           <?php }?>
</div>
<div class="col-sm-6">
    <label class="text_white">UPI Address</label>
    <input type="text" class="form-control" name="btc_address"
           value="<?php echo set_value('btc_address', $data->btc_address) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Nominee Name</label>
    <input type="text" class="form-control" name="nominee_name"
           value="<?php echo set_value('nominee_name', $data->nominee_name) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Nominee Address</label>
    <input type="text" class="form-control" name="nominee_add"
           value="<?php echo set_value('nominee_add', $data->nominee_add) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Nominee Relation</label>
    <input type="text" class="form-control" name="nominee_relation"
           value="<?php echo set_value('nominee_relation', $data->nominee_relation) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Date of Birth</label>
    <input type="text" class="form-control datepicker" name="date_of_birth"
           value="<?php echo set_value('date_of_birth', $data->date_of_birth) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Pan / Tax No</label>
    <input type="text" class="form-control" name="tax_no" value="<?php echo set_value('tax_no', $data->tax_no) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Aadhar No</label>
    <input type="text" class="form-control" name="aadhar_no"
           value="<?php echo set_value('aadhar_no', $data->aadhar_no) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">GSTIN</label>
    <input type="text" class="form-control" name="gstin" value="<?php echo set_value('gstin', $data->gstin) ?>">
</div>
<div class="col-sm-6">
    <label class="text_white">Address Proof</label>
    <input type="file" class="form-control" name="add_proof">
</div>
<div class="col-sm-6">
    <label class="text_white">ID Proof</label>
    <input type="file" class="form-control" name="id_proof">
</div>
<div class="col-sm-6">
    <label class="text_white">Current Password</label>
    <input type="password" class="form-control" name="oldpass">
</div>
<div class="col-sm-6">
    <br/>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
<?php echo form_close() ?>
<br/>
<p>&nbsp;</p>