<div class="fdLoader"></div>
<?php
include PJ_VIEWS_PATH . 'pjFront/elements/locale.php'; 
$index = $_GET['index'];
?>
<div class="fdContainerInner">
	<div id="fdMain_<?php echo $index; ?>" class="fdMain">
		<div class="fdFormContainer">
			<div class="fdFormHeading"><?php echo strtoupper(__('front_login_to_account', true, false)); ?></div>
			<form id="fdLoginForm_<?php echo $index;?>" action="" method="post" class="fdForm">
				<p class="fdParagraph">
					<label class="fdTitle"><?php __('front_email_address'); ?> <span class="fdRed">*</span>:</label>
					<input type="text" name="login_email" class="fdText fdW100p" data-required="<?php __('front_email_address_required');?>" data-email="<?php __('front_email_not_valid');?>"/>
				</p>
				<p class="fdParagraph">
					<label class="fdTitle"><span class="fdBlock fdFloatLeft"><?php __('front_password'); ?> <span class="fdRed">*</span>:</span><span class="fdBlock fdFloatRight"><a class="fdForogtPassword" href="#"><?php __('front_forgot_password');?></a></span></label>
					<input type="password" name="login_password" class="fdText fdW100p" data-required="<?php __('front_password_required');?>"/>
				</p>
			</form>
			<div class="fdOverflow fdButtonContainer">
				<a href="#" class="fdButton fdNormalButton fdButtonGetCategories fdFloatLeft"><?php __('front_button_back');?></a>
				<a href="#" class="fdButton fdOrangeButton fdButtonNext fdButtonLogin fdFloatRight"><?php __('front_button_login');?></a>
			</div>
			<div id="fdLoginMessage_<?php echo $index;?>" class="fdLoginMessage"></div>
			<div class="fdOverflow fdButtonContainer">
				<label><?php __('front_do_not_have_account');?>&nbsp;<a class="fdContinue" href="#"><?php __('front_continue');?></a></label>
			</div>
		</div>
	</div>
	<div id="fdCart_<?php echo $index; ?>" class="fdCart"><?php include PJ_VIEWS_PATH . 'pjFront/elements/cart.php'; ?></div>
</div>