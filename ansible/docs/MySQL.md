# MySQL

A few notes on MySQL export / import performances and technics.

## Export

Several ways to export the various database:

- Full databases dump; including user credentials

```
mysqldump -u debian-sys-maint -p --single-transaction --default-character-set=utf8 \
    --all-databases | gzip -c - > db_dump.sql.gz
```

- Only dump the databases with business related data

```
mysqldump -u debian-sys-maint -p --single-transaction --default-character-set=utf8 \
    --databases                     \
        abicloud                    \
        fusionway_letsktv_wechat    \
        fusionway_promo_girls       \
        fusionway_wechat            \
        letsktv_biz_promogirls      \
        letsktv_biz_wechat          \
        letsktv_games               \
        letsktv_tmp                 \
        letsktv_wechat | gzip -c - > db_dump.sql.gz
```

- Only dump the db schema; useful if you want to change the create statement

```
mysqldump --no-data -u debian-sys-maint -p --default-character-set=utf8 \
    --databases                     \
        abicloud                    \
        fusionway_letsktv_wechat    \
        fusionway_promo_girls       \
        fusionway_wechat            \
        letsktv_biz_promogirls      \
        letsktv_biz_wechat          \
        letsktv_games               \
        letsktv_tmp                 \
        letsktv_wechat > schema.sql
```


## Import

The databases can be quite big; and with the current settings (very conservative toward data reliability), we may want to tune the database prior the import.

```
shell> mysql -u root -p
password: 

# Get the former values
mysql> show global variables;

# Tune settings to maximaize import performance
mysql> set global sync_binlog=100000;
mysql> set global innodb_flush_neighbors=0;
mysql> set global innodb_adaptive_hash_index=OFF;
mysql> exit;
shell>

# In addition to the changes above, you optionaly could change other variables
# Note: those variables are read-only and will require the config file to be 
#       changed and the server restarted. (you will have to re-apply the changes
#       from above)
# innodb_doublewrite=OFF

# Ensure we are not touching the disk for every single access (improve IO)
shell> mount -o remount,noatime /dev/sda1
```

Then you can run the DB import

```
zcat db_dump.sql.gz |  mysql --default-character-set=utf8
```

Finally revert the former settings to re-improve reliability

```
shell> mysql -u root -p
password: 

# Revert to the former values
mysql> set global sync_binlog=100;
mysql> set global innodb_flush_neighbors=2;
mysql> set global innodb_adaptive_hash_index=ON;
mysql> exit;
shell>

# If you changed the config file, you may want to revert those changes and
# restart the mysql server.

# Ensure we are not touching the disk for every single access (improve IO)
shell> mount -o remount,atime /dev/sda1
```

# Links

A few links worth reading:
- http://techblog.netflix.com/2016/08/netflix-billing-migration-to-aws-part.html
- http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html
