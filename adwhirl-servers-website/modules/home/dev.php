<?php
/*
 -----------------------------------------------------------------------
Copyright 2009-2010 AdMob, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
------------------------------------------------------------------------
*/
?>
<?php

class dev extends Webpage {
  public function __default() {
    // $this->printHeader = false;
    // $this->printFooter = false;
		$network_options = array("http://www.greystripe.com/" => "GreyStripe",
		"http://inmobi.com/" => "InMobi",
		"https://www.google.com/adsense/" => "AdSense",
		"http://www.smaato.com/" => "Smaato",
		"http://www.advertising.com/" => "Advertising.com",
		"http://www.adtini.com/" => "Adtini",
		"http://www.pointroll.com/" => "Pointroll",
		"http://www.mojiva.com/" => "Mojiva",
		"http://www.valueclick.com/solutions/" => "Valueclick media",
		"http://www.buzzcity.com/" => "Buzzcitiy",
		"http://adfonic.com/" => "Adfonic",
		"http://advertising.aol.com/ " => "Platform A",
		"http://www.whereapps.com/whereads.html" => "Where Ads",
		"http://www.mobixell.com/" => "Mobixell",
		"http://www.admarvel.com/" => "AdMarvel",
		"http://www.ubiyoo.com/home.html" => "Ubiyoo",
		"http://lat49.com/" => "Lat49",
		"http://www.todacell.com/" => "Todacell",
		"http://www.puddingmedia.com/" => "Pudding Media");
		asort($network_options);
		$this->smarty->assign('network_options',$network_options);
    $this->tab_current = 'resources';
    return $this->smarty->fetch('../tpl/www/home/dev.tpl');
  }
  
  public function requiresUser() {
    return false;
  }
}
