<table>
  <thead>
    <tr>
      <th>
        Ad Name
      </th>
      <th class="center">
        Type
      </th>
      <th class="center">
        Impressions
      </th>
      <th class="center">
        Clicks
      </th>
      <th class="center">
        CTR
      </th>
  </tr>
 </thead>
 <tbody>

 {foreach from=$houseAds item=houseAd}
  {cycle values="odd,even" assign="class"}
  <tr>
   <td class="{$class}">
    {$houseAd->name}
   </td>
   <td class="{$class} center">
    {assign var="type" value=`$houseAd->type`}
    {$houseAdTypes.$type}
   </td>
   <td class="{$class} center">
    {$houseAd->totals.impressions}
   </td>
   <td class="{$class} center">
    {$houseAd->totals.clicks}
   </td>
   <td class="{$class} center">
    {$houseAd->totals.ctr}
   </td>
  </tr>
 {/foreach}
	
  </tbody>
</table>