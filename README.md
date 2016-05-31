# Team manager
sports management app made with php code igniter
This application was developed to reduce the setup time of a PHP sports / team management application that uses a Public site and secure members area. It handles Manager & player registration, game attendances, game finances 

(1)	Codeigniter
Used CI version 2.1.0 with Datamapper to manage MYSQL queries. See www.codeigniter.com for more info on the CI framework. Datamapper info can be found here – www.datamapper.org
The start url of the app is “YourDomain”/index.php/site

(2)	Templates
The app has been setup with two templates (i) default_manager (Secure) and default_public (Public) both which can be found in the public/templates folder. Additional templates can easily be added by adding a new template file in public/templates/.

(3)	Authentication
The app registers new managers and players via the accounts/register controller and authentication a user is done via the sessions library and within accounts/login controller.

(4)	Database
The app uses a MYSQL database Schema included in the folder called app_db_schema.
