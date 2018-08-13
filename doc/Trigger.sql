/* When a User is deleted, CommentText linked to their UserID is set to NULL*/
DROP TRIGGER IF EXISTS deleteTrigger;
DELIMITER $$
CREATE TRIGGER deleteTrigger 
BEFORE DELETE ON User 
FOR EACH ROW
BEGIN
	UPDATE Comment
    SET Comment.CommentText = NULL WHERE Comment.UserID = OLD.UserID;
END $$
DELIMITER ;
