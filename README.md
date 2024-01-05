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

# docker swarm deploy
docker stack deploy -c web-php.yaml web

# docker-compose deploy
docker-compose up -d
```

## Visit
- web: http://your_ip:8090
- db: http://your_ip:8091


# [optional] Build your php container
```
cd builder/php/7.4
docker build -t stcer/php:7.4-fpm .
```
