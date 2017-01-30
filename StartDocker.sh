#!/bin/bash
dev_docker_file="docker-compose.yml"

export localhost_ip="$(ip addr list br-090ca7df458f |grep "inet " |cut -d' ' -f6|cut -d/ -f1)"

docker-compose -f $dev_docker_file up --build -d
