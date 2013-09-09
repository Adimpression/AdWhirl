{* Parameters needed:
	items_per_page = how many items to display per page
	current_offset = number of the first record currently being displayed
	total = total number of records
	base = base url to use
*}
{assign var=current_page value=$current_offset/$items_per_page+1}
{assign var=left_ellipse value=true}
{assign var=right_ellipse value=true}
{assign var=total_pages value=$total/$items_per_page}

{if $total_pages > 1}
<div class='nav' style='text-align: right; float: right;'>
	{if $current_offset == 0}
		<span>&laquo; Previous</span>
	{else}
		<a href='{$base}?&o={if $current_offset-$items_per_page <= 0}0{else}{$current_offset-$items_per_page}{/if}{if $params}&{$params}{/if}' >&laquo; Previous</a>
	{/if}

    {section name=pages start=0 loop=$total step=$items_per_page}
        {assign var=page value=$smarty.section.pages.index/$items_per_page+1}
        {if $total_pages < 5} {* total pages are less than 4, don't do anything silly *}
	        {if $current_page == $page}
	            <span class='current'>{$page}</span>
	        {else}
	            <a href='{$base}?&o={$smarty.section.pages.index}{if $params}&{$params}{/if}'>{$page}</a>
	        {/if}
        {else}
            {if $current_page == $page}
                <span class='current'>{$page}</span>
            {else}
                {if $current_page > 2 && $page < 2} 
                  <a href='{$base}?&o={$smarty.section.pages.index}{if $params}&{$params}{/if}'>{$page}</a>
                {elseif ($page < $current_page && $page >= $current_page-2) ||
                        ($page > $current_page && $page <= $current_page+2) ||
                        ($page > $total_pages )} {* ellipse for pages not 1 away from next button *}
                  <a href='{$base}?&o={$smarty.section.pages.index}{if $params}&{$params}{/if}'>{$page}</a>
                {else}
                    {if $page < $current_page}
                        {if $right_ellipse}&nbsp;...&nbsp;{assign var=right_ellipse value=false}{/if}
                    {else}
                        {if $left_ellipse}&nbsp;...&nbsp;{assign var=left_ellipse value=false}{/if}
                    {/if}
                {/if}
	        {/if}
	    {/if}
        
	{/section}

	{if $current_offset >= ($total - $items_per_page) }
		<span>Next &raquo;</span>
	{else}
		<a href='{$base}?&o={if $current_offset+$items_per_page > $total}{$total}{else}{$current_offset+$items_per_page}{/if}{if $params}&{$params}{/if}'>Next &raquo;</a>
	{/if}
</div>
{/if}