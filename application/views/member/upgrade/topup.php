<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Bidush Sarkar
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/
?>
<style>
    
</style>
<div class="row">
    <?php echo form_open('member/topup_member') ?>
    <div class="col-sm-6">
        <label>User ID <span class="text-danger">*</span></label>
        <input type="text" value="<?php echo $this->uri->segment(3) ? $this->uri->segment(3) : '' ?>" class="form-control" id="userid" name="userid" id="userid">
    </div>
    <div class="col-sm-6">
        <label>Epin to Topup <span class="text-danger">*</span></label>
        <select class="form-control myselect" id="topup" name="topup" >
            <option value="">Select</option>
            <?php foreach ($epin as $val) {
                echo '<option value="' . $val['epin'] . '">' . $val['epin']." | ₹".number_format($val['amount'],2) .' </option>';
            } ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <label for="product" class="control-label">Package <span class="text-danger">*</span></label>
        <select class="form-control" id="product" name="product" >
            <option value="">Select</option>
            <?php foreach ($products as $val) {
                echo '<option value="' . $val['id'] . '">' . $val['prod_name'] . '. Price :' . config_item('currency') . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) . ' </option>';
            } ?>
        </select>
    </div>
    <div class="col-sm-6"><br />
        <input type="submit" class="btn btn-success" value="TopUp" onclick="this.value='Working..'">
    </div>
    <?php echo form_close() ?>
</div>



<script>
    var base_url = "<?php echo base_url(); ?>";
    $("#userid").on('change', function() {

        var id = $("#userid").val();

        $.ajax({
            url: base_url + "member/get_user_name",
            type: "post",
            data: {
                "id": id
            },
            success: function(data) {
                $("#mem_name").html("<span class='text-danger'>" + data + "</span>")
            }
        });

    });
</script>

