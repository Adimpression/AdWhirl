#!/usr/bin/bash

set -o errexit

if [ $# -lt 1 ]; then
  echo "Usage: $0 <version>"
  exit 1
fi

ver=$1

dir=AdWhirlSDK_iPhone_$ver
rm -rf $dir
mkdir -p $dir
cp -r iphone/AdWhirl $dir/
cp -r iphone/AdWhirlSDK2_Sample $dir/
cp -r iphone/TouchJSON $dir/
cp iphone/Changelog.txt $dir/
cp iphone/README $dir/
zip -r $dir.zip $dir
