# php-fotoverwaltung 0.92 Beta

### PHP Photo Management System

A lightweight, ultra-fast, and zero-installation photo management system. It combines a minimalist PHP backend with a single-file frontend interface (fotos.html) designed to view, organize, and manage large photo collections locally or on a private web server. 

### Core Philosophy: Data Transparency & Non-Destructive Management

* **100% Readable Text Database:** All ratings, tags, and metadata are stored in a plain, human-readable text file (fotos_db.txt). You never need proprietary database software or special tools to view, back up, or migrate your data. Anyone can open it with a basic text editor.
* **Non-Destructive Integrity:** Your original photo files are **completely untouched**. The system never modifies your original files, nor does it inject or overwrite metadata (like EXIF data) within the images themselves. All information is managed externally.

### Key Architectural & User Features

### 1. Zero Installation & Single-File Frontend

* **Single HTML File:** The entire user interface is contained within a single file (fotos.html).
* **Zero Dependencies:** Runs completely standalone without external frameworks, libraries, or internet-dependent CDNs (no React, Angular, jQuery, or Bootstrap).
* **Portable Architecture:** Works immediately out of the box. You can run it locally on your PC (using local server environments like XAMPP) or host it on any remote web server supporting PHP.

### 2. Cross-Device & Browser Compatibility

* **Desktop & Mobile:** Fully responsive UI that adapts seamlessly to desktop monitors as well as mobile devices (smartphones and tablets).
* **Chromium Optimized:** Built using advanced web standards that require a modern Chromium-based browser such as **Google Chrome** or **Microsoft Edge**.

### 3. Photo Categorization & Labeling

* **Star Ratings:** Rate your photos with 0 to 3 stars to easily separate your best shots from the rest.
* **Color Coding:** Assign visual color tags (Red, Green, Yellow, Blue, White, Magenta, Black) to group photos by categories or editing status.
* **Albums:** Create custom photo albums and easily add or remove selected images to organize collections.
* **Tagging & Keywords:** Add searchable metadata tags (Schlagwörter) to your photos to find specific subjects instantly.
* **Geo-Data Storage:** Save and manage geographical coordinates attached to your photos for location-based sorting.

### 4. Smart Photo Browsing & Selection

* **Flexible Views:** Browse your collection chronologically via a dynamic timeline, view recently added files, or navigate your original folder structures.
* **Instant Filtering:** Filter your view instantly by specific star counts, assigned color tags, or keywords.
* **Side-by-Side Comparison:** Compare multiple selected images simultaneously on one screen to pick the best shot of a series.
* **Sammelbearbeitung (Multi-Select):** Select multiple photos at once to apply ratings, colors, or album changes to entire groups simultaneously.

### 5. Advanced Image Exporting & Formats

* **Supported Export Formats:** Export your images directly into various optimized targets: 

  * **JPEG:** High-compatibility image format with an adjustable 0–100% quality slider.
  * **WebP:** Modern, highly compressed web format.
  * **PNG:** Lossless web image format.
  * **PDF:** Available as lossless document export or JPEG-compressed (100%) container.
* **Smart Versioning (No Overwrites):** Exported files are automatically numbered sequentially (e.g., IMAGE_001.jpg, IMAGE_002.png) and saved right next to the original file. The system scans the directory first to ensure **your original work is never overwritten**.
* **Chunked Uploads:** Seamlessly handles large image or document exports (up to 400 MB) by splitting them into small, stable upload fragments under 2 MB.

### 6. Background Automation & Safety

* **High-Speed Caching (Thumbnails):** Automatically generates and manages unique preview thumbnails using fast MD5 hashes. This guarantees rapid loading times even for massive photo libraries.
* **Automatic Data Safety (Daily Backups):** The system automatically backs up your entire metadata database once a day before any new changes are saved. It keeps the last 3 days of backups safe while automatically cleaning up older history.
* **Real-Time Synchronisation:** New photos dropped into your directories appear automatically in the app via an automatic recursive folder scanner.

### Directory Structure

* fotos.html - The all-in-one frontend interface.
* fotos_db.txt - Flat-file database storing all your ratings, tags, and metadata.
* Thumbnail/ - Cached .jpg previews for rapid interface loading.
* .export_tmp/ - Temporary workspace for active chunked image uploads.

