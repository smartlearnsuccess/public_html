<?php
echo $this->Html->css('/app/webroot/css/font-awesome-5.7.2');
// echo $this->Html->css('app/webroot/design300/css/bootstrap.min');
echo $this->Html->css('/app/webroot/design300/css/font');
echo $this->Html->css('/app/webroot/design300/css/settings');
echo $this->Html->css('/app/webroot/design300/css/style');
echo $this->Html->css('/app/webroot/design300/css/slider');
echo $this->Html->css('/app/webroot/css/validationEngine.jquery');
echo $this->Html->css('/app/webroot/css/bootstrap-multiselect');
echo $this->Html->css('style');
echo $this->fetch('meta');
echo $this->fetch('css');
/* ========================== */
echo $this->Html->css('/app/webroot/css/v2/bootstrap.min');
echo $this->Html->css('/app/webroot/css/v2/feather');
echo $this->Html->css('/app/webroot/css/v2/owl.carousel.min');
echo $this->Html->css('/app/webroot/css/v2/owl.theme.default.min');
echo $this->Html->css('/app/webroot/css/v2/slick');
echo $this->Html->css('/app/webroot/css/v2/slick-theme');
echo $this->Html->css('/app/webroot/css/v2/select2.min');
echo $this->Html->css('/app/webroot/css/v2/swiper.min');
//echo $this->Html->css('/app/webroot/css/v2/aos');
//echo $this->Html->css('/app/webroot/css/v2/style_v1');
echo $this->Html->css('/app/webroot/css/v2/style');
echo $this->Html->css('/app/webroot/css/v2/slider');
echo $this->Html->script('/app/webroot/design300/js/jquery.min', array());
// echo $this->Html->script('app/webroot/js/v2/jquery-3.6.0.min', array('defer'));
// echo $this->Html->script('app/webroot/js/v2/custom.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/jquery.flexslider-min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/en', array('defer'));
?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#coupon_code").click(function () {
			$("#ccp").toggle();
		});

		$("#ccp").hide();

		$("#paypal").click(function () {
			$("#loadingImage").show();
		});
		$("#ccavenue").click(function () {
			$("#loadingImage").show();
		});
		$("#payu").click(function () {
			$("#loadingImage").show();
		});

		function response_value(req) {
			$("#apply_button").button('reset');
			var a = req;
			if (a.indexOf('^') > -1) {
				response_msg = a.split("^");
				if (response_msg[1].length > 0) {
					$('#result_details').html(response_msg[0]);
					$('#result_details0').html('');
					$('.result_details1').html('<?php echo $currency; ?>' + response_msg[1]);
					if (response_msg[1] <= 0) {
						$('#proceed_to_pay').hide();
						$('#make_payment').hide();
						$('#cart').html(
							'<table id="cart" class="table table-hover table-condensed" style="margin:0px"><tbody><tr><td class="center"><div class=""><?php echo $this->Html->link(__("Free Checkout"), array("controller" => "checkouts", "action" => "paymentGateway", "FREE"), array("class" => "btn btn-lg btn-success", "escape" => false)); ?></div></td></tr></table>'
						);
					}
				}
				if (response_msg[2].length > 0) {
					$('#result_details2').html(response_msg[2]);
				}
			} else {
				$('#result_details0').html(a);
			}
		}

		$("#apply_button").click(function () {
			$("#apply_button").button('loading');
			var str = $("#post_req1").serialize();
			$.ajax({
				url: "<?php echo $this->Html->url(array('action' => 'coupon')); ?>",
				data: str,
				type: "POST",
				context: document.body,
				success: function (data) {
					response_value(data);
					//location.reload();
				}
			});
		});

		$("#paymentGateway").hide();
		$("#paymentcheck").hide();


		$("#proceed_to_pay").click(function () {
			$("#paymentGateway").toggle();
			$("#review_order").toggle();
			$("#paymentcheck").show();

			$("#review").removeClass("bl");
			$("#review").addClass("wh");
			$("#make_payment").addClass("bl");
			$("#make_payment").removeClass("wh");
			$(window).scrollTop(0);

		});

		$("#review").click(function () {
			$("#paymentGateway").toggle();
			$("#review_order").toggle();

			$("#review").removeClass("wh");
			$("#review").addClass("bl");
			$("#make_payment").addClass("wh");
			$("#make_payment").removeClass("bl");
		});


	});
