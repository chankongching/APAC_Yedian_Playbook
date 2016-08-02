# Code Deployment

The purpose of this document is to outline the steps needed to deploy and test the code following a platform migration.

## Source code

It is assumed that the authoritative code source is located on the current production server. 

Eventually this should be moved away and rely on the GitHub repository. 

```
# On both web servers
rsync -avz --partial --progress -e ssh letsktv.chinacloudapp.cn:/opt/myapps_git /opt/myapps_git

# optionally can remove the logs that are quite big
rsync -avz --partial --progress --exclude '*.log' -e ssh letsktv.chinacloudapp.cn:/opt/myapps_git /opt/myapps_git
```

## Config files

We ned to update the config file so the PHP app can connect to :
- redis
- memcache
- mysql

Found config files are:

```
./ThinkPHP/Library/Vendor/WxPay/Config.php
./Verify/Common/Conf/config.php
./Verify/Admin/Conf/config.php
./Verify/Home/Conf/config.php
./wechat_ktv/Common/Conf/config.php
./wechat_ktv/Pay/Conf/config.php
./wechat_ktv/Home/Conf/config.php
./APP/Common/Conf/config.php
./APP/Spr/Conf/config.php
./APP/Admin/Conf/config.php
./APP/SAdmin/Conf/config.php
./APP/Home/Conf/config.php
./APP/Business/Conf/config.php
./abicloud/letsktv_biz/promo_girls/admin/_/_inc.php 
./callcenter/_/_inc.php
./games/GUESS_SONG/_/_inc.php
./visual-data/_/_inc.php
./letsktv_biz/_wechat/_inc.php
./letsktv_biz/promo_girls/admin/_/_inc.php
./letsktv_biz/promo_girls/_/_inc.php
./letsktv_biz/promo_girls_old/_/_inc.php
./fusionway/_wechat/_inc.php
./fusionway/promo_girls/_/_inc.php
./wechat/_inc.php
```

Those config files need to be updated to use the correct backend DB.

# Deploy workflow

A few words on the eventual deployment workflow. 

**Note**: this workflow is not in place and would need to be implemented along with the dev team.

- all code is commited in dedicated branches 
- dedicated branches code is deployed on dev platforms and QA'd
- ultimately such deployment would be automated
- upon QA validation, PR is performed and merged back to master branch
- once all feature branches are merged and ready to be released, master branch is tagged
- latest tag gets pushed (automatically) on staging for last validation / QA
- validated tag gets pushed (manually) on prod on release.

In practice, there is a few more steps - due to the architecture of the platform:

- the admin box should be used to trigger the build process on the whole platform
- depending whether there is build steps involved (composer / brew / grunt / Makefile), the build process should be performed on the admin box or a dedicated build box
- the artifact of the build process is what would be pushed to staging
- once the final QA is performed, the staging would be synced to prod.
