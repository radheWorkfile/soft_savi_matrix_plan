<div class="container-fluid set_form_bggg1 position-relative py-2 py-lg-5">
<div class="container ">
    <div class="row">
     <h1 class="text-center set_register position-absolute">REGISTERED CUSTOMERS</h1>
    </div>
</div>
</div>

<div class="container-fluid set_login_bg py-2 py-lg-5">
<div class="container p-5 set_form_bggg" >
    <div class="row">
        <div class="col-lg-6  col-sm-12 col-xs-12" >
            <div class="woocommerce-notices-wrapper"></div>
            <h4 class="font-weight-bold mb-4 text-warning">REGISTERED CUSTOMERS</h4>
            <p class="text-white">If you have an account, sign in with your ID and Password.</p>

            <form class="woocommerce-form woocommerce-form-login login" method="post">

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username" class="text-info">ID / Username&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input set_input_box woocommerce-Input--text input-text form-control" name="username"  placeholder="Username/Id" id="username" autocomplete="username" value="">
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"  class="text-info ">Password&nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input  set_input_box woocommerce-Input--text input-text form-control" type="password" name="password"  placeholder="Password" id="password" autocomplete="current-password">
                </p>
              <br>
                <p class="form-row float-left">
                    <button type="submit" class="woocommerce-button  btn btn-primary button woocommerce-form-login__submit" name="login" value="Log in">Log in</button>
                </p>

            </form>
            <div class="d-flex justify-content-end">
            <div class="woocommerce-notices-wrapper"></div>
            <a href="<?php echo base_url() . 'Site/register' ?>" class="btn btn-lg btn-primary" style=" font-size:15px;">Create An Account </a>
        </div>
        </div>

        <div class="col-lg-6  col-sm-12 col-xs-12 text-center">
           <div>
            <img src="<?php echo base_url()?>media/theme_assets/img/bg/login.png" alt="">
           </div>
        </div>
    </div>
</div>
</div>