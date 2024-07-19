function openPage(pageName, elmnt, color) {
    // Hide all elements with class="tabcontent" by default */
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Remove the background color of all tablinks/buttons
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].style.backgroundColor = "";
    }
  
    // Show the specific tab content
    document.getElementById(pageName).style.display = "block";
  
    // Add the specific color to the button used to open the tab content
    elmnt.style.backgroundColor = color;
  }
  
  // Get the element with id="defaultOpen" and click on it
  document.getElementById("defaultOpen").click();

  
  $(document).ready(function(){$('#selectAll').click(function(){$('.chkselect').prop('checked',this.checked);});$(this).bind("contextmenu",function(e){e.preventDefault();});});function show_modal(target)
{waitingDialog.show();target=target;$('#targetModal').load(target,function(){$('#targetModal').modal('show');waitingDialog.hide();});}
$(document).ready(function(){$('.multiselectgrp').multiselect({includeSelectAllOption:true,allSelectedText:getLocale('All Selected'),filterPlaceholder:getLocale('Search'),nonSelectedText:getLocale('None selected'),selectAllText:getLocale('Select All'),enableFiltering:true,enableCaseInsensitiveFiltering:true});$('form').validationEngine();});function check_perform_delete()
{var numberOfChecked=$('.chkselect:checked').length;if(numberOfChecked==0)
	alert(getLocale("Please Select Any Record"));else
{if(confirm(getLocale("Do You Want To Delete")+" "+numberOfChecked+" "+getLocale("Record")))
	document.deleteallfrm.submit();}}
document.onkeydown=function(e){e=e||window.event;if(e.ctrlKey){var c=e.which||e.keyCode;switch(c){case 83:case 87:case 85:case 65:case 67:case 88:case 86:case 80:case 73:case 67:case 75:case 81:e.preventDefault();e.stopPropagation();break;}
	if(c==80){if(navigator.userAgent.match(/msie/i)||navigator.userAgent.match(/trident/i)){alert("You can not print");}}}
	if(e.shiftKey){var c=e.which||e.keyCode;switch(c){case 116:case 118:e.preventDefault();e.stopPropagation();break;}}
	var c=e.keyCode;switch(c){case 123:e.preventDefault();e.stopPropagation();break;}};function showpop_up(page){createPopUp(page,"New Window","yes","no");}
function createPopUp(theURL,Name,scroll,resize){var winWidth=screen.width-16;var winHeight=screen.height-106;winProp='width='+winWidth+',height='+winHeight+',left='+0+',top='+0+',scrollbars='+scroll+',resizable='+resize+',minimizable=no';window.open(theURL,"",winProp);}
window.onresize=function()
{var winWidth=screen.width;var winHeight=screen.height;window.resizeTo(winWidth,winHeight);}
window.onclick=function()
{var winWidth=screen.width;var winHeight=screen.height;window.resizeTo(winWidth,winHeight);}
