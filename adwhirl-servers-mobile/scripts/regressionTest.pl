#!/usr/bin/perl -w

print "Initializing tests... ";

my @tests = (
  { 
    'appid' => '8be6df14314d4347af7077bb3e0fba61',
    'appver' => '103',
    'result' => '[{"admob_ration":1,"google_adsense_ration":2,"greystripe_ration":3,"jumptap_ration":6,"adrollo_ration":7,"millennial_ration":8,"mobclix_ration":9,"quattro_ration":10,"videoegg_ration":11},{"admob_key":"admob","google_adsense_key":"adsense","greystripe_key":"greystripe","jumptap_key":"jumptap_pub","adrollo_key":"mdotm","millennial_key":"millennial","mobclix_key":"mobclix","quattro_key":{"siteID":"quattro_site","publisherID":"quattro_pub"},"videoegg_key":{"publisher":"videoegg_partner","area":"videoegg_site"}},{"admob_priority":1,"google_adsense_priority":2,"greystripe_priority":3,"jumptap_priority":6,"adrollo_priority":7,"millennial_priority":8,"mobclix_priority":9,"quattro_priority":10,"videoegg_priority":11},{"background_color_rgb":{"red":17,"green":17,"blue":17,"alpha":1},"text_color_rgb":{"red":34,"green":34,"blue":34,"alpha":1},"refresh_interval":45,"location_on":0,"banner_animation_type":7,"fullscreen_wait_interval":60,"fullscreen_max_ads":2,"metrics_url":"","metrics_flag":0}]'
  },
  {
    'appid' => '8be6df14314d4347af7077bb3e0fba61',
    'appver' => '127',
    'result' => '[{"admob_ration":1,"google_adsense_ration":2,"greystripe_ration":3,"jumptap_ration":6,"adrollo_ration":7,"millennial_ration":8,"mobclix_ration":9,"quattro_ration":10,"videoegg_ration":11},{"admob_key":"admob","google_adsense_key":"adsense","greystripe_key":"greystripe","jumptap_key":"jumptap_pub","adrollo_key":"mdotm","millennial_key":"millennial","mobclix_key":{"appID":"mobclix"},"quattro_key":{"siteID":"quattro_site","publisherID":"quattro_pub"},"videoegg_key":{"publisher":"videoegg_partner","area":"videoegg_site"}},{"admob_priority":1,"google_adsense_priority":2,"greystripe_priority":3,"jumptap_priority":6,"adwhirl_12_priority":7,"millennial_priority":8,"mobclix_priority":9,"quattro_priority":10,"videoegg_priority":11},{"background_color_rgb":{"red":17,"green":17,"blue":17,"alpha":1},"text_color_rgb":{"red":34,"green":34,"blue":34,"alpha":1},"refresh_interval":45,"location_on":0,"banner_animation_type":7,"fullscreen_wait_interval":60,"fullscreen_max_ads":2,"metrics_url":"","metrics_flag":0}]'
  },
  {
    'appid' => '8be6df14314d4347af7077bb3e0fba61',
    'appver' => '200',
    'result' => '{"extra":{"location_on":0,"background_color_rgb":{"red":17,"green":17,"blue":17,"alpha":1},"text_color_rgb":{"red":34,"green":34,"blue":34,"alpha":1},"cycle_time":45,"transition":7},"rations":[{"nid":"3d206518e0314d4785ec0a4d47c0c4bf","type":1,"nname":"admob","weight":1,"priority":1,"key":"admob"},{"nid":"5d9658bc50584e338e494b9496aa3de2","type":14,"nname":"google_adsense","weight":2,"priority":2,"key":"adsense"},{"nid":"a8baeefa148e431a99fec884776efc07","type":7,"nname":"greystripe","weight":3,"priority":3,"key":"greystripe"},{"nid":"b8a114c55951497a9a15da55839e705c","type":19,"nname":"iad","weight":4,"priority":4,"key":"iad"},{"nid":"b5574bfb3172415ca1286258b0bca536","type":18,"nname":"inmobi","weight":5,"priority":5,"key":"inmobi"},{"nid":"2401def2b2b945478eadcb11dec58488","type":2,"nname":"jumptap","weight":6,"priority":6,"key":"jumptap_pub"},{"nid":"5f9cb68b2c154882a80f3f8289d70346","type":12,"nname":"mdotm","weight":7,"priority":7,"key":"mdotm"},{"nid":"20633145a820457f834e5042e2d5b31e","type":6,"nname":"millennial","weight":8,"priority":8,"key":"millennial"},{"nid":"4c107889e89a42f499485d7fa16e3c88","type":11,"nname":"mobclix","weight":9,"priority":9,"key":{"appID":"mobclix"}},{"nid":"602b854c2f224695912217a20b7bd824","type":8,"nname":"quattro","weight":10,"priority":10,"key":{"siteID":"quattro_site","publisherID":"quattro_pub"}},{"nid":"da8e068062074f658c07148b71c869d9","type":3,"nname":"videoegg","weight":11,"priority":11,"key":{"publisher":"videoegg_partner","area":"videoegg_site"}},{"nid":"95a9bf1516de4e89a3e5bf135905c17f","type":20,"nname":"zestadz","weight":34,"priority":12,"key":"zestadz"}]}'
  },
  {
    'appid' => '8be6df14314d4347af7077bb3e0fba61',
    'appver' => '250',
    'result' => '{"extra":{"location_on":0,"background_color_rgb":{"red":17,"green":17,"blue":17,"alpha":1},"text_color_rgb":{"red":34,"green":34,"blue":34,"alpha":1},"cycle_time":45,"transition":7},"rations":[{"nid":"3d206518e0314d4785ec0a4d47c0c4bf","type":1,"nname":"admob","weight":1,"priority":1,"key":"admob"},{"nid":"5d9658bc50584e338e494b9496aa3de2","type":14,"nname":"google_adsense","weight":2,"priority":2,"key":"adsense"},{"nid":"a8baeefa148e431a99fec884776efc07","type":7,"nname":"greystripe","weight":3,"priority":3,"key":"greystripe"},{"nid":"b8a114c55951497a9a15da55839e705c","type":19,"nname":"iad","weight":4,"priority":4,"key":"iad"},{"nid":"b5574bfb3172415ca1286258b0bca536","type":18,"nname":"inmobi","weight":5,"priority":5,"key":"inmobi"},{"nid":"2401def2b2b945478eadcb11dec58488","type":2,"nname":"jumptap","weight":6,"priority":6,"key":{"publisherID":"jumptap_pub","siteID":"jumptap_site","spotID":"jumptap_spot"}},{"nid":"5f9cb68b2c154882a80f3f8289d70346","type":12,"nname":"mdotm","weight":7,"priority":7,"key":"mdotm"},{"nid":"20633145a820457f834e5042e2d5b31e","type":6,"nname":"millennial","weight":8,"priority":8,"key":"millennial"},{"nid":"4c107889e89a42f499485d7fa16e3c88","type":11,"nname":"mobclix","weight":9,"priority":9,"key":{"appID":"mobclix"}},{"nid":"602b854c2f224695912217a20b7bd824","type":8,"nname":"quattro","weight":10,"priority":10,"key":{"siteID":"quattro_site","publisherID":"quattro_pub"}},{"nid":"da8e068062074f658c07148b71c869d9","type":3,"nname":"videoegg","weight":11,"priority":11,"key":{"publisher":"videoegg_partner","area":"videoegg_site"}},{"nid":"95a9bf1516de4e89a3e5bf135905c17f","type":20,"nname":"zestadz","weight":34,"priority":12,"key":"zestadz"}]}'
  }
  );

print "OK\n";

my $tests_passed = 0;
my $tests_total = 0;

for $test (@tests) {
  $appid = $test->{'appid'};
  $appver = $test->{'appver'};
  $result = $test->{'result'};

  print "Running test <appid:$appid, appver:$appver>... ";

  $url = 'http://localhost/getInfo.php?appid=$appid&appver=$appver';

  my $response = `curl $url`;

  if($response eq $result) {
    print "OK\n";
    $tests_passed++;
  }
  else {
    print "FAILED\n";
  }

  $tests_total++;
}

print "Passed $tests_passed of $tests_total tests\n";
