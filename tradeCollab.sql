drop table if exists tradeCollab_members;

CREATE TABLE tradeCollab_team (
	team_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	team_name varchar(1024) NOT NULL,
	ideology varchar(4096),
	contract varchar(8192),
	contract_duration MEDIUMINT,
	team_since DATETIME,
	budget DOUBLE,
	budget_current DOUBLE,
	markets varchar(4096)

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tradeCollab_members (
	member_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	member_email varchar(128) NOT NULL,
	member_name varchar(1024),
	member_last_name varchar(1024),
	member_bio varchar(4096),
	member_skills varchar(4096),
	member_since DATETIME,
	deals_initiated MEDIUMINT,
	team_id MEDIUMINT,

	CONSTRAINT `tradeCollab_members_ibfk_1`
    FOREIGN KEY (`team_id`)
    REFERENCES `tradeCollab_team` (`team_id`)
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE tradeCollab_deal (
	deal_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	team_id MEDIUMINT,
	member_id MEDIUMINT,
	member_email varchar(1024) NOT NULL,
	stock_name varchar(1024) NOT NULL,
	stock_price FLOAT,
	stock_quant MEDIUMINT,
	deal_nature varchar(128),
	reason varchar(4096),
	deal_end DATE,
	deal_created DATETIME,
	market varchar(1024),

	CONSTRAINT `tradeCollab_deal_ibfk_1`
    FOREIGN KEY (`team_id`)
    REFERENCES `tradeCollab_team` (`team_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `tradeCollab_deal_ibfk_2`
    FOREIGN KEY (`member_id`)
    REFERENCES `tradeCollab_members` (`member_id`)
    ON DELETE CASCADE ON UPDATE CASCADE


) ENGINE = InnoDB DEFAULT CHARSET=utf8;

create table tradeCollab_comments (
	chat_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY, 
	deal_id MEDIUMINT NOT NULL,
	member_id MEDIUMINT NOT NULL,
	chat varchar(4096),
	chat_created DATETIME NOT NULL,
	
	CONSTRAINT `tradeCollab_comments_ibfk_1`
        FOREIGN KEY (`deal_id`)
        REFERENCES `tradeCollab_deal` (`deal_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `tradeCollab_comments_ibfk_2`
        FOREIGN KEY (`member_id`)
        REFERENCES `tradeCollab_members` (`member_id`)
        ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tradeCollab_deal_status (
	team_id MEDIUMINT,
	deal_id MEDIUMINT,
	member_id MEDIUMINT,
	member_status varchar(128),

	CONSTRAINT `tradeCollab_deal_status_ibfk_1`
        FOREIGN KEY (`deal_id`)
        REFERENCES `tradeCollab_deal` (`deal_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `tradeCollab_deal_status_ibfk_2`
        FOREIGN KEY (`member_id`)
        REFERENCES `tradeCollab_members` (`member_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT `tradeCollab_deal_status_ibfk_3`
        FOREIGN KEY (`team_id`)
        REFERENCES `tradeCollab_team` (`team_id`)
        ON DELETE CASCADE ON UPDATE CASCADE    

) ENGINE = InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE tradeCollab_deals_completed (
	deal_id MEDIUMINT,
	team_id MEDIUMINT,
	member_id MEDIUMINT,
	member_email varchar(1024) NOT NULL,
	stock_name varchar(1024) NOT NULL,
	stock_price FLOAT,
	stock_quant MEDIUMINT,
	deal_nature varchar(128),
	reason varchar(4096),
	deal_end DATE,
	deal_created DATETIME,
	market varchar(1024),

	CONSTRAINT `tradeCollab_deals_completed_ibfk_1`
    FOREIGN KEY (`team_id`)
    REFERENCES `tradeCollab_team` (`team_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `tradeCollab_deals_completed_ibfk_2`
    FOREIGN KEY (`member_id`)
    REFERENCES `tradeCollab_members` (`member_id`)
    ON DELETE CASCADE ON UPDATE CASCADE


) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tradeCollab_recommendations (
	recommendation_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	member_id MEDIUMINT,
	recommender_id MEDIUMINT,
	recommendation varchar(8192),

	CONSTRAINT `tradeCollab_recommendations_ibfk_1`
    FOREIGN KEY (`member_id`)
    REFERENCES `tradeCollab_members` (`member_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,	

    CONSTRAINT `tradeCollab_recommendations_ibfk_2`
    FOREIGN KEY (`recommender_id`)
    REFERENCES `tradeCollab_members` (`member_id`)
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE tradeCollab_team_recommendations (
	recommendation_id MEDIUMINT NOT NULL AUTO_INCREMENT KEY,
	team_id MEDIUMINT,
	recommender_id MEDIUMINT,
	recommendation varchar(8192),

	CONSTRAINT `tradeCollab_team_recommendations_ibfk_1`
    FOREIGN KEY (`team_id`)
    REFERENCES `tradeCollab_team` (`team_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,	

    CONSTRAINT `tradeCollab_team_recommendations_ibfk_2`
    FOREIGN KEY (`recommender_id`)
    REFERENCES `tradeCollab_members` (`member_id`)
    ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;