<?php
// echo $this->Html->css('/app/webroot/design300/css/bootstrap.min');
?>
<?php
$this->Js->JqueryEngine->jQueryObject = 'jQuery';
// Paginator options
$this->Paginator->options(
	array(
		'update' => '#resultDiv',
		'evalScripts' => true,
	)
);

?>
<style>
	.package-item-class {
		min-height: 200px;
		height: 200px;
		width: 200px;
		min-width: 200px
	}

	.member-card {
		background-color: #eee;
	}

	.team .member {
		background-color: #eee;
	}

	.member-card:hover {
		transform: scale(1.025);
		box-shadow: rgba(0, 0, 0, 0.24) 0px 5px 10px
	}
</style>

<section class="section">
	<div class="container mycontainer">
		<div id="resultDiv">
			<div class="page-heading" style="padding-top:100px;">
				<div class="widget">
					<h2 class="title-border"><?php echo __('Packages'); ?></h2>
				</div>
			</div>


			<div class="team">
				<div class="row">
					<?php echo $this->Session->flash();
					$cartUrl = $this->Html->url(array('controller' => 'Carts', 'action' => 'viewajax')); ?>
					<?php foreach ($Package as $post):
						$id = $post['Package']['id'];
						if (strlen($post['Package']['photo']) > 0) {
							$photo = "package/" . $post['Package']['photo'];
						} else {
							$photo = "nia.png";
						}
						$viewUrl = $this->Html->url(array('controller' => 'Packages', 'action' => "view", $id)); ?>
						<div class="col-lg-3 col-md-6 col-6 d-flex align-items-stretch">
							<div class="member member-card" data-aos="fade-up" data-aos-delay="100">
								<div class="member-img">


									<?php echo $this->Html->image(
										$photo,
										array(
											'alt' => h($post['Package']['name']),
											'class' => '
					                        group list-group-image PackageImg package-item-class',
										)
									); ?>

									<div class="social">
										<?php
										$url = $this->Html->url(array('controller' => 'Carts', 'action' => 'buy'));
										if ($post['Package']['package_type'] == "F") {
											echo $this->Html->link('<span class="fa fa-play"></span>' . __(''), array('controller' => 'Packages', 'action' => 'startnow', $id), array('escape' => false, 'class' => 'btn btn-success'));
										} else {
											echo $this->Html->link('<span class="fa fa-shopping-cart"></span>' . __(''), 'javascript:void(0);', array('onclick' => "shopCart('$id');", 'rel' => $url, 'escape' => false, 'class' => 'btn btn-success shopCart', 'id' => 'addtocart' . $id));
										}

										?>
										<a class=""
											href="<?php echo $this->Html->url(array('controller' => 'Packages', 'action' => 'singleproduct', $id, Inflector::slug(strtolower($post['Package']['name']), "-"))) ?>">
											<span class="fa fa-info-circle"></span>

										</a>
									</div>
								</div>
								<div class="member-info">
									<h4><?php echo h($post['Package']['name']); ?></h4>

									<span class="d-flex">
										<?php if ($post['Package']['show_amount'] != $post['Package']['amount']) { ?>

											<span
												class="text-danger text-decoration-line-through"><?php echo "$" . $post['Package']['show_amount']; ?></span>


										<?php } ?>

										<span id="pac<?php echo $id ?>" class="ms-1 fw-bold"
											style="color: black; font-size: 14px">
											<?php if ($post['Package']['package_type'] == "P") {
												echo "$" . $post['Package']['amount'];
											} else {
												echo '&nbsp;';
											} ?>
										</span>
									</span>
								</div>
							</div>
						</div>
					<?php endforeach;
					unset($value); ?>

				</div>
			</div>



			<div class="col-sm-12">
				<?php echo $this->element('pagination', array('IsSearch' => 'No', 'IsDropdown' => 'No')); ?>
			</div>
		</div>
		<div class="modal fade" id="targetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			aria-hidden="true">
			<div class="modal-content">
			</div>
		</div>
	</div>
</section>
<script>
	function shopCart(selectedValue) {


		var targetUrl = $('#addtocart' + selectedValue).attr('rel') + '?prodId=' + selectedValue;

		$.ajax({
			type: 'get',
			url: targetUrl,
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			},
			success: function (response) {
				if (response) {

					$('#cart-counter').html(response);
					// var cart = $('#products_animation_id');
					// var imgtodrag = $('#addtocart' + selectedValue).parent().parent(
					// 	'.member-img').find("img").eq(0);
					// if (imgtodrag) {
					// 	console.log("this is image to drap", imgtodrag);
					// 	var imgclone = imgtodrag.clone()
					// 		.offset({
					// 			top: imgtodrag.offset().top,
					// 			left: imgtodrag.offset().left
					// 		})
					// 		.css({
					// 			'opacity': '0.9',
					// 			'position': 'absolute',
					// 			'height': '250px',
					// 			'width': '250px',
					// 			'z-index': '100',
					// 			'border': '1px solid #000',
					// 			'border-radius': '100%'


					// 		})
					// 		.appendTo($('body'))
					// 		.animate({
					// 			'top': cart.offset().top + 10,
					// 			'left': cart.offset().left + 10,
					// 			'width': 75,
					// 			'height': 75
					// 		}, 1000, 'easeInOutExpo');

					// 	setTimeout(function () {
					// 		cart.effect("shake", {
					// 			times: 2
					// 		}, 200);
					// 	}, 1500);

					// 	imgclone.animate({
					// 		'width': 0,
					// 		'height': 0
					// 	}, function () {
					// 		$(this).detach()
					// 	});

					// }

					setTimeout(function () {
						var checkoutUrl =
							'<?php echo $this->Html->url(array('controller' => 'Carts', 'action' => 'View')) ?>';
						window.location.href = checkoutUrl;
					}, 1200);
				}

			},
			error: function (e) {

			}
		});

	}
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#sort').change(function () {
			var selectedValue = $(this).val();
			post_req.action = selectedValue;
			post_req.submit();
		});
	});
</script>
<?php
echo $this->fetch('script');

echo $this->Html->script('/app/webroot/design700/js/jquery-ui.min.js');

echo $this->Html->script('/app/webroot/js/v2/bootstrap.bundle.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/owl.carousel.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/aos', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/jquery.waypoints', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/jquery.counterup.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/select2.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/slick', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/swiper.min', array('defer'));
echo $this->Html->script('/app/webroot/js/v2/script', array('defer'));

echo $this->Html->script('/app/webroot/design300/js/jquery.min', array());
echo $this->Html->script('/app/webroot/design300/js/jquery.flexslider-min', array('defer'));
echo $this->Html->script('/app/webroot/js/html5shiv.js', array('defer'));
echo $this->Html->script('/app/webroot/js/respond.min', array('defer'));
echo $this->Html->script('/app/webroot/js/jquery.validationEngine-en', array('defer'));
echo $this->Html->script('/app/webroot/js/jquery.validationEngine', array('defer'));
echo $this->Html->script('/app/webroot/design300/js/bootstrap.min', array('defer'));
echo $this->Html->script('/app/webroot/design300/js/rs-slider', array('defer'));
echo $this->Html->script('/app/webroot/js/bootstrap-multiselect', array('defer'));
echo $this->Html->script('/app/webroot/js/waiting-dialog.min', array('defer'));
echo $this->Html->script("/app/webroot/js/langs/$configLanguage", array('defer'));
// echo $this->Html->script('app/webroot/js/custom.min', array('defer'));
?>





?>