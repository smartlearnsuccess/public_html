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

<?php $cartUrl = $this->Html->url(array('controller' => 'Carts', 'action' => 'viewajax')); ?>
<style type="text/css">
	.gold {
		color: #FFBF00;
	}

	.product {
		/*border: 1px solid #dddddd;*/
		/*height: 600px;*/
	}

	.product>img {
		max-width: 230px;
	}

	.product-rating {
		font-size: 20px;
		margin-bottom: 25px;
	}

	.product-title {
		font-size: 20px;
	}

	.product-desc {
		font-size: 14px;
	}

	.product-price {
		font-size: 22px;
	}

	.product-stock {
		/*color: #74DF00;*/
		font-size: 14px;
		/*margin-top: 10px;*/
	}

	.product-info {
		margin-top: 30px;
	}

	hr {
		margin: 15px 0 !important;
	}

	.service-image-left>center>img,
	.service-image-right>center>img {
		/*max-height: 320px;*/
		padding: 5px;
	}

	.section1 {
		background: #f7f7f7;
	}

	.back-page:hover {
		color: #434343;
	}

	.pdp-breadcrumb a {
		text-transform: capitalize;
		position: absolute;
		left: 0;
	}

	.content-product .content-heading {
		font-size: 18px;
		margin-top: 20px;
		font-family: dinnextprobold;
		color: #434343;
		margin-bottom: 8px;
		text-transform: uppercase;
		font-weight: bold;
		text-align: center;
	}

	span.pdp-breadcrumb {
		padding: 10px;
	}

	.back-page {
		position: absolute;
		z-index: 0;
		cursor: pointer;
		text-transform: inherit;
		color: #8b8a8a;
		padding: 5px;
	}

	.title-border.one {
		width: 160px;
		margin: 0 auto;
		text-transform: uppercase;
		font-family: dinnextprobold;
		color: #434343;
		font-size: 20px;
		font-weight: bold;
	}

	h2.title-border {
		width: 235px;
		margin: 0 auto;
		text-transform: uppercase;
		color: #434343;
		font-size: 20px;
		font-weight: bold;
	}

	.product.col-md-8.service-image-left {
		text-align: center;
	}

	img#item-display {
		width: 100%;
		max-width: 512px;
		max-height: 512px;
		padding: 10px;
	}

	.page-heading {
		padding: 10px;
	}

	.img-thumbnail {
		min-height: 235px;
	}

	@media (max-width: 767px) {
		.img-thumbnail {
			min-height: auto;
		}

		span.text-info {
			font-size: 12px;
		}
	}

	a.btn.btn-success.shopCart {
		text-transform: uppercase;
	}

	.product_description {
		background: #fff;
		padding: 10px;
		margin-bottom: 10px;
	}

	.addtocart {
		text-align: center;
		margin-bottom: 20px;
	}

	.flex-viewport {
		background: #f7f7f7;
	}

	.flexslider {
		box-shadow: none;
	}

	.mycontainer {
		padding: 10px;
		background: #fff;
		border: 1px solid #ddd;
		box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2)
	}

	.singleproduct {}
