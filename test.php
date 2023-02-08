*/15 * * * * php /var/www/ispexperts/controller/cron/cron_battery_sensor.php

curl -d "fullName=OmarAlberto&template=d-d451365d82394c369b47f375cd19ed6b&email=omar.a.hernandez.d@gmail.com" -X POST http://146.71.79.111:3001/mail
