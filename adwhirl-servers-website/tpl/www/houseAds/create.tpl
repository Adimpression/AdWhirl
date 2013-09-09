<div id ="main" class="content">
  <div class="column leftColumn">
    <form id="infoForm" class="createAd" action="{if $createOrEdit=='create' || $createOrEdit=='createForApp'}/houseAds/ad/createSubmit{else}/houseAds/ad/editSubmit{/if}" enctype="multipart/form-data" method="post">
	    <input type="hidden" name="returnPage" value="{$returnPage}" class="text"/>
	    <div class="sectionHeader sectionHeaderActive">
		    Information            
		  </div>
   	  <input type="hidden" name="cid" value="{$houseAd->id}" class="text"/>

      <p class="formElement required ">
        <label for="name">Name:</label>
        <input id="adName" type="text" name="name" value="{$houseAd->name}" />
      </p>        
        <p class="formElement required ">
          <label for="goal">Goal:</label>
          <select id="selectGoal" name="linkType">
{html_options options=$linkTypeOptions selected=$houseAd->linkType}

 				 </select>
        </p>        
        <p class="formElement required ">
          <label for="link">Goal URL:</label>
          <input id="adGoal" type="text" name="link" value="{$houseAd->link}" />
        </p>
       
       <div class="sectionHeader sectionHeaderActive">
         Creative
       </div>
        <p class="formElement required ">
          <label for="name">Type:</label>
          <select id="selectType" name="type">
				{if $createOrEdit=='edit'}
					{html_options options=$typeOptions selected=$houseAd->type}
				{else}
					{html_options options=$typeOptions selected=2}
				{/if}
 				 </select>
        </p>							
              <p id="textad" style="width:465px" class="formElement required ">
           <label for="description">Ad Text:</label>
           <input id="ad_text_input" type="text" name="description" value="{$houseAd->description}" style="width:275px" maxlength="35" />
					 <span id="charLeft" style="font-style:italic;color:#999;padding-left:133px"><span>
			
         </p>              
         <p id="image" style="margin-bottom:0px;height:97px;width:420px" class="formElement required ">
           <label for="image">Image:</label>
			<span style="height:50px;">
				<img id="imgDefault" class="ad_image default type2" src="/img/ad_size_38x38.png"/> 
				<span><input id="radioDefault" name="imageDefault" type="radio" id="defaultImage" value="true" checked="checked" /> Use our default image
					</span>
			</span>
			<br>
			<label>&nbsp;</label>
			<img class="ad_image custom type2" src="{if $houseAd->imageLink && $houseAd->type==2}{$houseAd->imageLink}{else}/img/ad_size_38x38.png{/if}"/> <input id="radioCustom" name="imageDefault" type="radio" id="CustomImage" value="false" /> Select your own 38x38 image
			<label>&nbsp;</label>                
           <input id="file" type="hidden" value="{$houseAd->imageLink}" />
           <input id="imageLink" type="hidden" name="imageLink" value="{$houseAd->imageLink}" />

         </p>
         <p id="banner" style="margin-bottom:0px;height:83px;" class="formElement required ">
           <label for="image">Banner:</label>
			<img style="padding-left:133px" class="ad_image custom type1" src="{if $houseAd->imageLink && $houseAd->type==1}{$houseAd->imageLink}{else}/img/ad_size_320x50.png{/if}"/>
		</p>
			<span style="padding-left:180px;padding-bottom:20px" class="button"><a href="#" id="chooseFile" class="disabled"><span id="uploadText">Upload Banner</span></a></span>
			<div id="sp" style="height:40px;width:133px"> </div>
	
       <div class="sectionHeader sectionHeaderActive">
         Include in App Inventory
       </div>
		<table>
			<tr>
				<th style="width:20px"><input type="checkbox" {if $createOrEdit=='create'}checked{/if}/></th>
				<th>App Name</th>
				<th style="width:85px;text-align:center">App Type</th>
			</tr>
		</table>
		<div id="overflow" style="overflow:auto;height:200px">
			<table id="inventory">
				 {foreach from=$apps item=app}
				  {cycle values="odd,even" assign="class"}
				  <tr class="app" style="width:20px">
					 <td class="check {$class}">
							{if $createOrEdit=='create'}
								<input class="appCheckBox" type="checkbox" checked name="apps[]" value="{$app->id}"/>
							{elseif $createOrEdit=='createForApp'}
								<input class="appCheckBox" type="checkbox" {if $app->id==$aid}checked{/if} name="apps[]" value="{$app->id}"/>													
							{elseif $createOrEdit=='edit'}
								{if $app->ahid==''}
									<input class="appCheckBox" type="checkbox" name="apps[]" value="{$app->id}"/>
								{else}
								  <input class="appCheckBox" type="checkbox" checked name="ahids[]" value="{$app->ahid}"/>
								{/if}
							{/if}
					 </td>
				   <td class="name {$class}">
				    <a href="/apps/oneApp/appNetworks?aid={$app->id}">{$app->name}</a>
				   </td>
				   <td class="type {$class}" style="width:85px;text-align:center">{if $app->platform == '1'}iPhone{elseif $app->platform == '2'}Android{else}Unknown{/if}</td>
				</tr>
				{/foreach}
				</table>									
			</div>
			<div class="selectBar">Selected apps:<span id="checked_count" class="bold">x out of y</span></div>


	 </form>
{if $createOrEdit=='edit'}
				<br/>
					<div class="sectionHeader sectionHeaderActive">
						Remove Ad
					</div>
            <p class="formElement required" style="width:471px">
              <label for="delete">Delete Ad:</label>
               <span class="button"><a id="delete" href='#'><span>Delete</span></a></span>	 <span style="padding-left:10px">You'll be asked one more time to confirm.<span>
            </p>
        </div>        
{/if}				
				 </div>

  <div class="column rightColumn">
    <div class="sectionHeader sectionHeaderActive">
      Preview      
    </div>
    <div id="inapp" class="preview">
        <div id="iphone_inapp">
          <div id="iphone_preview_top">&nbsp;</div>
          <div id="iphone_preview_content">
              <div id="iphone_preview_ad">                            
                <div id="iphone_banner_preview" >
                  <img class="left_image ad_image_preview" src="{$houseAd->imageLink}"/>              
                  <p id="iphone_preview_ad_text" class="ad_text">{$houseAd->description}</p>                        
              	</div>              
          	  </div>                   
          </div>
          <div id="iphone_preview_bottom">&nbsp;</div>
					iPhone Preview
		</div>
        <div id="android_inapp">
          <div id="android_preview_top">&nbsp;</div>
          <div id="android_preview_content" class='banner_preview'>
            <div id="android_banner_preview" >
              <img class="left_image ad_image_preview" src="{$houseAd->imageLink}"/>              
              <p id="iphone_preview_ad_text" class="ad_text">{$houseAd->description}</p>
            </div>
          </div>
					Android Preview
        </div>
    </div>    

  </div>
