<div class="content">
{if !$backfill} 
	{include file="../tpl/www/apps/appNav.tpl"
		header="How to Activate Networks"
		message='<ul><li class="bullet1"> Click on the key and provide the required keys to activate the explicitly supported networks</li><li class="bullet2">Use the "+ Add Custom Events" button at the top of the table to add another network to this app. Be sure to provide a name and function name.</li></ul>'
	}
{else}
	{include file="../tpl/www/apps/appNav.tpl"
		header="Backfill Priority Explained"
		message="Order in which AdWhirl will request an ad from the other ad networks if the share-based 1st request fails. Active House Ads are also used for unfilled inventory."
	}
{/if}
<div style="clear:right"></div>
<div class="mainContent">
  <div class="sectionHeader sectionHeaderActive">
  Networks

  </div>
{if !$backfill}
	<div class="clear" style="height:3px"></div>  
  <span class="plusContainer"><a id="addGeneric" href="#"><span class="plus">Add Custom Event</span></a></span>
  <div class="anchor">
     <div id="addGenericTip" class="appInfoTip">
       <div class="appInfoTipTop genericTipTop">
         
			  <span class="plusContainer"><a href='#'><span class="plus">Add Custom Event</span></a></span>

         <hr/>
         <div style="float:left;width:323px;">
	     		{foreach from=$networkTypes.17.keyinfo key=index item=keyname name=keynames}
      				<p class="formElement required network">
        				<label style="width:90px" for="key[]">{$keyname}:</label>
        				<input tyle="width:196px" class="key" type="text" name="key[]" value="{$networks.$17->keys.$index}" class="text"/>
								{if $smarty.foreach.keynames.last}<a href="#" class="helpTip"><img class="helpTip" src="/img/help.png" title="This is the exact name of the method you would like AdWhirl to call when it makes a call through Generic Notification. Learn more from our FAQ page." alt="This is the exact name of the method you would like AdWhirl to call when it makes a call through Generic Notification. Learn more from our FAQ page."/></a>{/if}
      				</p>         
      			{/foreach} 
         			<div style="text-align:center;padding-top:7px">
		       		<span class="button disabled">
								<a href="#" val_nid="" val_type="17" class="disabled setKeyButton button">								
									<span>Add Event</span>
								</a>
							</span>
		       		<a href="#" class="cancel">Cancel</a>
							</div>
        </div>


				<div class="clear"></div>
       </div>

			 <div class="appInfoTipBottom genericTipBottom">&nbsp;
			 </div>         
      </div>
    </div>
		<div class="clear" style="height:3px"></div>
{/if}

