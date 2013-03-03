#! bash
# Needs to take in the sha1 to update head to
echo starting update
cd ../web-animations-js
git pull
cd ../web-animations-test-framework
git pull
echo finished