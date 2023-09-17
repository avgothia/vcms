<?php
$moduleName = "Semesterhistorie";
$version = "2.05";
$styleSheet = "";
$installScript = "";
$uninstallScript = "";
$updateScript = "";

$pages[] = new LibPage("semesterhistorie_liste", "scripts/", "history.php", new LibAccessRestriction(array("F", "B", "P", "C", "G", "W", "Y"), ""), "Semesterhistorie");
$menuElementsInternet = array();
$menuElementsIntranet[] = new LibMenuEntry("semesterhistorie_liste", "Semesterhistorie", 8000);
$menuElementsAdministration = array();
$dependencies[] = new LibMinDependency("Dependency zum Login-Modul", "base_internet_login", 1.0);
$dependencies[] = new LibMinDependency("Dependency zum Mitglieds-Modul", "base_intranet_personen", 1.0);
$includes = array();
$headerStrings = array();
?>