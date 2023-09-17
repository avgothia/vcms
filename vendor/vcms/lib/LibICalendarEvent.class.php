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

use DateTime;
use DateTimeZone;

class LibICalendarEvent{
	var $uid;
	var $summary;
	var $dtstart;
	var $dtend;
	var $description;
	var $location;
	var $url;
	var $duration;
	var $rrule;

	var $allDay = false;

	function setStartAndEndDateTime($startDateTime, $endDateTime){
		if($startDateTime != '' && $startDateTime != '0000-00-00 00:00:00'){
			if($endDateTime < $startDateTime){
				$endDateTime = '';
			}

			$startYear = (int) substr($startDateTime, 0, 4);
			$startMonth = (int) substr($startDateTime, 5, 2);
			$startDay = (int) substr($startDateTime, 8, 2);
			$startHour = (int) substr($startDateTime, 11, 2);
			$startMinute = (int) substr($startDateTime, 14, 2);
			$startSecond = (int) substr($startDateTime, 17, 2);

			$endYear = (int) substr($endDateTime, 0, 4);
			$endMonth = (int) substr($endDateTime, 5, 2);
			$endDay = (int) substr($endDateTime, 8, 2);
			$endHour = (int) substr($endDateTime, 11, 2);
			$endMinute = (int) substr($endDateTime, 14, 2);
			$endSecond = (int) substr($endDateTime, 17, 2);


			if($startHour == 0 && $startMinute == 0 && $startSecond == 0){ //Startdatum ohne Zeit?
				$this->allDay = true;

				//shortened start date
				$this->dtstart = str_pad($startYear, 4, '0', STR_PAD_LEFT).str_pad($startMonth, 2, '0', STR_PAD_LEFT).str_pad($startDay, 2, '0', STR_PAD_LEFT);

				//end date given?
				if($endDateTime != '' && $endDateTime != '0000-00-00 00:00:00'){
					$endDateTime = new DateTime(substr($endDateTime, 0, 10));
					$endDateTime->modify('+1 day');
					//no change to UTC, as otherwise the day may be shiftet
					//shortened end date
					$this->dtend = $endDateTime->format('Ymd');
				}
				//end date not given
				else{
					//whole day
					$this->duration = 'P1D';
				}
			}
			//start date with time
			else{
				//complete start date
				$startDateTime = new DateTime($startDateTime);
				$startDateTime->setTimezone(new DateTimeZone('UTC'));
				$this->dtstart = $startDateTime->format('Ymd').'T'.$startDateTime->format('His').'Z';

				//end date given?
				if($endDateTime != '' && $endDateTime != '0000-00-00 00:00:00'){
					//end date without time
					if($endHour == 0 && $endMinute == 0 && $endSecond == 0){
						//shortened end date
						$this->dtend = str_pad($endYear, 4, '0', STR_PAD_LEFT).str_pad($endMonth, 2, '0', STR_PAD_LEFT).str_pad($endDay, 2, '0', STR_PAD_LEFT);
					} else {
						//complete end date
						$endDateTime = new DateTime($endDateTime);
						$endDateTime->setTimezone(new DateTimeZone('UTC'));
						$this->dtend = $endDateTime->format('Ymd').'T'.$endDateTime->format('His').'Z';
					}
				}
				//no end date
				else{
					//duration of 2 hours
					$this->duration = 'PT2H';
				}
			}
		}
	}

	function getEvent(){
		$br = chr(13).chr(10); //define line break, RFC 5545 chapter 3.1

		$retstr = '';

		$retstr .= 'BEGIN:VEVENT'.$br;

		if($this->uid){
			$retstr .= 'UID:'.$this->uid.$br;
		}

		if($this->summary){
			$retstr .= $this->format('SUMMARY:'.$this->summary).$br;
		}

		$dateTime = new DateTime('now', new DateTimeZone('UTC'));
		$retstr .= 'DTSTAMP:'.$dateTime->format('Ymd').'T'.$dateTime->format('His').'Z'.$br;

		if($this->dtstart){
			$retstr .= 'DTSTART';

			if($this->allDay){ //because of RFC 5545 chapter 3.8.2.4.
				$retstr .= ';VALUE=DATE';
			}

			$retstr .= ':'.$this->dtstart.$br;
		}

		//either DTEND or DURATION, but not both on the same time, RFC 5545 chapter 3.6.1
		if($this->dtend){
			$retstr .= 'DTEND';

			if($this->allDay){ //because of RFC 5545 chapter 3.8.2.2.
				$retstr .= ';VALUE=DATE';
			}

			$retstr .= ':'.$this->dtend.$br;
		} elseif($this->duration){
			$retstr .= 'DURATION:'.$this->duration.$br;
		}

		if($this->location){
			$retstr .= $this->format('LOCATION:'.$this->location).$br;
		}

		if($this->description){
			$retstr .= $this->format('DESCRIPTION:'.$this->description).$br;
		}

		if($this->url){
			$retstr .= $this->format('URL:'.$this->url).$br;
		}

		if($this->rrule){
			$retstr .= 'RRULE:'.$this->rrule.$br;
		}

		$retstr .= 'END:VEVENT'.$br;
		return $retstr;
	}

	function format($string){
		$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');

		$string = str_replace('\\', '\\\\', $string); //RFC 5545 chapter 3.3.11
		$string = str_replace(',', '\,', $string); //RFC 5545 chapter 3.3.11
		$string = str_replace(';', '\;', $string); //RFC 5545 chapter 3.3.11

		$string = str_replace(array(chr(13).chr(10), chr(10), chr(13)), '\n', $string); // \n is correct!
		$string = wordwrap($string, 73, chr(13).chr(10).'  '); //RFC 5545 chapter 3.1
		return $string;
	}
}