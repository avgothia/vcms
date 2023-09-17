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

if(!$libGenericStorage->attributeExistsInCurrentModule('datenschutzbeauftragter')){
	$libGenericStorage->saveValueInCurrentModule('datenschutzbeauftragter', '');
}

if(!$libGenericStorage->attributeExistsInCurrentModule('datenschutz_email')){
	$libGenericStorage->saveValueInCurrentModule('datenschutz_email', '');
}

$datenschutzbeauftragter = $libGenericStorage->loadValueInCurrentModule('datenschutzbeauftragter');
$email = $libGenericStorage->loadValueInCurrentModule('datenschutz_email');

if($email == ''){
	$email = $libConfig->emailInfo;
}
?>

<h1>Datenschutzerklärung</h1>

<p class="mb-4">Wir freuen uns über Ihr Interesse an unserer Homepage. Der Schutz Ihrer Privatsphäre ist für uns sehr wichtig. Die Nutzung unserer Webseite (<?php echo $libGlobal->getSiteUrl(); ?>) ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unserer Seite personenbezogene Daten (beispielsweise Name, Anschrift, E-Mail-Adressen, im Rahmen einer Kontaktaufnahme oder Registrierung) doch erhoben werden sollte, erfolgt dies, soweit es uns möglich ist, nur auf freiwilliger Basis (vgl. § 13 TMG). Persönliche Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben. Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich. Der Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien durch die Benutzung der Kontaktdaten, die im Rahmen der Erfüllung der Impressumspflicht gem. § 5 TMG preisgegeben werden, wird hiermit ausdrücklich widersprochen. Wir behalten uns ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails (insbesondere Newsletter), vor.</p>

<h2>Name und Kontaktdaten des für die Verarbeitung Verantwortlichen</h2>

<p class="mb-4">Diese Datenschutz-Information gilt für die Datenverarbeitung durch:</p>

<p class="mb-4">Verantwortlicher:
<?php
echo $libConfig->verbindungName .', '. $libConfig->verbindungStrasse .', '. $libConfig->verbindungPlz .' '. $libConfig->verbindungOrt;

if($libConfig->verbindungLand){
	echo ', '. $libConfig->verbindungLand;
}
?>
</p>

<p class="mb-4">Datenschutzbeauftragter:
<?php
if($datenschutzbeauftragter != ''){
	echo $datenschutzbeauftragter .', ';
}

echo $email; ?>
</p>

<h2>Erhebung und Speicherung personenbezogener Daten sowie Art und Zweck von deren Verwendung</h2>

<h3>a) Beim Besuch der Website</h3>

<p class="mb-4">Beim Aufrufen unserer Website <?php echo $libGlobal->getSiteUrl(); ?> werden durch den auf Ihrem Endgerät zum Einsatz kommenden Browser automatisch Informationen an den Server unserer Website gesendet. Diese Informationen werden temporär in einem sog. Logfile gespeichert. Folgende Informationen werden dabei ohne Ihr Zutun erfasst und bis zur automatisierten Löschung gespeichert:</p>

<ul>
	<li>IP-Adresse des anfragenden Rechners,</li>
	<li>Datum und Uhrzeit des Zugriffs,</li>
	<li>Name und URL der abgerufenen Datei,</li>
	<li>Website, von der aus der Zugriff erfolgt (Referrer-URL),</li>
	<li>verwendeter Browser und ggf. das Betriebssystem Ihres Rechners</li>
</ul>

<p class="mb-4">Die genannten Daten werden durch uns zu folgenden Zwecken verarbeitet:</p>

<ul>
	<li>Gewährleistung eines reibungslosen Verbindungsaufbaus der Website,</li>
	<li>Gewährleistung einer komfortablen Nutzung unserer Website,</li>
	<li>Auswertung der Systemsicherheit und -stabilität sowie</li>
	<li>zu weiteren administrativen Zwecken.</li>
</ul>

<p class="mb-4">Die Rechtsgrundlage für die Datenverarbeitung ist Art. 6 Abs. 1 S. 1 lit. f DSGVO. Unser berechtigtes Interesse folgt aus oben aufgelisteten Zwecken zur Datenerhebung. In keinem Fall verwenden wir die erhobenen Daten zu dem Zweck, Rückschlüsse auf Ihre Person zu ziehen. Darüber hinaus setzen wir beim Besuch unserer Website Cookies ein. Nähere Erläuterungen dazu erhalten Sie unter Punkt "Cookies" dieser Datenschutzerklärung.</p>

