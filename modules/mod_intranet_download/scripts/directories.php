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

use vcms\filesystem\Folder;

if (!is_object($libGlobal) || !$libAuth->isLoggedin())
    exit();


if (!$libGenericStorage->attributeExistsInCurrentModule('preselect_rights')) {
    $libGenericStorage->saveValueInCurrentModule('preselect_rights', 1);
}


if (!isset($_SESSION['openFolders']) || !is_array($_SESSION['openFolders'])) {
    $_SESSION['openFolders'] = array();
}

$rootFolderPathString = 'custom/intranet/downloads';
$rootFolderAbsolutePathString = $libFilesystem->getAbsolutePath($rootFolderPathString);

/*
* pre scan actions
*/
foreach ($libAuth->getAemter() as $amt) {
    if (!is_dir($rootFolderAbsolutePathString . '/' . $amt)) {
        mkdir($rootFolderAbsolutePathString . '/' . $amt);
    }
}

if (isset($_GET['aktion']) && $_GET['aktion'] == 'open') {
    $_SESSION['openFolders'][$_GET['hash']] = 1;
} elseif (isset($_GET['aktion']) && $_GET['aktion'] == 'close') {
    unset($_SESSION['openFolders'][$_GET['hash']]);
}


$rootFolderObject = new \vcms\filesystem\Folder('', '/', $rootFolderAbsolutePathString);
$hashes = $rootFolderObject->getHashMap();

/*
* actions
*/

//delete file
if (isset($_GET['aktion']) && $_GET['aktion'] == 'delete' && isset($_GET['hash'])) {
    $element = $hashes[$_GET['hash']];

    if (in_array($element->owningAmt, $libAuth->getAemter())) {
        $element->delete();
        $libGlobal->notificationTexts[] = 'Das Element wurde gelöscht.';
    } else {
        $libGlobal->errorTexts[] = 'Du hast keine Löschberechtigung.';
    }
} //upload file
elseif (isset($_POST['aktion']) && $_POST['aktion'] == 'upload' && isset($_POST['hash'])) {
    $folder = $hashes[$_POST['hash']];

    if (in_array($folder->owningAmt, $libAuth->getAemter())) {
        if (isset($_POST['gruppen']) && count($_POST['gruppen']) > 0) {
            if ($_FILES['datei']['tmp_name'] != '') {
                $groupArray = array_merge($_POST['gruppen'], array($libAuth->getGruppe()));
                $folder->addFile($_FILES['datei']['tmp_name'], $_FILES['datei']['name'], $groupArray);
                $libGlobal->notificationTexts[] = 'Die Datei wurde hochgeladen.';
                $rootFolderObject->scanFileSystem();
            }
        } else {
            $libGlobal->errorTexts[] = 'Du hast keine Gruppe mit Leseberechtigung ausgewählt.';
        }
    } else {
        $libGlobal->errorTexts[] = 'Du darfst die Datei nicht in diesen Ordner hochladen.';
    }
} // new folder
elseif (isset($_POST['aktion']) && $_POST['aktion'] == "newfolder" && isset($_POST['hash'])) {
    $folder = $hashes[$_POST['hash']];

    if (in_array($folder->owningAmt, $libAuth->getAemter())) {
        $folder->addFolder($_POST['foldername']);
        $libGlobal->notificationTexts[] = 'Der Ordner wurde angelegt.';
    } else {
        $libGlobal->errorTexts[] = 'Du darfst in diesem Ordner keinen Unterordner anlegen.';
    }
}


/*
* output
*/
echo '<h1>Dateien</h1>';

echo $libString->getErrorBoxText();
echo $libString->getNotificationBoxText();

$currentFolder = $rootFolderObject;
if (isset($_GET['hash'])) {
    $currentFolder = findInNestedFolderElements($rootFolderObject, $_GET['hash']);
}

echo '<div class="row">';
echo '<ol class="breadcrumb">';
echo '<li><a href="index.php?pid=intranet_directories">Dateien</a></li>';
if ($currentFolder !== $rootFolderObject && $currentFolder != null) {
    $nestingFolder = $currentFolder->nestingFolder;
    while ($nestingFolder !== $rootFolderObject) {
        echo '<li><a href="index.php?pid=intranet_directories&amp;aktion=open&amp;hash=' . $nestingFolder->getHash() . '">' . $nestingFolder->name . '</a></li>';
        $nestingFolder = $nestingFolder->nestingFolder;
    }
    echo '<li class="active">' . $currentFolder->name . '</li>';
}
echo '</ol>';
displayFolderContents($currentFolder);

