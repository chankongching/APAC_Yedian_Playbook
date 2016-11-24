#!/bin/bash
#########################
# Spawn an ENV
#########################

# refer to /etc/network/interfaces and `brctl show`
BR_PUB=xenbr12

#
# Provision router
#
provision create --cpu 2 --ram 128 --interface eth0:dhcp --interface eth1:static:172.17.0.1 --bridge eth0:xenbr0 --bridge eth1:$BR_PUB --hostname dev-router --firewall --lvm --image yedian/router dev-router

#
# Provision admin
#
provision create --cpu 4 --ram 1024 --disk 20 --interface eth0:static:172.17.0.2::172.17.0.1 --bridge eth0:$BR_PUB --hostname dev-admin --lvm --image yedian/admin dev-admin

#
# Provision docker
#
provision create --cpu 8 --ram 2048 --disk 20 --interface eth0:static:172.17.0.3::172.17.0.1 --bridge eth0:$BR_PUB --hostname dev-docker --lvm --image yedian/centos7 dev-docker

#
# Provision psql
#
provision create --cpu 4 --ram 512 --disk 20 --interface eth0:static:172.17.0.4::172.17.0.1 --bridge eth0:$BR_PUB --hostname dev-psql --lvm --image yedian/centos7 dev-psql

#
# Provision couchbase
#
provision create --cpu 4 --ram 1536 --disk 20 --interface eth0:static:172.17.0.5::172.17.0.1 --bridge eth0:$BR_PUB --hostname dev-couchbase --lvm --image yedian/centos7 dev-couchbase


