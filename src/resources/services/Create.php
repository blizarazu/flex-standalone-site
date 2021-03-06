<?php
/**
 * Babelium Project open source collaborative second language oral practice - http://www.babeliumproject.com
 *
 * Copyright (c) 2014 GHyM and by respective authors (see below).
 *
 * This file is part of Babelium Project.
 *
 * Babelium Project is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Babelium Project is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'utils/Datasource.php';
require_once 'utils/Config.php';
require_once 'utils/SessionValidation.php';

require_once 'Exercise.php';
require_once 'Search.php';
require_once 'Subtitle.php';
require_once 'vo/ExerciseVO.php';

require_once 'Zend/Json.php';

/**
 * This class deals with all aspects of exercise creation
 *
 * @author Babelium Team
 *
 */
class Create {
	
	const STATUS_UNDEF=0;
	const STATUS_ENCODING=1;
	const STATUS_READY=2;
	const STATUS_DUPLICATED=3;
	const STATUS_ERROR=4;

	const LEVEL_UNDEF=0;
	const LEVEL_PRIMARY=1;
	const LEVEL_MODEL=2;
	
	const EXERCISE_READY=1;
	const EXERCISE_DRAFT=0;
	
	const TYPE_VIDEO='video';
	const TYPE_AUDIO='audio';
	
	private $conn;
	private $cfg;

