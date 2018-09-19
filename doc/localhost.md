I would recommend updating the version of your php to at least version 7 before running on a localhost.  
You can check your version through the command line with "php -v".  

If you need to update your php:  
I have only updated it through the command line, so if you google "update php command line" you will find a lot of resources.
                                
Running on localhost:                                
1. Open terminal / command prompt
2. Command line: git clone https://github.com/PoacherNews/PoacherNews.git
3. Open the folder PoacherNews, then open the folder util
4. Create db.php
6. Copy db.php.scrubbed to db.php
6. In db.php, add the password in the blank single quotes (I'll put the password in the groupme), then save it
7. Command line: cd PoacherNews
8. Command line: php -S localhost:8000
