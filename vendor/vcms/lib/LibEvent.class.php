<?php
/*
This file is part of VCMS.

VCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

VCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with VCMS. If not, see <http://www.gnu.org/licenses/>.
*/

namespace vcms;

use PDO;

class LibEvent{

	function getEventUrl($id){
		global $libGlobal;

		return $libGlobal->getSiteUrl(). '/index.php?pid=event&id=' .$id;
	}

	function getShareTitle($id){
		global $libConfig, $libDb, $libTime;

		$stmt = $libDb->prepare('SELECT id, datum, titel FROM base_veranstaltung WHERE id=:id');
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$result = $libConfig->verbindungName. ' - ' .$row['titel']. ' am ' .$libTime->formatDateString($row['datum']);
		return $result;
	}

	function getStatusString($status){
		$result = '';

		switch($status){
			case 'o':
				$result = 'offiziell';
				break;
			case 'ho':
				$result = 'hochoffiziell';
				break;
			case '':
				$result = 'inoffiziell';
				break;
			default:
				$result = $status;
		}

		return $result;
	}

	function getTitle($id){
		global $libDb;

		$stmt = $libDb->prepare('SELECT id, datum, titel FROM base_veranstaltung WHERE id=:id');
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$stmt->bindColumn('titel', $title);
		$stmt->fetch();

		return $title;
	}

	function hasBannedTitle($id){
		global $libDb, $libGenericStorage;

		$bannedTitlesString = $libGenericStorage->loadValue('base_core', 'event_banned_titles');
		$bannedTitles = explode(',', $bannedTitlesString);
		$bannedTitlesCleaned = array();

		foreach($bannedTitles as $bannedTitle){
			$bannedTitlesCleaned[] = strtolower(trim($bannedTitle));
		}

		$title = $this->getTitle($id);
		$titleCleaned = strtolower(trim($title));
		$result = in_array($titleCleaned, $bannedTitlesCleaned);
		return $result;
	}

	function isFacebookEvent($row){
		global $libGenericStorage;

		$facebookAppid = $libGenericStorage->loadValue('base_core', 'facebook_appid');
		$facebookSecretKey = $libGenericStorage->loadValue('base_core', 'facebook_secret_key');

		$result = isset($row['fb_eventid']) && is_numeric($row['fb_eventid'])
			&& ini_get('allow_url_fopen') && $facebookAppid != '' && $facebookSecretKey != '';
		return $result;
	}

	function printFacebookShareButton($id){
		$url = $this->getEventUrl($id);
		$title = $this->getShareTitle($id);

		echo '<a href="http://www.facebook.com/share.php?u=' .rawurlencode($url). '&amp;title=' .rawurlencode($title). '" rel="nofollow">';
		echo '<i class="fa fa-facebook-official fa-lg hvr-pop" aria-hidden="true"></i>';
		echo '</a> ';
	}

	function printTwitterShareButton($id){
		$url = $this->getEventUrl($id);
		$title = $this->getShareTitle($id);

		echo '<a href="http://twitter.com/share?url=' .rawurlencode($url). '&amp;text=' .rawurlencode($title). '" rel="nofollow">';
		echo '<i class="fa fa-twitter-square fa-lg hvr-pop" aria-hidden="true"></i>';
		echo '</a> ';
	}

	function printWhatsAppShareButton($id){
		$url = $this->getEventUrl($id);
		$title = $this->getShareTitle($id);

		echo '<a href="whatsapp://send?text=' .rawurlencode($title. ' ' .$url). '" rel="nofollow">';
		echo '<i class="fa fa-whatsapp fa-lg hvr-pop" aria-hidden="true"></i>';
		echo '</a> ';
	}

	function getEventSchema($row){
		global $libGlobal, $libTime;

		$result = array();

		$result['@context'] = 'http://schema.org';
		$result['@type'] = 'SocialEvent';
		$result['name'] = $row['titel'];
		$result['url'] = $this->getEventUrl($row['id']);

		if($row['beschreibung'] != ''){
			$result['description'] = $row['beschreibung'];
		}

		$result['startDate'] = $libTime->formatUtcString($row['datum']);

		if($row['datum_ende'] != ''){
			$result['endDate'] = $libTime->formatUtcString($row['datum_ende']);
		}

		$address = array();
		$address['@type'] = 'Place';

		if($row['ort'] != ''){
			$address['name'] = $row['ort'];
			$address['address'] = $row['ort'];
		} else {
			$address['name'] = 'a.d.H.';
			$address['address'] = 'a.d.H.';
		}

		$result['location'] = $address;

		return $result;
	}
}
