<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html lang="<?php echo $configLanguage; ?>" dir="<?php echo $dirType; ?>">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google-translate-customization" content="839d71f7ff6044d0-328a2dc5159d6aa2-gd17de6447c9ba810-f">
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $metaTitle; ?></title>
	<meta name="keyword" content="<?php echo $metaKeyword; ?>" />
	<meta name="description" content="<?php echo $metaContent; ?>" />
	<link rel="icon" type="image/png"
		href="<?php echo $this->Html->url(array('crm' => false, 'controller' => 'img')) . '/' . $sysSetting['Configuration']['favicon']; ?>">
	<link rel="stylesheet" type="text/css"
		href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"
		integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

	<?php
	echo $this->Html->css('/app/webroot/css/font-awesome-5.7.2');

	echo $this->Html->css('/app/webroot/assets/vendor/aos/aos.css');

	echo $this->Html->css('/app/webroot/design300/css/font');
	echo $this->Html->css('/app/webroot/design300/css/settings');
	echo $this->Html->css('/app/webroot/design300/css/style');
	echo $this->Html->css('/app/webroot/design300/css/slider');
	echo $this->Html->css('/app/webroot/css/validationEngine.jquery');
	echo $this->Html->css('/app/webroot/css/bootstrap-multiselect');
	// echo $this->Html->css('style');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	// /* ========================== */
	
	echo $this->Html->css('/app/webroot/css/v2/feather');
	echo $this->Html->css('/app/webroot/css/v2/owl.carousel.min');
	echo $this->Html->css('/app/webroot/css/v2/owl.theme.default.min');
	echo $this->Html->css('/app/webroot/css/v2/slick');
	echo $this->Html->css('/app/webroot/css/v2/slick-theme');
	echo $this->Html->css('/app/webroot/css/v2/select2.min');
	echo $this->Html->css('/app/webroot/css/v2/swiper.min');
	echo $this->Html->css('/app/webroot/css/v2/style');
	echo $this->Html->css('/app/webroot/css/v2/slider');

	echo $this->Html->css('/app/webroot/assets/vendor/bootstrap/css/bootstrap.min.css');
	echo $this->Html->css('/app/webroot/assets/vendor/bootstrap-icons/bootstrap-icons.css');
	echo $this->Html->css('/app/webroot/assets/vendor/boxicons/css/boxicons.min.css');
	echo $this->Html->css('/app/webroot/assets/vendor/glightbox/css/glightbox.min.css');
	echo $this->Html->css('/app/webroot/assets/vendor/remixicon/remixicon.css');
	echo $this->Html->css('/app/webroot/assets/vendor/swiper/swiper-bundle.min.css');
	echo $this->Html->css('/app/webroot/assets/css/style.css');


	echo $this->Html->script('/app/webroot/design300/js/jquery.min', array());
	echo $this->Html->script('/app/webroot/js/v2/jquery.flexslider-min', array('defer'));
	echo $this->Html->script('/app/webroot/js/v2/en', array('defer'));


	echo "<style>
        .empty-cart center>a>i.fa.fa-shopping-bag {
                border: 1px solid #ddd;
                font-size: 35px;
                color: #000;
                padding: 20px;
                border-radius: 100%;
                box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
        }
    </style>";
	if ($this->params['controller'] == "pages") {
		$this->params['controller'] = "";
	}

	$currentUrl = Router::url(null, true);



	?>
	<?php if ($translate > 0) { ?>
		<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({
					pageLanguage: 'en',
					layout: google.translate.TranslateElement.InlineLayout.SIMPLE
				}, 'google_translate_element');
			}
		</script>
		<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
		</script>
	<?php } ?>
	<style>
		.ulproducts_animation {
			width: 380px;
			background: #fff;
			z-index: 999999999;
			border: 1px solid #ddd;
			box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
		}

		#products_animation {
			visibility: visible !important;
			opacity: 1 !important;
			margin: 0 auto;
			width: 100%;
			box-shadow: unset;
			position: relative;
			z-index: 1;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function () {

			$("#products_animation_id").click(function (event) {
				$("#cart_items_list").toggle();

			});

		});
	</script>
</head>


