<!doctype html>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="Description" content="Dynamically change between ad networks for your iPhone/Android apps and create and display custom ads to cross-promote your own apps.">
  <link rel="icon" type="image/ico" href="/favicon.ico" />
  <link rel="shortcut icon" href="/favicon.ico" />
  <title>{$title}</title>

  <!-- CSS -->
  <link href="/css/styles.css" rel="stylesheet" type="text/css" />
  {foreach from=$styleSheets item=sheet}
    <link href="{$sheet}" rel="stylesheet" type="text/css" />
  {/foreach}
  <!-- JavaScript -->
  <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
  {foreach from=$jsFiles item=jsFile}
    <script type="text/javascript" src="{$jsFile}"></script>
  {/foreach}
 </head>
 <body>

  <div id="header">


   <div class="logoHeaderContainer">
      <div id="account_container">
      <div id="account">
	     {if $user}
         {$user->email} | 
         <a href="/home/account">Account Settings</a> | 
         <a href="http://helpcenter.adwhirl.com" target="_newtab">Help</a> |
         <a href="http://groups.google.com/group/adwhirl-users?pli=1" target="_newtab">Forum</a> |
         <a href="/home/login/logout">Log Out</a>
			
			 {else}
			 		{if $className!='register'}
       			<a href="/home/register">Sign Up</a> | 
					{/if}
       <a href="http://helpcenter.adwhirl.com" target="_newtab">Help</a>
       		{if $className!='home'} |
					<a href="/home/">Login</a>
					{/if}
			
		   {/if} 
      </div>
      </div>

           <div id="logo_header">
                   <a href="/main"><img id="logo_img" border="0" src="/img/logo.png" alt="AdWhirl" /></a>
           </div>
       </div>
       
   


  {if $tabs_left}
   <div id="tabsContainer">
   <div id="tabs">
    <ul class="left">
     {foreach from=$tabs_left item=tab key=key}
      {if $tab.display}
       <li>
        <a{if $key == $tab_current} class='active{if $className=='home'}White{/if}'{/if} href='{$tab.url}'>
         <div>
          {$tab.name}
         </div>
         <span>&nbsp;</span>
        </a>
       </li>
      {/if}
     {/foreach}
    </ul>

    {if $tabs_right}
    <ul class="right">
     {foreach from=$tabs_right item=tab key=key}
      {if $tab.display}
       <li>
        <a{if $key == $tab_current} class='active'{/if} href='{$tab.url}'>
         <div>
          {$tab.name}
         </div>
        </a>
       </li>
      {/if}
     {/foreach}
    </ul>
    {/if}

    <br />
   </div>
   </div>
  {/if}
  <div class="clear"></div>
  {if  $breadcrumbs || $className=='dev'}
  <div id="breadcrumbsContainer">
  <div id="breadcrumbs">
   {foreach from=$breadcrumbs item=crumb name=breadcrumbs}
     {if !$smarty.foreach.breadcrumbs.last && $crumb.link!=""}<a href="{$crumb.link}">{$crumb.text}</a>{/if}
   			
     {if $smarty.foreach.breadcrumbs.last && $crumb.link!=""}<span style="color:#666">{$crumb.text}</span>{/if}
	   {if !$smarty.foreach.breadcrumbs.last}	&nbsp;&#0187;&nbsp;{/if}
   {/foreach}
	 {if $extra_breadcrumbs}&nbsp;&#0187;{/if}
  </div>
  </div>
  {/if}
  <div id="subtitleContainer">
    <div id="subtitleBox">
      {if $subtitle} 
        <span id="subtitle">{$subtitle}</span>
     {/if}
     {if $needSwitcher}
     <span class="boxLeft right">
     <span id="appSwitcher" class="boxRight">
       <form action="{$smarty.server.PHP_SELF}" method="get">
         <select name="{if $className=='oneApp'}aid{else}cid{/if}" onchange="this.form.submit();">
  	 <option value="">{$switcherText}</option>
  	  {foreach from=$switcherList item=app}
  	   <option value="{$app->id}">{$app->name}</option>
  	  {/foreach}
         </select>
       </form>
     </span>
     </span>
  	 <div class="clear"></div>
     {/if}
    </div>
  </div>
 </div>
<div class="clear"></div>
<div id="content_and_footer">