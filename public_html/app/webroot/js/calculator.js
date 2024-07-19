$(document).ready(function() {
       var loadUrl = $('#scriptCalcUrl').text();
       $("#loadCalc").load(loadUrl, function() {
       });
});
	
function loadCalculator(){
	var showCalculator = "SCIENTIFIC";
	if(showCalculator=="NORMAL"){
		$('#keyPad a').css('margin-right','3px');
		$('.degree_radian').css('display','none');
		 $('.keyPad_TextBox').attr('style', 'width: 188px !important;');
		 $('.keyPad_TextBox1').attr('style', 'width: 188px !important;');
		 $('.keyPad_TextBox').css("font-size", "17px");
		 $('.keyPad_TextBox1').css("font-size", "17px");   
		jQuery('.memoryhide').css('right','192px');
		$('#Pi').hide();
    	$('#dr').removeClass('degree_radian');
	    $('.degree_radian').hide(); 
		$('.keyPad_btnConst').hide();
		$('.keyPad_btnConst').hide();
		$('#keyPad_btnMod').hide();
		$('#keyPad_btnFact').hide();
		$('#keyPad_btnSinH').hide();
		$('#keyPad_btnCosinH').hide();
		$('#keyPad_btnTgH').hide();
		$('#keyPad_EXP').hide();
		$('#keyPad_btnOpen').hide();
		$('#keyPad_btnClose').hide();
		$('#keyPad_btnAsinH').hide();
		$('#keyPad_btnAcosH').hide();
		$('#keyPad_btnAtanH').hide();
		$('#keyPad_btnLogBase2').hide();
		$('#keyPad_btnLn').hide();
		$('#keyPad_btnLg').hide();
		$('#keyPad_btnExp').hide();
		$('#keyPad_btnYlogX').hide();	
		$('#keyPad_btn10X').hide();
		$('#keyPad_btnSin').hide();
		$('#keyPad_btnCosin').hide();
		$('#keyPad_btnTg').hide();
		$('#keyPad_btnYpowX').hide();
		$('#keyPad_btnCube').hide();
		$('#keyPad_btnSquare').hide();
		$('#keyPad_btnAsin').hide();
		$('#keyPad_btnAcos').hide();
		$('#keyPad_btnAtan').hide();
		$('#keyPad_btnYrootX').hide();
		$('#keyPad_btnCubeRoot').hide();
		$('#keyPad_btnAbs').css('display','none');
		if(checkIEVersion()&&!(!!navigator.userAgent.match(/Trident\/7\./))){
			$('#keyPad_btnEnter').addClass('importantRule');
			$('#keyPad_btnEnter').attr('style', 'height: 53px !important;');
			$('#memory').addClass('importantRuleMemory');
			jQuery('.calc_container').css('width','214px');
		}
		else
			{
			$('#keyPad_btnEnter').addClass('importantRule1');
			$('#keyPad_btnEnter').attr('style', 'height: 53px !important;');
			$('#memory').addClass('importantRuleMemory1');
			$('#keyPad_btn0').attr('style', 'width: 72px !important;');
			$('#keyPad_btnBack').attr('style', 'width: 72px !important;');
			jQuery('.calc_container').css('width','214px');
			}
	    $('#keyPad_btn0').attr('style', 'width: 72px !important;');
		$('#keyPad_btnBack').attr('style', 'width: 72px !important;');
		jQuery("#keyPad").css("top",0).css("left",0);
		$('#keyPad_Help').hide();
		 $('#normalText').show();
	
	}
	if(showCalculator=="SCIENTIFIC"){
		$('#memory').addClass('importantRuleMemoryScientific');
		 $('.keyPad_TextBox').attr('style', 'width: 434px !important;');
		 $('.keyPad_TextBox1').attr('style', 'width: 434px !important;');
		 $('#keyPad_btn0').attr('style', 'width: 76px !important;');
		 $('.degree_radian').attr('style', 'width: 80px !important;');
		  $('#scientificText').show();
		
	}
	var pLft = ( $(window).width() - $("#loadCalc").width() );
	//pLft = pLft + 430;
	 jQuery("#loadCalc").css("top",121).css("left",pLft);
	$('#loadCalc').show();
}
//This function is used to check Browser is Compatible or not for Candidate Machine.
function checkIEVersion(){
	try{
	var currentIEVersion = getIEVersion();
	if((currentIEVersion >=7 && currentIEVersion != -1) || (($.browser.msie || navigator.userAgent.indexOf("Trident")!=-1) && $.browser.version>=7)) {
		return true;
	}else {
		return false;
	}
	 }catch(e) {
	    	return false;
	    }
}