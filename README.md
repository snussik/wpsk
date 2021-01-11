# wp-skeleton


## Авто запуск и сворачивание образа

При первом запуске:

`chmod +x ./run.sh & chmod +x ./stop.sh`
### Запуск
`sh ./run.sh`

### Остановка 
`sh ./stop.sh`

## Пароли
Доступ в админку WP: 
`http://localhost:8080`
`admin:admin`

Доступ в adminer: 
`http://localhost:9090`
`db`: `exampledb`
`user` : `exampleuser`
`password`: `examplepass`


## Разворачивание и сворачивание образа вручную

1. `docker-compose -f ./docker/stack.yml up --detach`

2. `docker-compose -f ./docker/stack.yml down -v`

При ручном запуске нужно дампить БД:


### Дамп БД
`docker exec DBCONTAINER /usr/bin/mysqldump -u root --password=password exampledb > ./docker/docker-entrypoint-initdb.d/init.sql`

## Восстановление дампа
`cat ./docker/docker-entrypoint-initdb.d/init.sql | docker exec -i DBCONTAINER /usr/bin/mysql -u root --password=password exampledb`