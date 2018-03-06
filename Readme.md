# Usage
```
vim /etc/docker/daemon.json 
{
  "registry-mirrors": ["https://registry.docker-cn.com"],
  "insecure-registries": ["devel:5000"]
}

git clone git@git.jz.cn:jz/docker-php-dev.git YOUR_DIR
cd YOUR_DIR

# vim web-php.yaml 
# change your local path

docker stack deploy -c web-php.yaml web
```

## Visit
- db: http://your_ip:8082
- visualizer: http://your_ip:8081
- web: http://your_ip:8090

# [optional] Build your php container
```
cd php
docker build -t stcer/php-test .
```
