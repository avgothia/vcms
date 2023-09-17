<?php
/*
This file is part of VCMS.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

if(!is_object($libGlobal))
	exit();
?>
<h1>Semesterprogramm <?php echo $libTime->getSemesterString($libGlobal->semester); ?></h1>

<?php
require("lib/gallery.class.php");
$libGallery = new LibGallery();

$stmt = $libDb->prepare("SELECT DATE_FORMAT(datum,'%Y-%m-01') AS datum FROM base_veranstaltung GROUP BY datum ORDER BY datum DESC");
$stmt->execute();

$daten = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$daten[] = $row['datum'];
}

echo $libTime->getSemesterMenu($libTime->getSemestersFromDates($daten), $libGlobal->semester);
?>

<p>Das aktuelle Semesterprogramm kann als <a href="webcal://<?php echo $libConfig->sitePath; ?>/inc.php?iid=semesterprogramm_icalendar"><img src="<?php echo $libModuleHandler->getModuleDirectory(); ?>img/ical.png" alt="ical" width="12" /> iCalendar-Datei</a> in ein Kalenderprogramm wie z. B. Outlook oder iCal importiert werden.</p>
<div class="vcalendar">
<?php
//access level for galleries
if($libAuth->isLoggedin()){
	$level = 1;
} else {
	$level = 0;
}

$zeitraum = $libTime->getZeitraum($libGlobal->semester);
$calendar = new LibCalendar($zeitraum[0], $zeitraum[1]);

$stmt = $libDb->prepare("SELECT * FROM base_veranstaltung WHERE (DATEDIFF(datum, :startdatum1) >= 0 AND DATEDIFF(datum, :startdatum2) <= 0) OR (DATEDIFF(datum_ende, :enddatum1) >= 0 AND DATEDIFF(datum_ende, :enddatum2) <= 0) ORDER BY datum");
$stmt->bindValue(':startdatum1', $zeitraum[0]);
$stmt->bindValue(':startdatum2', $zeitraum[1]);
$stmt->bindValue(':enddatum1', $zeitraum[0]);
$stmt->bindValue(':enddatum2', $zeitraum[1]);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	//build event
	$event = new LibEvent($row['datum']);
	$event->setId($row['id']);
	$event->setLocation($row['ort']);
	$event->setSummary($row['titel']);
	$event->setLinkUrl('index.php?pid=semesterprogramm_event&amp;eventid=' .$row['id']);
	$event->setTimeStyle(1);
	$event->setStatus($row['status']);

	if(substr($row['datum'], 11, 8) == "00:00:00"){
		$event->isAllDay(true);
	}

	if($row['datum_ende'] != '' && $row['datum_ende'] != '0000-00-00 00:00:00'){
		$event->setEndDateTime($row['datum_ende']);
	}

	$description = "";

	if($libGallery->hasPictures($row['id'], $level)){
		$event->setImageUrl('inc.php?iid=semesterprogramm_picture&amp;eventid='.$row['id'].'&amp;pictureid=' .$libGallery->getFirstVisiblePictureId($row['id'],$level). '&amp;thumb=1');
	}

	$stmt2 = $libDb->prepare("SELECT COUNT(*) AS number FROM base_veranstaltung_teilnahme WHERE person=:person AND veranstaltung=:veranstaltung");
	$stmt2->bindValue(':person', $libAuth->getId(), PDO::PARAM_INT);
	$stmt2->bindValue(':veranstaltung', $row['id'], PDO::PARAM_INT);
	$stmt2->execute();
	$stmt2->bindColumn('number', $anzahl);
	$stmt2->fetch();

	if($libAuth->isloggedin() == true && $anzahl > 0){
		$event->isAttended(true);
		$event->attendedImageUrl = $libModuleHandler->getModuleDirectory().'img/angemeldet.png';
	}

	$event->setDescription($description);

	$calendar->addEvent($event);
}

echo $calendar->toString();
?>
</div>