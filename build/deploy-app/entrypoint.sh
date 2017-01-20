#!/bin/bash

#set permissions to web-service/public directories
cd /var/www/html/frontend && chown -R 1000:1000 public storage bootstrap/cache && chmod -R 0777 public storage bootstrap/cache
