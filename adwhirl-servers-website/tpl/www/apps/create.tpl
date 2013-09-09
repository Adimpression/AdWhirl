<div class="content">

  <div id="subtitleBox">
    Add a new App
  </div>
<div style="clear:right"></div>
    <form id="infoForm" action="/apps/oneApp/createSubmit" enctype="multipart/form-data" method="post">
	    <input type="hidden" id="returnPage" name="returnPage" value="{$returnPage}" class="text"/>
	
        <div id="appInfo" class="section">
          <div class="sectionHeader sectionHeaderActive">
            Application Information
            
          </div>
          <div class="sectionBody">          
            <input type="hidden" name="aid" value="{$app->id}" />
            <p class="formElement required ">
              <label for="name">Name:</label>
              <input type="text" name="name" value="{$app->name}" class="text"/>
            </p>
            <p class="formElement required ">
              <label for="storeUrl">URL:</label>
              <input type="text" name="storeUrl" value="{$app->storeUrl}" class="text"/>
            </p>
            <p class="formElement required ">
              <label for="platform">Platform:</label>
              <select name ="platform">
                <option value="1" selected>iPhone</option>
                <option value="2">Android</option>
              </select>              
              
            </p>
<!--
            <p class="formElement required">
              <label>AdWhirl Downloads:</label>
  
              <small class="download">
              <a id="sdk_link" href="">Download <span class="platform">iPhone</span> SDK >></a><br />
              <a id="instruction_link" href="">Download <span class="platform">iPhone</span> SDK Instructions [PDF] >></a>
              </small>

            </p>
-->
          </div>          
        </div>
       <div id="serverInfo" class="section">
          <div class="sectionHeaderActive">
            Ad Serving Settings (Optional)
<!--            <span class="right"> <a href='#' class="showHideButton">Show</a> </span> -->
          </div>
          <div class="sectionBody"> <!-- Had class "hide" -->
            <p class="formElement required">
              <label for="bgColor">Background Color:</label>
              <input class="hint" type="text" name="bgColor" value="" default="FFFFFF" title="FFFFFF (default)"/>
            </p>
            <p class="formElement required ">
              <label for="fgColor">Text Color:</label>
              <input class="hint" type="text" name="fgColor" value="" default="000000" title="000000 (default)"/>
            </p>
            <p class="formElement required">
              <label for="cycleTime">Refresh Rate:</label>
							<select name="cycleTime">
							{html_options values=$cycleTime output=$cycleLabel selected=$app->cycleTime}
							</select>              
            </p>
            <p class="formElement required">
              <label for="transition">Transition Animation:</label>
							<select name="transition">
							{html_options values=$animationValues output=$animationLabels selected=$app->transition}
							</select>
            </p>
            <p class="formElement required">
              <label for="locationOn">Allow Location Access:</label>
              <a href='#' class="onOffImg onOffImg{if $app->locationOn == '1'}On{else}Off{/if}"><input type="hidden" name="locationOn" value="{$app->locationOn}" /></a>            
            </p>                              
          </div>
        </div>
       
          
      </form>
       
		  <div style="text-align:center">

		  	<hr/>
		  	<span class="button disabled"><a href="#" id="save" class="disabled"><span>Add App</span></a></span>
		  	<a href="{$returnPage}" class="cancel">Cancel</a>
			</div>
</div>
<div id="waitingDialog" class="hidden">
	<div class="modalTop center">
		<img src="/img/ajax-loader.gif"><span class="modalHeader">&nbsp;Your settings are being updated.</span>
	</div>
	<div class="modalBottom"></div>
</div>
<script>
{literal}
$(document).ready(function() {
	$("#infoForm").validate({
		rules:{
			name: "required",
			storeUrl: {url:true}
			},
		messages: {
		name: "Please enter your name"
		}
	});
	
	$('input[title!=""]').hint();
	$("a.disabled").click(function(event) {
    event.preventDefault();
  });
  $('#save').bind("click",
    function(e) {
      if (!$(this).is(".disabled")) {
        $(".blur").each(function() {					
	  $(this).val($(this).attr('default'));
	});
	$("#waitingDialog").modal({ opacity:80, overlayCss: {backgroundColor:"#fff"} });
	$.post("/apps/oneApp/createSubmit", $("#infoForm").serialize(), function(){
	  window.location = $("#returnPage").val();
	});
      }
    });
	var activateSave = function() {
 	 $("#save").removeClass("disabled").parent().removeClass("disabled");
   $(".cancel").removeClass("disabled");		
	};
  $('a.onOffImg').bind("click",
    function(e) {
		  activateSave();
      var val = $("input",this).val();
      if (val==1) {
        $("input",this).val(0);
        $(this).removeClass("onOffImgOn").addClass("onOffImgOff");
 
      } else {
        $("input",this).val(1);
        $(this).removeClass("onOffImgOff").addClass("onOffImgOn");      
      }
    });


  $("a.showHideButton").bind("click",
    function(e) {
      var secId = $(this).parent().parent().parent().attr("id");
      if ($(this).text()=="Show") {
        $(this).text("Hide");
        $("#"+secId+" div.sectionHeader").addClass("sectionHeaderActive");
        $("#"+secId+" div.sectionBody").show();
      } else {

        $(this).text("Show");
        $("#"+secId+" div.sectionHeader").removeClass("sectionHeaderActive");
        $("#"+secId+" div.sectionBody").hide();        
      }      
    });
	
  $("select").change(function () {
    var platform = $("select option:selected").text();
    var sdk_link = platform=="iPhone"? 
      "{/literal}{$iPhone_sdk_link}{literal}"          
      :"{/literal}{$Android_sdk_link}{literal}";
    var instruction_link = platform == "iPhone" ?
    "{/literal}{$iPhone_instruction_link}{literal}"          
    :"{/literal}{$Android_instruction_link}{literal}";
    
    $("a#sdk_link").attr("href",sdk_link);
    $("a#instruction_link").attr("href",instruction_link);
    
    $("span.platform").text(platform);
  })
  .change();
	$('select, input').bind("change keypress", activateSave);
});
{/literal}
</script>