</script>
<style type="text/css">
	strong {
		cursor: pointer;
	}

	.widget>a {
		color: #3a99d9;
	}

	.coupon_applied {
		color: #303539;
		font-weight: 600;
		margin-bottom: 3px;
		text-transform: uppercase;
		font-size: 14px;
		border-bottom-style: ridge;
	}

	.cart-summary {
		text-transform: uppercase;
		font-size: 14px;
		font-weight: 600;
	}

	.total {
		font-size: 15px;
		font-weight: 600;
		padding: 10px;
	}

	.cart-summary .line {
		border-bottom-style: solid;
		border-bottom-width: thin;
		padding-top: 0px;
	}

	.mamount {
		padding: 0;
		text-align: right;
	}

	.table>tbody>tr>td,
	.table>tfoot>tr>td {
		vertical-align: middle;
	}

	.center {
		text-align: center;
	}

	@media screen and (max-width: 600px) {
		.right {
			float: right;
		}

		.center {
			text-align: left;
		}

		.actions .btn {
			width: 36%;
			margin: 1.5em 0;
		}

		.actions .btn-info {
			float: left;
		}

		.actions .btn-danger {
			float: right;
		}

	}

	.SECURED {
		color: #434343;
	}

	b.SECURED>i {
		font-size: 20px;
	}

	.checkouttable {
		border: 1px solid #ccc;
	}

	.checkout-proceed {
		border-top: 1px solid #ccc;
		padding: 16px;
	}

	a {
		color: #337ab7;
	}

	button#proceed_to_pay {
		margin-right: 30px;
		font-size: 16px;
	}

	.checkout-proceed>a {
		font-size: 16px;
	}

	.login_id {
		padding: 16px;
		margin-bottom: 10px;
		font-size: 15px;
	}

	.review_order {
		padding: 16px;
		color: #fff;
	}

	span.login_id>strong {
		font-size: 20px;
	}

	span.login_id>i {
		color: #489a11;
	}

	.login_id.make_payment {
		margin: 10px 0 0 0;
	}

	.bl {
		background: #000;
		color: #fff;
	}

	.wh {
		background: #f7f7f9;
		color: #000
	}

	.col-sm-3 {
		font-weight: normal;
	}

	.col-sm-9 {
		font-weight: normal;
	}

	input#apply_button {
		margin-top: 5px;
	}

	button#coupon_code:hover {
		background: #000;
		color: #fff;
	}

	.summary-heading {
		font-size: 16px;
		font-weight: bold;
		margin-top: 15px;
	}
