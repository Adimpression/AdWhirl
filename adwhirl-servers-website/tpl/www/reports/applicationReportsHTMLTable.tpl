<table>
  <thead>
    <tr>
      <th>
        App Name
      </th>
      <th class="center">
        Platform
      </th>
      {foreach from=$networks key=type item=nname}
      <th class="center">
       {$nname}
      </th>   
      {/foreach} 
  </tr>
 </thead>
 <tbody>

 {foreach from=$apps item=app}
  {cycle values="odd,even" assign="class"}
  <tr>
   <td class="{$class}">
    {$app->name}
   </td>
   <td class="{$class} center">
     {if $app->platform == '1'}iPhone{elseif $app->platform == '2'}Android{else}Unknown{/if}
   </td>
   {foreach from=$networks key=type item=nname}
   <td class="{$class} center">
      {if $app->totals[$type].impressions}{$app->totals[$type].impressions}{else}0{/if}
   </td>   
   {/foreach}
  </tr>

 {/foreach}
	
  </tbody>
</table>