#/bin/bash



version=$(date +%s)

appname=$1

namespace=onic

. /root/env/merapi


echo $namespace 

echo "Deploying App  With name " $appname;

git pull



DOCKER_BUILDKIT=1  docker build . --pull -t harbor.merapi.javan.id/$namespace/$appname:$version

docker push  harbor.merapi.javan.id/$namespace/$appname:$version

helm upgrade --install $appname helm/ --namespace $namespace --create-namespace --set image.tag=$version -f helm/values.yaml

