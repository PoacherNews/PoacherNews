DROP TABLE IF EXISTS Featured;
DROP TABLE IF EXISTS Rating;
DROP TABLE IF EXISTS Favorite;
DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS User;

CREATE TABLE User (
    UserID INT AUTO_INCREMENT,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Email VARCHAR(50),
    Username VARCHAR(50),
    Usertype VARCHAR(50) DEFAULT 'U',
    Password VARCHAR(255),
    CONSTRAINT PKUser PRIMARY KEY (UserID)
);

CREATE TABLE Article (
    ArticleID INT AUTO_INCREMENT,
    UserID INT NULL,
    Headline VARCHAR(255),
    Body VARCHAR(10000),
    Category VARCHAR(50),
    PublishDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Views MEDIUMINT(9),
    Image VARCHAR(500) DEFAULT 'https://i.imgur.com/U469uHI.jpg',
    ArticleRating DOUBLE DEFAULT 0,
    IsDraft BOOLEAN DEFAULT true,
    IsPublished BOOLEAN DEFAULT false,
    FOREIGN KEY (UserID) REFERENCES User (UserID),
    CONSTRAINT PKArticle PRIMARY KEY (ArticleID)
);

CREATE TABLE Comment (
    CommentID INT,
    UserID INT,
    ArticleID INT,
    CommentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES User (UserID),
    FOREIGN KEY (ArticleID) REFERENCES Article (ArticleID),
    CONSTRAINT PKComment PRIMARY KEY (CommentID)

);

CREATE TABLE Favorite (
    UserID INT,
    ArticleID INT,
    FOREIGN KEY (UserID) REFERENCES User (UserID),
    FOREIGN KEY (ArticleID) REFERENCES Article (ArticleID),
    CONSTRAINT PKFavorite PRIMARY KEY (UserID, ArticleID)
);

CREATE TABLE Rating (
    RatingID INT,
    UserID INT,
    ArticleID INT,
    RatingDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Score DOUBLE,
    FOREIGN KEY (UserID) REFERENCES User (UserID),
    FOREIGN KEY (ArticleID) REFERENCES Article (ArticleID),
    CONSTRAINT PKRating PRIMARY KEY (RatingID)
);

CREATE TABLE Featured (
    FeaturedType ENUM('EditorPick'),
    ArticleID INT,
    FOREIGN KEY (ArticleID) REFERENCES Article (ArticleID),
    CONSTRAINT PKFeatured PRIMARY KEY (FeaturedType)
);