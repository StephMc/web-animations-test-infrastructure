#! bash
bash updateRepos.sh $1
#echo starting to run tests
rm -R tmpChromeCache
mkdir tmpChromeCache
xvfb-run -a google-chrome --disk-cache-dir=tmpChromeCache  http://14.200.8.150/web-animations-test-framework/tests/testRunner.html?$2 &
# How do I kill this after the tests are run....
#echo tests finished
