<?php
$moduleName = "Semesterprogramm";
$version = "2.18";
$styleSheet = "custom/style.css";
$installScript = "";
$uninstallScript = "";
$updateScript = "";

$ar = new LibAccessRestriction(array("F", "B", "P"), "");

$pages[] = new LibPage("semesterprogramm_calendar", "scripts/", "calendar.php", "", "Semesterprogramm");
$pages[] = new LibPage("semesterprogramm_event", "scripts/", "event.php", "", "Veranstaltung");
$pages[] = new LibPage("semesterprogramm_admin_galerienliste", "scripts/admin/", "gallery_list.php", $ar, "Foto-Verwaltung");
$pages[] = new LibPage("semesterprogramm_admin_galerie", "scripts/admin/", "gallery.php", $ar, "Galerie");
$menuElementsInternet[] = new LibMenuEntry("semesterprogramm_calendar", "Veranstaltungen und Fotos", 200);
$menuElementsIntranet[] = new LibMenuEntry("semesterprogramm_admin_galerienliste", "Foto-Verwaltung", 30000);
$menuElementsAdministration = array();
$dependencies[] = new LibMinDependency("Dependency zum Login-Modul", "base_internet_login", 1.0);
$includes[] = new LibInclude("semesterprogramm_icalendar", "scripts/", "icalendar.php", "");
$includes[] = new LibInclude("semesterprogramm_picture", "scripts/", "picture.php", "");
$includes[] = new LibInclude("semesterprogramm_admin_galerie_upload", "scripts/admin/", "gallery_upload.php", $ar);
$headerStrings[] = '<script type="text/javascript" src="js/highslide/highslide.js"></script>';
$headerStrings[] = '<link rel="stylesheet" type="text/css" href="js/highslide/highslide.css" />';
$headerStrings[] = '<link rel="stylesheet" type="text/css" href="js/fileuploader/fileuploader.css" />';
$headerStrings[] = '<script type="text/javascript">
hs.graphicsDir = \'js/highslide/graphics/\';
hs.align = \'center\';
hs.transitions = [\'expand\', \'crossfade\'];
hs.wrapperClassName = \'dark borderless floating-caption\';
hs.fadeInOut = true;
hs.dimmingOpacity = 0.75;
hs.showCredits = false;
// Add the controlbar
if (hs.addSlideshow) hs.addSlideshow({
	interval: 3000,
	repeat: false,
	useControls: true,
	fixedControls: \'fit\',
	overlayOptions: {
		opacity: .6,
		position: \'bottom center\',
		hideOnMouseOut: true
	},
	thumbstrip: {
		position: \'above\',
		mode: \'horizontal\',
		relativeTo: \'expander\'
	}

});
</script>';
?>