</style>
<section class="section my-checkout-page">
	<div class="container mycontainer">
		<div class="">
			<div class="row" style="min-height: 420px;">
				<?php echo $this->Session->flash(); ?>
				<div class="col-sm-9">
					<div class="col-sm-4 p0">
						<div class="panel-heading">
							<div class="widget view-cart">
								<a
									href="<?php echo $this->Html->url(array('controller' => 'Carts', 'action' => 'View')); ?>"><?php echo __('VIEW CART'); ?></a>
							</div>
						</div>
					</div>
					<div class="col-sm-4 p0">
						<div class="panel-heading secure-chckout">
							<div class="widget">
								<b class="SECURED"><i class="fa fa-lock"></i> <?php echo __('SECURED CHECKOUT'); ?></b>
							</div>
						</div>
					</div>

					<div style="clear: both;"></div>
					<div id="review" class="review_order bl">
						<span class="login_id">
							<i id="paymentcheck" class="fa fa-check" aria-hidden="true"></i>
							<strong>3. <?php echo __('REVIEW ORDER'); ?></strong>
						</span>
					</div>

					<div class="checkouttable">
						<div id="review_order">
							<table id="cart" class="table table-hover table-condensed">
								<thead>
									<tr>
										<th><?php echo __('Product'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php $total = 0;
									$totalQuantity = 0; ?>
									<?php foreach ($products as $product):
										if (strlen($product['Package']['photo']) > 0) {
											$photo = "package_thumb/" . $product['Package']['photo'];
										} else {
											$photo = "nia.png";
										} ?>
										<tr>
											<td>
												<div class="row">
													<div class="col-sm-3 col-xs-12">
														<?php echo $this->Html->image($photo, array('alt' => h($product['Package']['name']), 'class' => 'img-responsive', 'width' => '100', 'height' => '100')); ?>
													</div>
													<div class="col-sm-9 col-xs-12">
														<h4 class="nomargin">
															<strong><?php echo h($product['Package']['name']); ?></strong>
														</h4>
														<div>
															<?php echo $this->Form->hidden('package_id.', array('value' => $product['Package']['id'])); ?>
															<strong><?php echo __('Qty'); ?>: </strong><label
																style="background-color:#eee"
																class=""><?php echo $product['Package']['count']; ?></label>
														</div>
														<div><strong
																style="font-size:17px;"><?php echo __('Amount'); ?><?php if ($product['Package']['show_amount'] != $product['Package']['amount']) { ?>
																	<strike><span style="font-weight: normal;">
																			<?php echo $currency . 1 * $product['Package']['show_amount']; ?></span>
																	</strike><?php } ?>
																<?php echo $currency . $product['Package']['count'] * $product['Package']['amount']; ?>
															</strong></div>
														<div>
															<span><strong><?php echo __('Exams'); ?>: </strong></span><span
																class="text-success"><strong><?php foreach ($product['Exam'] as $examName):
																	echo h($examName['name']); ?> |
																	<?php endforeach;
																unset($examName);
																unset($examName); ?></strong></span>
														</div>
														<div style="height: 150px;overflow-y: auto;">
															<p align="justify">
																<?php echo $product['Package']['description']; ?>
															</p>
														</div>
													</div>
												</div>
											</td>
										</tr>
										<?php $total = $total + ($product['Package']['count'] * $product['Package']['amount']);
										$totalQuantity = $totalQuantity + $product['Package']['count']; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
							<div class="checkout-proceed row">
								<div class="col-sm-6 col-xs-12 edit-cart-btn">
									<a
										href="<?php echo $this->html->url(array('controller' => 'Carts', 'action' => 'View')); ?>"><?php echo __('EDIT CART'); ?></a>
								</div>
								<div class="col-sm-6 col-xs-12 checkout-button">
									<button id="proceed_to_pay" type="button"
										class="btn btn-success text-uppercase"><?php echo __('proceed to pay'); ?>
									</button>
								</div>

							</div>
						</div>
					</div>
					<div id="make_payment" class="login_id make_payment">
						<span class="login_id"><strong>4. <?php echo __('MAKE PAYMENT'); ?></strong></span>
					</div>
					<center>
						<div id="loadingImage" style="display: none;">
							<?php echo $this->Html->image('loading-lg.gif'); ?>
						</div>
					</center>
					<div id="paymentGateway" class="checkouttable">
						<table id="cart" class="table table-hover table-condensed" style="margin:0px">
							<tbody>
								<?php if ($products) { ?>
									<tr>
										<?php if ($totalAmount == 0) { ?>
											<td class="center">
												<div class="">
													<?php echo $this->Html->link(__('Free Checkout'), array('controller' => 'checkouts', 'action' => 'paymentGateway', 'FREE'), array('class' => 'btn btn-lg btn-success', 'escape' => false)); ?>
												</div>
											</td>
										<?php } else { ?>
											<?php if ($CAE == true) { ?>
												<td class="center">
													<div class="">
														<?php echo $this->Html->link($this->Html->image('ccavenue.png'), array('controller' => 'checkouts', 'action' => 'paymentGateway', 'CCAVENUE'), array('class' => 'img-thumbnail', 'escape' => false, 'id' => 'ccavenue')); ?>
													</div>
												</td>
											<?php } ?>

											<?php if ($PME == true) { ?>
												<td class="center">
													<div class="">
														<?php echo $this->Html->link($this->Html->image('payumoney.png'), array('controller' => 'checkouts', 'action' => 'paymentGateway', 'PAYUMONEY'), array('class' => 'img-thumbnail', 'escape' => false, 'id' => 'payu')); ?>
													</div>
												</td>
											<?php } ?>

											<?php if ($PPL == true) { ?>
												<td class="center">
													<div class="">
														<?php echo $this->Html->link($this->Html->image('paypal.png'), array('controller' => 'checkouts', 'action' => 'paymentGateway', 'PAYPAL'), array('class' => 'img-thumbnail', 'escape' => false, 'id' => 'paypal')); ?>
													</div>
												</td>
											<?php } ?>

										</tr>
									<?php }
								} ?>

							</tbody>
						</table>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="bg-summary"></div>
					<div class="cart-summerymain">
						<div>
							<div id="result_details0"></div>
							<div id="result_details">
								<?php if ($this->Session->check('couponArr')) {
									$couponArr = $this->Session->read('couponArr');
									$couponTotalAmount = $couponArr['couponTotalAmount'];
									$couponCode = $couponArr['couponCode']; ?>
									<div class="coupon_applied"><?php echo __("Coupon '%s' Applied", $couponCode); ?>
										&nbsp;<?php echo $this->Html->link(__('Remove'), array('controller' => 'checkouts', 'action' => 'couponDelete')); ?>
									</div>
								<?php } else {
									if ($totalAmount > 0) {
										echo $this->Form->create('Checkout', array('name' => 'post_req1', 'id' => 'post_req1')); ?>
										<button type="button" class="btn btn-block btn-info text-uppercase"
											id="coupon_code"><?php echo __('Apply Coupon'); ?></button>
										<div id="ccp" style="margin-top: 5px;">
											<input type="text" name="redeem_code" id="redeem_code" autocomplete="off" />
											<input type="button" name="apply" id="apply_button"
												value="<?php echo __('Apply Now'); ?>"
												data-loading-text="<?php echo __('Loading'); ?>..."
												class="btn btn-success btn-xs" />
										</div>
										<?php echo $this->Form->end();
									}
								} ?>
							</div>

						</div>
						<?php if ($totalAmount >= 0) { ?>
							<div class="cart-summary">
								<div class="summary-heading"><?php echo __('Order Summary'); ?></div>
								<div class="row m0 row-space">
									<div class="col-sm-6 col-xs-6  p0"><?php echo __('Price'); ?></div>
									<div class="col-sm-6 col-xs-6 mamount"><?php echo $currency . $total; ?></div>
								</div>
								<div class="row m0 bottom-space">
									<div class="col-sm-6 col-xs-6  p0"><?php echo __('Processing Fees'); ?></div>
									<div class="col-sm-6 col-xs-6  mamount"><span
											class="text-success"><?php echo __('Free'); ?></span></div>
								</div>
								<div id="result_details2"></div>
								<?php if ($this->Session->check('couponArr')) {
									$couponArr = $this->Session->read('couponArr');
									$couponTotalAmount = $couponArr['couponTotalAmount']; ?>
									<div class="col-sm-6 col-xs-6  p0"><?php echo __('Coupon Discount'); ?></div>
									<div class="col-sm-6 col-xs-6  mamount">
										-<?php echo $currency . $couponTotalAmount; ?></div>
								<?php } ?>
								<div class="col-sm-12 p0">
									<div class="line"></div>
								</div>
								<div class="row m0">
									<div class="col-sm-6 col-xs-6 total" style="padding-left:0">
										<b><?php echo __('Total'); ?></b>
									</div>
									<div class="col-sm-6 col-xs-6 total" style="padding-right: 0;text-align: right;">
										<div class="result_details1"><b><?php echo $currency . $totalAmount; ?></b></div>
									</div>
								</div>
								<div class="" style="font-weight: normal;">
									<?php echo __('Prices are inclusive of all taxes'); ?>
								</div>
							</div>
							<div style="clear: both;"></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>