<chart showNames='1' animation='1' chartRightMargin='50'>
  <categories>
  {foreach from=$dates key=date item=label}  
    <category name='{$date}' hoverText='{$label}' />
  {/foreach}
  </categories>

  <dataset seriesName='Total' showValues='0'>
    {foreach from=$dates key=date item=label}  
      <set value='{if $totals[$date].impressions}{$totals[$date].impressions}{else}0{/if}' />
    {/foreach}
  </dataset>

  {foreach from=$houseAds item=houseAd}
  <dataset seriesName='{$houseAd->name}' showValues='0'>
    {foreach from=$dates key=date item=label}  
     <set value='{if $houseAd->reports[$date].impressions}{$houseAd->reports[$date].impressions}{else}0{/if}' />
    {/foreach}
  </dataset>
  {/foreach}
</chart>
