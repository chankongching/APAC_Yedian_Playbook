# Project

This `README` file is meant to provide relevant information to whomever is gonna run the ansible commands. 

## Pre-requisite

Need:

- Ansible 2.1.0

## Installation

- Fetch the roles

```
ansible-galaxy install -r requirements.yml -p roles
```

## Setup & deployment

- A to Z setup

```
# run setup-base first
ansible-playbook -i inventory.dev -e @vars.dev --vault-password-file ~/vault-password setup-base.yml
```

```
ansible-playbook -u wcladmin -i inventory.dev -e @vars.dev --vault-password-file ~/vault-password main.yml
ansible-playbook -i inventory.staging -e @vars.staging --ask-vault-pass main.yml
```

- Install only the postgresql components

```
ansible-playbook -i inventory.staging -e @vars.staging --ask-vault-pass setup-postgresql.yml
```

- Deploy only the app (considering the setup is done already)

```
ansible-playbook -i inventory.staging -e @vars.staging --ask-vault-pass deplo.yml
```
