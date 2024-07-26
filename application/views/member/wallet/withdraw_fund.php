<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/
?>
<?php if ($tranPin_have_or_not['transection_pin'] != "") { ?>
    <?php if ($redonate == 'yes') { ?>
        <p>You Are Eligible to Donate:&nbsp;<a href="<?php echo base_url('member'); ?>" class="btn btn-success">Donate First</a></p>

    <?php } else {
        echo form_open() ?>

        <div class="col-sm-6" id="con_man_1">
            <h3>
                <strong style="color: #0cc745">Available Wallet Balance:
                    <?php echo config_item('currency') . $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></strong>
            </h3>
            <p>
                <label class="text_white">Enter Amount to withdraw:</label>
                <br />
            <h4 class="text_white">You have to withdraw minium: <?php echo config_item('currency') . config_item('min_withdraw') ?></h4>
            <input type="text" name="amount" id="amount" required class="form-control" value="1" oninput="this.value = this.value.toUpperCase().replace(/[^0-9]/g, '').replace(/(\  *?)\  */g, '$1')"><br />
            <button class="btn btn-success" id="submit_1">Withdraw</button>
            </p>
        </div>
        <div class="col-md-6" style="display:none;"></div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5 box-shdow with-con" id="man-con">
                <p><img src="<?php echo base_url(); ?>media/epin-img.png" alt="" class="img-ani"></p>
                <h2 style="font-weight:600;padding-bottom: 3rem;"><span style="border-bottom:3px solid #159f15;padding-top:1rem;color:green">Withdrow Amout</span></h2>
                <!-- <input type="text" name="epin" id="epin" required class="form-control" placeholder="Enter Epin *"><br /> -->
                <input type="password" name="transection_pin" id="transection_pin" required class="form-control" placeholder="Enter Transection Pin*"><br />
                <button class="btn btn-succ" name="submit" value="add"><i class="fa fa-sign-out"></i>&nbsp;Withdraw Amount</button>
            </div>
            <div class="col-md-4"></div>
        </div>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5 with-con" id="man-error" style="display:none;">
                <div class="alert alert-danger" style="padding-bottom:3rem;">
                    <strong><br><i class="fa fa-sign-out"></i> Oops, something went wrong</strong><br>To withdraw an amount, please enter the amount above 500.<br>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    <?php echo form_close();
    } ?>
<?php } else { ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="color:green;padding-bottom:3rem;"><i class="fa fa-xing-square"></i>Your transaction PIN is not set, please set it first.</h3>
            </div>
            <div class="col-md-12">
                <a href="<?php echo base_url(); ?>member/transection_pin"> <button class="btn btn-succ" name="submit" value="add"><i class="fa fa-sign-out"></i>&nbsp;Create Transection Pin</button></a>
            </div>
        </div>
    </div>
<?php } ?>

<style>
    #man-con {
        background-color: #a9cdd4;
        text-align: center;
        padding: 4rem;
        position: relative;
        display: none;
    }

    .btn-succ {
        background-color: #189418;
        color: white;
        font-weight: 600;
    }

    .btn-succ:hover {
        background-color: #1a6c1a;
        color: white;
    }

    .box-shdow {
        padding: 15px;
        background-color: #9eccda;
        box-shadow: 10px 10px 5px lightblue inset;
    }

    .img-ani {
        position: absolute;
        top: 5rem;
        height: 6rem;
        left: 3rem;
        animation: move 2.5s linear infinite;
    }

    @keyframes move {
        0% {
            transform: translate(-15px, 0px);
        }

        50% {
            transform: translate(0px, -15px);
        }

        100% {
            transform: translate(-15px, 0px);
        }
    }
</style>



<!-- submit_1 -->
<script>
    $(document).ready(function() {
        $("#submit_1").click(function() {
            var amount = $("#amount").val();
            if (amount < 500) {
                $("#con_man_1").hide();
                $("#man-error").show();
            } else if (amount >= 500) {
                $("#man-con").show();
                $("#con_man_1").hide();
                $("#man-error").hide();
            }

        });
    });
</script>