### Security Architecture Notice

**CRITICAL:** This script is designed for privacy and local performance. It features **no built-in user login or cloud authentication**. Because it grants direct filesystem privileges (like deleting files), do NOT host it openly on the public internet. Always secure its directory using web server security (.htaccess / Basic Auth) or run it strictly within a local network (LAN) or secure VPN tunnel. 

### Open Source & Contributions

This is a free and open-source project! Contributions, bug reports, and feature requests are highly welcome. Feel free to fork the repository and submit pull requests. 

### License

This project is licensed under the GNU General Public License v3.0 - see the LICENSE file for details.

### PHP Foto-Verwaltungssystem

Ein schlankes, extrem schnelles und installationsfreies System zur Verwaltung digitaler Fotos. Es kombiniert ein minimalistisches PHP-Backend mit einer Benutzeroberfläche, die in einer einzigen Datei (fotos.html) gebündelt ist, um große Bildersammlungen lokal oder auf einem privaten Server komfortabel zu organisieren. 

### Kernphilosophie: Datentransparenz & Zerstörungsfreie Verwaltung

* **100% lesbare Text-Datenbank:** Alle Bewertungen, Alben, Schlagwörter und Geo-Daten werden in einer reinen, absolut menschenlesbaren Textdatei (fotos_db.txt) abgespeichert. Du benötigst keine spezielle Datenbanksoftware oder proprietäre Tools, um deine Daten einzusehen, zu sichern oder zu migrieren. Jedes einfache Textprogramm kann die Datei öffnen.
* **Zerstörungsfreie Bildintegrität:** Deine Originalbilder werden **absolut unangetastet gelassen**. Das System nimmt keinerlei Modifikationen an deinen Originaldateien vor und schleust keine versteckten Metadaten (wie EXIF-Daten) in die Bilddateien selbst ein. Alle Informationen werden strikt extern verwaltet.

### Hauptmerkmale & Programmfunktionen

### 1. Keine Installation & Single-File Frontend

* **Eine einzige HTML-Datei:** Die gesamte Benutzeroberfläche befindet sich in nur einer einzigen Datei (fotos.html).
* **Keine Abhängigkeiten (Zero Dependencies):** Läuft komplett autark ohne externe Frameworks, Skriptbibliotheken oder internetabhängige CDNs (kein React, Angular, jQuery oder Bootstrap).
* **Flexible Nutzung:** Funktioniert ohne Installation sofort out of the box. Du kannst es lokal auf deinem PC (z. B. über lokale Serverumgebungen wie XAMPP) starten oder auf einem klassischen Webserver hosten, der PHP unterstützt.

### 2. Geräte- und Browser-Kompatibilität

* **PC & Mobilgeräte:** Die responsive Benutzeroberfläche passt sich automatisch an große Desktop-Monitore sowie an mobile Geräte (Smartphones und Tablets) an.
* **Chromium-Optimiert:** Nutzt modernste Web-Standards und ist gezielt für die Ausführung in modernen Chromium-basierten Browsern wie **Google Chrome** oder **Microsoft Edge** entwickelt worden.

### 3. Fotokategorisierung & Metadaten

* **Sterne-Bewertungen:** Vergib 0 bis 3 Sterne, um deine besten Aufnahmen schnell von Ausschussware zu trennen.
* **Farbmarkierungen:** Weise Fotos visuelle Farben zu (Rot, Grün, Gelb, Blau, Weiß, Magenta, Schwarz), um Arbeitsabläufe oder Themen zu kennzeichnen.
* **Alben-Verwaltung:** Erstelle maßgeschneiderte Fotoalben und füge ausgewählte Bilder flexibel hinzu oder entferne sie wieder.
* **Verschlagwortung (Tags):** Hinterlege Suchbegriffe und Schlagwörter für deine Fotos, um bestimmte Motive oder Personen sofort wiederzufinden.
* **Geo-Daten-Speicherung:** Speichere und verwalte geografische Koordinaten direkt am Foto für eine ortsbasierte Sortierung.

### 4. Komfortable Bildrecherche & Auswahl

