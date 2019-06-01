*************Simple and Secure PHP Login System ************

Features V1.0

1.Simple and Fast design implementing twitter Bootstrap
2.Form validation using jquery Validation plugin
3.Submit form data without page refresh
4.User registration using activation key sent via Email
5.Highly secure password storage using blowfish encryption
6.Admin Screen with list of registered 

Features V1.1  - 20-July-2013

1.Bug fixes
2.Added new feature to resend activation email.
3.Fixed ajax response issue and migrated to JSON.


Features V1.2  - 18-Aug-2013
1.Couple of bug fixes in password resend.
2.Styling issue in activation process is fixed.
3.New feature: Password reset functionality, Added new activation status '2' for reset in progress.
4.Combined error message in db.php for easy editing.
5.Logo text can be changed in db.php- single place for all pages.

Features v2.2 -03-Oct-2013
1.Included username in session variable

Features V3.0  - 6-oct-2013
1. Sign-in using Facebook Account.
2.Revamped Admin screen.
3.Search user and user count screens added.


Features V3.1 – 20-July-2014


1. Add new user from Admin screen 
2.Styles updated to Bootstrap 3.0
3. Ajax improvements.


Features V3.2 – 13- June 2015


1. Migrated to MySQLi extension 
2. Updated Bootstrap version to 3.3 
3. Updated Jquery to 1.11 
4. Fixed several critical bugs on send mail function. 
5. Styling issues on admin interface is now fixed.
6. Fixed minor issues in email content alignment.

Features V4.0 - March 13 2016

1. Updated the bcrypt logic to fix password issue
2. Blank Facebook username issue- fixed
3. New-Change password page for users logged in
4. Revamped administration panel using SB-Admin2
Note for exisitng users: Take backup of your exisiting user tables before upgrading to this version as it deals with password logic update

Features V4.2 - August 27 2017

1. Important security fixes
2. Updated facebook library and fixed redirect loops.

**************Steps to Deploy the Login system*************

Open index.html inside help folder
Modify the database configuration on db.php
Create database tables using the script available in Query.sql file

