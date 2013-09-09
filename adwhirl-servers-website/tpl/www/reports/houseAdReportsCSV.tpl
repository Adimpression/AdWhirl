{php}header("Content-Type: text/x-csv; charset=utf-8"); header('Content-Disposition: attachment; filename="AdWhirlReport.csv"');{/php}
{* This template has weird spacing so the csv output looks nice *}
"Ad Name","Type","Impressions","Clicks","CTR"
{foreach from=$houseAds item=houseAd}
"{$houseAd->name}","{assign var="type" value=`$houseAd->type`}
{$houseAdTypes.$type}","{$houseAd->totals.impressions}","{$houseAd->totals.clicks}","{$houseAd->totals.ctr}"
{/foreach}

