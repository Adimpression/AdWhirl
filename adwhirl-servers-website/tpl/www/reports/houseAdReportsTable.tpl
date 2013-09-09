<table>
  <thead>
    <tr>
      <th>
        Date
      </th>
      <th class="center">
        {$metricLabel}
      </th>
      {foreach from=$nets key=type item=nname}
      <th class="center">
       {$nname}
      </th>   
      {/foreach} 
  </tr>
 </thead>
 <tbody>

   {foreach from=$dates item=date}
  {cycle values="odd,even" assign="class"}
  <tr>
   <td class="{$class}">
    {$date}
   </td>
   <td class="{$class} center">
     {if $reports[$date][0].$metric}{$reports[$date][0].$metric}{else}0{/if}
   </td>
  {foreach from=$nets key=type item=name }
  <td class="{$class} center">
     {if $reports[$date][$type].$metric}{$reports[$date][$type].$metric}{else}0{/if}
	</td>
  {/foreach}
  </tr>

 {/foreach}
	
  </tbody>
</table>