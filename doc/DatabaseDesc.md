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
	Public DNS: ec2-52-14-177-55.us-east-2.compute.amazonaws.com
	Username: user
	Password: pass
