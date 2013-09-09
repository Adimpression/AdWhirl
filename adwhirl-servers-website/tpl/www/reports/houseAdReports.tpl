
<div class="content">
	{include file="../tpl/www/reports/reportNav.tpl"
		header = "Common Questions"
		message = "<ul>
			<li class='bullet1'>Why am I not seeing all my applications on the graph?<br><div style='height:3px'> </div>
		In order to keep the graph clean we are only showing the top applications in terms of impressions.
		The other applications are grouped under 'Others'</li>
			<li class='bullet2'>How can I get access to reporting for all my applications?<br/><div style='height:3px'> </div>
		Just download the CSV file, all the information is detailed in that file</li>
			<li class='bullet3'>	Why are my impression numbers different in AdWhirl than they are in my application reporting page?<br><div style='height:3px'> </div>See <a style='display:inline;background-color:#fff' href='http://helpcenter.adwhirl.com/content/reporting'>Help Content</a></li></ul>"
	
	}
<div>
<span class="reportSelect houseAdReportSelect">
	<label  for="name">Select:</label>
	<select id="dateOptions">
   {html_options options=$dateOptions selected=$selectedDate}
 	</select>
</span><span class="reportSelect houseAdReportSelect" style="border-width:0 0 0 2px;border-style:solid;border-color:#ccc">
	<label for="name">Select:</label>
	<select id="houseAdOptions" style="width:170px">
		{if $houseAdOptions|@count>0}
   		{html_options options=$houseAdOptions selected=$selectedHouseAd}
		{else}
			<option>Select a house ad</option>
		{/if}
   
 	</select>
</span><span class="reportSelect houseAdReportSelect" style="border-width:0 0 0 2px;border-style:solid;border-color:#ccc">
	<label for="name">Select:</label>
	<select id="catOptions">
   {html_options options=$catOptions selected=$selectedCat}
 	</select>
</span>
{foreach from=$metricTypes item = metricType}<span class="{if $metricTypeSelected==$metricType}metricTypeSelected{/if} metricType">{$metricType}</span>
{/foreach}
<div class="left">
  <div id="chartDiv"/>
</div>
<span><input id="showDeleted" type="checkbox" {if $showDeleted}checked="checked"{/if}/> Show Deleted Ads</span>
<div id="table">

</div>
<a id="exportToCSV" href="{$csvURL}{$queryParam}">Download CSV Report</a>

</div>
<script type="text/javascript">
	var csvURL = "{$csvURL}";
	var dataURL = "{$dataURL}";
	var htmlTableURL = "{$htmlTableURL}";  
  var chart;
  var orgQueryParam = '{$queryParam}';
  var isShowDeleted = {if $showDeleted}true{else}false{/if};
{literal}
  function showDeleted(showDeleted) {
    var opts = $("#houseAdOptions > option[label$='deleted']");
    if (!showDeleted) {
      $("#houseAdOptions > option[label$='-- deleted']").hide();
      $.get('/reports/houseAdReports/notShowDeleted');      
    } else {
      $.get('/reports/houseAdReports/showDeleted');
      $("#houseAdOptions > option[label$='-- deleted']").show();
    }
  }

  function update(queryParam) {		
    chart.setDataURL(dataURL + queryParam);
    $("#exportToCSV").attr('href',csvURL + queryParam);
    $.get(htmlTableURL+queryParam, function(data){
      $("#table").html(data);
     });
		// $.get(dataURL + queryParam, function(data) {
		// 	console.log(data);
		// });    
  }  
  $(document).ready(function() {
    $("#showDeleted").change(function() {
      showDeleted($(this).is(":checked"));
    });
    showDeleted(isShowDeleted);    
		$("select").change(function() {
			update($("#dateOptions").val()+"&cid="+$("#houseAdOptions").val()+"&selectedCat="+$("#catOptions").val()+"&metricTypeSelected="+$(".metricTypeSelected").text());
			$("#subtitle").text($('#houseAdOptions :selected').text())			
		});
		$(".metricType").click(function() {
			$(".metricTypeSelected").removeClass("metricTypeSelected");
			$(this).addClass("metricTypeSelected");
			update($("#dateOptions").val()+"&cid="+$("#houseAdOptions").val()+"&selectedCat="+$("#catOptions").val()+"&metricTypeSelected="+$(".metricTypeSelected").text());
		});
    chart = new FusionCharts("/FusionCharts/MSLine.swf", "SalesByCat", "790", "400", "0", "1");
  	chart.setDataURL(dataURL+orgQueryParam);
  	chart.render("chartDiv");
    $.get(htmlTableURL+orgQueryParam, function(data){
      $("#table").html(data);
     });
  });  
{/literal}
</script>


</div>