echo '</div>';


if (!empty($libAuth->getAemter())) {
    /*
    * upload form
    */
    echo '<h2>Datei hochladen</h2>';

    echo '<div class="panel panel-default">';
    echo '<div class="panel-body">';
    echo '<form action="index.php?pid=intranet_directories" method="post" enctype="multipart/form-data" class="form-horizontal">';
    echo '<fieldset>';
    echo '<input type="hidden" name="aktion" value="upload" />';

    echo '<div class="form-group">';
    echo '<label for="hash" class="col-sm-3 control-label">in den Ordner</label>';
    echo '<div class="col-sm-3"><select name="hash" class="form-control">';

    foreach ($rootFolderObject->getNestedFoldersRec() as $folderElement) {
        if (in_array($folderElement->owningAmt, $libAuth->getAemter())) {
            echo '<option value="' . $folderElement->getHash() . '">' . $folderElement->name . '</option>';
        }
    }

    echo '</select></div>';
    echo '</div>';


    echo '<div class="form-group">';
    echo '<label class="col-sm-3 control-label">mit Leserecht für</label>';
    echo '<div class="col-sm-9">';

    $stmt = $libDb->prepare("SELECT * FROM base_gruppe ORDER BY bezeichnung");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['bezeichnung'] != "X" && $row['bezeichnung'] != "T" && $row['bezeichnung'] != "V") {
            echo '<div class="checkbox"><label><input type="checkbox" name="gruppen[]" value="' . $row['bezeichnung'] . '"';

            if ($libGenericStorage->loadValueInCurrentModule('preselect_rights') == 1) {
                echo 'checked="checked"';
            }

            echo '/>';
            echo $row['bezeichnung'] . ' - ' . $row['beschreibung'];
            echo '</label></div>';
        }
    }

    echo '</div></div>';

    echo '<div class="form-group">';
    echo '<div class="col-sm-offset-3 col-sm-3">';
    echo '<label class="btn btn-default btn-file"><i class="fa fa-upload" aria-hidden="true"></i> Datei hochladen';
    echo '<input type="file" name="datei" onchange="this.form.submit()" style="display:none">';
    echo '</label>';
    echo '</div>';
    echo '</div>';

    echo '</fieldset>';
    echo '</form>';
    echo '</div>';
    echo '</div>';


    /*
    * new folder form
    */
    echo '<h2>Ordner anlegen</h2>';

    echo '<div class="panel panel-default">';
    echo '<div class="panel-body">';
    echo '<form action="index.php?pid=intranet_directories" method="post" class="form-horizontal">';
    echo '<fieldset>';
    echo '<input type="hidden" name="aktion" value="newfolder" />';

    echo '<div class="form-group">';
    echo '<label for="foldername" class="col-sm-3 control-label">Neuen Ordner</label>';
    echo '<div class="col-sm-3"><input type="text" id="foldername" name="foldername" class="form-control" /></div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="hash" class="col-sm-3 control-label">in Ordner</label>';
    echo '<div class="col-sm-3"><select name="hash" class="form-control">';

    foreach ($rootFolderObject->getNestedFoldersRec() as $folderElement) {
        if (in_array($folderElement->owningAmt, $libAuth->getAemter())) {
            echo '<option value="' . $folderElement->getHash() . '">' . $folderElement->name . '</option>';
        }
    }

    echo '</select></div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<div class="col-sm-offset-3 col-sm-3">';
    echo '<button type="submit" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i> anlegen</button>';
    echo '</div>';
    echo '</div>';

    echo '</form>';
    echo '</div>';
    echo '</div>';
}


/*
* functions
*/

