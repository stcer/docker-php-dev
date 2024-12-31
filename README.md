# Usage
```
vim /etc/docker/daemon.json 
{
  "registry-mirrors": ["https://registry.docker-cn.com"],
  "insecure-registries": ["devel:5000"]
}

git clone git@git.jz.cn:jz/docker-php-dev.git YOUR_DIR
cd YOUR_DIR

# docker swarm deploy
docker stack deploy web

# docker-compose deploy
docker-compose up -d
```

# [optional] Build your php container
```
cd builder/php/7.4
docker pull php:7.4-fpm
docker build -t stcer/php:7.4-fpm .
```
