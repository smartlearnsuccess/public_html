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

.resend-verify {
	text-decoration: none;
	font-size: small;
	color: chocolate;
}

.resend-verify:hover {
	text-decoration: none;
	color: coral;
}

@media (max-width: 767px) {
	.pageMarginTab {
		margin-top: 50px;
	}
}
</style>
<section class="section registration-wrapper mt-5 mb-5 pb-0">
	<div class="container mycontainer mb-5">
		<div class="RegisterPage">
			<div class="row justify-content-center">

				<div class="col-md-6 pageMarginTab">
					<ul class="nav nav-pills">

						<li class="p-2 w-100">
							<?php echo __('Login'); ?>
						</li>

					</ul>
					<div class="tab-content clearfix pt-3">

						<div class="tab-pane active" id="2a" style="margin-top: 15px;">

							<?php echo $this->Form->create('Register', array('url' => array('controller' => 'login', 'action' => 'login'), 'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'role' => 'form')); ?>


							<div class="form-group">

								<?php echo $this->Form->input('email', array('required' => true, 'type' => 'text', 'label' => false, 'class' => 'form-control', 'placeholder' => __('Email or Mobile Number'), 'div' => false)); ?>
							</div>
							<div class="form-group">
								<?php echo $this->Form->input('password', array('required' => true, 'label' => false, 'class' => 'form-control', 'placeholder' => __('Password'), 'value' => '', 'type' => 'password', 'div' => false)); ?>
							</div>
							<div class="clearfix"></div>


							<div class="row mb-4 mt-4">
								<div class="col d-flex justify-content-center">
									<!-- Checkbox -->
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="form2Example31"
											checked />
										<label class="form-check-label" for="form2Example31"> Remember me </label>
									</div>
								</div>

								<div class="col">
									<!-- Simple link -->
									<?php echo $this->Html->link(__('Forgot Password'), array('crm' => true, 'controller' => 'forgots', 'action' => 'password'), array('class' => '', 'escape' => false)); ?>
								</div>
							</div>

							<!-- Submit button -->
							<button type="submit" data-mdb-button-init data-mdb-ripple-init
								class="btn btn-success btn-block mb-4 mt-3"><?php echo __('Log in'); ?></button>

							<hr>
							<div class="form-group">
								<?php echo $this->Html->link(__('Re-Send Email Verification'), array('crm' => true, 'controller' => 'Emailverifications', 'action' => 'resend'), array('class' => 'btn-link resend-verify', 'escape' => false)); ?>
							</div>

							<!-- Register buttons -->
							<div class="text-center pb-5">
								<p><?php echo __("Don't have an account?"); ?> <a
										href="<?= $siteDomain ?>/Registers/index"><?php echo __('New User? Create Account'); ?></a>
								</p>

							</div>

							<?php echo $this->Form->end(); ?>
						</div>


					</div>
				</div>
			</div>

		</div>
	</div>
</section>

</br>