<?php
/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/

 
?>
<?php echo form_open() ?>
<h2 style="padding-left:4rem;color:white;">Reset Transection Pin</h2><hr>
<div class="col-sm-5"style="padding:4rem 4rem 4rem 4rem;" id="con_man_1">
    <!-- <h4 style="text-align:center; background-color:#5cb85c;padding:0.8rem;color:white;font-weight:600;border:3px solid #4dbf4d;">fffffffff</h4> -->
    <label class="text_white">Enter New Transection Pin:</label>
    <input type="password" name="transection_pin" id="transection_pin" required class="form-control"><br/>
    <label class="text_white">Re-Enter Transection Pin:</label>
    <input type="password" name="re_transection_pin" id="re_transection_pin" required class="form-control"><br/>
    <!-- <p style="color:#5275e8;"><span>&nbsp;<b>Create<a href="<?//php echo base_url();?>member/re_transection_pin"> Transection PIN</a></b> </span></p> -->
    <button class="btn btn-success" id="submit_1">&nbsp;&nbsp; save &nbsp;&nbsp; </button>
</div>


<div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5 box-shdow with-con" id="man-con"style="display:none;">
            <p><img src="<?php echo base_url(); ?>media/epin-img.png" alt="" class="img-ani"></p>
            <h2 style="font-weight:600;padding-bottom: 3rem;"><span style="border-bottom:3px solid #159f15;padding-top:1rem;color:green">Transection Pin</span></h2>
            <!-- <input type="text" name="epin" id="epin" required class="form-control" placeholder="Enter Epin *"><br /> -->
            <input type="password" name="old_transection_pin" id="old_transection_pin" required class="form-control" placeholder="Enter Old Transection Pin *"><br />
            <button class="btn btn-succ" name="submit" value="add"><i class="fa fa-sign-out"></i>&nbsp;Submit</button>
        </div>
        <div class="col-md-4"></div>
</div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5 with-con" id="man-error" style="display:none;">
            <div class="alert alert-danger"style="padding-bottom:3rem;">
                <strong><br><i class="fa fa-sign-out"></i> Oops, something went wrong</strong><br>To withdraw an amount, please enter the amount above 500.<br>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>


<?php echo form_close() ?>

<style>
        #man-con {
            background-color: #a9cdd4;
            text-align: center;
            padding: 4rem;
            position: relative;
            /* display: none; */
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


<script>

    $(document).ready(function() {
        $("#submit_1").click(function() {
            var transection_pin = $("#transection_pin").val();
              if (transection_pin != "") {
                $("#con_man_1").hide();
                $("#man-con").show();
            }else if(amount >= 500)
            {
                $("#man-con").show();
                $("#con_man_1").hide();
                $("#man-error").show();

            }
            
        });
    });
    
</script>

