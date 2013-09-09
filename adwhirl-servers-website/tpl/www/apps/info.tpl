<div class="content">

{include file="../tpl/www/apps/appNav.tpl"}
<div style="clear:right"></div>
<div class="mainContent">
    <form id="infoForm" action="/apps/oneApp/infoSubmit" enctype="multipart/form-data" method="post">
	    <input type="hidden" name="returnPage" value="{$returnPage}" class="text"/>
        <div id="subApp" class="subsectionWraper">
					<div class="sectionHeader sectionHeaderActive">
						Application Information          
					</div>
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
              <option value="1" {if $app->platform==1}selected{/if}>iPhone</option>
              <option value="2" {if $app->platform==2}selected{/if}>Android</option>
            </select>
          </p>
        </div>
        <div id="subServer" class="subsectionWraper">
					<div class="sectionHeader sectionHeaderActive">
						Ad Serving Settings (optional)        
					</div>
          <p class="formElement required">
            <label for="bgColor">Background Color:</label>
            <input class="hint" type="text" name="bgColor" value="{$app->bgColor}" default="FFFFFF" title="FFFFFF (default)"/>
          </p>
          <p class="formElement required ">
            <label for="fgColor">Text Color:</label>
            <input class="hint" type="text" name="fgColor" value="{$app->fgColor}" default="000000" title="000000 (default)"/>
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
        
        </form>

        <div id="subRemoveApp" class="subsectionWraper">
					<div class="sectionHeader sectionHeaderActive">
						Delete App    
					</div>
            <p class="formElement required">
              <label for="delete">Delete App:</label>
               <span class="button"><a id="delete" href='#'><span>Delete</span></a></span>
   			   <span style="padding-left:10px">You'll be asked one more time to confirm</span>
            </p>
        </div>        
			  <div style="text-align:center">

			  <hr/>
			  <span class="button disabled"><a href="#" id="save" class="disabled"><span>Save Changes</span></a></span>
			  <a href="{$returnPage}" class="cancel">Cancel</a>

			  </div>      
  </div>
	<div id="deleteConfirm" class="hidden">
		<div class="modalTop center">
			<img src="/img/exclamation.png"> <span class="modalHeader"> Delete Confirmation</span>
		<span class="modalBody">Are you sure you want to delete this app?</span>
		<hr>
		<span class="button"><a href="#" id="confirmDelete"><span>Delete</span></a></span>
		<span class="button"><a href="#" class="simplemodal-close"><span>Cancel</span></a></span>
		</div>
		<div class="modalBottom"></div>
	</div>

</div>
<script>
var aid="{$app->id}";
var returnPage ="{$returnPage}";
{literal}
$(document).ready(function() {
	$('#confirmDelete').click(function() {
		window.location = "/apps/oneApp/deleteSubmit?aid="+aid; 
	});		
	$("#infoForm").validate({		
		rules: {
				name: "required",
				storeUrl: {
					url:true
				}
			},
			messages: {
				name: "Please enter your name"
			}
		});
	$('input[title!=""]').hint();
  $('#save').bind("click", function(e) {
		e.preventDefault();
		if (!$(this).is(".disabled")) {
			$(".blur").each(function() {					
				$(this).val($(this).attr('default'));
			});
      $("#infoForm").submit();
		}
  });
   $('#delete').bind("click",
     function(e) {
			$("#deleteConfirm").modal({
				opacity:80,
				overlayCss: {backgroundColor:"#fff"}
			});
			return false;       
     });
		var activateSave = function() {
	 	 $("#save").removeClass("disabled").parent().removeClass("disabled");
	   $(".cancel").removeClass("disabled");		
		};  
		$('input, select').bind("change keypress", activateSave);
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
});
{/literal}
</script>