<form id="networkForm" action="/apps/oneApp/appNetworksSubmit" method="post">
<input type="hidden" name="aid" value="{$app->id}" />
<table id="dataTable">
 <thead>
  <tr>
	  {if !$backfill}
   <th style="width:200px">
    <a href="#">Ad Network</a>
   </th>
   <th style="width:200px">
   </th> 
   <th class="center" style="width:160px">
    <a href="#">Ad Serving</a>
   </th>
	 <th></th>
   <th class="center" style="width:160px">
    <a href="#">% of Traffic</a>
   </th>
   {else}
   <th>
    <a href="#">Ad Network</a>
   </th>
   <th style="width:160px">
    <a href="#">Priority</a>
   </th>
   {/if}
  </tr>
 </thead>

 <tbody>

 {foreach from=$networks key=discard item=network}
	{assign var="type" value=$network->type}
	{assign var="networkType" value=$networkTypes.$type}

 {if ($app->platform == '1' && $networkType.iphone ||  $app->platform == '2' && $networkType.android)}
 {if
		(!$backfill && !(!$network->id!='' && ($type == 16 || $type==9 ||$type==17)) )
		||
		($backfill  && $network->id!='' && $network->adsOn==1)}

  <tr>
   <td class="networkName">
   	<input type="hidden" name="nid[]" value="{$network->id}" /><input type="hidden" name="type[]" value="{$type}" />
		<span class="networkName">

		{if $type==17}{$network->keys.0}{else}{$networkType.name}{/if}</span>
		</td>
    {if !$backfill}		
		<td>			
			<div class="anchor">
	       <div id="{$network->id}" class="appInfoTip">
	         <div class="appInfoTipTop">
	           <p class="networkName">{if $type==17}{$network->keys.0}{else}{$networkType.name}{/if}</p>
	           <hr style="margin:0px"/>
	           <div style="float:left;width:{if $type!= 16}265px{else}150px{/if}"> 
								{if $type!= 16}
	          		{foreach from=$networkType.keyinfo key=index item=keyname}
	         				<p class="formElement required network">
	           				<label for="key[]">{$keyname}:</label>
	           				<input class="key" type="text" name="key[]" value="{$network->keys.$index}" class="text"/>
	         				</p>         
	         			{/foreach}
								{else}
									&nbsp;
								{/if}
	           </div>
	           <div style="float:left;width:195;text-align:center;padding-top:11px">
							{if $type!= 16}
	         		<span class="button disabled">
								<a href="#" val_nid="{$network->id}" val_type="{$type}" class="disabled setKeyButton button">
									<span>Save Changes</span>
								</a>
							</span>
							{/if}
		         		<span class="button {if $network->id==NULL}disabled{/if}">
									<a href="#" class="deleteNetwork" val_nid="{$network->id}" val_type="{$type}" class="button">
										<span>{if $type==9 || $type==16 || $type==17}Delete{else}Disable{/if}</span>
									</a>
								</span>							
							<br/>
	         		<a href="#" style class="cancel">Cancel</a>
	         	 </div>
						 <div class="clear"></div>
						 {if !($type==17 || $type==16)}
						 <a href="{$networkType.Website}" target="_newtab">Network Website >></a>
						 {/if}
	         </div>

					 <div class="appInfoTipBottom">&nbsp;
					 </div>         
	       </div>
	    </div>	
		<span class="editDetail">
			&nbsp;&nbsp;
    	<a href='#' class="editLink"> <img style="vertical-align:middle" class="editLink {if $type==9}editHouseAd{/if}" src="/img/key.png"/></a>&nbsp;&nbsp;
			<a href='#' class="editLink {if $type==9}editHouseAd{/if}" ><span class="appInfo">Edit Settings</span></a> 
      </span>			
    </td> 
    {/if}
   {if !$backfill}
   <td class="center adServing">
     {if $network->id==''} 
				<a class="editLink notConfigured" href='#'>Not Configured</a> 
		 {else}
     		<a href='#' class="onOffImg onOffImg{if !$network}Disabled{else}{if $network->adsOn == '1'}On{else}Off{/if}{/if}"></a>   
     {/if}

   </td>
   <td><input class="adServing" type="hidden" name="adsOn[]" value="{$network->adsOn}" /></td>
   <td class="center traffic">
    <input name="weight[]" class="traffic" type="text" {if !$network || !$network->adsOn } disabled="disabled"{/if} maxlength="3" temp="{$network->weight}" value="{if !$network || !$network->adsOn }--{else}{$network->weight}{/if}"/> &nbsp; %
		<input name="priority[]" class="priority" type="hidden" maxlength="3" value="{if $network->priority}{$network->priority}{else}99{/if}" />
   </td>
   {else}
   <td class="priority">
    <input name="priority[]" class="priority" type="{if $network->id==''}hidden{else}text{/if}" maxlength="3" value="{$network->priority}" />
		<input name="weight[]" class="traffic" type="hidden" value="{$network->weight}" />
		<input name="adsOn[]" class="adServing" type="hidden" value="{$network->adsOn}" />
   </td>
   {/if}
  </tr>
  {/if}
 {/if}
 {/foreach}

 </tbody>
</table>
{if !$backfill}

<div class="sumBar">
	<span style="color:#aaa">Total Allocation:</span> <span style="color:#000;padding-right:50px;" id="sum">100%</span>
</div>
{/if}
<div style="text-align:center;margin-top:20px">
  
<hr/>
<span id="loading"><img src="/img/ajax-loader.gif"/>&nbsp;&nbsp;</span><span class="button disabled"><a href="" id="save" class="disabled"><span>Save Changes</span></a></span>
<a id="cancel" href="" class="cancel disabled">Cancel</a>

</div>

</form>


</div>
<div id="saveModal" class="hidden">
	<div class="modalTop center">
		<img class="modalImage" src="/img/accept.png"> <span class="modalHeader"></span>
		<div class="clear"></div>
		<span class="modalBody"></span>
		<hr>
		<span class="button"><a href="#" class="simplemodal-close"><span>OK</span></a></span>
	</div>
	<div class="modalBottom"></div>
</div>

<div id="turnOffModal" class="hidden">
	<div class="modalTop center">
		<img class="modalImage" src="/img/exclamation.png"> <span class="modalHeader"></span>
		<div class="clear"></div>
		<span class="modalBody"></span>
		<hr>
		<span class="button"><a id="confirmTurnOff" href="#"><span>OK</span></a></span>
		<span class="button"><a href="#" class="simplemodal-close"><span>Cancel</span></a></span>
	</div>
	<div class="modalBottom"></div>
