# CSCI4300 Project

## Usage Instructions

### Dependencies

This site requires MySQL and PHP in order to function.

It is also recommended to use Apache to host the site (or any other server that supports `.htaccess` files), as one is
used to enable Server Side Includes on our site without needing any special configurations. If you're using a server
that does not support `.htaccess` files, please enable Server Side Includes for this site.

### Installation

Copy the website files into the proper location to set up a webserver on your machine.

For example, for XAMPP users, the website files should go into `htdocs` in the XAMPP installation folder.

The website files can be stored in a subdirectory if you desire; the site will still function properly as long as the
file structure is not changed apart from placing everything into one big subdirectory or chain of subdirectories.

### Initialization

The file `databaseInit.sql` contains all the SQL queries necessary to initialize some initial values into the site's
database to provide the functionality shown in the class presentation. Please execute it or execute all the queries
yourself before trying any of the website's features.

### Usage

After everything has been set up, the site can be access from the root folder of where you placed the website files
(e.g. `localhost` on XAMPP if you placed the files directly inside `htdocs`; `localhost/subdirectory` if you placed it
in an immediate subdirectoy inside `htdocs`, etc.).

As listed in the dependencies, MySQL, PHP, and Server Side Includes must both be active for proper functionality.

## Contributions

### Hayden Crawford

* Created search page

* Created book information / review page

* Designed database tables

### Harry Goeller

* Designed art assets like the logo

* Found images and all information for each book in the database

* Populated the database with users and reviews

### James Hyun 

* Made the user list and profile pages where you can change user info

* Created the login system

* Worked on the cart and checkout with Zach Cushenberry

### Zach Cushenberry

* Designed cart system that could track carts across various users on one machine

* Implemented system + checkout page with help of James Hyun

### Isaiah McCoy

* Designed overall look and aesthetic of website

* Created the nav bar

---

*This section contains some information that was originally for project members while development was still underway.*
*Nothing here pertains to the contents of the README file required by the project instructions.*

## Kanban Board

[Trello board](https://trello.com/b/f7xykh5g/cs4300-project) for tracking feature development.

## Branches

* `master` is the main branch. No dysfunctional or malformed HTML/CSS markup or JS & PHP code allowed.

* `development` is the main branch for development. Merge changes into this branch and not `master`, please.

* Please don't use fast-forward merges on `master` and `development` except for very minor changes so it's easier to keep track of last-known working commits.

* Do whatever you'd like for other branches made for specific features or hotfixes, or for personal working branches offline.

Consider:

https://guides.github.com/introduction/flow/

https://nvie.com/posts/a-successful-git-branching-model/
