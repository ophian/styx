#!/bin/bash
# addlang.sh: Move input to backup after run.
# And check for input file before starting.

if [ "x$1" = "x" ] 
 then
   echo "USAGE: addlang.sh INPUT-FILE"
   echo "----------------------------"
   echo "This script will append the contents of INPUT-FILE to every "
   echo "available language file."
   exit 1
 else
   #find . -maxdepth 1 -name \*.php ! -name recode.php ! -name plugin_lang.php -exec ./append.sh $1 {} \;
   if [ ! -f $1 ]; then
    echo "$1 does not exist."
    exit 10;
   fi
   find . -maxdepth 1 -name \*.inc.php -exec ./append.sh $1 {} \;
   TIMESTAMP=$(date +"%Y%m%d%H%M")
   mv $1 $1.$TIMESTAMP
   cd UTF-8
   ./recode.sh
   echo "-------------------------------------------------"
   echo "Please check the result and remove $1.$TIMESTAMP."
fi
