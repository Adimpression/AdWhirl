<div class="content">
	{include file="../tpl/www/apps/appNav.tpl"
		header = "Manage House Ads"
		message = "Share: Desired split of house ad impressions within your house ads' share of traffic"
	
	}
	<div style="clear:right"></div>
	<div class="mainContent">		
	  <div class="sectionHeader sectionHeaderActive">
	  <span class="left">House Ads</span><span class="right">House Ads {if $houseAdShare}has Traffic Share of {$houseAdShare}%{else}are turned <span class="bold">OFF</span>{/if}</span>
    <div style="clear: both;"></div>
	  </div>

	

	<span class="button {if $houseAds|@count==0}disabled{/if}">
		<a id="removeApp" href='#'><span>Remove From App</span></a>
	</span>
	<span class="divider">|</span>
	<span class="plusContainer">
			<a id="addHouseAd" href='#'><span class="plus">Add House Ad</span></a>
	</span>

  <div class="anchor">
     <div id="addHouseAdTip" class="appInfoTip">
       <div class="appInfoTipTop genericTipTop" style="margin-left:15px">         
			  <span class="plusContainer"><a href='#'><span class="plus">Add House Ad</span></a></span>
         <hr/>
         <div style="float:left;width:323px;"> 
       				<p class="formElement required network">
         				<label style="width:105px" for="name">Account House Ad:</label>
								<select id="chooseAd" name="houseAd">
	 						   {html_options options=$addableHouseAds}
	    				 	</select>
       				</p>         
		       		<span class="button">
								<a id="createNew" href="/houseAds/ad/create?aid={$app->id}" class="setKeyButton button">
									<span>Create New</span>
								</a>
							</span>
         			<div style="text-align:center;padding-top:7px">
		       		<span class="button disabled">
								<a href='#' id="addToApp" class="disabled setKeyButton button">
									<span>Add To App</span>
								</a>
							</span>

		       		<a href="#" class="cancel">Cancel</a>
							</div>
        </div>


				<div class="clear"></div>
       </div>

			 <div class="appInfoTipBottom genericTipBottom" style="margin-left:15px">&nbsp;
			 </div>         
      </div>
    </div>
		<div class="clear" style="height:3px"></div>

<form id="houseAdsForm" action="/apps/oneApp/houseAdsSubmit" method="post">
      <input type="hidden" name="aid" value="{$app->id}" />
	<table>
	 <thead>
	  <tr>
		 <th><input type="checkbox"/></th>
	   <th style="padding-left:5px;width:200px">
	    Ad Name
	   </th>
			<th style="width:200px"></th>
	   <th class="center" style="width:100px">
	    Goal
	   </th>
	   <th class="center" style="width:100px">
	    Type
	   </th>
	   <th style="width:100px">
	    Share
	   </th>
	  </tr>
	 </thead>
	 <tbody>

	{foreach from=$houseAds item=houseAd}
	  <tr>
		 <td>
			<input type="hidden" name="ahids[]" value="{$houseAd->ahid}"/>
			<input type="checkbox" name="del_ahids[]" value="{$houseAd->ahid}"/>
		 </td>
	   <td style="padding-left:5px">
			<span><a href="/houseAds/ad/edit?cid={$houseAd->id}">{if $houseAd->name}{$houseAd->name}{else}Untitled{/if}</a></span>			
	   </td>
		<td class="center" >
			<span class="editDetail">		
  	    <a href="#" class="previewLink"><img text="{$houseAd->description}" type="{$houseAd->type}" imageLink="{$houseAd->imageLink}" style="vertical-align:middle" class="previewLink" src="/img/picture.png"/>&nbsp;&nbsp;Preview</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/reports/houseAdReports?cid={$houseAd->id}"><img class="editLink" style="vertical-align:middle" src="/img/report.png"/>&nbsp;&nbsp;Reporting</a>
			<input type="hidden" name="cids[]" value="{$houseAd->id}" />
			</span>
		</td>
	   <td class="center" >
			{assign var="linkType" value = `$houseAd->linkType`}
		 {$linkLabels.$linkType}
	   </td>
	   <td class="center" >			
	    {assign var="type" value=`$houseAd->type`}
	    {$houseAdTypes.$type}
	   </td>
	   <td>
		  <input name="weight[]" class="traffic" type="text" maxlength="3" value="{$houseAd->weight}"/> &nbsp; %	   
	   </td>

	  </tr>
	{/foreach}

	 </tbody>
	</table>
</form>
<div class="sumBar">
	{if $houseAds|@count>0}
		<span style="color:#aaa">Total Share:</span> <span style="padding-right: 50px;color:#000" id="sum">100 %</span>
	{/if}