</div>
<div class="clear">&nbsp;</div>
<div style="text-align:center">
	<hr/>
	<span class="button disabled"><a href="#" id="save" class="disabled"><span>Save Changes</span></a></span>
	<a href="{$returnPage}" class="cancel">Cancel</a>
</div>
<form id="deleteForm" method="post" action="/houseAds/ad/delete?cid={$houseAd->id}">
	
	
</form>
<div id="deleteConfirm" class="hidden">
	<div class="modalTop center">
		<img src="/img/exclamation.png"> <span class="modalHeader"> Delete Confirmation</span>
	<span class="modalBody">Are you sure you want to delete this house ad?</span>
	<hr>
	<span class="button"><a href="#" id="confirmDelete"><span>Delete</span></a></span>
	<span class="button"><a href="#" class="simplemodal-close"><span>Cancel</span></a></span>
	</div>
	<div class="modalBottom"></div>
</div>
<script>
var imageLink = "{$houseAd->imageLink}";
var imgPrefix = "http://www.adwhirl.com/img/adwhirl_icons/adwhirl__000";
var hasNoAndroidApp = {if $hasNoAndroidApp=='TRUE'}true{else}false{/if};
var hasNoiPhoneApp = {if $hasNoiPhoneApp=='TRUE'}true{else}false{/if};
var banPlat = new Array();
var isEdit = {if $createOrEdit=='edit' || $createOrEdit=='createForApp'}true{else}false{/if};
var hasImage = false;
var bannerImg = "/img/ad_size_320x50.png";
var imgTextImg = "/img/ad_size_38x38.png";

