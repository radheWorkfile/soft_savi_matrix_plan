<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: ishu
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/
?>
<style>
    ul ol {
        list-style-type: number_format;
    }
</style>

<div class="row set_ppppg">

    <div class="col-sm-4 col-md-4 col-lg-4 col-4">
        <?php echo form_open_multipart('Member/requestedfor_epin'); ?>

        <div class="row">

            <h4 class="text-center text_white">payable Amount:<?php echo config_item('currency');?> <span id="pay_amount" class="text-danger text_white">0</span></h4>

            <div class="col-sm-12">
                <label>e-PIN Type<span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <select name="epin_type" id="epin_type" class="form-control">
                        <option value="">Select</option>
                        <?php foreach ($epin_value as $ev) : ?>
                            <option value="<?php echo $ev->prod_price; ?>"><?php echo config_item('currency')."&nbsp;" . $ev->prod_price; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-12">
                <label class="text_white text_white">e-PIN Quantity Require<span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon text_white">#</span>
                    <input type="number" class="form-control" value="1" placeholder="For free e-pin enter 0" id="epin_qty" name="epin_qty">


                </div>

            </div>

           

            <div class="col-sm-12">

                <label class="text_white">e-PIN Total Amount<span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon"><?php echo config_item('currency');?></span>

                    <input type="text" readonly class="form-control" value="0" placeholder="For free e-pin enter 0" id="total_amount" name="total_amount">
                </div>

            </div>

            <div class="col-sm-12">

                <label class="text_white">Attach Payment Slip<span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-addon">#</span>

                    <input type="file" class="form-control" placeholder="For free e-pin enter 0" id="attach_doc" name="attach_doc">
                </div>

            </div>


            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4 margin_top">

                <button type="submit" class="btn btn-danger">Request</button>
            </div>


        </div>



        <?php echo form_close(); ?>

    </div>

    <div class="col-sm-4 col-md-4 col-lg-4 col-4">

        <img src="<?php echo base_url() ?>uploads/qr_code1.png" style="max-width: 100%;" alt="qr">

    </div>

    <div class="col-sm-4 col-md-4 col-lg-4 col-4 text_white ">
        <h3 class="text_white ">Instruction for Request</h3>

        <p class="text_white ">1. Enter No of e-pin Require</p>
        <p class="text_white ">2. See Total payable Amount</p>
        <p class="text_white ">3. Scan Qr Code</p>
        <p class="text_white ">4. Make Payment</p>
        <p class="text_white ">5. Take Screen short</p>
        <p class="text_white ">6. Attach Payment Screenshot</p>
        <p class="text_white ">7. Submit Request</p>


    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 col-12 table-responsive " style="overflow-x:auto;">
        <table class="table nowrap table-striped" id="example">
            <thead style="background-color:#8b5cf6;color:white">
                <tr>
                    <th>S.no</th>
                    <th>Qty</th>
                    <th>Total Amount</th>
                    <th>Request date</th>
                    <th>Epin</th>
                    <th>Status</th>


                </tr>
            </thead>
            <tbody>
                <?php foreach ($request as $i => $req) : ?>
                    <tr style=" word-wrap: break-word;">
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $req['epin_qty']; ?></td>
                        <td><?php echo config_item('currency').'&nbsp;'.$req['total_amount']; ?></td>
                        <td><?php echo date('d M Y', strtotime($req['request_date'])); ?></td>
                        <td><?php echo $req['epin']; ?></td>
                        <td><?php if ($req['status'] == 1) {
                                echo "<span class='text-warning'>Pending</span>";
                            } elseif ($req['status'] == 2) {
                                echo "<span class='text-success'>Generated</span>";
                            } else {
                                echo "<span class='text-danger'>Cancel</span>";
                            }
                            ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>


</div>


<script>
    $(document).on('change', '#epin_qty,#epin_type', function() {

        var epi_qty = parseInt($('#epin_qty').val());
        if (epi_qty > 0) {
            var amount = $("#epin_type").val() * epi_qty;

            $('#pay_amount').text(amount).css('color', 'red');
            $('#total_amount').val(amount);
        } else {
            if (confirm("Quantity MUst be greater or equal to one")) {
                window.location.reload(true);
            }

        }







    });
</script>