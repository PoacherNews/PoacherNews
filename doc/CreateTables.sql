DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Articles;

CREATE TABLE Users (
    UserID INT AUTO_INCREMENT,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Email VARCHAR(50),
    Username VARCHAR(50),
    Usertype VARCHAR(50) DEFAULT 'U',
    Password VARCHAR(255),
    CONSTRAINT PKUsers PRIMARY KEY (UserID)
);

CREATE TABLE Articles (
    ArticleID INT AUTO_INCREMENT,
    Headline VARCHAR(255),
    Body VARCHAR(10000),
    Category VARCHAR(50),
    IsPublished BOOLEAN DEFAULT false,
    PublishDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Views MEDIUMINT(9),
    Img VARCHAR(500),
    CONSTRAINT PKArticles PRIMARY KEY (ArticleID)
);
