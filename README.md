# :palm_tree: VacManager
With this web app you can manage your vacation days and check how many days left.

* manage your vacations :mag:
* get a calendar overview :calendar:
* analyize your vacations with charts :bar_chart:
* always keep your contingent in mind :hourglass_flowing_sand:

Press F to pay respect to glorious developer.

## :checkered_flag: Getting started
Install this app like discribed in the manual below, creat an account and get started.

[Live Demo](http://iperka.com)

## :package: Prerequisites

* Apache2
* PHP 7.2
* MariaDB / MySQL
* Bootstrap
* JQuery

## :rocket: Installation
At first you have to clone the git reprository in your web directory:
```
git clone https://github.com/MichiBeutler/VacManager.git
```

After this you have to import the SQL script
```
# create a database
mysql -u root
CREATE DATABASE `vac-manager`;
quit

# import sql to created database
mysql -u root vac-manager < VacManager/sql/vacation_manager.sql
```

Now you have to create a user for the app to work.
Insert the credentials in the [dbconnect.php](https://github.com/MichiBeutler/VacManager/blob/master/includes/dbconnect.php)
```
nano VacManager/includes/dbconfig.php
```

## :books: Built With

* [Bootstrap](https://getbootstrap.com/) - The web framework used

## :octocat: Authors
* **Michael Beutler** - *initial work* - [MichiBeutler](https://github.com/MichiBeutler)

See also the list of [contributors](https://github.com/MichiBeutler/VacManager/graphs/contributors) who participated in this project.

## :lock: License
This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/MichiBeutler/VacManager/blob/master/LICENSE) file for details
      
## :ok_hand: Acknowledgments
* manage your vacations in your workplace :stuck_out_tongue_closed_eyes:
