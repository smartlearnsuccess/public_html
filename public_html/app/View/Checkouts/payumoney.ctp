<html>
<head>
    <script>
        var hash = '<?php echo $hash ?>';

        function submitPayuForm() {
            if (hash == '') {
                return;
            }
            var payuForm = document.forms.payuForm;
            payuForm.submit();
        }
    </script>
</head>
<body onload="submitPayuForm()">
<center><?php echo $this->Html->image('loading-lg.gif'); ?></center>
<form action="<?php echo $action; ?>" method="post" name="payuForm">
    <input type="hidden" name="key" value="<?php echo $merchantKey; ?>"/>
    <input type="hidden" name="hash" value="<?php echo $hash; ?>"/>
    <input type="hidden" name="txnid" value="<?php echo $txnid; ?>"/>
    <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
    <input type="hidden" name="firstname" value="<?php echo $firstname; ?>"/>
    <input type="hidden" name="email" value="<?php echo $email; ?>"/>
    <input type="hidden" name="phone" value="<?php echo $phone; ?>"/>
    <input type="hidden" name="productinfo" value='<?php echo $productinfo; ?>'/>
    <input type="hidden" name="surl" value="<?php echo $surl; ?>"/>
    <input type="hidden" name="furl" value="<?php echo $furl; ?>"/>
    <input type="hidden" name="curl" value="<?php echo $curl; ?>"/>
    <input type="hidden" name="firstname" value="<?php echo $firstname; ?>"/>
    <input type="hidden" name="email" value="<?php echo $email; ?>"/>
    <input type="hidden" name="address1" value="<?php echo $address1; ?>"/>
    <input type="hidden" name="phone" value="<?php echo $phone; ?>"/>
	<?php if($sandboxMode==true){?>
    <input type="hidden" name="service_provider" value="<?php echo $serviceProvider; ?>"/>
	<?php }?>
</form>
</body>
</html>