<h3>b)  Bei Nutzung unseres Kontaktformulars</h3>

<p class="mb-4">Bei Fragen jeglicher Art rund um <?php echo $libConfig->verbindungName; ?> bieten wir Ihnen die Möglichkeit, mit uns über ein auf der Website bereitgestelltes Formular Kontakt aufzunehmen. Dabei ist die Angabe einer gültigen E-Mail-Adresse, Name und freiwillig auch Ihre Telefonnummer,  erforderlich, damit wir wissen, von wem die Anfrage stammt und um diese beantworten zu können. Die Datenverarbeitung zum Zwecke der Kontaktaufnahme mit uns erfolgt nach Art. 6 Abs. 1 S. 1 lit. A DSGVO auf Grundlage Ihrer freiwillig erteilten Einwilligung.</p>

<h2>Weitergabe von Daten</h2>

<p class="mb-4">Eine Übermittlung Ihrer persönlichen Daten an Dritte zu anderen als den im Folgenden aufgeführten Zwecken findet nicht statt. Wir geben Ihre persönlichen Daten nur an Dritte weiter, wenn: Sie Ihre nach Art. 6 Abs. 1 S. 1 lit. a DSGVO ausdrückliche Einwilligung dazu erteilt haben, die Weitergabe nach Art. 6 Abs. 1 S. 1 lit. f DSGVO zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen erforderlich ist und kein Grund zur Annahme besteht, dass Sie ein überwiegendes schutzwürdiges Interesse an der Nichtweitergabe Ihrer Daten haben, für den Fall, dass für die Weitergabe nach Art. 6 Abs. 1 S. 1 lit. c DSGVO eine gesetzliche Verpflichtung besteht, sowie dies gesetzlich zulässig und nach Art. 6 Abs. 1 S. 1 lit. b DSGVO für die Abwicklung von Vertragsverhältnissen mit Ihnen erforderlich ist.</p>

<h2>Cookies</h2>

<p class="mb-4">Wir setzen auf unserer Seite Cookies ein. Hierbei handelt es sich um kleine Dateien, die Ihr Browser automatisch erstellt und die auf Ihrem Endgerät (Laptop, Tablet, Smartphone o.ä.) gespeichert werden, wenn Sie unsere Seite besuchen. Cookies richten auf Ihrem Endgerät keinen Schaden an, enthalten keine Viren, Trojaner oder sonstige Schadsoftware. In dem Cookie werden Informationen abgelegt, die sich jeweils im Zusammenhang mit dem spezifisch eingesetzten Endgerät ergeben. Dies bedeutet jedoch nicht, dass wir dadurch unmittelbar Kenntnis von Ihrer Identität erhalten.</p>

<p class="mb-4">Der Einsatz von Cookies dient einerseits dazu, die Nutzung unseres Angebots für Sie angenehmer zu gestalten. So setzen wir sogenannte Session-Cookies ein, um zu erkennen, dass Sie für einzelne Seiten unserer Website authentifiziert sind.</p>

<p class="mb-4">Darüber hinaus setzen wir ebenfalls zur Optimierung der Benutzerfreundlichkeit temporäre Cookies ein, die für einen bestimmten festgelegten Zeitraum auf Ihrem Endgerät gespeichert werden. Besuchen Sie unsere Seite erneut, um unsere Dienste in Anspruch zu nehmen, wird automatisch erkannt, dass Sie bereits bei uns waren und welche Eingaben und Einstellungen sie getätigt haben, um diese nicht noch einmal eingeben zu müssen.</p>

<h2>Facebook Social Plugin</h2>

<p class="mb-4">Wir nutzen auf Grundlage unserer berechtigten Interessen (d.h. Interesse an der Analyse, Optimierung und wirtschaftlichem Betrieb unseres Onlineangebotes im Sinne des Art. 6 Abs. 1 lit. f. DSGVO) Social Plugins ("Plugins") des sozialen Netzwerkes facebook.com, welches von der Facebook Ireland Ltd., 4 Grand Canal Square, Grand Canal Harbour, Dublin 2, Irland betrieben wird ("Facebook"). Hierzu können z.B. Inhalte wie Bilder, Videos oder Texte und Schaltflächen gehören, mit denen Nutzer Inhalte dieses Onlineangebotes innerhalb von Facebook teilen können. Die Liste und das Aussehen der Facebook Social Plugins kann hier eingesehen werden: https://developers.facebook.com/docs/plugins/.</p>

