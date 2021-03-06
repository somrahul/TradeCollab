drop table if exists webauto_rps;
create table webauto_rps (
    rps_guid    varchar(64) NOT NULL,
    link_id     MEDIUMINT NOT NULL,
    user1_id    MEDIUMINT NOT NULL,
    play1       INTEGER NOT NULL,
    user2_id    MEDIUMINT,
    play2       INTEGER,
    started_at  DATETIME NOT NULL,
    finished_at  DATETIME,

    CONSTRAINT `webauto_rps_ibfk_1`
        FOREIGN KEY (`link_id`)
        REFERENCES `webauto_lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `webauto_rps_ibfk_2`
        FOREIGN KEY (`user1_id`)
        REFERENCES `webauto_lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `webauto_rps_ibfk_3`
        FOREIGN KEY (`user2_id`)
        REFERENCES `webauto_lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX `webauto_rps_indx_1` USING HASH (`rps_guid`),
    UNIQUE(rps_guid)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;


drop table if exists webauto_chats;
create table webauto_chats (
	link_id MEDIUMINT NOT NULL,
	chat_id MEDIUMINT NOT NULL,
	user_id MEDIUMINT NOT NULL,
	chat varchar(4096),
	chat_created DATETIME NOT NULL,
	
	CONSTRAINT `webauto_rps_ibfk_1`
        FOREIGN KEY (`link_id`)
        REFERENCES `webauto_lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `webauto_rps_ibfk_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `webauto_lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;
	
drop table if exists webauto_guess;
create table webauto_guess (
	link_id MEDIUMINT NOT NULL,
	guess_id MEDIUMINT NOT NULL,
	user_id MEDIUMINT NOT NULL,
	guess varchar(4096),
	
	CONSTRAINT `webauto_guess_ibfk_1`
        FOREIGN KEY (`link_id`)
        REFERENCES `webauto_lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `webauto_guess_ibfk_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `webauto_lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE = InnoDB DEFAULT CHARSET=utf8;
	
