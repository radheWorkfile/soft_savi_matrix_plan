<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/

?>
  <div class="table-responsive table_padding">
<table id="example" class="table nowrap table-striped">
    <thead class="text-white thead_design">
        <tr>
            <th>S.N.</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Remark</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sn = 1;
        foreach ($member as $e) {

        ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('currency') . $e->amount ?></td>
                <td><?php echo $e->type ?></td>
                <td><?php echo $e->remark ?></td>
                <td><?php echo $e->date ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
  </div>