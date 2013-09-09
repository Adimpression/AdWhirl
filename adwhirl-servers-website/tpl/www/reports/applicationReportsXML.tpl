<chart showNames='1' animation='1' chartRightMargin='50'>
  <categories>
  {foreach from=$dates key=date item=label}  
    <category name='{$date}' hoverText='{$label}' />
  {/foreach}
  </categories>
  <dataset seriesName='Total' showValues='0'>
    {foreach from=$dates key=date item=label}
     <set value='{if $reports[$date][0].$metric}{$reports[$date][0].$metric}{else}0{/if}' />
    {/foreach}
  </dataset>

  {foreach from=$nets key=type item=name }
  <dataset seriesName='{$name|escape:"html"}' showValues='0'>
    {foreach from=$dates key=date item=label}
     <set value='{if $reports[$date][$type].$metric}{$reports[$date][$type].$metric}{else}0{/if}' />
    {/foreach}
  </dataset>
  {/foreach}
</chart>