<p class="mb-4">Facebook ist unter dem Privacy-Shield-Abkommen zertifiziert und bietet hierdurch eine Garantie, das europäische Datenschutzrecht einzuhalten (https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&status=Active).</p>

<p class="mb-4">Wenn ein Nutzer eine Funktion dieses Onlineangebotes aufruft, die ein solches Plugin enthält, baut sein Gerät eine direkte Verbindung mit den Servern von Facebook auf. Der Inhalt des Plugins wird von Facebook direkt an das Gerät des Nutzers übermittelt und von diesem in das Onlineangebot eingebunden. Dabei können aus den verarbeiteten Daten Nutzungsprofile der Nutzer erstellt werden. Wir haben daher keinen Einfluss auf den Umfang der Daten, die Facebook mit Hilfe dieses Plugins erhebt und informiert die Nutzer daher entsprechend unserem Kenntnisstand.</p>

<p class="mb-4">Durch die Einbindung der Plugins erhält Facebook die Information, dass ein Nutzer die entsprechende Seite des Onlineangebotes aufgerufen hat. Ist der Nutzer bei Facebook eingeloggt, kann Facebook den Besuch seinem Facebook-Konto zuordnen. Wenn Nutzer mit den Plugins interagieren, zum Beispiel den Like Button betätigen oder einen Kommentar abgeben, wird die entsprechende Information von Ihrem Gerät direkt an Facebook übermittelt und dort gespeichert. Falls ein Nutzer kein Mitglied von Facebook ist, besteht trotzdem die Möglichkeit, dass Facebook seine IP-Adresse in Erfahrung bringt und speichert. Laut Facebook wird in Deutschland nur eine anonymisierte IP-Adresse gespeichert.</p>

<p class="mb-4">Zweck und Umfang der Datenerhebung und die weitere Verarbeitung und Nutzung der Daten durch Facebook sowie die diesbezüglichen Rechte und Einstellungsmöglichkeiten zum Schutz der Privatsphäre der Nutzer, können diese den Datenschutzhinweisen von Facebook entnehmen: https://www.facebook.com/about/privacy/.</p>

<p class="mb-4">Wenn ein Nutzer Facebookmitglied ist und nicht möchte, dass Facebook über dieses Onlineangebot Daten über ihn sammelt und mit seinen bei Facebook gespeicherten Mitgliedsdaten verknüpft, muss er sich vor der Nutzung unseres Onlineangebotes bei Facebook ausloggen und seine Cookies löschen. Weitere Einstellungen und Widersprüche zur Nutzung von Daten für Werbezwecke, sind innerhalb der Facebook-Profileinstellungen möglich: https://www.facebook.com/settings?tab=ads  oder über die US-amerikanische Seite http://www.aboutads.info/choices/  oder die EU-Seite http://www.youronlinechoices.com/. Die Einstellungen erfolgen plattformunabhängig, d.h. sie werden für alle Geräte, wie Desktopcomputer oder mobile Geräte übernommen.</p>

<h2>Betroffenenrechte</h2>

<p class="mb-4">Sie haben das Recht:</p>

