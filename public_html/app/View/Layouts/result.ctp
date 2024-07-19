<!DOCTYPE html>
<html lang="<?php echo$configLanguage;?>" dir="<?php echo$dirType;?>">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="google-translate-customization" content="839d71f7ff6044d0-328a2dc5159d6aa2-gd17de6447c9ba810-f">
	  <link rel="icon" type="image/png"
			href="<?php echo $this->Html->url(array('crm' => false, 'controller' => 'img')) . '/' . $sysSetting['Configuration']['favicon']; ?>">
		<?php
		echo $this->Html->css('/design500/assets/css/font-awesome.min');
		echo $this->Html->css('/design500/assets/css/bootstrap.min');
                echo $this->Html->css('style.css');
		echo $this->fetch('meta');
		echo $this->fetch('css');
                echo $this->Html->script('jquery-1.11.1.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->fetch('script');
                echo $this->Js->writeBuffer();
		$UserArr=$userValue;
		if(strlen($UserArr['Student']['photo'])>0)
		$std_img='student_thumb/'.$UserArr['Student']['photo'];
		else
		$std_img='User.png';
		echo $this->Html->scriptBlock("$(document).ready(function () {
    document.body.oncopy = function() { return false; }
    document.body.oncut = function() { return false; }
});
", array('inline' => true)); ?>
<?php if($translate>0){?>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php }?>
<style type="text/css" media="screen">
    .panel{
            border: 1px solid #ddd;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
    }
    .btn {
  display: inline-block;
  padding: 8px 1rem;
  overflow: hidden;
  position: relative;
  text-decoration: none;
  text-transform: uppercase;
  border-radius: 3px;
  -webkit-transition: 0.3s;
  -moz-transition: 0.3s;
  -ms-transition: 0.3s;
  -o-transition: 0.3s;
  transition: 0.3s;
  box-shadow: 0 2px 10px rgba(0,0,0,0.5);
  border: none;
  font-size: 15px;
  text-align: center;

}
.btn-default {
    background-color: #00bcd4;
}
       .ulproducts_animation{
        width: 380px;background: #fff;z-index: 999999999;
        border: 1px solid #ddd;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);

    }

    .divproducts_animation{
        width: 380px;background: #fff;z-index: 999999999;
        height: 250px;
        overflow-y: scroll;
    }
    .divproducts_animation>li.ani_li{width: 100%; border-bottom: 1px solid #000;padding: 5px;overflow: hidden;}
    .textblack{color: black;}

    .ulproducts_animation>li.ani_li{width: 100%; border-bottom: 1px solid #000;padding: 5px;overflow: hidden;}
    .textblack{color: black;}

    .empty-cart center>a>i.fa.fa-shopping-bag {
        border: 1px solid #ddd;
        font-size: 35px;
        color: #000;
        padding: 20px;
        border-radius: 100%;
         box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
    }

    .product_animation_img{padding: 3px;}
  </style>
	  <script type="text/javascript">
          window.onbeforeunload = function () {
              opener.location.reload();
          };
          $(document).ready(function () {
          $(this).bind("contextmenu", function (e) {
              e.preventDefault();
          });
          });
	  </script>
  </head>
    <body>
      <div id="google_translate_element"></div>

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
