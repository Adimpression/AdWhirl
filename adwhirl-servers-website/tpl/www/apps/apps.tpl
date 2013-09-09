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
  
<span class="plusContainer"><a href="/apps/oneApp/create"><span class="plus">Add Application</span></a></span>
{if $apps|@count==0}
<div id="topBanner">&nbsp;</div>
<div id="addBody">
  <div>AdWhirl allows you to maximize your revenue by adding multiple ad networks to your application.</div>
  <div id="add_application">
    <div>
      <p class="header2">Add an application</p>
	  <p class="text w3">You will be taken to a new page and led through the process of adding your app to our system. You will have to download the AdWhirl SDK to start showing ads.</p>
    </div>
    <a href="/apps/oneApp/create">Add an Application &raquo;</a>
  </div>
  <div id="add_adwhirl_sdk">
    <div>
      <p class="header2">Add the AdWhirl SDK</p>
	  <p class="text w3">You can either add Ad Networks while adding your app to the system or after, remember to get your site and/or pub ID when you do. You will have to add the app to your account to start showing ads.</p>
    </div>
    <a href="/home/dev">Download the AdWhirl SDK &raquo;</a>
  </div>
</div>
<div id="bottomBanner">&nbsp;</div>

{else}
<div class="clear" style="height:3px"></div>
<table>
 <thead>
  <tr>
   <th style="width:200px">
    App Name  
    
   </th>
	 <th>
	 </th>
   <th class="center" width="100px">
    Platform
   </th>
   <th class="center" width="100px">
    Active Networks
   </th>
  </tr>
 </thead>
 <tbody>
 {foreach from=$apps item=app}
  {cycle values="odd,even" assign="class"}
  <tr>
   <td class="{$class}">
    <a href="/apps/oneApp/appNetworks?aid={$app->id}">{if $app->name}{$app->name}{else}Untitled{/if}</a>
	 </td>
	 <td>
  	<span class="reportDetail">
    <a href='#' class="editLink"></a>
<a href="/reports/applicationReports/?aid={$app->id}"> <img class="editLink" style="vertical-align:middle" src="/img/report.png"/> Reporting</a> 
      </span>	
   </td>
   <td class="{$class} center">
    {if $app->platform == '1'}iPhone{elseif $app->platform == '2'}Android{else}Unknown{/if}
   </td>
   <td class="{$class} center">
     <span>{$app->getActiveNetworksCount()}<span>
   </td>
  </tr>

 {/foreach}
	
  </tbody>
</table>
<div style="width:100%;padding:0px" class="sumBar">&nbsp;</div>
{/if}

</div>
{include file='../tpl/www/common/pagination.tpl'
    items_per_page=$itemsPerPage
    total=$total
    current_offset=$current_offset
    base='/apps/apps'
    params=''}

<script>
var msg_id = "{$msg_id}";
{literal}


	$(document).ready(function() {
	  $(".count").each(function() {
	    if ($(this).text()=='0') {
	      $(this).css("color:#c00");
	    }
	  });
	  
    $("#messageBoxClose").click(function(e) {
      e.preventDefault();
      $("#messageBox").hide();
      $.get('/home/account/setPref?key='+msg_id+'&value=true');
      
    });	  

	  
		$("tr").hover(
			function() {
				$(this).addClass("highlighted");
				$(".reportDetail",this).show();
			}, function() {
	  		$(".reportDetail",this).hide();
				$(this).removeClass("highlighted");
			});
		
	});
</script>
{/literal}