<ul>
	<li>gemäß Art. 15 DSGVO Auskunft über Ihre von uns verarbeiteten personenbezogenen Daten zu verlangen. Insbesondere können Sie Auskunft über die Verarbeitungszwecke, die Kategorie der personenbezogenen Daten, die Kategorien von Empfängern, gegenüber denen Ihre Daten offengelegt wurden oder werden, die geplante Speicherdauer, das Bestehen eines Rechts auf Berichtigung, Löschung, Einschränkung der Verarbeitung oder Widerspruch, das Bestehen eines Beschwerderechts, die Herkunft ihrer Daten, sofern diese nicht bei uns erhoben wurden, sowie über das Bestehen einer automatisierten Entscheidungsfindung einschließlich Profiling und ggf. aussagekräftigen Informationen zu deren Einzelheiten verlangen;</li>
	<li>gemäß Art. 16 DSGVO unverzüglich die Berichtigung unrichtiger oder Vervollständigung Ihrer bei uns gespeicherten personenbezogenen Daten zu verlangen;</li>
	<li>gemäß Art. 17 DSGVO die Löschung Ihrer bei uns gespeicherten personenbezogenen Daten zu verlangen, soweit nicht die Verarbeitung zur Ausübung des Rechts auf freie Meinungsäußerung und Information, zur Erfüllung einer rechtlichen Verpflichtung, aus Gründen des öffentlichen Interesses oder zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen erforderlich ist;</li>
	<li>gemäß Art. 18 DSGVO die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen, soweit die Richtigkeit der Daten von Ihnen bestritten wird, die Verarbeitung unrechtmäßig ist, Sie aber deren Löschung ablehnen und wir die Daten nicht mehr benötigen, Sie jedoch diese zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen benötigen oder Sie gemäß</li>
	<li>Art. 21 DSGVO Widerspruch gegen die Verarbeitung eingelegt haben; gemäß Art. 20 DSGVO Ihre personenbezogenen Daten, die Sie uns bereitgestellt haben, in einem strukturierten, gängigen und maschinenlesebaren Format zu erhalten oder die Übermittlung an einen anderen Verantwortlichen zu verlangen;</li>
	<li>gemäß Art. 7 Abs. 3 DSGVO Ihre einmal erteilte Einwilligung jederzeit gegenüber uns zu widerrufen. Dies hat zur Folge, dass wir die Datenverarbeitung, die auf dieser Einwilligung beruhte, für die Zukunft nicht mehr fortführen dürfen und</li>
	<li>gemäß Art. 77 DSGVO sich bei einer Aufsichtsbehörde zu beschweren. In der Regel können Sie sich hierfür an die Aufsichtsbehörde Ihres üblichen Aufenthaltsortes oder Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes wenden.</li>
</ul>

<h2>Widerspruchsrecht</h2>

<p class="mb-4">Sofern Ihre personenbezogenen Daten auf Grundlage von berechtigten Interessen gemäß Art. 6 Abs. 1 S. 1 lit. f DSGVO verarbeitet werden, haben Sie das Recht, gemäß Art. 21 DSGVO Widerspruch gegen die Verarbeitung Ihrer personenbezogenen Daten einzulegen, soweit dafür Gründe vorliegen, die sich aus Ihrer besonderen Situation ergeben oder sich der Widerspruch gegen Direktwerbung richtet. Im letzteren Fall haben Sie ein generelles Widerspruchsrecht, das ohne Angabe einer besonderen Situation von uns umgesetzt wird. Möchten Sie von Ihrem Widerrufs- oder Widerspruchsrecht Gebrauch machen, genügt eine E-Mail an <?php echo $email; ?>

<h2>Datensicherheit</h2>

<p class="mb-4">Wir verwenden innerhalb des Website-Besuchs das verbreitete SSL-Verfahren (Secure Socket Layer) in Verbindung mit der jeweils höchsten Verschlüsselungsstufe, die von Ihrem Browser unterstützt wird. In der Regel handelt es sich dabei um eine 256 Bit Verschlüsselung. Ob eine einzelne Seite unseres Internetauftrittes verschlüsselt übertragen wird, erkennen Sie an der geschlossenen Darstellung des Schüssel- beziehungsweise Schloss-Symbols in der unteren Statusleiste Ihres Browsers. Wir bedienen uns im Übrigen geeigneter technischer und organisatorischer Sicherheitsmaßnahmen, um Ihre Daten gegen zufällige oder vorsätzliche Manipulationen, teilweisen oder vollständigen Verlust, Zerstörung oder gegen den unbefugten Zugriff Dritter zu schützen. Unsere Sicherheitsmaßnahmen werden entsprechend der technologischen Entwicklung fortlaufend verbessert.</p>

<h2>Aktualität und Änderung dieser Datenschutzerklärung</h2>

<p class="mb-4">Diese Datenschutzerklärung ist aktuell gültig und hat den Stand Mai 2018. Durch die Weiterentwicklung unserer Website und Angebote darüber oder aufgrund geänderter gesetzlicher beziehungsweise behördlicher Vorgaben kann es notwendig werden, diese Datenschutzerklärung zu ändern. Die jeweils aktuelle Datenschutzerklärung kann jederzeit auf der Website unter <?php echo $libGlobal->getPageCanonicalUrl(); ?> von Ihnen abgerufen und ausgedruckt werden.</p>
