#! bash
bash updateRepos.sh $1
#echo starting to run tests
touch imhere.txt
xvfb-run -a google-chrome http://14.200.8.150/web-animations-test-framework/tests/testRunner.html?$1 &
# How do I kill this after the tests are run....
#echo tests finished
