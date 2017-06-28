# SKOJ

SKOJ is an online judge featuring a new programming language based on prefix syntax.

~~Currently hosted on [skoj.ivan100sic.in.rs](http://skoj.ivan100sic.in.rs).~~
Offline due to a DDoS attack on my page.

## Origin

SKOJ is a project written for my 3rd year CS class - Web Programming. While everyone
else has decided to build a basic website and just pass the exam, I've decided to
go further by designing and implementing an entire programming language, complete
with a decent front-end.

I'm not bashing on my peers, I'm almost as lazy as they are - I just needed a challenge!

SKOJ is a joint effort by me and Aleksandar Jovanović, my faculty colleague.

## Technical details

SKOJ is written in *PHP 7.0*  and uses *MySQL 5.7* as its database engine. It also uses
some *JQuery*. I've tried to make it *HTML5* and *CSS3* compliant but I'm not sure if did
it. It works in Chrome. Good enough. Passwords are salted with random strings and
the username and are then hashed using SHA-1.

The site currently requires an email to register, but it will not send any e-mails.
**This may change in the future.** If you want your password reset, contact the administrator (me).

## Local testing

If you want to run SKOJ on your own machine, you'll need *PHP 7.0* and *MySQL 5.7* or later,
unless PHP or MySQL developers decide to break backwards compatibility in the future. You'll
also need internet access because JQuery is not included locally. Obviously you'll also need
a web server, for example *Apache2*. I'm not sure which PHP modules are used, but these
should be enough:

+ dom
+ fileinfo
+ http
+ ioncube_loader
+ json
+ mbstring
+ mysqlnd
+ nd_mysqli
+ nd_pdo_mysql
+ pdo
+ phar
+ posix
+ propro
+ raphf
+ sockets
+ sysvmsg
+ sysvsem
+ sysvshm
+ xdebug
+ xmlreader
+ xmlwriter
+ zip

Download this entire repository. When you get all these services up and running,
create a user for your database and update the connection parameters in sql.php
accordingly. Next, run setup.sql on your MySQL server. You should now be able to
open the site in your browser. Register on the site, and after you've done it,
give yourself the ADMIN_PANEL permission, by inserting ([your user id], 6) into
the table user_permissions. Now you should be able to access the admin panel.

If you're lazy or you just want to try out the site, ~~it is currently hosted on 
[skoj.ivan100sic.in.rs](http://skoj.ivan100sic.in.rs).~~ See above

## Authors and contributors

Authors of SKOJ are, at the time of writing, 3rd year BSc students at the
department of Computer Science, Faculty of Science, University of Niš:

+ Ivan Stošić (ivan100sic), Lead developer
  * All back-end code
  * Some front-end code
  * Some parts of SKOJ's stylesheet
+ Aleksandar Jovanović, Assistant developer
  * Some front-end code
  * Most of SKOJ's content, such as tasks
  * Most of SKOJ's stylesheet
  * Useful suggestions and improvements
  
I'd also like to thank other people for testing the website. 
