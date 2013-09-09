{php}header("Content-Type: text/x-csv; charset=utf-8"); header('Content-Disposition: attachment; filename="AdWhirlReport.csv"');{/php}
{* This template has weird spacing so the csv output looks nice *}
"Date","{$metricLabel}"{foreach from=$nets key=type item=nname},"{$nname}"{/foreach} 
{foreach from=$dates item=date}
"{$date}",{if $reports[$date][0].$metric}{$reports[$date][0].$metric}{else}0{/if}{foreach from=$nets key=type item=nname},{if $reports[$date][$type].$metric}{$reports[$date][$type].$metric}{else}0{/if}{/foreach}

{/foreach}

