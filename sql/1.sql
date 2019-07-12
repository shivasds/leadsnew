ALTER TABLE  `close_callback_details` CHANGE  `advisor_1`  `advisor1_id` INT( 11 ) NOT NULL ;
ALTER TABLE  `close_callback_details` CHANGE  `advisor_2`  `advisor2_id` INT( 11 ) NULL ;
ALTER TABLE  `close_callback_details` CHANGE  `builder`  `sub_source` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
ALTER TABLE  `close_callback_details` DROP  `incentive_of_advisor1` ,
DROP  `incentive_of_advisor2` ;