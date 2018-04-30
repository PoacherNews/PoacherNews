# USE CS4320;
  
## Articles
  (ArticleID, Headline, Body, Visits, Date, Category, IsPublished)

  INSERT INTO Articles(ArticleID, Headline, Body, Category, IsPublished)
	VALUES (1, 'Test Article', 'Test body.', 'Humor', 'N');

## Users
  (UserID, Username, Usertype, Password)

  INSERT INTO Users(UserID, Username, Usertype, Password)
	VALUES (3, 'TestAdmin', 'A', 'FDSA4321');
	
## Connection to the Server
	Hostname: poacherdatabase.ccbtf4xhozoc.us-east-2.rds.amazonaws.com
	Username: mysqladmin
	Password: Hunter1234
	Database Name: poacherdatabase
	
	mysql command: mysql -h poacherdatabase.ccbtf4xhozoc.us-east-2.rds.amazonaws.com -u mysqladmin -p
