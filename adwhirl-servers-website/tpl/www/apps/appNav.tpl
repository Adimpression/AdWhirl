
<div id="sideNav">
 <ul>
  <li>
   <a href="/apps/oneApp/appNetworks?aid={$app->id}"{if $sideNav_current == 'networks'} class='active'{/if}>
    <div>Ad Network Settings</div>
   </a>
  </li>
  <li>
   <a href="/apps/oneApp/backfillPriority?aid={$app->id}"{if $sideNav_current == 'backFill'} class='active'{/if}>
    <div>Backfill Priority</div>
   </a>
  </li>
  <li>
   <a href="/apps/oneApp/appHouseAds?aid={$app->id}"{if $sideNav_current == 'houseAds'} class='active'{/if}>
    <div>House Ads</div>
   </a>
  </li>
  <li>
   <a href="/apps/oneApp/info?aid={$app->id}"{if $sideNav_current == 'info'} class='active'{/if}>
    <div>App Settings</div>
   </a>
  </li>
 </ul>

<div style="height:40px"></div>
{if $header}
<!-- <span class="right"> <a href='#' class="sideShowHideButton">Hide</a> </span> -->
<br>    
<div class="subSectionHeader">{$header}</div>
<div class="sectionBody">
{$message}
</div>
</div>
{/if}
</div>
<script>
{literal}
$("a.sideShowHideButton").bind("click",
  function(e) {
		var parent = $(this).parent().parent();
		var body = $(this).parent().parent().find(".sectionBody")
    if ($(this).text()=="Show") {
      $(this).text("Hide");
			$(this).parent().parent().find(".sectionBody").show();
    } else {
			$(this).parent().parent().find(".sectionBody").hide();
      $(this).text("Show");
    }      
  });

{/literal}
</script>