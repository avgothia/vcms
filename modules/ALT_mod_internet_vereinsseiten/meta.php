<?php
$moduleName = "Verbindung";
$version = "1.0";
$styleSheet = "";
$installScript = "";
$uninstallScript = "";
$updateScript = "";

$pages[] = new LibPage("v_ueberuns","custom/","wirueberuns.html","");
$pages[] = new LibPage("v_aktivitaeten","custom/","aktivitaeten.html","");
$pages[] = new LibPage("v_haus","custom/","haus.html","");
$pages[] = new LibPage("v_geschichte","custom/","geschichte.html","");
$pages[] = new LibPage("v_prinzipien","custom/","prinzipien.html","");
$pages[] = new LibPage("v_symbole","custom/","symbole.html","");

$menuFolder = new LibMenuFolder("v_ueberuns","Verbindung",600);
$menuFolder->addElement(new LibMenuEntry("v_ueberuns","Wir über uns",100));
$menuFolder->addElement(new LibMenuEntry("v_aktivitaeten","Aktivitäten",200));
$menuFolder->addElement(new LibMenuEntry("v_haus","Zimmer / Haus",300));
$menuFolder->addElement(new LibMenuEntry("v_geschichte","Geschichte",400));
$menuFolder->addElement(new LibMenuEntry("v_prinzipien","Prinzipien",500));
$menuFolder->addElement(new LibMenuEntry("v_symbole","Symbole",600));
$menuElementsInternet[] = $menuFolder;
$menuElementsIntranet = array();
$menuElementsAdministration = array();
$dependencies = array();
$includes = array();
$headerStrings = array();
?>