function displayFolderContents(Folder &$folder): void
{
    global $libAuth;

    if (!$folder->hasNestedFolderElements()) {
        echo '<p class="text-center text-muted>Dieser Ordner ist leer</p>';
        return;
    }

    echo '<table class="table table-hover">';
    echo '<thead><tr>';
    echo '<th class="">Datei</th>';
    echo '<th class=""><i class="fa fa-users"aria-hidden="true"></i></th>';
    echo '<th class=""><i class="fa fa-hdd-o" aria-hidden="true"></i></th>';
    echo '<th class=""></th>';
    echo '</tr></thead><tbody>';

    usort($folder->nestedFolderElements, fn($a, $b) => strcmp($a->type, $b->type));
    foreach ($folder->nestedFolderElements as $folderElement) {
        echo '<tr>';
        if ($folderElement->type == 1) { // folder
            echo '<td class="col-xs-5 col-md-7"><a href="index.php?pid=intranet_directories&amp;aktion=open&amp;hash=' . $folderElement->getHash() . '"><i class="fa fa-lg fa-fw fa-folder-o" aria-hidden="true"></i>' . $folderElement->name . '</a></td>';
            echo '<td class="col-xs-2 col-md-1"></td>';
            echo '<td class="col-xs-2 col-md-1"></td>';
            if ($folderElement->isDeleteable() && in_array($folderElement->owningAmt, $libAuth->getAemter())) {
                echo '<td class="col-xs-1"><a href="index.php?pid=intranet_directories&amp;aktion=delete&amp;hash=' . $folderElement->getHash() . '" onclick="return confirm(\'Willst Du den Ordner wirklich löschen?\')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
            }
            echo '<td class="col-xs-1"></td>';
        } elseif ($folderElement->type == 2 && in_array($libAuth->getGruppe(), $folderElement->readGroups)) { // file
            echo '<td class="col-xs-5 col-md-7"><a href="api.php?iid=intranet_download&amp;hash=' . $folderElement->getHash() . '">' . getIconForFolder($folderElement) . $folderElement->getFileName() . '</a></td>';
            echo '<td class="col-xs-2 col-md-1"><span class="text-muted">' . implode('', $folderElement->readGroups) . '</span></td>';
            echo '<td class="col-xs-2 col-md-1"><span class="text-muted">' . getSizeString($folderElement->getSize()) . '</span></td>';
            if (in_array($folderElement->owningAmt, $libAuth->getAemter())) {
                echo '<td class="col-xs-1"><a href="index.php?pid=intranet_directories&amp;aktion=delete&amp;hash=' . $folderElement->getHash() . '" onclick="return confirm(\'Willst Du die Datei wirklich löschen?\')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
            }
        }
        echo '</tr>';
    }
    echo '</tbody></table>';
}

function getSizeString($size): string
{
    if ($size > 1000000) {
        return round($size / 1000000, 1) . ' MB';
    } else {
        return round($size / 1000, 0) . ' KB';
    }
}

function getIconForFolder(\vcms\filesystem\File $file): string
{
    switch ($file->getExtension()) {
        case 'doc':
        case 'docx':
            return '<i class="fa fa-lg fa-fw fa-file-word-o" aria-hidden="true"></i>';
        case 'xls':
        case 'xlsx':
            return '<i class="fa fa-lg fa-fw fa-file-excel-o" aria-hidden="true"></i>';
        case 'ppt':
        case 'pptx':
            return '<i class="fa fa-lg fa-fw fa-file-powerpoint-o" aria-hidden="true"></i>';
        case 'pdf':
            return '<i class="fa fa-lg fa-fw fa-file-pdf-o" aria-hidden="true"></i>';
        case 'cdr':
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'png':
        case 'svg':
            return '<i class="fa fa-lg fa-fw fa-file-image-o" aria-hidden="true"></i>';
        case 'txt':
            return '<i class="fa fa-lg fa-fw fa-file-text-o" aria-hidden="true"></i>';
        case 'aac':
        case 'mp3':
        case 'wav':
            return '<i class="fa fa-lg fa-fw fa-file-audio-o" aria-hidden="true"></i>';
        case 'mp4':
        case 'xvid':
            return '<i class="fa fa-lg fa-fw fa-file-video-o" aria-hidden="true"></i>';
        case 'html':
        case 'htm':
        case 'css':
            return '<i class="fa fa-lg fa-fw fa-file-code-o" aria-hidden="true"></i>';
        default:
            return '<i class="fa fa-lg fa-fw fa-file-o" aria-hidden="true"></i>';
    }
}

function findInNestedFolderElements(Folder &$topLevelFolder, ?string $hash): ?Folder
{
    if ($hash == null) return null;

    foreach ($topLevelFolder->getNestedFolderElements() as $nested) {
        if ($nested->type != 1) continue;

        if ($nested->getHash() == $hash) {
            return $nested;
        }

        $innerNested = findInNestedFolderElements($nested, $hash);
        if ($innerNested != null) {
            return $innerNested;
        }
    }

    return null;
}