</style>
<section class="container mycontainer singleproduct" style="margin-top:200px;">


	<div class="item-container">
		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<span class="pdp-breadcrumb d-block p-0">

						<a href="<?php echo $this->Html->url(array('controller' => 'Packages', 'action' => 'index')) ?>"
							class="back-page position-static"><i class="fa fa-chevron-left" aria-hidden="true"></i>
							<?php echo __('Back to Packages'); ?></a>
					</span>
				</div>
			</div>

			<div class="mt-4 row">

				<div class="product col-md-6 service-image-left p-0">

					<div class="mycontainer">

						<center>
							<?php if (strlen($post['Package']['photo']) > 0) {
								$photo = "package/" . $post['Package']['photo'];
							} else {
								$photo = "nia.png";
							} ?>
							<?php echo $this->Html->image($photo, array('alt' => $post['Package']['name'], 'id' => 'item-display')); ?>
						</center>
					</div>
				</div>
				<div class="col-md-6 summery-block">
					<div class="mycontainer">
						<div class="product-title"><?php echo h($post['Package']['name']); ?></div>
						<hr>
						<div class="product-price">
							<?php if ($post['Package']['show_amount'] != $post['Package']['amount']) { ?>
								<span class="text-danger">
									<strong>
										<strike><?php echo "$" . $post['Package']['show_amount']; ?></strike></strong>
								</span>
							<?php } ?>
							<span class="text-success"><big><strong> <?php if ($post['Package']['package_type'] != 'F') {
								echo "$" . $post['Package']['amount'];
							} else {
								echo '&nbsp;';
							} ?></strong></big></span>
						</div>
						<div class="product-stock">
							<strong><?php echo __('Expiry'); ?> :</strong>
							<span class="text-info"><strong>
									<?php if ($post['Package']['expiry_days'] == 0) {
										echo __('Unlimited');
									} else {
										echo $post['Package']['expiry_days'];
									}
									?>
									<?php echo __('Days'); ?>
								</strong></span>
						</div>
						<div class="product-stock">
							<strong><?php echo __('Exams'); ?> :</strong> <span class="text-info"><strong>
									<?php
									if (!empty($post['Exam'])) {
										foreach ($post['Exam'] as $examName):
											echo h($examName['name']); ?> |
										<?php endforeach;
										unset($examName);
										unset($examName);
									} else {
										echo __('No Exam Found');
									}

									?>

								</strong></span>
						</div>
						<hr>
						<?php $url = $this->Html->url(array('controller' => 'Carts', 'action' => 'buy'));
						$id = $post['Package']['id']; ?>
						<div class="addtocart">

							<?php
							$url = $this->Html->url(array('controller' => 'Carts', 'action' => 'buy'));
							if ($post['Package']['package_type'] == "F") {
								echo $this->Html->link('<span class="fa fa-play"></span>&nbsp;' . __('Start Now'), array('controller' => 'Packages', 'action' => 'startnow', $id), array('escape' => false, 'class' => 'btn btn-success btn-block'));
							} else {
								echo $this->Html->link('<span class="fa fa-shopping-cart"></span>&nbsp;' . __('Add to Cart'), 'javascript:void(0);', array('onclick' => "shopCart('$id');", 'rel' => $url, 'escape' => false, 'class' => 'btn btn-success btn-block shopCart', 'id' => 'addtocart' . $id));
							}
							?>
							<?php echo $this->Html->link('<span class="fa fa-share-alt"></span>&nbsp;' . __('Share'), 'javascript:void(0)', array('data-toggle' => 'modal', 'data-target' => '#shareModal', 'escape' => false, 'class' => 'btn btn-warning btn-block share-btn')); ?>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<section class="section">
		<div class="container mycontainer">
			<div class="page-heading">
				<div class="widget">
					<h2 class="title-border"><?php echo __('Product Description'); ?></h2>
				</div>
			</div>
			<div class="product_description">
				<?php echo str_replace("<script", "", $post['Package']['description']); ?>
			</div>
		</div>
	</section>

	<div class="container-fluid">
		<div class="col-md-12 product-info">
			<div class="col-md-12">
				<div class="page-heading">
					<div class="widget">
						<h2 class="title-border"><?php echo __('Recommended Packages'); ?></h2>
					</div>
				</div>
				<div class="flexslider carousel">
					<ul class="slides">
						<?php
						foreach ($Packages as $Packagesvalue) {
							$Package_id = $Packagesvalue['Package']['id'];
							$Packagesvalue['Package']['name'];
							$Packagesvalue['Package']['photo'];
							if ($post['Package']['id'] != $Package_id) {
								?>
								<li>
									<div class="col-md-12">
										<a
											href="<?php echo $this->Html->url(array('action' => 'singleproduct', $Package_id, Inflector::slug(strtolower($Packagesvalue['Package']['name']), "-"))); ?>">
											<div class="img-thumbnail">
												<?php if (strlen($Packagesvalue['Package']['photo']) > 0) {
													$photo1 = "package/" . $Packagesvalue['Package']['photo'];
												} else {
													$photo1 = "nia.png";
												} ?>
												<?php echo $this->Html->image($photo1, array('alt' => $post['Package']['name'])); ?>
											</div>
											<div style="clear: both;"></div>
											<span class="text-info"><?php echo $Packagesvalue['Package']['name']; ?></span>
										</a>
									</div>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>

			</div>
		</div>
	</div>
</section>
<br><br><br><br><br>

<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-content">
	</div>
</div>
<script>
	function shopCart(selectedValue) {
		$(document).ready(function () {
			var targetUrl = $('#addtocart' + selectedValue).attr('rel') + '?prodId=' + selectedValue;
			$.ajax({
				type: 'get',
				url: targetUrl,
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				},
				success: function (response) {
					if (response) {
						setTimeout(function () {
							var checkoutUrl =
								'<?php echo $this->Html->url(array('controller' => 'Carts', 'action' => 'View')) ?>';
							window.location.href = checkoutUrl;
						}, 100);
					}
				},
				error: function (e) {

				}
			});
		});
	}
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('.flexslider').flexslider({
			animation: "slide",
			animationLoop: false,
			itemWidth: 265,
			itemMargin: 5,
			minItems: 1,
			maxItems: 4,
		});
	});
</script>

<div style="clear: both;"></div>


<script type="text/javascript">
	$(window).on('load', function () {
		//$('#shareModal').modal('show');
	});
</script>


<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span>&nbsp;
				</button>
				<h6 class="modal-title" id="exampleModalLabel"><?php echo __('Share Package'); ?></h6>
			</div>
			<div class="modal-body">
				<!-- Go to www.addthis.com/dashboard to customize your tools -->
				<script type="text/javascript"
					src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-574048d3b7d7d507"></script>

				<!-- Go to www.addthis.com/dashboard to customize your tools -->
				<div class="addthis_sharing_toolbox"></div>

			</div>
		</div>
	</div>
</div>