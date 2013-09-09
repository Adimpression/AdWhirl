

<div id="sideNav">
 <ul>
  <li>
   <a href="/reports/applicationReports"{if $sideNav_current == 'applications'} class='active'{/if}>
    <div>Application</div>
   </a>
  </li>
  <li>
   <a href="/reports/houseAdReports"{if $sideNav_current == 'houseAds'} class='active'{/if}>
    <div>House Ads</div>
   </a>
  </li>
  <!-- <li>
   <a href="/reports/networkReports"{if $sideNav_current == 'networks'} class='active'{/if}>
    <div>Networks</div>
   </a>
  </li> -->
 </ul>
<div style="height:40px"></div>
{if $header}
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
