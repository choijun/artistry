<?php
/**
 * Created by PhpStorm.
 * User: Shailesh Suryawanshi
 * Date: 15-02-2018
 * Time: 17:17
 */
?>

    <div class=" mo_registration_divided_layout">

        <div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;">
			<?php is_gsuite_customer_registered();?>
			<?php Mo_GSuite_Utility::micr()?>
            <h3>Sign in options</h3>
            <input type="checkbox" style="background: #DCDAD1;" disabled/> <span style="color: red;">*</span>Redirect to
            IdP if user not logged in.
            <a href="#" id="registered_only_access">[What does this mean?]</a>
            <br>
            <div hidden id="registered_only_access_desc" class="mo_saml_help_desc">
                <span>Select this option if you want to restrict your site to only logged in users. Selecting this option will redirect the users to your IdP if logged in session is not found.</span>
            </div>
            <br/>
            <form id="mo_saml_force_authentication_form" method="post" action="">
                <input type="hidden" name="option" value="mo_saml_force_authentication_option"/>
                <input type="checkbox" style="background: #DCDAD1;" name="mo_saml_force_authentication"
                       value="true" <?php if (  mo_saml_is_sp_configured() ){
					echo 'disabled title="Disabled. Configure your Service Provider"';} ?>

					<?php checked( get_option( 'mo_saml_force_authentication' ) == 'true' ); ?>
                       onchange="document.getElementById('mo_saml_force_authentication_form').submit();"/><span
                        style="color: red;"> *</span>Force
                authentication with your IdP on each login attempt.
                <a href="#" id="force_authentication_with_idp">[What does this mean?]</a>
                <br>
                <div hidden id="force_authentication_with_idp_desc" class="mo_saml_help_desc">
                    <span>It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</span>
                </div>
            </form>
            <br/>
            Choose how you want users to log into your WordPress website. You can choose any or all of the three options
            below.<br/><br/>
            <span style="font-size:15px;"><b>Option 1: Use Default WordPress LogIn</b></span>
            <div style="margin-left:17px;margin-top:2%;">
                <form id="mo_saml_enable_redirect_form" method="post" action="">
                    <input type="hidden" name="option" value="mo_saml_enable_login_redirect_option"/>
					<?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                        <input type="checkbox" name="mo_saml_enable_login_redirect"
                               value="true" <?php if (  mo_saml_is_sp_configured() )
							echo 'disabled title="Disabled. Configure your Service Provider"' ?> <?php checked( get_option( 'mo_saml_enable_login_redirect' ) == 'true' ); ?>
                               onchange="document.getElementById('mo_saml_enable_redirect_form').submit();"/> Check this option if you want to
                        <b>auto redirect the user to IdP</b>.
                        <a href="#" id="redirect_to_idp">[What does this mean?]</a>
                        <br>
                        <div hidden id="redirect_to_idp_desc" class="mo_saml_help_desc">
                            <span>Users visiting any of the following URLs will get redirected to your configured IdP for authentication:</span>
                            <br/><code><b><?php echo wp_login_url(); ?></b></code> or
                            <code><b><?php echo admin_url(); ?></b></code>
                        </div>
					<?php } else { ?>
                        <input type="checkbox"
                               style="background: #DCDAD1;" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               disabled/> <span style="color: red;">*</span>Check this option if you want to <b>auto
                            redirect the user to IdP</b>.
                        <a href="#" id="redirect_to_idp">[What does this mean?]</a>
                        <br>
                        <div hidden id="redirect_to_idp_desc" class="mo_saml_help_desc">
                            <span>Users visiting any of the following URLs will get redirected to your configured IdP for authentication:</span>
                            <br/><code><b><?php echo wp_login_url(); ?></b></code> or
                            <code><b><?php echo admin_url(); ?></b></code>
                        </div>
					<?php } ?>
                </form>
                <form id="mo_saml_allow_wp_signin_form" method="post" action="">
                    <input type="hidden" name="option" value="mo_saml_allow_wp_signin_option"/>
                    <p>
						<?php if ( ! get_option( 'mo_saml_free_version' ) ) {
						//TODO Hardcoded disabled here down. Please look into this.
						?>

                        <input type="checkbox" name="mo_saml_allow_wp_signin"
                               value="true" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               onchange="document.getElementById('mo_saml_allow_wp_signin_form').submit();"/> Checking
                        this option creates a backdoor to login to your Website using WordPress credentials incase you
                        get locked out of your IdP.
                        <i>(Note down this URL: <code><b><?php echo site_url(); ?>
                                    /wp-login.php?saml_sso=false</b></code> )</i>
                    <div style="background-color:#CBCBCB;padding:1%;">
                        <span style="color:#FF0000;">WARNING:</span> Checking the above option will <b>enable a security
                            hole</b>. Anybody knowing the above URL will be able to login to your website using
                        WordPress Credentials. <b>Please do not share this URL.</b>
                    </div>
					<?php } else { ?>
                        <input type="checkbox"
                               style="background: #DCDAD1;" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               disabled/> <span
                                style="color: red;">*</span>Checking this option creates a backdoor to login to your Website using WordPress credentials incase you get locked out of your IdP.
                        <i>(Note down this URL: <code><b><?php echo site_url(); ?>
                                    /wp-login.php?saml_sso=false</b></code> )</i>
					<?php } ?>
                    </p>
                </form>
            </div>
            <span style="font-size:15px;"><b>Option 2: Use a Widget</b></span>
            <div style="margin:2% 0 2% 17px;">
                <input type="checkbox" name="mo_saml_add_widget"
                       id="mo_saml_add_widget" <?php if (  !mo_saml_is_sp_configured() )
					echo 'disabled title="Disabled. Configure your Service Provider"' ?> value="true"> Check this option
                if you want to add a Widget to your page.
                <div id="mo_saml_add_widget_steps" hidden>
                    <ol>
                        <li>Go to Appearances > Widgets.</li>
                        <li>Select "Login with <?php echo get_option( 'saml_identity_name' ); ?>". Drag and drop to your
                            favourite location and save.
                        </li>
                    </ol>
                </div>
            </div>

            <span style="font-size:15px;"><b>Option 3: Use a ShortCode</b></span>
            <div style="margin:2% 0 2% 17px;">
				<?php if (  !get_option( 'mo_saml_free_version' ) ) { ?>
                    <input type="checkbox" name="mo_saml_add_shortcode"
                           id="mo_saml_add_shortcode" <?php if ( mo_saml_is_sp_configured() )
						echo 'disabled title="Disabled. Configure your Service Provider"' ?>
                           value="true"> Check this option if you want to add a shortcode to your page.
                    <div id="mo_saml_add_shortcode_steps" hidden>
                        <table>
                            <tr>
                                <td>For PHP page:</td>
                                <td><code>&lt;?php echo do_shortcode('[MO_SAML_FORM]'); ?&gt;</code></td>
                            </tr>
                            <tr>
                                <td style="display:block;width:100px">
                                    For HTML page:
                                </td>
                                <td><code><?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) { ?>
                                            &lt;a href="<?php echo site_url() . '/?option=saml_user_login' ?>"&gt;Login with IdP&lt;/a&gt;
										<?php } else { ?>
                                            &lt;a href="<?php echo get_option( 'host_name' ) . "/moas/rest/saml/request?id=" . get_option( 'mo_gsuite_customer_validation_admin_customer_key' ) . "&returnurl=" . urlencode( site_url() . '/?option=readsamllogin' ) ?>"/&gt;Login with IdP&lt;/a&gt;
										<?php } ?></code></td>
                            </tr>
                        </table>
                    </div>
				<?php } else { ?>
                    <input type="checkbox" style="background: #DCDAD1;"
                           disabled <?php if (  ! mo_saml_is_sp_configured() ){

						echo 'disabled title="Disabled. Configure your Service Provider"'; }?> value="true"> <span
                            style="color: red">*</span>Check this option if you want to add a shortcode to your page.
                    <br/>
				<?php } ?>
            </div>
            <div style="display:block;text-align:center;margin:2%;">
                <input type="button"
                       onclick="window.location.href='<?php echo wp_logout_url( site_url() ); ?>'" <?php if ( ! mo_saml_is_sp_configured() )
					echo 'disabled title="Disabled. Configure your Service Provider"' ?>
                       class="button button-primary button-large" value="Log Out and Test">
            </div>
			<?php if ( get_option( 'mo_saml_free_version' ) ) { ?>
                <span style="color:red;">*</span>These options are configurable in the <a
                        href="<?php echo admin_url( 'admin.php?page=gsuitepricing' ); ?>"><b>standard,
                        premium and enterprise</b></a> version of the plugin.</h3>
                <br/><br/>
			<?php } ?>
        </div>
        <br/>

    </div>
<?php
echo '<script>
jQuery("#redirect_to_idp").click(function (e) {
		e.preventDefault;
        jQuery("#redirect_to_idp_desc").slideToggle(400);
    });
	
	//redirect to idp
	jQuery("#registered_only_access").click(function (e) {
		e.preventDefault;
        jQuery("#registered_only_access_desc").slideToggle(400);
    });
	
	//redirect to idp
	jQuery("#force_authentication_with_idp").click(function (e) {
		e.preventDefault;
        jQuery("#force_authentication_with_idp_desc").slideToggle(400);
    });
	
</script>';

echo '<style>
.mo_saml_help_desc {
	font-size:13px;
	border-left:solid 2px rgba(128, 128, 128, 0.65);
	margin-top:10px;
	padding-left:10px;
}
</style>'
?>