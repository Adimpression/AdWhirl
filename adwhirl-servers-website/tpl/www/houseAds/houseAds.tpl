<div class="content">
  {if $message}
	<div id="messageBox">
    <span id="messageBoxLeft">
      <img id="messageBoxIcon" src="/img/information.png" style="vertical-align:middle;padding-right:5px"><span id="messageBoxText">{$message}</span>
    </span>
    <span id="messageBoxRight">
      <a id="messageBoxClose" href="#" style="vertical-align:baseline"><img style="vertical-align:middle" src="/img/message_box_close.png"></a>               
    </span>
  </div>
  {/if}
  <div class="clear"> </div>
{if $houseAds|@count==0}

	<span class="plusContainer">
		<a class="addApp" href="/houseAds/ad/create"><span class="plus">Add House Ad</span></a>
	</span>
	<div id="topBanner">&nbsp;</div>
	<div id="addBody">
	  <p class="header1">
	  Basic Steps to using House Ads in AdWhirl
	  </p>
		<p class="text">
		House Ads allow you to serve your own ads into your apps, and cross promote your other applications.
		</p>
		<div style="padding: 10px 0px">
		  <span class="third">
				<span class="left">
					<img src="/img/adwhirl_icons/upload_creative.png"/>
				</span>
				<span class="left">
		    	<p class="header2">Upload Creative</p>
					<p class="text w3">If you are using images, otherwise, just type in your ad text</p>
				</span>
		  </span>
		  <span class="third">
				<span class="left">
					<img src="/img/adwhirl_icons/pick_your_apps.png"/>

				</span>
				<span class="left">
		    	<p class="header2">Pick your apps</p>
					<p class="text w3">Your ad can run in all or in selected apps</p>
				</span>
		  </span>
		  <span class="third">
				<span class="left">
					<img src="/img/adwhirl_icons/set_inventory.png"/>
				</span>
				<span class="left">
		    	<p class="header2">Set inventory aside</p>
					<p class="text w3">Within each app, you can decide how much inventory you want to dedicate to your House Ads</p>
				</span>
		  </span>		 
		</div>
		<div class="clear"></div>
		<div style="top-margin:10px">
			<a class="addApp" href="/houseAds/ad/create">Click Here</a> or the button above to create a house ad.
		</div>
	</div>




	<div id="bottomBanner">&nbsp;</div>
	{if $appsCount==0}
		<div id="noApps" class="hidden">
			<div class="modalTop center">
				<img src="/img/exclamation.png"> <span class="modalHeader">Warning</span>
			<span class="modalBody">You currently have no apps. Add an app!</span>
			<hr>
			<span class="button"><a href="/apps/oneApp/create" id="addAnApp"><span>Add App</span></a></span>
			<span class="button"><a href="#" class="simplemodal-close"><span>Cancel</span></a></span>
			</div>
			<div class="modalBottom"></div>
		</div>		
	<script>
	{literal}
	$(document).ready(function() {
		$(".addApp").click(function(e) {
			e.preventDefault();
			$("#noApps").modal({
				opacity:80,
				overlayCss: {backgroundColor:"#fff"}
			});			
		});
		$("#messageBoxIcon").attr('src','/img/information.png');
		$("#messageBoxText").html("<span class='msg'>Learn about our upgraded <a href='http://helpcenter.adwhirl.com/content/step-6-allocate-your-house-ads'>House Ads</a> functionality>");
		$("#messageBox").show();
	
	});
	{/literal}
	</script>
	{/if}
{else}
<form id="deleteForm" action="/houseAds/houseAds/delete" method="POST">
	<span class="button {if $houseAds|@count==0}disabled{/if}"><a href='#' id="delete"><span>Delete</span></a></span>  <span class="divider">|</span><span class="plusContainer">
		<a href="/houseAds/ad/create"><span class="plus">Add House Ad</span></a>
	</span>
	<div class="clear" style="height:3px"></div>	
	<table>
	 <thead>
	  <tr>
		 <th style="width:30px"><input type="checkbox"/></th>
	   <th style="width:200px">
	    <a href="?sortBy={$name}">Ad Name</a>
	   </th>
		 <th>
		 </th>
	   <th style="text-align:center;width:100px">

	     {if $total <= 10}
	     <a href="?sortBy={$numApp}">Apps it runs in</a>	    
	     {else}
	     Apps it runs in
	     {/if}
	   </th>
	   <th style="text-align:center;width:100px">
	     <a href="?sortBy={$linkType}">Goal</a>	    
	   </th>
	   <th style="text-align:center;width:100px">	    
	    <a href="?sortBy={$type}">Type</a>
	   </th>
	  </tr>
	 </thead>
	 <tbody>

	{foreach from=$houseAds item=houseAd}
	  <tr>
		 <td>
			<input type="checkbox" id="{$houseAd->id}" name="deletes[]" value="{$houseAd->id}"/>
		 </td>
	   <td> <!-- style="line-height:18px" -->
			<input type="hidden" name="cids[]" value="{$houseAd->id}" />
			<span><a id="n_{$houseAd->id}" href="/houseAds/ad/edit?cid={$houseAd->id}">{if $houseAd->name}{$houseAd->name}{else}Untitled{/if}</a></span> &nbsp; &nbsp;
			
			<!-- <div style="height:19px"> -->
		</div>
	   </td>
		 <td>
			<span class="editDetail">		
	    <a href="#" class="previewLink"><img text="{$houseAd->description}" type="{$houseAd->type}" imageLink="{$houseAd->imageLink}" style="vertical-align:middle" class="previewLink" src="/img/picture.png"/>&nbsp;&nbsp;Preview</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/reports/houseAdReports?cid={$houseAd->id}"><img class="editLink" style="vertical-align:middle" src="/img/report.png"/>&nbsp;&nbsp;Reporting</a>
			</span>		 			
		 </td>
	   <td style="text-align:center">
		 <span {if $houseAd->apps|@count==0}style="color:#c00"{/if}>{$houseAd->apps|@count}<span>
	   </td>
	   <td style="text-align:center">
			{assign var="linkType" value = `$houseAd->linkType`}
		 {$linkLabels.$linkType}
	   </td>
	   <td style="text-align:center">			
	    {assign var="type" value=`$houseAd->type`}
	    {$houseAdTypes.$type}
	   </td>
	  </tr>
	{/foreach}

	 </tbody>
	</table>
</form>
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
{include file='../tpl/www/common/pagination.tpl'
    items_per_page=$itemsPerPage
    total=$total
    current_offset=$current_offset
    base='/houseAds/houseAds'
    params=''}
		<script>
		{literal}

		$(document).ready(function() {	  
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
		  })
			$("tr").hover(
				function() {
					$(this).addClass("highlighted");
					$(".editDetail",this).show();
				}, function() {
          $(".editDetail",this).hide();
					$(this).removeClass("highlighted");
				})

			$("#delete").click(function() {
				errorObj.reset();				
				$.get('/houseAds/houseAds/canDelete',$('#deleteForm').serialize(),function(data) {
					if (data=='OK') {
						$("#deleteForm").submit();
					} else {
						var cids = data.split(",");
						for (var i in cids) {
							errorObj.attacheError($("#n_"+cids[i]),"&nbsp;This house ad still has apps.&nbsp;");
						}
					}
				});				
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
{/if}
<script>
{literal}
var msg_id = "{$msg_id}";
$(document).ready(function() {
  $("#messageBoxClose").click(function(e) {
    e.preventDefault();
    $("#messageBox").hide();
    $.get('/home/account/setPref?key='+msg_id+'&value=true');    
  });	  
});
{/literal}
</script>
</div>

