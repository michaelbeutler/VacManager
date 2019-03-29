# :palm_tree: VacManager
With this web app you can manage your vacation days and check how many days left.

## Prerequisites

* Apache2
* PHP 7.2
* MariaDB / MySQL
* Bootstrap
* JQuery

## Installation
At first you have to clone the git reprository in your web directory:
```
git clone https://github.com/MichiBeutler/VacManager.git
```

After this you have to import the SQL script
```
# create a database
mysql -u root
CREATE DATABASE `vac`;
quit

# import sql to created database
mysql -u root vac < VacManager/sql/vac.sql
```

Now you have to create a user for the app to work.
Insert the credentials in the [dbconnect.php](VacManager/dbconnect.php)
```
nano VacManager/bin/dbconfig.php
```

## Built With

* [Bootstrap](https://getbootstrap.com/) - The web framework used

## Authors
* **Michael Beutler** - *initial work* - [MichiBeutler](https://github.com/MichiBeutler)

See also the list of [contributors](VacManager/contributors) who participated in this project.

## License
This project is licensed under the MIT License - see the [LICENSE.md](VacManager/LICENSE) file for details
      
