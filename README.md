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

## Состав плагинов

- `Yoast SEO`- чтобы убрать /category/ из url и настроить базовое SEO
- `Permalink Manager Lite` - настройки permalinks
- `CPT-UI` - создание кастомных типов записей
- `Advanced Custom Fields PRO` - настройка кастомных полей
- `Classic Editor` - классический редактор в замену Gutenberg
- `Code Snippets` - сниппеты в functions.php
- `WP All Import Pro` - первоначальный импорт данных
- `WP All Import - ACF Add-On` - аддон для поддержки кастомных полей
- `Elementor Website Builder` - билдер страниц
- `Elementor Pro ^v3.0.8` - дополнительный функционал для билдера
- `Hello Elementor` - стартовая тема для разработки

## Данные для разработки

1. Импорт