	public function __construct(){
		try {
			$this->cfg = new Config();
			$verifySession = new SessionValidation();
			$this->mediaHelper = new VideoProcessor();
			$this->conn = new Datasource($this->cfg->host, $this->cfg->db_name, $this->cfg->db_username, $this->cfg->db_password);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function listUserCreations($offset=0, $rowcount=0){
		try {
			$verifySession = new SessionValidation(true);
	
			$sql = "SELECT e.id,
			e.title,
			e.description,
			e.language,
			e.exercisecode,
			e.timecreated,
			e.difficulty,
			e.visible,
			e.status
			FROM exercise e
			WHERE e.fk_user_id = %d
			ORDER BY e.timecreated DESC";
				
			$searchResults = array();
				
			if($rowcount){
				if($offset){
					$sql .= " LIMIT %d, %d";
					$searchResults = $this->conn->_multipleSelect($sql, $_SESSION['uid'], $offset, $rowcount);
				} else {
					$sql .= " LIMIT %d";
					$searchResults = $this->conn->_multipleSelect($sql, $_SESSION['uid'], $rowcount);
				}
			} else {
				$searchResults = $this->conn->_multipleSelect($sql, $_SESSION['uid']);
			}
	
			if($searchResults){
				foreach($searchResults as $searchResult){
					//$searchResult->isSubtitled = $searchResult->isSubtitled ? true : false;
					//$searchResult->avgRating = $exercise->getExerciseAvgBayesianScore($searchResult->id)->avgRating;
					//$searchResult->descriptors = $exercise->getExerciseDescriptors($searchResult->id);
					$ex = new Exercise();
					$url = $ex->getExerciseDefaultThumbnail($searchResult->id);
					$searchResult->thumbnail = $url;
				}
			}
			return $searchResults;
	
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function deleteSelectedCreations($params){
		try {
			$verifySession = new SessionValidation(true);
	
			if(!$params)
				throw new Exception("Invalid parameters",1000);
	
			$userid=$_SESSION['uid'];
	
			if(count($params) > 0){
				foreach ($params as $exercise){
					$exerciseid=$exercise->id;
					$sql = "DELETE FROM exercise WHERE fk_user_id=%d AND id=%d";
					$affrows = $this->conn->_delete($sql,$userid,$exerciseid);
					if($affrows) { //The exercise belonged to user and was deleted
						//Delete the media associated with this exercise
						$msql = "DELETE FROM media WHERE component='exercise' AND instanceid=%d";
						$maffrows = $this->conn->_delete($msql,$exerciseid);	
					}
				}
			}
			return TRUE;
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage(),$e->getCode());
		}
	}
	
	
	public function getExerciseData($exercisecode = null){
		try{
			$verifySession = new SessionValidation(true);
			
			$exercise = new Exercise();
			$exercisedata = $exercise->getExerciseByCode($exercisecode);
			
			//The requested code was not found, user is adding a new exercise
			if(!$exercisedata){
				//Generate an exercise-uid and store it in the session and return it to client.
				//In following calls to saveExerciseData check for exercise-uid to determine if adding new exercise.
				$euid = $this->uuidv4();
				$_SESSION['euid'] = $euid;
				return $euid;
			} else {
				$exercisedata->media = $this->getExerciseMedia($exercisecode);
			}
		} catch (Exception $e){
			throw new Exception ($e->getMessage());
		}
	}
	
	public function getExerciseMedia($exercisecode){
		if(!$exercisecode) return;
		try{
			$verifySession = new SessionValidation(true);
			
			$exercise = new Exercise();
			$exercisedata = $exercise->getExerciseByCode($exercisecode);
			if(!$exercisedata)
				throw new Exception("Exercise code doesn't exist",1003);
		
			$statuses = '0,1,2,3,4';
			$levels = '0,1,2';
			$component = 'exercise';
			$sql = "SELECT m.id, m.instanceid as exerciseid, m.mediacode, m.defaultthumbnail, m.type, m.timecreated, m.timemodified, m.duration, m.level
					FROM media m
					WHERE m.component='%s' AND m.level IN (%s) AND m.instanceid=(SELECT id FROM exercise WHERE exercisecode='%s')";
			$results = $this->conn->_multipleSelect($sql, $component, $levels, $exercisecode);
			if($results){
				foreach($results as $r){
					$mediaid = $r->id;
					$sql_rendition = "SELECT id, fk_media_id, MAX(`status`) as `status`, filename FROM media_rendition WHERE fk_media_id=%d";
					$rendition = $this->conn->_singleSelect($sql_rendition, $mediaid);
					if($rendition){
						if($rendition->status==self::STATUS_READY){
							$r->status=self::STATUS_READY;
							$r->filename=$rendition->filename;
							$r->subtitlestatus=$this->getSubtitleStatus($r->id);
							if($r->type==self::TYPE_VIDEO){
								$posterurl = '/resources/images/posters/'.$r->mediacode.'/0'.$r->defaultthumbnail.'.jpg';
								$r->posterurl = $posterurl;
								$thumburls=array();
								for($i=1;$i<4;$i++){
									$thumburls[] = '/resources/images/thumbs/'.$r->mediacode.'/0'.$i.'.jpg';
								}
								$r->thumburls = $thumburls;
							}
						} else {
							$r->status=$rendition->status;
						}
					}
				}
			}
			return $results;
		} catch (Exception $e){
			throw new Exception ($e->getMessage());
		}
	}
	
	private function getSubtitleStatus($mediaid){
		$status=0;
		$sql = "SELECT complete FROM subtitle WHERE fk_media_id=%d";
		$results = $this->conn->_multipleSelect($sql, $mediaid);
		if($results){
			$status=1;
			foreach($results as $r){
				if($r->complete==1){
					$status=2;
					break;
				}
			}
		}
		return $status;
	}
	
	public function setDefaultThumbnail($data){
		try{
			$session = new SessionValidation(true);
			
			if(!$data || !isset($data->mediacode)) return;
			
			$thumbidx = (isset($data->defaultthumbnail) && $data->defaultthumbnail >=1 && $data->defaultthumbnail <= 3) ? $data->defaultthumbnail : 1;
			$mediacode = $data->mediacode;
			
			$posterfolder = $this->cfg->posterPath .'/'. $mediacode;
			$thumbfolder = $this->cfg->imagePath .'/'. $mediacode;
			
			/*
			@unlink($thumbfolder.'/default.jpg');
			@unlink($posterfolder.'/default.jpg');
			
			if( !symlink($thumbfolder.'/0'.$thumbidx.'.jpg', $thumbfolder.'/default.jpg')  ){
				throw new Exception ("Couldn't create link for the thumbnail $thumbfolder/0$thumbidx.jpg, $thumbfolder/default.jpg\n");
			}
			if( !symlink($posterfolder.'/0'.$thumbidx.'.jpg', $posterfolder.'/default.jpg') ){
				throw new Exception ("Couldn't create link for the poster\n");
			}
			*/
			
			$sql = "UPDATE media SET defaultthumbnail=%d WHERE mediacode='%s' AND type='video' AND fk_user_id=%d";
			
			$rowcount = $this->conn->_update($sql, $thumbidx, $mediacode, $_SESSION['uid']);
		
			$sql = "SELECT instanceid FROM media WHERE mediacode='%s' AND component='exercise'";
			$result = $this->conn->_singleSelect($sql, $mediacode);
			
			if(!$result || !isset($result->instanceid)){
				throw new Exception("No exercise matches the given mediacode");
			}else{
				$exercise = new Exercise();
				$exercisedata = $exercise->getExerciseById($result->instanceid);
				return $this->getExerciseMedia($exercisedata->exercisecode);
			}
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
	
	public function deleteExerciseMedia($data = null){
		try {
			$valid = new SessionValidation(true);
			if(!$data || !$data->mediaid){
				throw new Exception("Invalid parameters", 1000);
			}
			
			$mediaid=$data->mediaid;
			
			$sql = "SELECT instanceid FROM media WHERE id=%d";
			$media = $this->conn->_singleSelect($sql,$mediaid);
			if($media){
				$exerciseid = $media->instanceid;
				$e = new Exercise();
				$exercise = $e->getExerciseById($exerciseid);
				if(!$exercise)
					throw new Exception("Exercise code doesn't exist",1003);
				$exercisecode = $exercise->exercisecode;	
			} else {
				throw new Exception("Media id doesn't exist", 1004);
			}
			
			$sql = "DELETE FROM media WHERE id=%d";
			$this->conn->_delete($sql,$mediaid);
			
			return $this->getExerciseMedia($exercisecode);
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), $e->getCode());
		}
		
	}
	
	public function saveExerciseMedia($data = null){
		try{
			$verifySession = new SessionValidation(true);
			
			if(!$data || (!isset($data->exerciseid) && !isset($data->exercisecode)) || !isset($data->filename) || !isset($data->level))
				throw new Exception("Invalid parameters", 1000);
			
			//Retrieve the ID for the given exercise code
			if(!isset($data->exerciseid)){
				$e = new Exercise();
				$exercise = $e->getExerciseByCode($data->exercisecode);
				if(!$exercise)
					throw new Exception("Exercise code doesn't exist",1003);
				$instanceid = $exercise->id;
				$instancecode = $data->exercisecode;
			} else {
				$instanceid = $data->exerciseid;
				$instancecode = $data->exercisecode;
			}
			
			//Check if media has already been added for the given 'instanceid', 'component' and 'level'
			$sql = "SELECT id FROM media WHERE instanceid=%d AND component='%s' AND level=%d";
			$mediaexists = $this->conn->_multipleSelect($sql, $instanceid, 'exercise', $data->level);
			
			if($mediaexists)
				throw new Exception("The exercise already has media for that level", 1001);
			
			$this->_getResourceDirectories();		
			
			$optime = time();
			$mediacode = $this->uuidv4();
			
			$webcammedia = $this->cfg->red5Path . '/' . $this->exerciseFolder . '/' . $data->filename;
			$filemedia = $this->cfg->filePath . '/' . $data->filename;
			
			$status = self::STATUS_UNDEF; //raw video
			
			if(is_file($webcammedia)){
				$filename = $mediacode.'_'.$optime.'.flv';
				$fileabspath = $this->cfg->red5Path.'/'.$this->exerciseFolder.'/'.$filename;
				rename($webcammedia,$fileabspath);
				$medianfo = $this->mediaHelper->retrieveMediaInfo($fileabspath);
				$dimension = $medianfo->videoHeight;
				$filesize = filesize($fileabspath);
				$thumbdir = $this->cfg->imagePath.'/'.$mediacode;
				$posterdir = $this->cfg->posterPath.'/'.$mediacode;
				$this->mediaHelper->takeFolderedRandomSnapshots($fileabspath, $thumbdir, $posterdir);
				$status = self::STATUS_READY;
			} else if(is_file($filemedia)){
				$filename = $data->filename;
				$medianfo = $this->mediaHelper->retrieveMediaInfo($filemedia);
				$dimension = $medianfo->videoHeight;
				$filesize = filesize($filemedia);			
			} else {
				throw new Exception("Media file not found", 1002);
			}
			$contenthash = $medianfo->hash;
			$duration = $medianfo->duration;
			$type = $medianfo->hasVideo ? 'video' : 'audio';
			$metadata = $this->custom_json_encode($medianfo);
			
			$insert = "INSERT INTO media (instanceid, component, mediacode, type, timecreated, duration, level, defaultthumbnail, fk_user_id) 
					   VALUES (%d, '%s', '%s', '%s', %d, %d, %d, %d, %d)";
			
			$mediaid = $this->conn->_insert($insert, $instanceid, 'exercise', $mediacode, $type, $optime, $duration, $data->level, 1, $_SESSION['uid']);
			
			$insertr = "INSERT INTO media_rendition (fk_media_id, filename, contenthash, status, timecreated, filesize, metadata, dimension) 
						VALUES (%d, '%s', '%s', %d, %d, %d, '%s', %d)";
			
			$mediarendition = $this->conn->_insert($insertr, $mediaid, $filename, $contenthash, $status, $optime, $filesize, $metadata, $dimension);
			
			//Set exercise's status to draft
			$this->changeExerciseStatus($instanceid,self::EXERCISE_DRAFT);
			
			//TODO add raw media to asynchronous task processing queue
			//videoworker->add_task($mediaid);
			
			return $this->getExerciseMedia($instancecode);
			
		} catch (Exception $e){
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	public function requestCreateRecordingSlot(){
		$optime = time();
		$mediacode = $this->uuidv4();
		$mediaUrl = 'exercises/'.$mediacode.'_'.$optime.'.flv';
		$netConnectionUrl = $this->cfg->streamingserver;
		$data = new stdClass();
		$data->mediaUrl = $mediaUrl;
		$data->netConnectionUrl = $netConnectionUrl;
		
		return $data;
	}
	
	public function getMediaStatus($mediaid){
		$component = 'exercise';
		$sql = "SELECT max(`status`) as `status` FROM media_rendition WHERE fk_media_id=%d";
		$result = $this->conn->_singleSelect($sql, $mediaid);
		return $result ? $result->status : -1;
	}
	
	public function getExercisePreview($exercisecode){
		try{
			if(!$exercisecode)
				throw new Exception("Invalid parameter",1000);
			
			$verifySession = new SessionValidation(true);
				
			$exercise = new Exercise();
			$exercisedata = $exercise->getExerciseByCode($exercisecode);
			if(!$exercisedata)
				throw new Exception("Exercise code doesn't exist",1003);
			
			if($exercisedata){
				$status = 2;
				$level = array(1,2);
				$media = $exercise->getExerciseMedia($exercisedata->id, $status, $level);
				if($media){
					$sub = new Subtitle();
					foreach($media as $m){
						$arg = new stdClass();
						$arg->mediaid = $m->id;
						$m->subtitles = $sub->getSubtitleLines($arg);
					}
					$exercisedata->media = $media;
				}
			}
			return $exercisedata;
			
		} catch (Exception $e){
			throw new Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * Helper function to generate RFC4122 compliant UUIDs
	 * 
	 * @return String $uuid
	 * 		A RFC4122 compliant string
	 */
	public function uuidv4()
	{
		//When the openssl extension is not available in *nix systems try using urandom
		if(function_exists('openssl_random_pseudo_bytes')){
			$data = openssl_random_pseudo_bytes(16);
		} else {
			$data = file_get_contents('/dev/urandom', NULL, NULL, 0, 16);
		}	
	
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
	
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
	
	public function saveExerciseData($data = null){
		try{
			$verifySession = new SessionValidation(true);
	
			if(!$data)
				return;
			
			$optime = time();
			$exercise = new Exercise();
			$parsedTags = $exercise->parseExerciseTags($data->tags);
			$parsedDescriptors = $exercise->parseDescriptors($data->descriptors);
	
			//Updating an exercise that already exists
			if($data->exercisecode && $exercise->getExerciseByCode($data->exercisecode)){
	
				//Turn off the autocommit
				//$this->conn->_startTransaction();
						
				//Remove previous exercise_descriptors (if any)
				$sql = "DELETE FROM rel_exercise_descriptor WHERE fk_exercise_id=%d";
				$arows4 = $this->conn->_delete($sql,$data->id);
		
				//Insert new exercise descriptors (if any)
				$exercise->insertDescriptors($parsedDescriptors,$data->id);
		
				//Remove previous exercise_tags
				$sql = "DELETE FROM rel_exercise_tag WHERE fk_exercise_id=%d";
				$arows3 = $this->conn->_delete($sql,$data->id);
		
				//Insert new exercise tags
				$exercise->insertTags($parsedTags,$data->id);
		
				//Update the fields of the exercise
				$sql = "UPDATE exercise SET title='%s', description='%s', language='%s', difficulty=%d, timemodified=%d, type=%d, situation=%d, competence=%d, lingaspects='%s'
						WHERE exercisecode='%s' AND fk_user_id=%d";
				$arows1 = $this->conn->_update($sql, $data->title, $data->description, $data->language, $data->difficulty, $optime, $data->type, 
													 $data->situation, $data->competence, $data->lingaspects, $data->exercisecode, $_SESSION['uid']);
				
				$search = new Search();
				$search->addDocumentIndex($data->id);
				
				//Turn on the autocommit, there was no errors modifying the database
				//$this->conn->_endTransaction();
				
				return $data->exercisecode;
	
			// Adding a new exercise
			} else {
				//TODO use some session value to know when transitioning back&forth from 'step1' and 'step2'
				
				$exercisecode = $this->str_makerand(11,true,true);
				
				$status = self::EXERCISE_DRAFT;
				
				$sql = "INSERT INTO exercise (exercisecode, title, description, language, difficulty, timecreated, type, situation, competence, lingaspects, fk_user_id, status) 
						VALUES ('%s', '%s', '%s', '%s', %d, %d, %d, %d, %d, '%s', %d, %d)";
				$exerciseid = $this->conn->_insert($sql, $exercisecode, $data->title, $data->description, $data->language, $data->difficulty, 
													     $optime, $data->type, $data->situation, $data->competence, $data->lingaspects, $_SESSION['uid'], $status);
				
				//Insert new exercise descriptors (if any)
				$exercise->insertDescriptors($parsedDescriptors,$exerciseid);
				
				//Insert new exercise tags
				$exercise->insertTags($parsedTags,$exerciseid);
				
				$search = new Search();
				$search->addDocumentIndex($exerciseid);
				
				return $exercisecode;
			}
		} catch (Exception $e){
			//$this->conn->_failedTransaction();
			throw new Exception ($e->getMessage());
		}
	}
	
	/**
	 * Checks if all the medias that belong to the exercise have at least one 'transcoded/ready' rendition
	 * and whether each of those medias have 'complete' subtitles
	 * 
	 * @param int $exerciseid
	 */
	public function checkExerciseItemsStatus($exerciseid){
		if(!$exerciseid)
			throw new Exception("Invalid parameter",1000);
		
		$updatedstatus = self::EXERCISE_DRAFT;
		
		$sql = "SELECT id FROM media WHERE component='exercise' AND instanceid=%d";
		
		$exmedia = $this->conn->_multipleSelect($sql,$exerciseid);
		if($exmedia){
			$updatedstatus = self::EXERCISE_READY;
			foreach($exmedia as $em){
				$substatus = 1;
				$subsql = "SELECT id FROM subtitle WHERE complete=%d AND fk_media_id=%d LIMIT 1";
				$subtitled = $this->conn->_singleSelect($subsql,$substatus,$em->id);
				
				$encstatus = self::STATUS_READY;
				$rensql = "SELECT id FROM media_rendition WHERE status=%d AND fk_media_id=%d LIMIT 1";
				$encoded = $this->conn->_singleSelect($rensql,$encstatus,$em->id);
				
				if(!$subtitled || !$encoded)
					$updatedstatus=self::EXERCISE_DRAFT;
			}
		}
		return $updatedstatus;
	}
	
	private function changeExerciseStatus($exerciseid,$status){
		if(!$exerciseid)
			throw new Exception("Invalid parameter",1000);
		
		$cstatus = $status ? self::EXERCISE_READY : self::EXERCISE_DRAFT;
		
		$update = "UPDATE exercise SET status=%d WHERE id=%d";
		$this->conn->_update($update,$cstatus,$exerciseid);
	}
	
	public function publishExercise($data){
		if(!$data || !isset($data->id) || !isset($data->visible) || !isset($data->licence) || !isset($data->attribution))
			throw new Exception('Invalid arguments',1000);
		$exerciseid = $data->id;
		if(!$exerciseid)
			throw new Exception('Invalid exercise id',1012);
		
		$status = self::EXERCISE_READY;
		$visible = $data->visible ? 1 : 0;
		$licence = $data->licence;
		$attribution = $data->attribution;
		
		$sql = "UPDATE exercise SET status=%d, visible=%d, licence='%s', attribution='%s'
				WHERE id=%d";
		
		$rowc = $this->conn->_update($sql,$status,$visible,$licence,$attribution,$exerciseid);
		
		//Rows affected returns 0 when the update does not change the row
		//if(!$rowc)
		//	throw new Exception('Invalid exercise id',1012);
		
		return $rowc;
	}
	
	/**
	 * Retrieves the names of the directories in which different kinds of videos are stored
	 */
	private function _getResourceDirectories(){
		$sql = "SELECT prefValue
		FROM preferences
		WHERE (prefName='exerciseFolder' OR prefName='responseFolder' OR prefName='evaluationFolder')
		ORDER BY prefName";
		$result = $this->conn->_multipleSelect($sql);
		if($result){
			$this->evaluationFolder = $result[0] ? $result[0]->prefValue : '';
			$this->exerciseFolder = $result[1] ? $result[1]->prefValue : '';
			$this->responseFolder = $result[2] ? $result[2]->prefValue : '';
		}
	}
	
	/**
	 * Encode the given array using Json
	 *
	 * @param Array $data
	 * @param bool $prettyprint
	 * @return mixed $data
	 */
	private function custom_json_encode($data, $prettyprint=0){
		$data = Zend_Json::encode($data,false);
		$data = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), "UTF-8", "UCS-2BE");'), $data);
		if($prettyprint)
			$data = Zend_Json::prettyPrint($data);
		return $data;
	}
	
	/**
	 * Returns a provided character long random alphanumeric string
	 *
	 * @author Peter Mugane Kionga-Kamau
	 * http://www.pmkmedia.com
	 *
	 * @param int $length
	 * @param boolean $useupper
	 * @param boolean $usenumbers
	 */
	public function str_makerand ($length, $useupper, $usenumbers)
	{
		$key= '';
		$charset = "abcdefghijklmnopqrstuvwxyz";
		if ($useupper)
			$charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($usenumbers)
			$charset .= "0123456789";
		for ($i=0; $i<$length; $i++)
			$key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
			return $key;
	}
}