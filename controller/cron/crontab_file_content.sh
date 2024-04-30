*/15 * * * * php /var/www/ispexperts/controller/cron/cron_battery_sensor.php #Cada 15 minutos
0 1 * * * php /var/www/ispexperts/controller/cron/cron_generate_task_updater.php #Cada dia a la 1 am  
0 8 * * * php /var/www/ispexperts/controller/cron/controller/cron/cron_program_sms_days_before.php #Cada dia a las 8 am
*/30 * * * * php /var/www/ispexperts/controller/cron/cron_reconect.php #Cada 30 minutos
0 11 * * * php /var/www/ispexperts/controller/cron/cron_shut_daily_by_date.php >> /tmp/phplog.txt #Cada dia a las 11 am
*/30 * * * * php /var/www/ispexperts/controller/cron/cron_shut_off.php #Cada 30 minutos
*/45 * * * * php /var/www/ispexperts/controller/cron/curlTelegram.php #Cada 45 minutos

30 9 * * * php /var/www/ispexperts/controller/cron/messages.php #Cada dia a las 9:30 am Mensajes solo a cte 1 y 15 desde JSON 
0 6 * * * php /var/www/ispexperts/controller/cron/mikrotik_signal_updater.php #Cada dia a las 6:00 am
0 */6 * * * php /var/www/ispexperts/controller/cron/ping_repeater_updater.php #Cada 6 horas
15 8 * * * php /var/www/ispexperts/controller/cron/ping_updater.php #Cada dia a las 8:15 am
30 11 * * * php /var/www/ispexperts/controller/cron/router_client_type.php #Cada dia a las 11:30 am
0 12 * * * php /var/www/ispexperts/controller/cron/suspender_status_updater.php >> /var/www/ispexperts/controller/cron/clogs.txt #Cada dia a las 12 pm
0 7 * * * php /var/www/ispexperts/controller/cron/ubiquiti_signal_updater.php #Cada dia a las 7 am
5 3 * * * mysqldump redesagi_facturacion > /tmp/redesagi_facturacion_bk.sql  && cd /tmp/ && tar -cvzf db_redesagi.tar.gz redesagi_facturacion_bk.sql && cd ~ #Cada dia a las 3:05 am
50 23 * * * curl 127.0.0.1:3001/bk >> /var/www/ispexperts/controller/cron/clogs.txt #Cada dia a las 11:50 pm