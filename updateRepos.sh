#! bash
# Needs to take in the sha1 to update head to

echo starting update
#cd ../web-animations-js
#git pull
cd ..
git clone https://github.com/StephMc/web-animations-test-framework.git
cd web-animations-test-framework
git pull

if [ "$1" != "" ];
then
  echo "reseting git to $1"
  git checkout $1
fi

echo finished