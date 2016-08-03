# Architecure and configuration

Brief description of the current architecture and the configuration of the various services.

## Load Balancer

- Rely on HaProxy
- 1 frontend (HTTP TCP/80)
- 2 backends (web1/2 TCP/80)
- health check: 
  - every 2sec against http://web1/status and http://web2/status
  - `/status` is only available internally
- Remove backend from the pool after 2 failures
- Put backend in the pool after 5 success

## Web

- Rely on Nginx + PHP-FPM
- Nginx 
  - config available at `/etc/nginx/sites-enable/yedian.conf`
  - health check only allow requests without `x-forwarded-for` in the header
  - a few redirects to support ThinkPHP apps
  - a few security config - 403 appear as 404, eventually 500 should do the same
- PHP-FPM 
  - config in `/etc/php5/fpm`
  - session are handled in memcache (relying on `session.save_path` in php.ini)
- /etc/hosts are updated so you can address to the backend service via `db`, `memcache`, `redis`

## DB

- offers mysql / memcache and redis services

##

## Servers Access

Only the Staging environment has been created.

- Admin:
  - name: staging-admin-1 
  - access: `ssh -l user staging-yedian.chinacloudapp.cn -p 22`
- LB: 
  - name: staging-lb-1
  - size: A2 - 2Core, 3.5GB RAM
  - access: 
    - (public) `ssh -l user staging-yedian.chinacloudapp.cn -p 50422`
    - (internal) `ssh -l user staging-lb-1`
- Web:
  - name: staging-web-1 / 2 
  - size: A3 - 4Core, 7GB RAM
  - access: 
    - (public) `ssh -l user staging-yedian.chinacloudapp.cn -p 61063/57098`
    - (internal) `ssh -l user staging-web-1 / 2`
- DB:
  - name: staging-db-1
  - access: 
    - (public) `ssh -l user staging-yedian.chinacloudapp.cn -p 56694`
    - (internal) `ssh -l user staging-db-1`
