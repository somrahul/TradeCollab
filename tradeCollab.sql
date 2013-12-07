CREATE TABLE tradeCollab_members (
	member_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	member_email varchar(128) NOT NULL,
	member_name varchar(1024) NOT NULL,
	team_id MEDIUMINT,

	CONSTRAINT `tradeCollab_members_ibfk_1`
    FOREIGN KEY (`team_id`)
    REFERENCES `tradeCollab_team` (`team_id`)
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tradeCollab_team (
	team_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	team_name varchar(1024) NOT NULL

) ENGINE = InnoDB DEFAULT CHARSET=utf8;