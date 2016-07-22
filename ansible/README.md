# YeDian - AB-InBev

Collection of playbooks to spawn test / staging / production environments for YeDian platform.

## Technologies

- MySQL (Percona)
- Nginx
- PHP-FPM
- Memcached
- Redis
- HaProxy
- Varnish (TBD)

## Architectures

Refer to https://github.com/ab-inbev/APAC-Yedian/wiki/Server-details

# Run Ansible playbooks

## Install Ansible

```
pip install ansible
ansible-galaxy install -r requirements.yml -p roles
```

## Run deployment

```
ansible-playbook -i inventory.staging main.yml --ask-vault-pass
```
