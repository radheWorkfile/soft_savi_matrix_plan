<!-- <div>
    <a href="javascript:void(0);" data-toggle="modal" data-target="#new_instructor" class="btn btn-danger pull-right"><i class="fa fa-plus">&nbsp;Instructor</i></a>

</div>
<br>
<br> -->
<div class="row">
  <form action="<?php echo base_url()?>admin/donar_reward_list" method="post">
    <div class="col-md-3"></div>
    <div class="col-md-4">
      <input type="text" name="userid" class="form-control" placeholder="Enter userid as 1001" value="<?php echo set_value('userid',$userid);?>">
    </div>
    <div class="col-md-2">
      <input type="submit" class="btn btn-danger" class="form-control" value="search">
    </div>
    <div class="col-md-3"></div>
  </form>
</div>
<div class="table-responsive">
  <table id="example" class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Paid</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($member as $in) {

      ?>
        <tr>
          <td><?php echo $in['id']; ?></td>
          <td><?php echo $in['name']; ?></td>
          <td><?php echo $in['email']; ?></td>
          <td><?php echo $in['phone']; ?></td>
          <td><?php echo config_item('currency');
              echo ($in['paid']) ? number_format($in['paid'], 2) : number_format('0', 2) ?></td>
          <td>
            <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal" onclick="document.getElementById('int_id').value='<?php echo $in['id'] ?>'" data-target="#myModal">PaY</a>&emsp;
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>





<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Amount to Pay Donar </h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/pay') ?>
        <label>Enter Amount</label>
        <input type="hidden" name="int_id" value="" id="int_id">
        <input type="number" name="amount" class="form-control" id="amount">
        <div class="pull-right">
          <button type="submit" class="btn btn-success">Pay Now</button>
        </div>
        <?php echo form_close() ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>