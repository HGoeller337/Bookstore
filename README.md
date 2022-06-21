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

This was a collaborative group project between me and other students.
