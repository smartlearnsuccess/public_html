<!DOCTYPE html>
<html lang="<?php echo $configLanguage; ?>" dir="<?php echo $dirType; ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-translate-customization" content="839d71f7ff6044d0-328a2dc5159d6aa2-gd17de6447c9ba810-f">
	<link rel="icon" type="image/png"
		  href="<?php echo $this->Html->url(array('crm' => false, 'controller' => 'img')) . '/' . $sysSetting['Configuration']['favicon']; ?>">
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700"/>
    <?php
    echo $this->Html->css('/design500/assets/css/font-awesome.min');
    echo $this->Html->css('/design500/assets/css/bootstrap.min');
    echo $this->Html->css('style.css');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->Html->script('jquery-1.11.1.min');
    echo $this->Html->script('html5shiv');
    echo $this->Html->script('respond.min');
    echo $this->Html->script('bootstrap.min');
    echo $this->Html->script('jquery.validationEngine-en');
    echo $this->Html->script('jquery.validationEngine');
    echo $this->Html->script('bootstrap-multiselect');
    echo $this->Html->script('waiting-dialog.min');
    echo $this->Html->script('custom.min');
    echo $this->Html->script("langs/$configLanguage");
	if ($mathEditor && strtolower($this->params['controller']) == "results") {
		echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_HTMLorMML');
	}
    echo $this->fetch('script');
    echo $this->Js->writeBuffer();
    $UserArr = $userValue;
    if (strlen($UserArr['Student']['photo']) > 0)
        $std_img = 'student_thumb/' . $UserArr['Student']['photo'];
    else
        $std_img = 'User.png';
	if ($mathEditor && strtolower($this->params['controller']) == "results") {
        ?>
        <script type="text/x-mathjax-config">
            MathJax.Hub.Config({extensions: ["tex2jax.js"],jax: ["input/TeX", "output/HTML-CSS"],tex2jax: {inlineMath: [["$", "$"],["\\(", "\\)"]]}});
        </script><?php } ?>
    <?php if ($translate > 0) { ?>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                }, 'google_translate_element');
            }
        </script>
        <script type="text/javascript"
                src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <?php } ?>
</head>
<style type="text/css">
    .page-mail .box-content {
        padding: 10px;
    }

    .page-mail .sidebar li.active {
        padding: 5px 9px !important;
        background: #3d78a7 !important;
        font-size: 24px !important;
    }

    .page-mail .sidebar li {
        padding: 5px 9px !important;
        background: #55acee !important;
        font-size: 24px !important;
        border-bottom: 1px solid #fff;
    }

    .page-mail .sidebar li a {
        color: #fff !important;
    }

    .page-mail {
        padding: 0px 15px
    }

    .page-title {
        display: none;
    }

    /* 	.row {
        margin: 0;
        padding: 0;
        } */
    .page-mail .sidebar li.mbm {
        background: transparent !important;
    }
</style>
<div id="google_translate_element"></div>
<body>

<!--END SIDEBAR MAIN--><!--BEGIN PAGE CONTENT-->
<div class="page-content"><!--BEGIN TITLE & BREADCRUMB PAGE-->
    <div class="box-content"><!--BEGIN CONTENT-->
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