{literal}

function adjustGoalType() {
	banPlat = new Array();
	$("#selectGoal > option").each(function() {
		$(this).removeAttr('disabled');
	});
	if (isEdit) {
		if (hasNoAndroidApp) banFor('iPhone');	
		if (hasNoiPhoneApp) banFor('Android');		
	}
	if (isEdit) {
		$(".appCheckBox:checked").each(function() {		
			if (!$(this).parent().is("th")) {
				var platform =  $(this).parent().parent().find('.type').text();
				banFor(platform);
			}		
		});
	}	 
	// console.log(banPlat);
	$("#selectGoal > option").each(function() {
		if (banPlat[$(this).val()]) $(this).attr('disabled','disabled');			
	});	
}

function banFor(platform) {
	if (platform == 'iPhone') {
		banPlat['8'] = true;
	}
  if (platform == 'Android') {
		banPlat['2'] = true;
		banPlat['6'] = true;
  }
}

$(document).ready(function() {
  
	$("#confirmDelete").click(function(){
		$("#deleteForm").submit();
	});

	$("#delete").click(function() {
		var form = $('#deleteForm');
		errorObj.reset();
		if ($('td > input:visible:checkbox:checked').length>0) {
			errorObj.attacheError($(this), "Please remove this ad from all apps");
		}
		$("input[name$='ahids[]']").each(function() {
			if ($(this).is(':checked')==false) {
				$('<input type="hidden" name="del_ahids[]" value="' + $(this).val() + '" />').appendTo(form);
			}			
		});
		if (!errorObj.hasError()) {
			$("#deleteConfirm").modal({
				opacity:80,
				overlayCss: {backgroundColor:"#fff"}
			});
		}		
	});
	$("#radioDefault").click(function() {
		$(".ad_image_preview").attr('src',$(".ad_image.default").attr("src"));
	});
	$("#radioCustom").click(function() {
		$(".ad_image_preview").attr('src',$(".ad_image.custom.type2").attr("src"));
	});

	if (isEdit) {
		if (imageLink.indexOf(imgPrefix)<0) {
			hasImage=true;
			$("#radioDefault").removeAttr('checked');		
			$("#radioCustom").attr('checked','checked');		
		}	else {
		  $(".ad_image.custom.type2").attr("src",imgTextImg);
		}
	}
	var aUpload = new AjaxUpload('chooseFile', 
		{
			action:'uploadImage',
			name:'image',
			onSubmit: function() {
				aUpload.setData({'type':$("#selectType").val()});
			},
		 	onComplete:function(file, response) {
				errorObj.reset();
				if (response=="") {
					errorObj.attacheError($("#chooseFile"), "<br/><span style='padding-left:65px'>There was a problem with your upload. You may want to try a smaller file size.</span>");
				} else {
					$("#file").val(response);
					$('.disabled').addClass('enabled').removeClass('disabled');				
					var type = $("#selectType").val();
					$(".ad_image.custom.type"+type).attr('src',response).attr('rel','uploaded');
					$(".ad_image_preview").attr('src',response).attr('rel','uploaded');													
				}
			}
		}
	);			

	$("#save").click(function(e) {
		e.preventDefault();
		$(":hidden:checked").each(function() {
			$(this).removeAttr('checked');		
		});
		if (!$(this).is('.disabled')) {
			var form = $('#infoForm');
			$("input[name='del_ahids[]']").remove();
			$("input[name$='ahids[]']").each(function() {
				if ($(this).is(':checked')==false) {
					$('<input type="hidden" name="del_ahids[]" value="' + $(this).val() + '" />').appendTo(form);
				}
				// console.log($(this).val()+" checked:"+$(this).is(':checked'));
			})

			errorObj.reset();

			var img = $(".ad_image_preview").attr('src');
			if (   ($("#selectType").val()==1 && img.indexOf(bannerImg)>=0)
					|| ($("#selectType").val()==2 && img.indexOf(imgTextImg)>=0) ) { // image and text
						errorObj.attacheError($("#chooseFile"),"Please upload an image");
			}
			if ($("#adName").val()=='') {
				errorObj.attacheError($("#adName"), " Please enter the house ad name");				
			}
			if ($("#adGoal").val()=='') {
				errorObj.attacheError($("#adGoal"), " Please enter the goal URL");				
			}
			$("#imageLink").val(img);
			if (!errorObj.hasError()) {
				// console.log($('#infoForm').serialize());			
				form.submit();
			}
				
		}
	})
	var setCheckedCount = function() {
		$("#checked_count").text(
				$('td > input:visible:checkbox:checked').length + " of " + 
				$('td >input:visible:checkbox').length);
	};
	setCheckedCount();
	adjustGoalType();
	var setCreativeTypeInputs = function() {
		if ($("#selectType").val()==1) {
			$("#image").hide();
			$("#banner").show();
			$("#uploadText").text('Upload Banner');
			$("#textad, .ad_text").hide();
			$(".ad_image_preview").attr('src',$(".ad_image.custom.type1").attr("src")).removeClass('left_image').addClass('banner_image');
		} else {
			$("#image").show();
			$("#banner").hide();
			$("#textad, .ad_text").show();
			$("#uploadText").text('Upload Image');
			if ($("#radioDefault").attr('checked')) {
				$(".ad_image_preview").attr('src',$(".ad_image.default").attr("src")).removeClass('banner_image').addClass('left_image');
			} else {
				$(".ad_image_preview").attr('src',$(".ad_image.custom.type2").attr("src")).removeClass('banner_image').addClass('left_image');
			}
			
		}
	};
	setCreativeTypeInputs();
	$("#selectType").change(setCreativeTypeInputs);

	var setGoal = function() {
		// console.log("setGoal");
		var goal = parseInt($("#selectGoal").val());
		var img = imgPrefix+goal+".jpg";
		var type = $("#selectType").val();
		$(".ad_image.default").attr('src',img);
		if ($("#radioDefault").attr('checked')) {
			$(".ad_image_preview").attr('src',img);			
		}

		// console.log("goal = "+goal);
		var showiPhone = goal!=8 && !($("#selectGoal > option[value='6']").is("[disabled]") || $("#selectGoal > option[value='2']").is("[disabled]"));
		var showAndroid = !(goal==6 || goal==2) && !$("#selectGoal > option[value='8']").is("[disabled]");
		if (!showiPhone && !showAndroid) {
			showAndroid = true;
			showiPhone = true;
		}
		if (showiPhone)	 $("#iphone_inapp").show();  else  $("#iphone_inapp").hide();
		if (showAndroid)	$("#android_inapp").show();	 else  $("#android_inapp").hide();
		$(".app").each(function() {
			var type = $("td.type",this).text(); // appType
			if (type=="iPhone") {
				if (showiPhone) $(this).show(); else $(this).hide();				
			} else {
				if (showAndroid) $(this).show(); else $(this).hide();
			}
		});
		var height = Math.min(130,$("#inventory > tbody").height());
		$(".app:visible").each(function(index, value) {
			$(this).removeClass('even').removeClass('odd').addClass(index==0?'even':'odd');
		})
		$("#overflow").css('height',height);
		setCheckedCount();		
	};
	setGoal();
	$("#selectGoal").change(setGoal);

	$('input, select').change(function () {
		$('.disabled').addClass('enabled').removeClass('disabled');
	});
	$('input:checkbox').change(function () {
		if ($(this).parent().is("th")) {
		 $('td > input:checkbox').attr('checked',$(this).attr('checked'));
		} else {
			$('th > input:checkbox').removeAttr('checked');
		}
		setCheckedCount(); 
		adjustGoalType();
	});
	$("#ad_text_input").bind("change keyup", function() {
		var val = $("#ad_text_input").val();
		$("#charLeft").text(val.length>0 ? ("("+(35-val.length)+" chracters remaining)"):"");
    $(".ad_text").text(val);
  });
});
{/literal}
</script>
