#!/bin/sh
# System + MySQL backup script
# Full backup day - Sun (rest of the day do incremental backup)
# ---------------------------------------------------------------------
### System Setup ###
DIRS="/var/www/vhosts/samlog.com/httpdocs"
BACKUP=/var/www/vhosts/samlog.com/backup.samlog.com
NOW=$(date +"%d-%m-%Y")
### MySQL Setup ###
MUSER="mysamlog"
MPASS="Kaip74#7"
MHOST="localhost"
MYSQLDUMP="$(which mysqldump)"
GZIP="$(which gzip)"
### Start Backup for file system ###
FILE="fs-full-$NOW.tar.gz"
tar -zcf $BACKUP/$FILE $DIRS
### Start MySQL Backup ###
FILE=$BACKUP/mysql-samlog.$NOW-$(date +"%T").gz
$MYSQLDUMP -u $MUSER -h $MHOST -p$MPASS samlog | $GZIP -9 > $FILE
