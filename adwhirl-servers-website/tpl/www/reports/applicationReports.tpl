
<div class="content">

	{include file="../tpl/www/reports/reportNav.tpl"
		header = "Common Questions"
		message = "<ul>
			<li class='bullet1'>Why am I not seeing all my networks on the graph?<br><div style='height:3px'> </div>
		In order to keep the graph clean we are only showing the top networks in terms of impressions.
		The other networks are grouped under 'Others'</li>
			<li class='bullet2'>How can I get access to reporting for all my networks?<br/><div style='height:3px'> </div>
		Just download the CSV file, all the information is detailed in that file</li>
			<li class='bullet3'>	Why are my impression numbers different in AdWhirl than they are in my network reporting page?<br><div style='height:3px'> </div>See <a style='display:inline;background-color:#fff' href='http://helpcenter.adwhirl.com/content/reporting'>Help Content</a></li></ul>"
	
	}

<span class="reportSelect">
	<label  for="name">Select:</label>
	<select id="dateOptions">
   {html_options options=$dateOptions selected=$selectedDate}
 	</select>
</span><span class="reportSelect" style="border-width:0 0 0 2px;border-style:solid;border-color:#ccc">
	<label for="name">Select:</label>
	<select id="appOptions" class="appOptions">
		{if $appsOption|@count>0}
   		{html_options options=$appsOption selected=$selectedApp}
		{else}
			<option>Select an application</option>
		{/if}
 	</select>
  <!-- <select id="appOptionsWithDeletes" class="appOptions" {if !$showDelete} class="hidden"{/if}>
    {if $appsOption|@count>0}
      {html_options options=$appsOptionWithDeletes selected=$selectedApp}
    {else}
      <option>Select an application</option>
    {/if}
  </select> -->

</span>
<div class="metricTypeSelected" style="height:38px;width:791px">
Impression
</div>
<div class="left">
  <div id="chartDiv"/>
</div>
<span><input id="showDeleted" type="checkbox" {if $showDeleted}checked="checked"{/if}/> Show Deleted Apps</span>
<div id="table">

</div>
<a id="exportToCSV" href="{$csvURL}{$queryParam}">Download CSV Report</a>


<script type="text/javascript">
  var csvURL = "{$csvURL}";
  var dataURL = "{$dataURL}";
  var htmlTableURL = "{$htmlTableURL}";  
  var chart;
  var orgQueryParam = '{$queryParam}';
  var isShowDeleted = {if $showDeleted}true{else}false{/if};
{literal}
  function showDeleted(showDeleted) {
    var opts = $("#appOptions > option[label$='deleted']");
    if (!showDeleted) {
      $("#appOptions > option[label$='-- deleted']").hide();
      $.get('/reports/applicationReports/notShowDeleted');      
    } else {
      $.get('/reports/applicationReports/showDeleted');
      $("#appOptions > option[label$='-- deleted']").show();
    }
  }
  function update(queryParam) {		
    chart.setDataURL(dataURL + queryParam);
    $("#exportToCSV").attr('href',csvURL + queryParam);
    $.get(htmlTableURL+queryParam, function(data){
      $("#table").html(data);
     });
  }  
  // Necessary to display the correct chart - see www.fusioncharts.com/docs/Contents/JSDataURL.html
  function FC_Rendered(DOMId){
    //This method is called whenever a FusionCharts chart is loaded.
    //Check if it's the required chart using ID
    if (DOMId=="SalesByCat"){
      update(orgQueryParam+'&aid='+$("#appOptions").val());
    }
  } 
  $(document).ready(function() {
    showDeleted(isShowDeleted);
    $("#showDeleted").change(function() {
      showDeleted($(this).is(":checked"));
    });

    chart = new FusionCharts("/FusionCharts/MSLine.swf", "SalesByCat", "790", "400", "0", "1");
    // Start it out blank so it doesn't get the wrong data
    chart.setDataXML("<chart></chart>");
    chart.render("chartDiv");
    $("#dateOptions").change(function() {
      update($("#dateOptions").val()+"&aid="+$("#appOptions").val());
    });
    $("#appOptions").change(function() {
      update($("#dateOptions").val()+"&aid="+$("#appOptions").val());			            
      $("#subtitle").text($('#appOptions :selected').text())
    });
  });  
{/literal}
</script>


</div>
