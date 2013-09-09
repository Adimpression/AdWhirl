{php}header("Content-Type: text/x-csv; charset=utf-8"); header('Content-Disposition: attachment; filename="AdWhirlReport.csv"');{/php}
{* This template has weird spacing so the csv output looks nice *}
"App Name","Platform"{foreach from=$networks key=type item=nname},"{$nname}"{/foreach} 
{foreach from=$apps item=app}
"{$app->name}","{if $app->platform == '1'}iPhone{elseif $app->platform == '2'}Android{else}Unknown{/if}"{foreach from=$networks key=type item=nname},{if $app->totals[$type].impressions}{$app->totals[$type].impressions}{else}0{/if}{/foreach}

{/foreach}

