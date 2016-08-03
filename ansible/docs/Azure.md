# Azure

Simple guide to describe the Azure architecture

## Cloud Services

Only **1 Cloud service** created before the servers. This allow to have only 1 public IP / interface for the complete architeture.

Staging: 
  - name: staging-yedian
  - region: China East

## Networks

Only **1 Network** created before the servers. This allow all the servers to be on the same network.

Staging:
  - name: yedian
  - address space: 10.0.0.0/8

## SSH keys

Azure allows the upload of SSH keys as PEM certificates. This eventually allow the users to connect without password.

Enter whichevr information you deem necessary.

```
shell> openssl req -x509 -key ~/.ssh/id_rsa -nodes -days 365 -newkey rsa:2048 -out id_rsa.pem

    You are about to be asked to enter information that will be incorporated
    into your certificate request.
    What you are about to enter is what is called a Distinguished Name or a DN.
    There are quite a few fields but you can leave some blank
    For some fields there will be a default value,
    If you enter '.', the field will be left blank.
    -----
    Country Name (2 letter code) [AU]:
    State or Province Name (full name) [Some-State]:
    Locality Name (eg, city) []:
    Organization Name (eg, company) [Internet Widgits Pty Ltd]:
    Organizational Unit Name (eg, section) []:
    Common Name (e.g. server FQDN or YOUR name) []:
    Email Address []:
```

The resulting `id_rsa.pem` is the PEM certificate you can upload on the Azure UI.
FYI - it only includes your public SSH key - no worries - you are not exporting your private key along!

## Servers

Creation process:
- choose `from gallery`
- select `Ubuntu 14.04`
- press `next`
- enter name
- select size
- select default username (where the SSH key will be applied)
- upload PEM cert (see SSH key section above)
- press `next`
- **Important**: select the `Cloud service` you created previously
- press `next`

It is critical that you connect the server to the correct cloud service, or it won't be able to communicate with the other servers from your architecture.

