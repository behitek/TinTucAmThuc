<?php
defined('_JEXEC') OR die('Access Denied!');
### © 2006-2016 Joobi Limited. All rights reserved.
### license GNU GPLv3 , link https://joobi.co

jimport('joomla.plugin.plugin');


/**
 * Plugin to sync user with jNews
 */
class plgUserjNewssyncuser extends JPlugin {

	var $oldUser = null;
	
	function onBeforeStoreUser($user, $isnew){
		$this->oldUser = $user;
		return true;
	}

	//joomla 16
	function onUserAfterSave( $user, $isnew, $success, $msg ) {
		return $this->onAfterStoreUser($user, $isnew, $success, $msg);
	}
	
	//joomla 15
	function onAfterStoreUser($user, $isnew, $success, $msg){
		if($success===false) return false;
		
		if ( strtolower( substr( JPATH_ROOT, strlen(JPATH_ROOT)-13 ) ) =='administrator' ) {
			$adminPath = strtolower( substr( JPATH_ROOT, strlen(JPATH_ROOT)-13 ) );
		} else {
			$adminPath = JPATH_ROOT;
		}

		if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );
		if ( !@include_once( $adminPath .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jnews'.DIRECTORY_SEPARATOR.'defines.php') ) return;
		include_once( JNEWSPATH_CLASS . 'class.jnews.php');
		require_once(JNEWS_JPATH_ROOT_NO_ADMIN .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.JNEWS_OPTION.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.subscribers.php');
		require_once(JNEWS_JPATH_ROOT_NO_ADMIN .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.JNEWS_OPTION.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.listssubscribers.php');
		
		jimport( 'joomla.html.parameter' );
		$plugin = JPluginHelper::getPlugin('user', 'jnewssyncuser');
		$registry = new JRegistry;
		  if( ! method_exists($registry,'loadString')) {
            $data = trim($plugin->params);
            $options = array('processSections' => false);
            if ((substr($data, 0, 1) != '{') && (substr($data, -1, 1) != '}'))
            {
                    $ini = JRegistryFormat::getInstance('INI');
                    $obj = $ini->stringToObject($data, $options);
            }
            else
            {
                    $obj = json_decode($data);
            }
            $registry->loadObject($obj);	
        } else {
              $registry->loadString( $plugin->params );
        }
      
		$params = $registry;

		$db = JFactory::getDBO();
		$subscriber = new stdClass;
		
		$confirmed = 1;
		if($user['block']) $confirmed = 0;

		$subscriber->email = trim(strip_tags($user['email']));
		if(!empty($user['name'])) $subscriber->name = trim(strip_tags($user['name']));
		if(empty($user['block'])) $subscriber->confirmed = 1;
		$subscriber->user_id = $user['id'];
		$subscriber->ip = jNews_Subscribers::getIP();
		$subscriber->receive_html = 1;
		$subscriber->confirmed = $confirmed;
		$subscriber->subscribe_date = time();
		$subscriber->language_iso = 'eng';
		$subscriber->timezone = '00:00:00';
		$subscriber->blacklist = 0;

		//check if the version of jnews is pro
		if($GLOBALS[JNEWS.'level'] > 2){
			$subscriber->column1='';
			$subscriber->column2='';
			$subscriber->column3='';
			$subscriber->column4='';
			$subscriber->column5='';
		}//end if check if the version is pro

		if(!$isnew AND !empty($this->oldUser['email']) AND $user['email'] != $this->oldUser['email']){
			$d['email']=$this->oldUser['email'];
			$infos=jNews_Subscribers::getSubscriberIdFromEmail($this->oldUser);
			$subscriber->id = $infos['subscriberId'];
		}

		if($isnew){ //new registered user

			$status= jNews_Subscribers::saveSubscriber($subscriber,$subscriber->id,true);

			if(empty($subscriber->id)){
				$subscriber->id =jNews_Subscribers::getSubscriberIdFromUserId($subscriber->user_id);
			}

			if(!($status)) return;
			$listsToSubscribe = $params->get('lists','');

			if(!empty($listsToSubscribe)) {
				$condition= ' WHERE `id` IN ('.$listsToSubscribe.')' ;
			}else{
				$condition=' WHERE `auto_add` > 0';
			}

			//get list ids of auto_add lists
			$query='SELECT `id`, `list_type`, `params` from `#__jnews_lists`'.$condition ;
			$db->setQuery($query);
			$autoListId=$db->loadObjectList();
			$error = $db->getErrorMsg();

			if (!empty($error)){
				echo  $error;
				return false;
			}else{

				//use for masterlists
				$listsA = array();

				foreach($autoListId as $autoId){

					if(!empty($autoId->params)){
						//use for masterlists
						$listsA[] = $autoId->id;
					}else{
						//for non-masterlists
						$subscriber->list_id=$autoId->id;
						jNews_ListsSubs::saveToListSubscribers($subscriber);
					}

					if($autoId->list_type == 2) {
						$subscribe = array();
						$subscribe[] = $autoId->id;
						if(!empty($subscribe)) jNews_ListsSubs::subscribeARtoQueue( $subscriber->id, $subscribe );
					}

				}//end of foreach

			}

			if( !empty($listsA) ){

			    //we check if the social class file exists for the implementation of master lists
				if ( @include_once( JNEWSPATH_ADMIN . 'social' .DIRECTORY_SEPARATOR. 'class.social.php' ) ) {
				if(class_exists('social')){

					$listidSubsA = array();
					$masterListSubscriber = new stdClass;

					//we check if configuration for master lists is enabled
					if( $GLOBALS[JNEWS.'use_masterlists'] ){

						if( ($GLOBALS[JNEWS.'level'] > 1) ){

							//we validate if the user can be subscribed to the list then we return the masterlistid
							//1 - MasterLists for all Potential Users
							$listidSubsA[] = jNews_Social::includeMasterListIds($subscriber->id,1,$listsA);
							//2 - MasterLists for all Registered Subscribers
							$listidSubsA[] = jNews_Social::includeMasterListIds($subscriber->id,2,$listsA);
						}

						if( ($GLOBALS[JNEWS.'level'] > 2) ){
							//we validate if the user can be subscribed to the list then we return the masterlistid
							//3 - MasterLists for all Front-end Subscribers
							$listidSubsA[] = jNews_Social::includeMasterListIds($subscriber->id,3,$listsA);
						}
					}

					$masterListSubscriber->id = $subscriber->id;
					$masterListSubscriber->list_id = $listidSubsA;
					jNews_ListsSubs::saveToListSubscribers($masterListSubscriber);

				}
				}

			}

		}else{ //confirmed registered user
//			if(!empty($this->oldUser['block']) AND !empty($subscriber->confirmed)){
			   if(empty($subscriber->id)) $subscriber->id =jNews_Subscribers::getSubscriberIdFromUserId($subscriber->user_id);
				plgUserjNewssyncuser::_confirmUserSubscription($subscriber->id);
                           if($isnew === false AND $success === true)
                           {
                                $status= jNews_Subscribers::saveSubscriber($subscriber,$subscriber->id);
                           }
//			}
		}//endelse

		return true;
	}
	
	function onUserAfterDelete($user, $success, $msg){
		return $this->onAfterDeleteUser($user, $success, $msg);
	}

	function onAfterDeleteUser($user, $success, $msg){
		if($success===false) return false;

		if ( strtolower( substr( JPATH_ROOT, strlen(JPATH_ROOT)-13 ) ) =='administrator' ) {
			$adminPath = strtolower( substr( JPATH_ROOT, strlen(JPATH_ROOT)-13 ) );
		} else {
			$adminPath = JPATH_ROOT;
		}

		if ( ! defined( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );
		if ( !@include_once( $adminPath .DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_jnews'.DIRECTORY_SEPARATOR.'defines.php') ) return;
		require_once(JNEWS_JPATH_ROOT_NO_ADMIN .DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.JNEWS_OPTION.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'class.subscribers.php');
		include_once( JNEWSPATH_CLASS . 'class.jnews.php');

		$d['email'] = $user['email'];
		$infos=jNews_Subscribers::getSubscriberIdFromEmail($d);
		$subscriberid= $infos['subscriberId'];

		if(!empty($subscriberid)){
			jNews_Subscribers::deleteSubscriber($subscriberid);
		}

		return true;
	}

/**
	 * Confirmed the subscriber
	 */
	 function _confirmUserSubscription($subscriberId) {
		$db = JFactory::getDBO();
		$status = false;
		if(empty($subscriberId)) return false;

		$query = 'UPDATE `#__jnews_subscribers` SET `confirmed` = 1 WHERE `id`=' . (int)$subscriberId;
	 	$db->setQuery($query);
		$status=$db->query();
		if($status){
			$query = 'UPDATE `#__jnews_queue` SET `suspend`= 0 WHERE `subscriber_id`=' . (int)$subscriberId;
			$db->setQuery($query);
			$status=$db->query();
		}
		return $status;

    }

}