</div>


<div id="deleteModal" class="hidden">
	<div class="modalTop center">
		<img class="modalImage" src="/img/accept.png"> <span class="modalHeader"></span>
		<div class="clear"></div>
		<span class="modalBody"></span>
		<hr>
		<span class="button"><a id="confirmDeleteNetwork" href="#"><span>Delete</span></a></span>
		<span class="button"><a href="#" class="simplemodal-close"><span>Cancel</span></a></span>
	</div>
	<div class="modalBottom"></div>
</div>
</div>
<div id="popup" style="left: 135px; top: 344.933px; display:none;">
    <div style="position: relative; top: 7px; left: 161px;" class="popUpArrow" id="popup-arrow"></div>
    <div class="largePopUpTop bottom">
        <div class="largePopUpBottom bottom" style="height:100px">
            <div class="popupTitle titleRed">Function Name</div>
            <a href="#"><img src="/img/small_close.gif" id="popup-close" style="float: right;"></a>
            <div id="popup-body" style="clear: both;" class="body">
                <p class="text">
                This is the exact name of the method you would like AdWhirl to call when it makes a call through Custom Event. Learn more from our <a href="http://helpcenter.adwhirl.com/content/custom-events-and-generic-notifications">FAQ page</a>.
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var aid = "{$app->id}";
var showNoNetworkRunning = {if $showNoNetworkRunning}true{else}false{/if};
var backfill = {if $backfill}true{else}false{/if};
{literal}

function save() {
	var props = new Array('weight','adsOn','priority','type','nid');
	var obj = {'aid':aid};
	for (var i in props) {
		var prop = props[i];
		var vals = new Array();
		var inputs = $(document).find('[name='+prop+'[]]');
		inputs.each(function(i) {
			vals[i]=inputs[i].value;
			if (prop=="weight") {
				if (inputs[i].value=="--") {
					vals[i]="0";
				}					
			}
			if (prop=="adsOn") {
				if (vals[i]=="") vals[i]="0";
			}
		});
		obj[prop+"[]"]=vals;
	}
	traffic.changed=false;
	$.ajax({
    type:'POST',
    url:"/apps/oneApp/appNetworksSubmit",
		context: $(this),
    data:obj,
    success: function (e) {
			$(".modalImage").attr('src','/img/accept.png')
			$(".modalHeader").text("Save Confirmation")
			$('.modalBody').text("Changes Saved.");
			$("#saveModal").modal({
				opacity:80,
				overlayCss: {backgroundColor:"#fff"}
			});
    },
    error: function(e) {
			$(".modalImage").attr('src','/img/exclamation.png')
			$(".modalHeader").text("Save Failed!")
			$('.modalBody').text("Please try again. If the problem presist, please contact support@adwhirl.com.");
			$("#saveModal").modal({
				opacity:80,
				overlayCss: {backgroundColor:"#fff"}
			});
    }
  });
}


