Quartiere für Menschen WordPress Theme
======================================

Quartiere für Menschen ist ein WordPress Theme für lokale (ortsbezogene) ADFC-Aktionen. Es wurde im ersten Quartal 2021 entwickelt und erstmals für das Projekt "Quartiere für Menschen" in Hamburg-Eimsbüttel eingesetzt. Evtl. sind noch minimale Anpassungen notwendig, um das Ganze für andere Initiativen nutzbar zu machen. Im Großen und Ganzen ist es aber so wie vorliegend (Version 0.7, 07.09.2021) bereits auf andere Projekte/Orte übertragbar.

Hier eine Kurzanleitung:

* Vorab: Das Theme benötigt die Plugins "Advanced Custom Fields" und "Classic Widgets"! Außerdem sollte ein Plugin installiert werden, das das Hinzufügen von Widget-CSS-Klassen erlaubt.

* Oberer Balken über dem Menü: Design > Widgets. Hier kann zum Beispiel "Ein Projekt von ADFC [Irgendeinort]" mit Link stehen.

* Logo und Site Icon: Customizer > Website-Informationen, dort die entsprechenden Felder

* Gewünschte Unterseiten erstellen, auch eine Seite für das Eintragungsformular für neue Orte sowie die Seite mit der Hauptkarte (kann auch die Startseite sein)

* Hauptkarte per Shortcode einbinden: [qfm-map]. Folgende Attribute können verwendet werden Attributsnamen und die Standards, wenn Attribut nicht angegeben:
	"zoom' => 15,
	'lat' => 53.57744,
	'lon' => 9.94786,
	'maxzoom' => '',
	'minzoom' => '',
	'showentry-zoom' => '', 
	'bounds-ne' => '',
	'bounds-sw' => '',
	'minzoom-setmarker' => 13,
	'singlezoom' => 18,
	
* Für das Eintragsungsformular für neue Orte bitte "Page with ACF form" als Seiten-Template laden

* Unten auf der Startseite bei den entsprechenden Feldern auswählen, welches die Seite mit dem Eintragsformular und welches die Seite mit der Karte ist (kann auch die Startseite sein)

* Wichtig: Einstellungen > Lesen - hier eine Startseite auswählen, nur so funktioniert das Theme

* Hauptmenü: Design > Menüs, Menü erstellen, "Primär" als Position wählen

* Headerbild (panoramaförmig): Als Beitragsbild der jeweiligen Seite setzen

* Ortstypen-Kategorien erstellen (Orte > Ortstypen), Titelform bitte nummerieren: 01-kategoriename1, 02-kategoriename2 ... (für ADFC-Einträge) und immer zusätzlich eine Community-Kategorie für Beiträge, die von Nutzerinnen und Nutzern kommen 01-kategoriename1-community, 02-kategoriename2-community ... Das Feld "Beschreibung" kann z.B. für kategoriebezogene Links (z.B. Anker-Links auf einer Glossar-Seite) genutzt werden.

* PNG-Bilder als Kartenicons zu den Kategorien hochladen, 52px breit und 64px hoch: marker-01-kategoriename1.png, marker-02-kategoriename2.png, marker-01-kategoriename1-community.png, marker-02-kategoriename2-community.png ... Auf eine Übereinstimmung mit den Nummern/Kategorienamen der Ortstypen achten, nur dann wird es korrekt angezeigt.

* Umriss des Projektgebiets als GeoJSON-Datei: Hier ist (leider) noch ein direkter Eingriff ins Theme notwendig. Die Datei projektgebiet.geojson im Ordner geojson muss per FTP durch eine gleichnamige Datei mit dem Umriss des Projektgebiets als Polygon ersetzt werden.

* Für die Footer-Widgets können folgende Klassen verwendet werden: adfc, kontakt sowie columns als ergänzende Klasse.

* [tbc]