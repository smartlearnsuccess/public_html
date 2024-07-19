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

    <script type="text/javascript">
    $(document).ready(function() {

        // $("#products_animation_id").hover(
        //     function () {
        //         $("#products_animation").show(500);
        //     }, function () {
        //         //$( "#products_animation" ).hide(500);
        //     }
        // );

        // $("#products_animation").hover(
        //     function () {
        //         // $( "#products_animation" ).show(500);
        //     }, function () {
        //         $("#products_animation").hide(500);
        //     }
        // );

        $("#products_animation_id").click(function(event) {
            $("#products_animation").toggle();
            //  $("#products_animation").attr('style','visibility:visible');
            //  $("#products_animation").attr('style','opacity:1');
            $("#products_animation").attr('style', 'display:block');
        });
        $("#products_animation").hide();
    });
    </script>
</head>

<body>
    <div class="main-wrapper">

        <header class="header header-two">
            <div class="header-fixed" style="background-color: aliceblue">
                <nav class="navbar navbar-expand-lg header-nav scroll-sticky">
                    <div class="container">
                        <div class="navbar-header">
                            <a id="mobile_btn" href="javascript:void(0);">
                                <span class="bar-icon">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
                            </a>
                            <!-- <a href="index-2.html" class="navbar-brand logo"> -->
                            <!-- <img src="app/webroot/img/v2/logo.svg" class="img-fluid" alt="Logo"> -->

                            <div class="logo-text"><?php if (strlen($frontLogo) > 0) { ?><?php echo $this->Html->link($this->Html->image($frontLogo, array('alt' => $siteName, 'class' => 'img-responsive front-logo')), array('controller' => '/'), array('escape' => false));
							} else { ?><?php echo $siteName; ?><?php } ?></div>
                        </div>



                        <div class="main-menu-wrapper">
                            <div class="menu-header">
                                <!-- <a href="index-2.html" class="menu-logo">
										<img src="app/webroot/img/v2/logo.svg" class="img-fluid" alt="Logo">
										</a> -->
                                <a id="menu_close" class="menu-close" href="javascript:void(0);">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            <ul id="frontMenu" class="main-nav">
                                <li>
                                    <a <?php if ($currentUrl == $siteDomain) { ?> style="color: #F66962" <?php } ?>
                                        href="<?= $siteDomain ?>"><i class="fa fa-home"></i>&nbsp;Home</a>
                                </li>
                                <li class="has-submenu menu-effect">
                                    <a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/About-Us' || $currentUrl == $siteDomain . '/Contents/Pages/Profile') { ?>
                                        style="color: #F66962" <?php } ?> href="#"><i
                                            class="fa fa-globe"></i>&nbsp;About&nbsp;<i
                                            class="fas fa-chevron-down"></i></a>
                                    <ul class="submenu">
                                        <li><a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/About-Us') { ?>
                                                style="color: #F66962" <?php } ?>
                                                href="<?= $siteDomain ?>/Contents/Pages/About-Us"><i></i>&nbsp;About
                                                Us</a>
                                        </li>
                                        <li><a <?php if ($currentUrl == $siteDomain . '/Contents/Pages/Profile') { ?>
                                                style="color: #F66962" <?php } ?>
                                                href="<?= $siteDomain ?>/Contents/Pages/Profile"><i></i>&nbsp;Profile</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a <?php if ($currentUrl == $siteDomain . '/Packages/index/index') { ?>
                                        style="color: #F66962" <?php } ?>
                                        href="<?= $siteDomain ?>/Packages/index/index"><i
                                            class="fa fa-shopping-cart"></i>&nbsp;Packages</a></li>

                                <li><a <?php if ($currentUrl == $siteDomain . '/Schedules/index') { ?>
                                        style="color: #F66962" <?php } ?> href="<?= $siteDomain ?>/Schedules/index"><i
                                            class="fa fa-calendar"></i>&nbsp;Schedules</a>
                                </li>

                            </ul>
                        </div>
                        <ul class="nav header-navbar-rht">
                            <li class="nav-item cart-nav">
                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                                    id="products_animation_id">
                                    <?php echo $this->Html->image('v2/cart.svg', array('class' => 'position-relative', 'alt' => 'Cart')); ?>
                                    <span>
                                        <i class="badge bg-secondary rounded-pill bg-warning " id="cart-counter">
                                            <?php echo $cartCount; ?>
                                        </i>
                                    </span>
                                </a>
                                <div class="wishes-list dropdown-menu dropdown-menu-right">
                                    <div class="wish-content overflow-hidden">
                                        <?php

										$total = 0;
										$totalQuantity = 0;
										if (!empty($products_animation)): ?>

                                        <ul id="products_animation"
                                            class="ulproducts_animation dropdown-menu dropdown-user pull-right">
                                            <div class="divproducts_animation w-100" bis_skin_checked="1">
                                                <?php foreach ($products_animation as $product_animation): ?>
                                                <?php
														$product_name = $product_animation['Package']['name'];
														$product_amount = $product_animation['Package']['amount'];
														$product_show_amount = $product_animation['Package']['show_amount'];
														if (strlen($product_animation['Package']['photo']) > 0) {
															$product_photo = "/img/package_thumb/" . $product_animation['Package']['photo'];
														} else {
															$product_photo = "/img/nia.png";
														}
														$total = $total + ($product_animation['Package']['count'] * $product_animation['Package']['amount']);
														$totalQuantity = $totalQuantity + $product_animation['Package']['count'];
														?>
                                                <li class="ani_li row">
                                                    <div class="col-md-2 col-xs-2 product_animation_img"
                                                        bis_skin_checked="1">
                                                        <img src="/img/nia.png" alt="<?php echo $product_name ?>">
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
                                            <li class="ani_li row" style="border-top: 1px solid #000;">
                                                <div class="col-md-9" bis_skin_checked="1"><span
                                                        class="textblack"><strong>TOTAL
                                                            PRICE</strong></span>
                                                </div>
                                                <div class="col-md-3 p0" style="text-align: right;"
                                                    bis_skin_checked="1">
                                                    <span
                                                        class="textblack"><strong><?php echo "$" . $total; ?></strong></span>
                                                </div>
                                                <div style="clear: both;" bis_skin_checked="1"></div>
                                            </li>
                                            <li class="ani_li" style="border: none;">
                                                <div class="col-md-12" style="text-align: center;" bis_skin_checked="1">
                                                    <a href="<?= $siteDomain ?>/Carts/View"
                                                        class="btn btn-success shopCart"
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
                                                        is
                                                        empty</span>
                                                    <br><br>
                                                    <a href="<?= $siteDomain ?>/Carts/View"
                                                        class="btn btn-primary"><span
                                                            class="fa fa-shopping-cart"></span>&nbsp;Continue
                                                        Shopping</a>
                                                </center>
                                            </div>
                                        </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $siteDomain ?>/Registers/index" class="nav-link header-login">Signin</a>
                            </li>
                            <li class="nav-item">
                                <a <?php if ($currentUrl == $siteDomain . '/Registers/index') { ?>
                                    style="border: 3px solid #F66962"
                                    <?php } ?>href="<?= $siteDomain ?>/Registers/index"
                                    class="nav-link header-login">Signup</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <?php echo $this->fetch('content'); ?>
        <footer class="footer footer-five">
            <div class="footer-top-five">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                            <div class="footer-contact footer-menu-five">
                                <h2 class="footer-title footer-title-five">Get in touch</h2>
                                <div class="footer-contact-info">
                                    <div class="footer-address">
                                        <span><i class="feather-map-pin"></i></span>
                                        <p> 3556 Beech Street, San Francisco,<br> California, CA 94108 </p>
                                    </div>
                                    <p class="mb-0">
                                        <span class="phone-icon"><i class="fa-solid fa-phone-volume"></i></span>
                                        +19 123-456-7890
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <div class="footer-menu footer-menu-five">
                                <h2 class="footer-title footer-title-five"><i class="fa-sharp fa-solid fa-dash"></i>For
                                    Instructor
                                </h2>
                                <ul>
                                    <li><a href="<?= $siteDomain ?>/Contents/Pages/Profile">Profile</a></li>
                                    <li><a href="<?= $siteDomain ?>/admin/Users/login_form">Login</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <div class="footer-menu footer-menu-five">
                                <h2 class="footer-title footer-title-five">For Student</h2>
                                <ul>
                                    <li><a href="<?= $siteDomain ?>/Contents/Pages/Profile">Profile</a></li>
                                    <li><a href="<?= $siteDomain ?>/crm/Users/login#">Login</a></li>
                                    <li><a href="<?= $siteDomain ?>/Registers/index#">Register</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <h2 class="footer-title footer-title-five">Get More Updates</h2>
                            <div class="footer-widget-five">
                                <div class="footer-news-five">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" placeholder="Enter Your Email Address">
                                        <button type="submit" class="btn btn-one">Subscribe</button>
                                    </div>
                                </div>
                                <div class="footer-about-five">
                                    <p>Stay ahead of the curve with our latest course releases, expert tips, and
                                        educational
                                        resources by subscribing to our newsletter.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom footer-bottom-five">
                <div class="container">
                    <div class="copyright-five">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="footer-logo-five">
                                    <a href="index-2.html">
                                        <a href="<?= $siteDomain ?>"><img src="app/webroot/img/v2/logo-website.fw.png"
                                                alt="Edu Expression Elite" class="img-fluid" /></a> </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="copyright-text">
                                    <p>&copy; <?php echo $this->Time->format('Y', time()); ?> DreamsLMS. All rights
                                        reserved.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="social-icon-five">
                                    <ul class="nav">
                                        <li>
                                            <a href="#" class="twitter-icon">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="linked-icon">
                                                <i class="fab fa-linkedin-in"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="facebook-icon">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="youtube-icon">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <?php
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
</body>



</html>