function updateTableCache() {
  $("#dataTable").trigger("appendCache");
  $("#dataTable").trigger("update");
}

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
  $(".helpTip").click(function(e) {
    e.stopPropagation();
    e.preventDefault();
    var pos = $(this).offset();    
    $("#popup").css({ top: (pos.top+15), left: (pos.left-168) }).show(); 
  });  
	if ($.browser.msie) {
		$("#addGenericTip").addClass('ie');
		$(".appInfoTip").addClass('ie');		
	}
	if (showNoNetworkRunning) {
	    $(".modalImage").attr('src','/img/exclamation.png')
		$(".modalHeader").text("No Network is turned on for this app.")
		$('.modalBody').text("No ads will be serve to this app. Please turn on or add a network.");
		$("#saveModal").modal({
			opacity:80,
			overlayCss: {backgroundColor:"#fff"}
		});
	}
  $(window).bind('beforeunload', function(e) {
		if (traffic.changed) {
			if(!e) e = window.event;
			//e.cancelBubble is supported by IE - this will kill the bubbling process.
			e.cancelBubble = true;
			e.returnValue = "There are some unsaved changes."; //This is displayed on the dialog

			//e.stopPropagation works in Firefox.
			if (e.stopPropagation) {
				e.stopPropagation();
				e.preventDefault();
			}
		}
	});

	
	$('#loading')
	    .hide()  // hide it initially
	    .ajaxStart(function() {
	        $(this).show();
	    })
	    .ajaxStop(function() {
	        $(this).hide();
	    })
	;

  $("a.disabled, a.cancel").click(function(event) {
    event.preventDefault();
  });

  $('#save').bind("click",
     function(e) {
			errorObj.reset();
			if (backfill) {

				var objRegExp  = /(^\d+$)/;
				var obj = {};
				var count = 0;
				$("input.priority").each(function() {
					if (!$(this).attr('disabled')) {
						count++;
					}
				});

				$("input.priority").each(function() {
					if (!$(this).attr('disabled')) {
						var val = $(this).val();
						if (!objRegExp.test(val)) {
							errorObj.attacheError($(this), "<br/>This is not a valid number.");
						} else if (obj[val]) {
							errorObj.attacheError($(this), "<br/>Can't be duplicated.");
						} else {
							var p = parseInt(val);
							if (p<=0) {
								errorObj.attacheError($(this), "<br/>Can't be negative or zero.");
							}
							if (p>count) {
								errorObj.attacheError($(this), "<br/>Out of range (1 - "+count+')');
							}
						}						
						obj[val] = 'true';						
					}
				});
			} else {
				var trafficSum = traffic.getSum();
				var turningOffApp = false;
				if (trafficSum != 100) {
				  var allOff = true;
				  if (trafficSum == 0) {				    
				    $("input.adServing").each(function() {
				      allOff &= $(this).val()=="0";
				    });
  				} 
  				turningOffApp = trafficSum == 0 && allOff;
  				if (turningOffApp) {
  				  $(".modalImage").attr('src','/img/exclamation.png');
      			$(".modalHeader").text("Turn Off Ad Serving");
      			$('.modalBody').text("Are you sure you want to turn Ad Serving off for all configured networks?  Your app will not make any ad requests after you click OK.");
      			$("#turnOffModal").modal({
      				opacity:80,
      				overlayCss: {backgroundColor:"#fff"}
      			});
  				} else {
  				  errorObj.attacheError($("#sum"),'Total allocation must be 100%.');					
  				}
  				
				}
				$("input.traffic").each(function() {
					if (!$(this).attr('disabled')) {
						var val = $(this).val();
						errorObj.testPercent(val,$(this));
					}
				});
			
			}
			if (!errorObj.hasError() && !turningOffApp) save();									
			
     });
     $("#confirmTurnOff").click(function() {
       save();
       $.modal.close();
     });
	// storing orig values
  $("input").each(function() {
   	$(this).attr('orig',$(this).attr('value'));
  });  
  $('input.priority').change(function() {
    traffic.activateSave();
    updateTableCache();
  });
  $('input.traffic').change(function() {
    traffic.setSum();
    updateTableCache();
  });

	// AppToolTip Section
	$("#addGeneric").click(function (e) {
		e.preventDefault();
		e.stopPropagation();
		$(".appInfoTip").hide();
		$("#addGenericTip").show();
	});
	$(".editLink").click(function (event) {
    event.preventDefault();
		event.stopPropagation();
		if ($(this).is(".editHouseAd")) {
			window.location = "/apps/oneApp/appHouseAds?aid="+aid;
		} else {
	  	$(".appInfoTip").hide();
    	$(this).parents("tr").find(".appInfoTip").show();
		}
  });
	$("tr").hover(
		function() {
			$(this).addClass("highlighted");
			$(".editDetail",this).show();
		}, function() {
  		$(".editDetail",this).hide();
			$(this).removeClass("highlighted");
		})
  $(".cancel").not("#cancel").click(function (event) {
	if (!$(this).is(".disabled")) {
		$(this).parents(".appInfoTip").find("input").each(
			function(e) {
				$(this).attr('value',$(this).attr('orig'));
			});
		$(".appInfoTip").hide();
	}
  });

	var setOnOff = function() {
		var $tr = $(this).parents("tr");
		var $adsInput = $tr.find("input.adServing");
    var val = $adsInput.val();
		var $trafficInput = $tr.find("input.traffic");
		
    if (val==1) {
      $(this).removeClass("onOffImgOn").addClass("onOffImgOff");
      $adsInput.val(0);
			$trafficInput.attr("disabled","disabled");
			$trafficInput.val("--");
    } else {
      $(this).removeClass("onOffImgOff").addClass("onOffImgOn");    
			$trafficInput.removeAttr("disabled");
			var new_val = 100-traffic.getSum();
			if (new_val<0) new_val = 0;
			$trafficInput.val(100-traffic.getSum());
      $adsInput.val(1);
		}
		if ($trafficInput.val()!=0)
		  traffic.activateSave();
		updateTableCache();  
		traffic.setSum(false);
	}
	$("body").click(function (e) {		
		$(".appInfoTip").hide();
	});	
	$(".appInfoTip").bind("click", function(e) {
		e.stopPropagation();
	});
	$('a.onOffImg').click(setOnOff);

  $('input.key').bind("keypress",
      function(e) {
		$(this).parents(".appInfoTip").find("a.disabled").not(".deleteNetwork").removeClass("disabled").addClass("enabled").parent().removeClass("disabled").addClass("enabled");
  });
	$(".deleteNetwork").click( function(e) {
		e.preventDefault();
		$(".modalImage").attr('src','/img/exclamation.png');
		if ($(this).attr('val_type')==17) {
  		$(".modalHeader").text("Delete Confirmation");
  		$('.modalBody').text("Are you sure you want to delete event?");		  
  		$("#confirmDeleteNetwork > span").text("Delete");
		} else {
  		$(".modalHeader").text("Disable Confirmation");
  		$('.modalBody').text("Are you sure you want to disable this network? You will stop receiving ads from this network. You can re-enable a network at anytime.");		  		  
  		$("#confirmDeleteNetwork > span").text("Disable");
		}
		$("#confirmDeleteNetwork").attr('href', '/apps/oneApp/deleteNetwork?aid='+aid+'&nid=' + $(this).attr('val_nid'));
		$("#deleteModal").modal({
			opacity:80,
			overlayCss: {backgroundColor:"#fff"}
		});
	});
	var tableSorterHeader = backfill?{}:{1:{sorter:false},3:{sorter:false}};
	
	$("#dataTable").tablesorter({
	  headers: tableSorterHeader,
	  textExtraction: function(node) { 
        $n = $(node);
        if ($n.is('.networkName')) return $n.find('span.networkName').text();
        if ($n.is('.adServing')) {
          var as = "-1";
          $a = $n.find('a');
          if ($a.is('.notConfigured')) as = "-1";
          if ($a.is('.onOffImgOff')) as = "0";
          if ($a.is('.onOffImgOn')) as = "1";
          return as;
        }
        if ($n.is('.traffic')) {
          var val = $n.find('input.traffic').val();
          if (val=='--') return "-1";
          else return val;
        }
        if ($n.is('.priority')) {
          return $n.find('input.priority').val();
        }
        
        return node.innerHTML;

    }
	  
	}); 
  $('.setKeyButton').bind("click",
        function(e) {
          e.preventDefault();
          // Disable the button while we save so it only gets pushed once to the DB
          $(this).parent().removeClass('enabled').addClass('disabled');
          traffic.setSum();
          var nid = $(this).attr('val_nid');    
          var inputs = $(this).parents(".appInfoTip").find('[name=key[]]');
          var keys = new Array();
          errorObj.reset();
          $(".error").remove();
          inputs.each(function(i) {					
            if (inputs[i].value=="") {
              errorObj.attacheError(this,'This key is required.')
            }
            keys[i]=inputs[i].value;		    
          });
          if (errorObj.hasError()) return false;
          var obj = {
                  'keys[]':keys,
                  'nid':nid,
                  'aid':aid,
                  'type':$(this).attr('val_type')};
          $.ajax({
            type:'POST',
            url:"/apps/oneApp/appNetworkKeySubmit",
            context: $(this),
            data:obj,
            success: function (data) {
              $(".appInfoTip").hide();
              var type = $($(this).context).attr('val_type');
              if (type=="17") {
                location.reload(true);
              } else {								
              var inputs = $(this).parents(".appInfoTip").find("[name='key[]']");
                inputs.each(function() {
                $(this).attr("orig",$(this).val());									
//									$(this).val($(this).attr("orig"));
                });
                var $tr = $(this).parents("tr");
  	        $tr.find("input[name='nid[]']").val(data);
  	        var $tdAdServing = $tr.find('td.adServing');
  	        if ($tdAdServing.find('a').is('.notConfigured')) {
    	          var $link = $('<a href="#" class="onOffImg onOffImgOff"></a>').click(setOnOff);
  	  	  $tdAdServing.html('').append($link);
    		  $tr.find('input.adServing').val(1);
    		  $tr.find('.deleteNetwork').parent().removeClass('disabled');
    		  $link.trigger('click');
  	        }							
              }
            },
            error: function(e) {
              alert("There was an error saving your changes: "+e);
            }
          });
        }
    );
		traffic.setSumOnly();		
});

{/literal}

</script>
