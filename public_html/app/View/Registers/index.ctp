<?php
// echo $this->Html->css('/app/webroot/design300/css/bootstrap.min');
echo $this->Html->script('/app/webroot/js/bootstrap-filestyle.min'); ?>
<script type="text/javascript">
	$(":file").filestyle();
</script>
<style type="text/css">
	.hr-text {
		line-height: 1em;
		position: relative;
		outline: 0;
		border: 0;
		color: black;
		text-align: center;
		height: 1.5em;
		opacity: .5;
	}

	.hr-text:before {
		content: '';
		background: linear-gradient(to right, transparent, #818078, transparent);
		position: absolute;
		left: 0;
		top: 50%;
		width: 100%;
		height: 1px;
	}

	.hr-text:after {
		content: attr(data-content);
		position: relative;
		display: inline-block;
		color: black;
		padding: 0 .5em;
		line-height: 1.5em;
		color: gray;
		background-color: #e8e8e8;
	}

	.text_center {
		text-align: center;
	}

	.mbs:hover {
		color: white;
	}

	hr {
		margin: 15px 0;
	}

	.btn-group {
		width: 100%;
	}

	button.multiselect.dropdown-toggle.btn.btn-default {
		width: 100%;
	}

	.scroll-class-register::-webkit-scrollbar {
		width: 8px;

	}

	.scroll-class-register::-webkit-scrollbar-track {
		background: transparent;
		border-radius: 5px;
		margin-top: 5px;
	}

	.scroll-class-register::-webkit-scrollbar-thumb {
		background: #52606e;
		border-radius: 5px;
	}

	.scroll-class-register::-webkit-scrollbar-thumb:hover {
		background: #555;
	}

	@media (max-width: 767px) {
		.pageMarginTab {
			margin-top: 50px;
		}
	}
</style>
<section class="section registration-wrapper mt-5">
	<div class="container mycontainer ">
		<div class="RegisterPage">
			<div class="row justify-content-center">

				<div class="col-md-6 pageMarginTab">
					<ul class="nav nav-pills">

						<li class="p-2 w-100">
							<?php echo __('Register'); ?>
						</li>

					</ul>
					<div class="tab-content clearfix pt-3">

						<div class="tab-pane active" id="1a" style="margin-top: 15px;">

							<?php echo $this->Form->create('Register', array('url' => array('action' => 'index', '1'), 'name' => 'post_req_register', 'id' => 'post_req_register', 'class' => 'form-horizontal', 'role' => 'form', 'type' => 'file', 'method' => 'post')); ?>
							<div class="form-group">
								<?php echo $this->Form->input('email', array('id' => 'register_email', 'label' => false, 'class' => 'form-control', 'placeholder' => '* ' . __('Email'), 'div' => false)); ?>
							</div>
							<div class="form-group">

								<?php echo $this->Form->select('StudentGroup.group_name', $group_id, array('multiple' => true, 'label' => false, 'class' => 'form-control multiselectgrp  scroll-class-register', 'placeholder' => __('Group'), 'div' => false)); ?>

							</div>
							<div class="form-group">
								<?php echo $this->Form->input('name', array('label' => false, 'class' => 'form-control', 'placeholder' => '* ' . __('Name'), 'div' => false)); ?>
							</div>
							<div class="form-group">
								<?php echo $this->Form->input('password', array('id' => 'register_password', 'label' => false, 'class' => 'form-control', 'placeholder' => '* ' . __('Password'), 'minlength' => '4', 'maxlength' => '15', 'div' => false)); ?>
							</div>
							<div class="form-group">
								<?php echo $this->Form->input('address', array('label' => false, 'class' => 'form-control', 'placeholder' => '* ' . __('Address'), 'div' => false)); ?>
							</div>
							<div class="form-group">
								<?php echo $this->Form->input('phone', array('label' => false, 'class' => 'form-control', 'placeholder' => '* ' . __('Mobile Number'), 'div' => false)); ?>
							</div>
							<div class="form-group">

								<?php echo $this->Form->input('guardian_phone', array('label' => false, 'class' => 'form-control', 'placeholder' => __('Alternate Number'), 'div' => false)); ?>
							</div>
							<div class="form-group">

								<?php echo $this->Form->input('photo', array('type' => 'file', 'label' => false, 'class' => 'form-control filestyle', 'div' => false, 'data-text' => __("Choose Photo"), 'data-btnClass' => "btn-primary")); ?>
							</div>
							<div class="form-group">
								<?php echo $this->Captcha->render($captchaSettings); ?>

							</div>
							<div class="form-group">
								<div class="submit-buttton">
									<button type="submit" class="btn btn-success w-100"><span class="fa fa-user"></span>
										<?php echo __('Submit'); ?></button>
								</div>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>


					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<br>