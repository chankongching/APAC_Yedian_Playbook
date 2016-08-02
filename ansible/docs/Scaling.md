# Scaling YeDian platform

The architecture of the YeDian platform allows scaling in and out.

Below are the steps to run to either increase or decrease the capacity of the platform.

**Note**: the details in this document are ISP agnostic and would be the same no matter if using Azure, QingCloud, or any other cloud provider. 

What this means is also that we are not taking benefit of whatever feature is available on those cloud provider. Alternerative to the approach described below are possible and may be mentioned, but won't be detailed.

## Web Servers

### Increasing the number of web server

1. Spawn a new Box on the cloud provier of your choice, make sure you give it the right DNS name
2. Ensure you have access to the server
3. Update `inventory.staging` or `inventory.prod` and add the new server to the `web` group
4. Run the base provisioning `ansible-playbook -i inventory.prod setup-base.yml -u USERNAME -s -e @vars.prod --limit THE_NEW_SERVER`
5. Run the web provisioning `ansible-playbook -i inventory.prod setup-web.yml -u USERNAME -s -e @vars.prod`
6. Deploy the code (manual step for the moment)
7. Update the Load balancer to include the new host `ansible-playbook -i inventory.prod setup-lb.yml -u USERNAME -s -e @vars.prod`

There might be faster approach involving either:
- cloning of and existing server, 
- rsync of the code base,
- use of pre-configured servers

### Decreasing the number of web server

1. Update `inventory` file and comment out / remove the mention to the old server
2. Update the Load balancer to remove the host `ansible-playbook -i inventory.prod setup-lb.yml -u USERNAME -s -e @vars.prod`

From that time, the server is removed from the pool, and you can either remove it, pause it or whatever you deem necessary.

## Databases

There is no easy way at the current stage to quickly scale up the DB servers.

## Load balancers

There should be no need to scale up load balancer, you might want to add additional servers only for HA purpose though.