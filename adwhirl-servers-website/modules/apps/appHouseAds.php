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

require_once('fn/global.php');
require_once('fn/app.php');
require_once('fn/house.php');

require_once('inc/global.php');
require_once('inc/auth.php');

outputHeader('App House Ads');

require_once('inc/tabs.php');

$appInfo = getActiveApp();
$aid = $appInfo['aid'];

if(empty($aid)) {
  header("Location: addApp");
  die;
 }

require_once('inc/appSwitcher.php');
require_once('inc/appNav.php');

$appHouseAds = getAppHouseAds($aid); 

?>

<form action="appHouseAdsx" method="post">
<input type="hidden" name="aid" value="<?= $aid ?>" />
<table>
 <thead>
  <tr>
   <th>
    Ad Name
   </th>
   <th>
    Type
   </th>
   <th>
    Weight
   </th>
  </tr>
 </thead>
 <tbody>
<?php
foreach($appHouseAds as $houseAd) {
  $acid = $houseAd['acid'];
  $cid = $houseAd['cid'];
  $name = $houseAd['name'];
  $type = $houseAd['type'];
  $weight = $houseAd['weight'];

  $type_s = ($type == CUSTOM_TYPE_ICON) ? 'Icon' : 'Banner';

print<<<END
  <tr>
   <td>
    $name<input type="hidden" name="acid[]" value="$acid" />
   </td>
   <td>
    $type_s
   </td>
   <td>
    <input name="weight[]" type="text" maxlength="3" value="$weight" />
   </td>
  </tr>
END;
}
?>
 </tbody>
</table>

<input name="submit" type="submit" value="Save" />
</form>

<?php
outputFooter();