* **Flexible Ansichten:** Durchstöbere deine Sammlung chronologisch über eine dynamische Zeitleiste, betrachte zuletzt hinzugefügte Bilder oder navigiere klassisch über deine Ordnerstruktur.
* **Blitzschnelle Filter:** Filtere deine Ansicht im Handumdrehen nach Sternen, zugewiesenen Farblabeln oder Suchbegriffen.
* **Foto-Vergleichsmodus:** Vergleiche mehrere ausgewählte Bilder direkt nebeneinander auf dem Bildschirm, um bei Serienaufnahmen ganz leicht das beste Bild herauszusuchen.
* **Sammelbearbeitung (Multi-Select):** Wähle mehrere Fotos gleichzeitig aus, um Sterne, Farben oder Alben für ganze Gruppen auf einmal zu ändern.

### 5. Intelligenter Bild-Export & Exportformate

* **Verfügbare Exportformate:** Exportiere deine Bilder direkt aus der Anwendung heraus in verschiedene optimierte Zielformate: 

  * **JPEG:** Maximale Kompatibilität mit stufenlosem Qualitätsregler (0–100 %).
  * **WebP:** Modernes, hocheffizient komprimiertes Bildformat für das Web.
  * **PNG:** Verlustfreies Grafikformat.
  * **PDF:** Wahlweise als verlustfreies Dokument oder als JPEG-komprimierter (100 %) Container.
* **Automatischer Überschreibschutz:** Beim Exportieren legt das System die Datei mit einer fortlaufenden Nummerierung (z. B. BILD_001.jpg, BILD_002.png) direkt neben das Original. Es prüft den Ordner vorab, damit **niemals bestehende Bilder überschrieben** werden.
* **Großdatei-Unterstützung:** Verarbeitet selbst riesige Exporte (bis zu 400 MB Gesamtgröße) problemlos, indem sie für eine stabile Übertragung in verdauliche Teilstücke unter 2 MB zerlegt werden.

### 6. Hintergrundprozesse & Automatisierung

* **Hochgeschwindigkeits-Vorschau (Thumbnails):** Erstellt und verwaltet im Hintergrund blitzschnelle Vorschaubilder mittels MD5-Hashes. Das garantiert minimale Ladezeiten der Galerie – selbst bei riesigen Fotoarchiven.
* **Automatische Datensicherung (Tägliche Backups):** Vor dem ersten Speichern eines neuen Tages sichert das System deine komplette Metadaten-Datenbank vollautomatisch als fotos_db_sicherung_JJJJ-MM-TT.txt. Es hält die letzten 3 Tage vor und löscht ältere Stände selbstständig.
* **Echtzeit-Aktualisierung:** Neue Fotos, die du in deine Ordnerstruktur kopierst, werden über einen rekursiven Scanner sofort erkannt und erscheinen ohne manuelles Zutun in deiner Anwendung.

### Ordnerstruktur

* fotos.html - Die All-in-One Benutzeroberfläche.
* fotos_db.txt - Die dateibasierte Hauptdatenbank, die all deine Bewertungen, Alben und Tags speichert.
* Thumbnail/ - Cache-Verzeichnis für die MD5-kodierten .jpg-Vorschauen für schnellen Seitenaufbau.
* .export_tmp/ - Temporärer Zwischenspeicher für unvollständige Upload-Fragmente.

### Sicherheitshinweis

**WICHTIG:** Dieses Programm ist auf maximale Privatsphäre und lokale Geschwindigkeit ausgelegt. Es besitzt **keine integrierte Benutzeranmeldung**. Da es tiefgreifende Rechte besitzt (z. B. das Löschen von Dateien auf der Festplatte), betreibe es niemals ungeschützt im öffentlichen Internet. Sichere das Verzeichnis zwingend über einen Passwortschutz (.htaccess / Basic Auth) ab oder nutze es ausschließlich im lokalen Netzwerk (LAN) oder über ein sicheres VPN. 

### Open Source & Mitwirken

Dies ist ein freies Open-Source-Projekt! Fehlerberichte, Verbesserungsvorschläge und Code-Beiträge (Pull Requests) sind herzlich willkommen. Du kannst das Repository gerne forken und deine eigenen Ideen einbringen. 

### Lizenz

Dieses Projekt ist unter der GNU General Public License v3.0 lizenziert – siehe die LICENSE-Datei für Details.