<body>
	<!-- ======= Header ======= -->
	<header id="header" class="fixed-top d-flex align-items-center">
		<div class="container d-flex align-items-center">
			<div class="presento-logo me-auto">
				<?php if (strlen($frontLogo) > 0) { ?> 	<?php echo $this->Html->link($this->Html->image($frontLogo, array('alt' => $siteName, 'class' => 'img-responsive front-logo')), array('controller' => '/'), array('escape' => false));
				} else { ?> 	<?php echo $siteName; ?><?php } ?>
			</div>



			<nav id="navbar" class="navbar order-last order-lg-0">
				<ul>
					<li>
						<a <?php if ($currentUrl == $siteDomain) { ?> class="nav-link scrollto active" <?php } ?>
							class="nav-link scrollto" href="<?= $siteDomain ?>"><i class="fa fa-home"></i>&nbsp;Home</a>
					</li>
					<li class="dropdown">
						<a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/About-Us' || $currentUrl == $siteDomain . '/Contents/Pages/Profile') { ?> class="nav-link scrollto active" <?php } ?> href="#"><i
								class="fa fa-globe"></i>&nbsp;About&nbsp;<i class="fas fa-chevron-down"></i></a>

						<ul>
							<li><a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/About-Us') { ?> class="active"
									<?php } ?> style="justify-content: flex-start"
									href="<?= $siteDomain ?>/Contents/Pages/About-Us"><i></i>&nbsp;About
									Us</a>
							</li>
							<li><a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/Profile') { ?> class="active"
									<?php } ?> style="justify-content: flex-start;"
									href="<?= $siteDomain ?>/Contents/Pages/Profile"><i></i>&nbsp;Profile</a>
							</li>
						</ul>

					</li>
					<li><a <?php if ($currentUrl == $siteDomain . '/Packages/index/index') { ?>
								class="nav-link scrollto active" <?php } ?> class="nav-link scrollto"
							href="<?= $siteDomain ?>/Packages/index/index"><i
								class="fa fa-shopping-cart"></i>&nbsp;Packages</a></li>

					<li><a <?php if ($currentUrl == $siteDomain . '/Schedules/index') { ?>
								class="nav-link scrollto active" <?php } ?> class="nav-link scrollto"
							href="<?= $siteDomain ?>/Schedules/index"><i class="fa fa-calendar"></i>&nbsp;Schedules</a>
					</li>

					<ul class="nav header-navbar-rht">
						<div class="dropdown nav-item cart-nav">
							<a href="#" class="dropdown-toggle" id="products_animation_id" data-bs-toggle="dropdown"
								aria-expanded="false">
								<?php echo $this->Html->image('v2/cart.svg', array('class' => 'position-relative', 'alt' => 'Cart')); ?>
								<span
									class="position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger"
									id="cart-counter">
									<!-- <i class="badge bg-secondary rounded-pill" id="cart-counter"> -->
									<?php echo $cartCount; ?>
									<!-- </i> -->
								</span>
							</a>
							<div class="wishes-list dropdown-menu dropdown-menu-right"
								aria-labelledby="products_animation_id" id="cart_items_list" style="display: none;">
								<div class="wish-content overflow-hidden">

									<?php

									$total = 0;
									$totalQuantity = 0;
									if (!empty($products_animation)): ?>

										<ul id="products_animation"
											class="ulproducts_animation dropdown-menu dropdown-user pull-right"
											style="left:0;">

											<div class="divproducts_animation w-100" bis_skin_checked="1">
												<?php foreach ($products_animation as $product_animation): ?>
													<?php
													$product_name = $product_animation['Package']['name'];
													$product_amount = $product_animation['Package']['amount'];
													$product_show_amount = $product_animation['Package']['show_amount'];
													if (strlen($product_animation['Package']['photo']) > 0) {
														$product_photo = "img/package_thumb/" . $product_animation['Package']['photo'];
													} else {
														$product_photo = "img/nia.png";
													}
													$total = $total + ($product_animation['Package']['count'] * $product_animation['Package']['amount']);
													$totalQuantity = $totalQuantity + $product_animation['Package']['count'];
													?>
													<li class="ani_li row">
														<div class="col-md-2 col-xs-2 product_animation_img"
															bis_skin_checked="1">
															<img src="img/nia.png" alt="<?php echo $product_name ?>">
														</div>
														<div class="col-md-7 col-xs-7 p0" bis_skin_checked="1">
															<span class="textblack"><?php echo $product_name ?></span>
															<p class="text-muted small p0">Exams:
																<span class=""><strong>
																		<?php
																		if (!empty($product_animation['Exam'])) {
																			foreach ($product_animation['Exam'] as $examName):
																				echo h($examName['name']); ?> |
																			<?php endforeach;
																			unset($examName);
																			unset($examName);
																		} else {
																			echo __('No Exam Found');
																		}
																		?>
																	</strong>

																</span>
															</p>
														</div>
														<div class="col-md-3 col-xs-3 text-right" bis_skin_checked="1">
															<span
																class="text-danger"><strong><strike><?php echo "$" . $product_show_amount; ?></strike></strong></span>
															<div style="clear: both;" bis_skin_checked="1"></div>
															<span class="text-success"><big><strong>
																		<?php echo "$" . $product_amount; ?></strong></big></span>
														</div>
													</li>

												<?php endforeach; ?>
											</div>
											<li class="ani_li row"
												style="border-top: 1px solid #000; width:90%; margin: auto;">
												<div class="col-md-9" bis_skin_checked="1"><span
														class="textblack"><strong>TOTAL
															PRICE</strong></span>
												</div>
												<div class="col-md-3 p0" style="text-align: right;" bis_skin_checked="1">
													<span
														class="textblack"><strong><?php echo "$" . $total; ?></strong></span>
												</div>
												<div style="clear: both;" bis_skin_checked="1"></div>
											</li>
											<li class="ani_li" style="border: none;  width:90%; margin: auto;">
												<div class="col-md-12" style="text-align: center;" bis_skin_checked="1">
													<a href="<?= $siteDomain ?>/Carts/View" class="btn btn-success shopCart"
														style="background: #68c6ec;border: #68c6ec;"><span>View
															Bag</span></a>
												</div>
											</li>

										</ul>
									<?php else: ?>
										<ul>
											<div class="empty-cart textblack mb-4">
												<center>
													<a class="cartbag" href="/Carts/View"><i
															class="fa fa-shopping-bag"></i></a>
													<br><br>
													<span class="textblack text-uppercase empty-msg">your shopping bag
														is empty</span>
													<br><br>
													<a href="<?= $siteDomain ?>/Carts/View" class="btn btn-primary"><span
															class="fa fa-shopping-cart"></span>&nbsp;Continue
														Shopping</a>
												</center>
											</div>
										</ul>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</ul>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav><!-- .navbar -->

			<a <?php if ($currentUrl == $siteDomain . '/Login/index') { ?> style="background: #111111; color: #fff;" <?php } ?>href="<?= $siteDomain ?>/Login/index" class="get-started-btn scrollto">Sign In</a>

			<a <?php if ($currentUrl == $siteDomain . '/Registers/index') { ?> style="background: #111111; color: #fff;"
				<?php } ?>href="<?= $siteDomain ?>/Registers/index" class="get-started-btn scrollto">Sign Up</a>
		</div>
	</header><!-- End Header -->
	<?php echo $this->fetch('content'); ?>

	<footer id="footer">



		<div class="container d-md-flex py-4">

			<div class="me-md-auto text-center text-md-start">
				<div class="copyright">
					&copy; Copyright <strong><span>Rhimo Tech Pvt Ltd</span></strong>. All Rights Reserved
				</div>
				<div class="credits">
					Designed by <a href="https://practiceboard.in/">Rhimo Tech Pvt Ltd</a>
				</div>
			</div>
			<div class="social-links text-center text-md-end pt-3 pt-md-0">
				<a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
				<a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
				<a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
				<a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
				<a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
			</div>
		</div>
	</footer><!-- End Footer -->

	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
			class="bi bi-arrow-up-short"></i></a>



	<?php
	echo $this->Html->script('/app/webroot/assets/vendor/purecounter/purecounter_vanilla.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/aos/aos.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/glightbox/js/glightbox.min.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/isotope-layout/isotope.pkgd.min.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/swiper/swiper-bundle.min.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/vendor/php-email-form/validate.js', array('defer'));
	echo $this->Html->script('/app/webroot/assets/js/main.js', array('defer'));
	echo $this->Html->script('/app/webroot/js/v2/bootstrap.bundle.min', array('defer'));
	echo $this->Html->script('/app/webroot/js/v2/owl.carousel.min', array('defer'));
	// echo $this->Html->script('/app/webroot/js/v2/aos', array('defer'));
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
	// echo $this->Html->script("/app/webroot/js/langs/$configLanguage", array('defer'));
	



	?>

</body>









































</html>