</div>
<div style="text-align:center;margin-top:50px">
  
<hr/>
<span class="button disabled"><a href="#" id="save" class="disabled"><span>Save Changes</span></a></span>
<a id="cancel" href="" class="cancel disabled">Cancel</a>
</div>

</div>
<div id="popup" style="left: 135px; top: 344.933px; display:none;">
    <div style="position: relative; top: 7px; left: 161px;" class="popUpArrow" id="popup-arrow"></div>
    <div class="largePopUpTop bottom">
        <div class="largePopUpBottom bottom" style="height:98px">
            <div class="popupTitle titleRed">Ad Preview</div>
            <a href="#"><img src="/img/small_close.gif" id="popup-close" style="float: right;"></a>
            <div id="popup-body" style="clear: both;" class="body">
                <div id="iphone_preview_ad">                            
                  <div id="iphone_banner_preview" >
                    <img class="left_image ad_image_preview" src=""/>              
                    <p id="iphone_preview_ad_text" class="ad_text">{$houseAd->description}</p>                        
                  </div>              
            	</div>
            </div>
        </div>
    </div>
</div>
<script>
var aid = '{$app->id}';
// var shortCircuitAddHouseAd = false;

var shortCircuitAddHouseAd = {if $addableHouseAds|@count<=1}true{else}false{/if};

{literal}
$(document).ready(function() {
	if ($.browser.msie) {
		$("#addHouseAdTip").addClass('ie');
	}
	$("tr").hover(
		function() {
			$(this).addClass("highlighted");
			$(".editDetail",this).show();
		}, function() {
  		$(".editDetail",this).hide();
			$(this).removeClass("highlighted");
		})
	
	$('input.traffic').change(traffic.setSum);
	traffic.setSumOnly();
	
	$("a.disabled, a.cancel").click(function(event) {
    event.preventDefault();
  });
  $("body").click(function (e) {		
		$("#popup").hide();
	});	
	$("#popup").click(function (e) {
		e.stopPropagation();
	});		  
  $("#popup-close").click(function(e) {
    e.preventDefault();
    $("#popup").hide();
  });
	
  $(".previewLink").click(function(e) {
    e.stopPropagation();
    e.preventDefault();
    var $img = $(this).parents("td").find("img.previewLink");
    var pos = $img.offset();
    $(".ad_image_preview").attr("src",$img.attr('imageLink'));
    if ($img.attr('type')=="1") {
      $(".ad_image_preview").removeClass('left_image')
    } else {
      $(".ad_image_preview").addClass('left_image')
    }
    $(".ad_text").text($img.attr('text'));
    
    $("#popup").css({ top: (pos.top+15), left: (pos.left-168) }).show(); 
  });
	$('#save').bind("click",
    function(e) {
			errorObj.reset();
			if (traffic.getSum()!=100) {
				errorObj.attacheError($("#sum"),'Total allocation must be 100%.');
			}
			$(".traffic").each(function() {
				if (!$(this).attr('disabled')) {
					var val = $(this).val();
					errorObj.testPercent(val,$(this));
				}
			});
			if (!errorObj.hasError()) $("#houseAdsForm").submit();									
    });
	$(".cancel").click(function (event) {
		if (!$(this).is(".disabled")) {
			$(".appInfoTip").hide();
		}
  });
	$("#chooseAd").change(function() {
		if ($(this).val()=='') $("#addToApp").addClass("disabled").parent().addClass("disabled");
		else $("#addToApp").removeClass("disabled").parent().removeClass("disabled");
	});
	$("#addToApp").click(function() {
		$.ajax({
	    type:'POST',
	    url:"/houseAds/ad/addApp",
	    data:{'cid':$("#chooseAd").val(),'apps[]':aid},
	    success: function (e) {
				$(".appInfoTip").hide();
				window.location = window.location;
	    },
	    error: function(e) {
	      alert("saveError:"+e);
	    }
	  });
	});
	$("#addHouseAd").click(function() {
		if (shortCircuitAddHouseAd) {
			window.location=$("#createNew").attr('href');			
		} else {
			$(".appInfoTip").show();
		}
		
	});
	$("#removeApp").click(function() {
		
		$("#houseAdsForm").attr('action','/houseAds/houseAds/deleteAppHouseAds?aid='+aid).submit();
	});
	$('input:checkbox').change(function () {
		if ($(this).parent().is("th")) {
	   $('td > input:checkbox').attr('checked',$(this).attr('checked'));
		} else {
	    $('th > input:checkbox').removeAttr('checked');
		}
	});
});
</script>
{/literal}