# wahelp

## Создание базы

Используйте файл database.sql

## Команды

### Заполнение базы пользователями

curl -X POST -F "csv_file=@Данные для тестового.csv" https://test-wahelp/upload.php

### Добавление рассылки

curl -X POST -H "Content-Type: application/json" -d '{"title":"Рассылка 1", "message":"Привет! Это сообщение!"}' https://test-wahelp/create_campaign.php

### Отправка рассылки

curl -X GET "https://test-wahelp/send_campaign.php?campaign_id=1"


Если прервалась рассылка (удалить несколько строк из таблицы campaign_user_status) и запустить Отправку рассылки заново.
