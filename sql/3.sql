ALTER TABLE  `callback_sv_data` ADD  `project_id` INT( 11 ) NOT NULL AFTER  `visit_date` ;
RENAME TABLE `callback_sv_data` TO `callback_extra_data`;
ALTER TABLE  `callback_extra_data` CHANGE  `visit_date`  `date` DATE NOT NULL ;
ALTER TABLE  `callback_extra_data` ADD  `type` ENUM(  '1',  '2',  '3' ) NOT NULL COMMENT '1=>Site visit fixed, 2=>Site Visit Fixed, 3=>Face to Face' AFTER  `callback_id` ;