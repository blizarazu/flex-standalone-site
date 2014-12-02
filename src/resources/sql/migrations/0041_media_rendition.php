<?php
		
function forwards(){
	global $DB, $CFG, $VP;
	
	$create = "CREATE TABLE IF NOT EXISTS `media_rendition` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`fk_media_id` int(10) unsigned NOT NULL,
				`filename` varchar(255) NOT NULL,
				`contenthash` varchar(40) NOT NULL,
				`status` tinyint(10) NOT NULL DEFAULT '0' COMMENT '0: raw 1: encoding, 2: ready, 3: duplicate, 4: error',
				`timecreated` int(11) NOT NULL,
				`timemodified` int(11) NOT NULL,
				`filesize` int(11) NOT NULL DEFAULT '0',
				`metadata` text,
				`dimension` int(10) DEFAULT NULL,
			    PRIMARY KEY (`id`),
			    KEY `FK_media_rendition_1` (`fk_media_id`),
			    CONSTRAINT `fk_media_rendition_1` FOREIGN KEY (`fk_media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$DB->_update($create);
	
	$sql = "SELECT * FROM media WHERE id>0";
	$results = $DB->_multipleSelect($sql);
	if($results){
		$er_sql = "INSERT INTO media_rendition (`fk_media_id`,`filename`,`contenthash`,`status`,`timecreated`,`timemodified`,`filesize`,`metadata`,`dimension`) VALUES ";
		$params = array();
		foreach($results as $r){
			$path = $CFG->red5Path.'/exercises/'.$r->filename;
			if(is_file($path)){
				$er_sql .= " (%d, '%s', '%s', %d, %d, %d, %d, '%s', %d),";
				$data = $VP->retrieveMediaInfo($path);
				$contenthash = $data->hash;
				$dimension = $data->hasVideo ? $data->videoHeight : 0;
				$metadata = custom_json_encode($data);
				array_push($params, $r->id, $r->filename, $contenthash, $r->status, $r->timecreated, $r->timemodified, $r->filesize, $metadata, $dimension );
			} else {
				print "File not found: $path\n (instance id: $r->instanceid)";
			}
		}
		unset($line);
		$er_sql = substr($er_sql,0,-1);
		$merge = array_merge((array)$er_sql, $params);
		print_r($merge);
		$DB->_insert($merge);
	}
	
	$drop = "ALTER TABLE `media`  DROP COLUMN `metadata`, DROP COLUMN `filesize`, DROP COLUMN `status`, DROP COLUMN `contenthash`, DROP COLUMN `filename`";
	$DB->_update($drop);
}

function backwards(){
	//TODO
}

$models = '{"assignment":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_course_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"course","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"name":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":null,"extra":""},"description":{"type":{"name":"longtext"},"empty":"NO","key":"","default":null,"extra":""},"duedate":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"allowsubmissionsfromdate":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"grade":{"type":{"name":"varchar","size":"45"},"empty":"YES","key":"","default":null,"extra":""},"timemodified":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"teamsubmission":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"requireallteammemberssubmit":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"maxattempts":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_course_id"}]},"assignment_submission":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_assignment_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"assignment","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"timecreated":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"timemodified":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"status":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"fk_group_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"course_groups","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"attempnumber":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_assignment_id"},{"type":"index","columns":"fk_user_id"},{"type":"index","columns":"fk_group_id"}]},"course":{"id":{"type":{"name":"int","size":"11","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"category":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":"0","extra":""},"fullname":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"fk_serviceconsumer_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"serviceconsumer","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"idnumber":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":"0","extra":""},"shortname":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"summary":{"type":{"name":"longtext"},"empty":"YES","key":"","default":null,"extra":""},"format":{"type":{"name":"varchar","size":"21"},"empty":"NO","key":"","default":"topics","extra":""},"startdate":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"visible":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"1","extra":""},"language":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":"","extra":""},"timecreated":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"timemodified":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_serviceconsumer_id"}]},"course_groups":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_course_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"course","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"name":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"description":{"type":{"name":"longtext"},"empty":"YES","key":"","default":null,"extra":""},"timecreated":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"timemodified":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_course_id"}]},"credithistory":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_response_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"response","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_eval_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"","default":null,"extra":""},"changeDate":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"changeType":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"changeAmount":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_user_id"},{"type":"index","columns":"fk_response_id"},{"type":"index","columns":"fk_exercise_id"}]},"evaluation":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_response_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"response","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"score_overall":{"type":{"name":"tinyint","size":"4"},"empty":"YES","key":"","default":"0","extra":""},"comment":{"type":{"name":"text"},"empty":"YES","key":"","default":null,"extra":""},"adding_date":{"type":{"name":"timestamp"},"empty":"YES","key":"","default":"CURRENT_TIMESTAMP","extra":""},"score_intonation":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"YES","key":"","default":"0","extra":""},"score_fluency":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"YES","key":"","default":"0","extra":""},"score_rhythm":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"YES","key":"","default":"0","extra":""},"score_spontaneity":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"YES","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_response_id"},{"type":"index","columns":"fk_user_id"}]},"evaluation_video":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_evaluation_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"evaluation","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"video_identifier":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"","default":null,"extra":""},"source":{"type":{"name":"enum"},"empty":"NO","key":"","default":null,"extra":""},"thumbnail_uri":{"type":{"name":"varchar","size":"200"},"empty":"NO","key":"","default":"nothumb.png","extra":""},"duration":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_evaluation_id"}]},"exercise":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"exercisecode":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"UNI","default":null,"extra":""},"title":{"type":{"name":"varchar","size":"80"},"empty":"NO","key":"","default":null,"extra":""},"description":{"type":{"name":"text"},"empty":"NO","key":"","default":null,"extra":""},"language":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"difficulty":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"status":{"type":{"name":"tinyint","size":"2","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"visible":{"type":{"name":"tinyint","size":"1","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"fk_scope_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"timecreated":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"timemodified":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"likes":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"dislikes":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"type":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"situation":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"","default":null,"extra":""},"competence":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"","default":null,"extra":""},"lingaspects":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"exercisecode"},{"type":"index","columns":"fk_user_id"}]},"exercise_descriptor":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"situation":{"type":{"name":"tinyint","size":"2","unsigned":true},"empty":"NO","key":"MUL","default":"1","extra":""},"level":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"NO","key":"","default":"1","extra":""},"competence":{"type":{"name":"tinyint","size":"3","unsigned":true},"empty":"NO","key":"","default":"1","extra":""},"number":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"1","extra":""},"alte":{"type":{"name":"tinyint","size":"1","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"situation,level,competence,number"}]},"exercise_descriptor_i18n":{"fk_exercise_descriptor_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"","constraint":{"referenced_table":"exercise_descriptor","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"locale":{"type":{"name":"varchar","size":"8"},"empty":"NO","key":"PRI","default":null,"extra":""},"name":{"type":{"name":"text"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"fk_exercise_descriptor_id,locale"},{"type":"index","columns":"fk_exercise_descriptor_id"}]},"exercise_like":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":""},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":""},"like":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"timecreated":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"timemodified":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_user_id"}]},"exercise_report":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"reason":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"","default":null,"extra":""},"report_date":{"type":{"name":"timestamp"},"empty":"NO","key":"","default":"CURRENT_TIMESTAMP","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_user_id"}]},"media":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"mediacode":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"UNI","default":null,"extra":""},"instanceid":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"component":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"type":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":"video","extra":""},"filename":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":null,"extra":""},"contenthash":{"type":{"name":"varchar","size":"32"},"empty":"NO","key":"","default":null,"extra":""},"status":{"type":{"name":"tinyint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"timecreated":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"timemodified":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"license":{"type":{"name":"varchar","size":"60"},"empty":"NO","key":"","default":"cc-by","extra":""},"authorref":{"type":{"name":"text"},"empty":"YES","key":"","default":null,"extra":""},"duration":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"filesize":{"type":{"name":"varchar","size":"45"},"empty":"YES","key":"","default":null,"extra":""},"metadata":{"type":{"name":"text"},"empty":"YES","key":"","default":null,"extra":""},"level":{"type":{"name":"tinyint","size":"3"},"empty":"NO","key":"","default":"0","extra":""},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"defaultthumbnail":{"type":{"name":"int","size":"11"},"empty":"YES","key":"","default":"1","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"mediacode"}]},"moodle_api":{"access_key":{"type":{"name":"varchar","size":"20"},"empty":"NO","key":"PRI","default":null,"extra":""},"secret_access_key":{"type":{"name":"varchar","size":"40"},"empty":"NO","key":"","default":null,"extra":""},"allowed_referer":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"UNI","default":null,"extra":""},"fk_user_id":{"type":{"name":"int","size":"11","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"date_created":{"type":{"name":"timestamp"},"empty":"NO","key":"","default":"CURRENT_TIMESTAMP","extra":""},"raw_referer":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"","default":"","extra":""},"Meta":[{"type":"primary","columns":"access_key"},{"type":"index","columns":"allowed_referer"},{"type":"index","columns":"fk_user_id"}]},"motd":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"title":{"type":{"name":"varchar","size":"250"},"empty":"NO","key":"","default":null,"extra":""},"message":{"type":{"name":"text"},"empty":"NO","key":"","default":null,"extra":""},"resource":{"type":{"name":"varchar","size":"250"},"empty":"NO","key":"","default":null,"extra":""},"displaydate":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"displaywhenloggedin":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"code":{"type":{"name":"varchar","size":"45"},"empty":"YES","key":"","default":null,"extra":""},"language":{"type":{"name":"varchar","size":"5"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"}]},"preferences":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"prefName":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"prefValue":{"type":{"name":"varchar","size":"200"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"}]},"rel_course_role_user":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_role_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"role","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_course_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"course","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"timemodified":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_course_id"},{"type":"index","columns":"fk_role_id"},{"type":"index","columns":"fk_user_id"}]},"rel_coursegroup_user":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":""},"fk_group_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"course_groups","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"timeadded":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_group_id"},{"type":"index","columns":"fk_user_id"}]},"rel_exercise_descriptor":{"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_exercise_descriptor_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"","constraint":{"referenced_table":"exercise_descriptor","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"Meta":[{"type":"primary","columns":"fk_exercise_id,fk_exercise_descriptor_id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_exercise_descriptor_id"}]},"rel_exercise_tag":{"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_tag_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"","constraint":{"referenced_table":"tag","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"Meta":[{"type":"primary","columns":"fk_exercise_id,fk_tag_id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_tag_id"}]},"response":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":""},"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"file_identifier":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"UNI","default":null,"extra":""},"is_private":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":null,"extra":""},"thumbnail_uri":{"type":{"name":"varchar","size":"200"},"empty":"NO","key":"","default":"nothumb.png","extra":""},"source":{"type":{"name":"enum"},"empty":"NO","key":"","default":null,"extra":""},"duration":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"adding_date":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"rating_amount":{"type":{"name":"int","size":"10"},"empty":"NO","key":"","default":null,"extra":""},"character_name":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"fk_transcription_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"transcription","referenced_column":"id","update_rule":"RESTRICT","delete_rule":"SET NULL"}},"fk_subtitle_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"subtitle","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"priority_date":{"type":{"name":"timestamp"},"empty":"NO","key":"","default":"CURRENT_TIMESTAMP","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"file_identifier"},{"type":"index","columns":"fk_user_id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_transcription_id"},{"type":"index","columns":"fk_subtitle_id"}]},"role":{"id":{"type":{"name":"int","size":"11","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"name":{"type":{"name":"varchar","size":"255"},"empty":"YES","key":"","default":"","extra":""},"shortname":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":null,"extra":""},"description":{"type":{"name":"varchar","size":"45"},"empty":"YES","key":"","default":"","extra":""},"sortorder":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"archetype":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"Meta":[{"type":"primary","columns":"id"}]},"serviceconsumer":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"access_key":{"type":{"name":"varchar","size":"20"},"empty":"NO","key":"UNI","default":null,"extra":""},"secret_access_key":{"type":{"name":"varchar","size":"40"},"empty":"NO","key":"","default":null,"extra":""},"name":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":null,"extra":""},"domain":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"UNI","default":null,"extra":""},"ipaddress":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":"","extra":""},"timecreated":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"timemodified":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"requestlimit":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"100","extra":""},"enabled":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"1","extra":""},"salt":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"access_key"},{"type":"index","columns":"domain"}]},"serviceconsumer_log":{"id":{"type":{"name":"int","size":"11"},"empty":"NO","key":"PRI","default":null,"extra":""},"fk_serviceconsumer_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"serviceconsumer","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"time":{"type":{"name":"bigint","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"method":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":"","extra":""},"statuscode":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":"500","extra":""},"ip":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":"","extra":""},"referer":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"origin":{"type":{"name":"varchar","size":"255"},"empty":"NO","key":"","default":"","extra":""},"consumertime":{"type":{"name":"bigint","size":"10"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_serviceconsumer_id"}]},"spinvox_request":{"id":{"type":{"name":"int","size":"11","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"x_error":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"url":{"type":{"name":"varchar","size":"200"},"empty":"YES","key":"","default":null,"extra":""},"date":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"fk_transcription_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"transcription","referenced_column":"id","update_rule":"NO ACTION","delete_rule":"NO ACTION"}},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_transcription_id"}]},"subtitle":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_media_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"language":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"translation":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"complete":{"type":{"name":"tinyint","size":"1","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"serialized_subtitles":{"type":{"name":"longtext"},"empty":"NO","key":"","default":null,"extra":""},"subtitle_count":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"timecreated":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"timemodified":{"type":{"name":"int","size":"11"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_user_id"}]},"tag":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"name":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"}]},"transcription":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"adding_date":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"status":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"transcription":{"type":{"name":"text"},"empty":"YES","key":"","default":null,"extra":""},"transcription_date":{"type":{"name":"datetime"},"empty":"YES","key":"","default":null,"extra":""},"system":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"}]},"user":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"username":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"UNI","default":null,"extra":""},"password":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"email":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"firstname":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"lastname":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"creditCount":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":"0","extra":""},"joiningDate":{"type":{"name":"timestamp"},"empty":"NO","key":"","default":"CURRENT_TIMESTAMP","extra":""},"active":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"activation_hash":{"type":{"name":"varchar","size":"20"},"empty":"NO","key":"","default":null,"extra":""},"isAdmin":{"type":{"name":"tinyint","size":"4"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"username"}]},"user_languages":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"language":{"type":{"name":"varchar","size":"45"},"empty":"NO","key":"","default":null,"extra":""},"level":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"positives_to_next_level":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"","default":null,"extra":""},"purpose":{"type":{"name":"enum"},"empty":"NO","key":"","default":"practice","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_user_id"}]},"user_session":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"session_id":{"type":{"name":"varchar","size":"100"},"empty":"NO","key":"","default":null,"extra":""},"session_date":{"type":{"name":"datetime"},"empty":"NO","key":"","default":null,"extra":""},"duration":{"type":{"name":"int","size":"10"},"empty":"NO","key":"","default":null,"extra":""},"keep_alive":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":null,"extra":""},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"closed":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_user_id"}]},"user_videohistory":{"id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"PRI","default":null,"extra":"auto_increment"},"fk_user_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_user_session_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"user_session","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_exercise_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"NO","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"exercise","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"response_attempt":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"fk_response_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"response","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"incidence_date":{"type":{"name":"timestamp"},"empty":"NO","key":"","default":"CURRENT_TIMESTAMP","extra":""},"subtitles_are_used":{"type":{"name":"tinyint","size":"1"},"empty":"NO","key":"","default":"0","extra":""},"fk_subtitle_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":"","constraint":{"referenced_table":"subtitle","referenced_column":"id","update_rule":"CASCADE","delete_rule":"CASCADE"}},"fk_exercise_role_id":{"type":{"name":"int","size":"10","unsigned":true},"empty":"YES","key":"MUL","default":null,"extra":""},"Meta":[{"type":"primary","columns":"id"},{"type":"index","columns":"fk_user_id"},{"type":"index","columns":"fk_user_session_id"},{"type":"index","columns":"fk_exercise_id"},{"type":"index","columns":"fk_response_id"},{"type":"index","columns":"fk_subtitle_id"},{"type":"index","columns":"fk_exercise_role_id"}]}}';
	
