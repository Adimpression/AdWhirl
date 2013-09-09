<graph showNames='1' animation='1'>
  <categories>
  {foreach from=$dates item=date}  
    <category name='{$date}' hoverText='{$date}' />
  {/foreach}
  </categories>

  <dataset seriesName='Total' showValues='0'>
    {foreach from=$dates item=date}
      <set value='{if $reports[$date][0].impressions}{$reports[$date][0].impressions}{else}0{/if}' />
    {/foreach}
  </dataset>

  {foreach from=$networks key=type item=nname}
  <dataset seriesName='{$nname}' showValues='0'>
    {foreach from=$dates item=date}
     <set value='{if $reports[$date][$type].impressions}{$reports[$date][$type].impressions}{else}0{/if}' />
    {/foreach}
  </dataset>
  {/foreach}
</graph>
