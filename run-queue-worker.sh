#!/bin/bash
cd /home/sifuqurangeek/public_html
php artisan queue:work --sleep=3 --tries=3 --timeout=60 >> /home/sifuqurangeek/public_html/public/logs/queue_worker.log 2>&1

