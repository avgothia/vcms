<?php/*This file is part of VCMS.This program is free software; you can redistribute it and/ormodify it under the terms of the GNU General Public Licenseas published by the Free Software Foundation; either version 2of the License, or (at your option) any later version.This program is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See theGNU General Public License for more details.You should have received a copy of the GNU General Public Licensealong with this program; if not, write to the Free SoftwareFoundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA*/if(!is_object($libGlobal) || !$libAuth->isLoggedin())	exit();require_once("lib/own/mime.class.php");//Dieser Teil löscht einen vorhandenen dump$dumpath = $_SERVER["DOCUMENT_ROOT"]."/temp/datenbankdump.sql.gz";@unlink($dumpath);$host = $libConfig->mysqlServer; $user = $libConfig->mysqlUser; $pass = $libConfig->mysqlPass; $db = $libConfig->mysqlDb; $port = $libConfig->mysqlPort; // Befehl bauen$sql1 = sprintf('mysqldump --opt -h %s ', $host);if($port != "")	$sql2 = sprintf(' -P %s ', $port);else	$sql2 = "";  	$sql3 = sprintf(' -u %s -p%s %s | gzip > %s', $user, $pass, $db, $dumpath);//Befehl ausführensystem($sql1.$sql2.$sql3); 	//Header sendenheader("Pragma: public");header("Expires: 0");header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); header("Content-Type: application/octet-stream");header("Content-Type: application/force-download");header("Content-Type: application/download");header('Content-Disposition: attachment; filename="datenbankdump.sql.gz"');header("Content-Transfer-Encoding: binary");readfile($dumpath);unlink($dumpath);?>