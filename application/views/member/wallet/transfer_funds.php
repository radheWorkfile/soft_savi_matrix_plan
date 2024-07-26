<?php
/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/
?>

<?php if($tran_pin_have_or_not['transection_pin'] != ""){?>
    
<?php if ($redonate == 'yes') {?>
    <p>You Are Eligible to Donate:&nbsp;<a href="<?php echo base_url('member');?>" class="btn btn-success">Donate First</a></p>

    

    
<?php } else { echo form_open() ?>
<div class="col-sm-7 col-md-offset-2" style="text-align: center" id="con_man_1">
    <h3>
        <strong style="color: #000000">Available Wallet Balance:
            <?php echo config_item('currency') . $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></strong>
    </h3>
    <label>Enter Amount:</label>
    <input type="text" name="amount" required class="form-control" value="1"><br/>
    <label>Transfer to User ID:</label>
    <input type="text" name="transferid" required class="form-control"><br/>
    <button class="btn btn-success" id="submit_1">Transfer</button>
</div>


     <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5 box-shdow with-con" id="man-con">
            <p><img src="<?php echo base_url(); ?>media/epin-img.png" alt="" class="img-ani"></p>
            <h2 style="font-weight:600;padding-bottom: 3rem;"><span style="border-bottom:3px solid #159f15;padding-top:1rem;color:green">Transfer Wallet Funds</span></h2>
            <!-- <input type="text" name="epin" id="epin" required class="form-control" placeholder="Enter Epin *"><br /> -->
            <input type="password" name="transection_pin" id="transection_pin" required class="form-control" placeholder="Enter Transection Pin*"><br />
            <button class="btn btn-succ" name="submit" value="add"><i class="fa fa-sign-out"></i>&nbsp;Transfer Wallet Funds</button>
        </div>
        <div class="col-md-4"></div>
    </div>
<?php }?>
<?php }else{?>
   <div class="container">
    <div class="row">
        <div class="col-md-12"><h3 style="color:green;padding-bottom:3rem;"><i class="fa fa-xing-square"></i> Your transaction PIN is not set, please set it first.</h3></div>
        <div class="col-md-12">
        <a href="<?php echo base_url();?>member/transection_pin"> <button class="btn btn-succ" name="submit" value="add"><i class="fa fa-sign-out"></i>&nbsp;Create Transection Pin</button></a>
        </div>
    </div>
   </div>
<?php }?>




     <style>
        #man-con {
            background-color: #a9cdd4;
            text-align: center;
            padding: 4rem;
            position: relative;
            display: none;
        }

        .btn-succ {
            background-color: #146a14; 
            color: white;
            font-weight: 600;
        }

        .btn-succ:hover {
            background-color: #189418;
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
<script>

    $(document).ready(function() {
        $("#submit_1").click(function() {
            var amount = $("#amount").val();
            var transferid = $("#transferid").val();
              if (amount != "" && transferid != "") {
                $("#con_man_1").hide();
                $("#man-con").show();
            }
        });
    });
    
</script>

