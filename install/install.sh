#!/bin/bash
echo " _   _               ___  ___"
echo "| | | |              |  \/  |"                                    
echo "| | | |  __ _   ___  | .  . |  __ _  _ __    __ _   __ _   ___  _ __" 
echo "| | | | / _  | / __| | |\/| | / _  || '_ \  / _  | / _  | / _ \| '__|"
echo "\ \_/ /| (_| || (__  | |  | || (_| || | | || (_| || (_| ||  __/| |" 
echo " \___/  \__,_| \___| \_|  |_/ \__,_||_| |_| \__,_| \__, | \___||_|"  
echo "                                                   __/ |"            
echo "                                                  |___/"      

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

read -p 'Mysql Username: ' username
mysql -u $username -p < $DIR/sql/vacation_manager2.sql

echo "done"
echo "no just adjust your database credentials in ./includes/class/Database.php"