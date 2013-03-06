#! bash
#bash updateRepos.sh $1
echo "new test bash out" >> logfile.txt
echo $1 >> logfile.txt
echo $2 >> logfile.txt
#echo starting to run tests
rm -R tmpChromeCache
mkdir tmpChromeCache
xvfb-run -a google-chrome --disk-cache-dir=tmpChromeCache  http://14.200.8.150/web-animations-test-framework/tests/testRunner.html?$2 &

