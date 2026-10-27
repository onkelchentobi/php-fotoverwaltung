<?php
// Speichert Sterne, Farben, Alben, Schlagwörter und Geo-Daten in fotos_db.txt (im selben Ordner wie fotos.html)
// listet mit ?liste alle Fotos unterhalb dieses Ordners auf und löscht mit ?loeschen die übergebenen Fotos
// und verwaltet die Vorschaubilder im Unterordner Thumbnail (Dateiname = MD5). Maximal 2 MB pro Anfrage.
// Vor dem ersten Speichern eines Tages wird die Datenbank als fotos_db_sicherung_JJJJ-MM-TT.txt gesichert (die letzten 3 bleiben).
// Mit ?exp=Pfad wird ein exportiertes Bild in Teilen (je unter 2 MB) hochgeladen und als NAME_001.jpg, NAME_002.png, NAME_003.webp, NAME_004.pdf, NAME_005.tif ... neben das Original gelegt.
$datei = __DIR__ . '/fotos_db.txt';
$thumbDir = __DIR__ . '/Thumbnail';
$max = 2000000;
header('Content-Type: text/plain; charset=utf-8');

function thumbNamen($dir) {
    $r = [];
    foreach (glob($dir . '/*.jpg') ?: [] as $f) {
        $n = basename($f, '.jpg');
        if (preg_match('/^[a-f0-9]{32}$/', $n)) $r[] = $n;
    }
    return $r;
}

// Tägliche Sicherung: vor dem ersten Schreiben eines Tages wird die bisherige Datenbank als fotos_db_sicherung_JJJJ-MM-TT.txt
// neben fotos_db.txt kopiert (= Stand vom Ende des Vortags). Die letzten 3 Sicherungen bleiben erhalten, ältere werden gelöscht.
function sicherung($datei) {
    $keep = 3;
    if (!is_file($datei) || filesize($datei) === 0) return;
    $dir = dirname($datei);
    $ziel = $dir . '/fotos_db_sicherung_' . date('Y-m-d') . '.txt';
    if (!file_exists($ziel)) @copy($datei, $ziel);
    $alle = glob($dir . '/fotos_db_sicherung_????-??-??.txt') ?: [];
    sort($alle);
    foreach (array_slice($alle, 0, max(0, count($alle) - $keep)) as $f) @unlink($f);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = file_get_contents('php://input', false, null, 0, $max + 1);
    if ($body === false || strlen($body) > $max) { http_response_code(413); echo 'zu gross'; exit; }
    if (isset($_GET['loeschen'])) { // Fotos löschen: eine Zeile pro relativem Pfad, Antwort "ok N" und danach die gelöschten Pfade
        $aus = ['@eaDir', '#recycle', 'PhotoLibrary', 'Thumbnail'];
        $ok = [];
        foreach (preg_split('/\r?\n/', $body, -1, PREG_SPLIT_NO_EMPTY) as $rel) {
            $rel = trim($rel);
            $bad = $rel === '' || $rel[0] === '/' || strpos($rel, '\\') !== false || strpos($rel, "\0") !== false
                || !preg_match('/\.(jpe?g|png|gif|webp|avif|bmp|dng|tiff?)$/i', $rel);
            foreach (explode('/', $rel) as $t) if ($t === '' || $t[0] === '.' || in_array($t, $aus, true)) $bad = true;
            if ($bad) continue;
            $voll = __DIR__ . '/' . $rel;
            if (is_file($voll) && @unlink($voll)) $ok[] = $rel;
        }
        echo 'ok ' . count($ok) . ($ok ? "\n" . implode("\n", $ok) : '');
        exit;
    }
    if (isset($_GET['exp'])) {      // Export: Teile in .export_tmp sammeln, mit ?fertig=jpg|png|webp neben das Original legen (nie ein vorhandenes Bild überschreiben)
        $aus = ['@eaDir', '#recycle', 'PhotoLibrary', 'Thumbnail'];
        $rel = (string)$_GET['exp'];
        $id = (string)($_GET['id'] ?? '');
        $bad = $rel === '' || $rel[0] === '/' || strpos($rel, '\\') !== false || strpos($rel, "\0") !== false
            || !preg_match('/\.(jpe?g|png|gif|webp|avif|bmp|dng|tiff?)$/i', $rel) || !preg_match('/^[a-f0-9]{16}$/', $id);
        foreach (explode('/', $rel) as $t) if ($t === '' || $t[0] === '.' || in_array($t, $aus, true)) $bad = true;
        $orig = __DIR__ . '/' . $rel;
        if ($bad || !is_file($orig)) { http_response_code(400); echo 'ungueltig'; exit; }
        $tmpDir = __DIR__ . '/.export_tmp';
        if (!is_dir($tmpDir) && !@mkdir($tmpDir, 0775)) { echo 'fehler: Ordner .export_tmp kann nicht angelegt werden'; exit; }
        foreach (glob($tmpDir . '/*.part') ?: [] as $f) if (@filemtime($f) < time() - 86400) @unlink($f);
        $tmp = $tmpDir . '/' . $id . '.part';
        if (isset($_GET['fertig'])) {
            $ext = in_array($_GET['fertig'], ['png', 'jpg', 'tif', 'webp', 'pdf'], true) ? $_GET['fertig'] : '';
            if ($ext === '' || !is_file($tmp)) { echo 'fehler: keine Daten'; exit; }
            $kopf = (string)file_get_contents($tmp, false, null, 0, 12);
            if (!(($ext === 'jpg' && substr($kopf, 0, 2) === "\xFF\xD8") || ($ext === 'png' && $kopf === "\x89PNG\r\n\x1a\n") || ($ext === 'pdf' && substr($kopf, 0, 5) === '%PDF-') || ($ext === 'webp' && substr($kopf, 0, 4) === 'RIFF' && substr($kopf, 8, 4) === 'WEBP') || ($ext === 'tif' && in_array(substr($kopf, 0, 4), ["II*\0", "MM\0*"], true)))) { @unlink($tmp); echo 'fehler: falsches Format'; exit; }
            $dir = dirname($orig);
            $stem = pathinfo($orig, PATHINFO_FILENAME);
            $hoch = 0;
            foreach (scandir($dir) ?: [] as $n) if (preg_match('/^' . preg_quote($stem, '/') . '_(\d{3,})\.[A-Za-z0-9]+$/', $n, $m)) $hoch = max($hoch, (int)$m[1]);
            for ($k = $hoch + 1; $k < $hoch + 1000; $k++) {
                $name = sprintf('%s_%03d.%s', $stem, $k, $ext);
                $ziel = $dir . '/' . $name;
                $fh = @fopen($ziel, 'x');           // reserviert den Namen, schlägt fehl wenn es ihn schon gibt
                if ($fh === false) continue;
                fclose($fh);
                if (@rename($tmp, $ziel) || (@copy($tmp, $ziel) && @unlink($tmp))) {
                    $d = dirname($rel);
                    echo 'ok ' . ($d === '.' ? $name : $d . '/' . $name);
                } else { @unlink($ziel); echo 'fehler: Speichern im Ordner nicht möglich'; }
                exit;
            }
            echo 'fehler: keine freie Nummer';
            exit;
        }
        $off = (int)($_GET['off'] ?? 0);
        if ($off === 0) {
            if (file_put_contents($tmp, $body, LOCK_EX) === false) { echo 'fehler: Schreiben nicht möglich'; exit; }
        } else {
            clearstatcache(true, $tmp);
            if (!is_file($tmp) || filesize($tmp) !== $off || $off + strlen($body) > 400000000) { http_response_code(409); echo 'fehler: Teil passt nicht'; exit; }
            if (file_put_contents($tmp, $body, FILE_APPEND | LOCK_EX) === false) { echo 'fehler: Schreiben nicht möglich'; exit; }
        }
        echo 'ok';
        exit;
    }
    if (isset($_GET['thumbs'])) {   // Vorschaubilder löschen: "*" = alle, sonst Liste von MD5-Namen
        $t = trim($body);
        $namen = ($t === '*') ? thumbNamen($thumbDir) : preg_split('/\s+/', $t, -1, PREG_SPLIT_NO_EMPTY);
        $n = 0;
        foreach ($namen as $k) {
            if (preg_match('/^[a-f0-9]{32}$/', $k) && @unlink($thumbDir . '/' . $k . '.jpg')) $n++;
        }
        echo 'ok ' . $n;
        exit;
    }
    if (isset($_GET['thumb'])) {    // ein Vorschaubild speichern
        $key = (string)$_GET['thumb'];
        if (!preg_match('/^[a-f0-9]{32}$/', $key) || substr($body, 0, 2) !== "\xFF\xD8") { http_response_code(400); echo 'ungueltig'; exit; }
        if (!is_dir($thumbDir) && !@mkdir($thumbDir, 0775)) { echo 'fehler'; exit; }
        echo (@file_put_contents($thumbDir . '/' . $key . '.jpg', $body, LOCK_EX) === false) ? 'fehler' : 'ok';
        exit;
    }
    sicherung($datei);
    echo (file_put_contents($datei, $body, LOCK_EX) === false) ? 'fehler' : 'ok';
    exit;
}
if (isset($_GET['thumbs'])) {       // Liste aller vorhandenen Vorschaubilder
    echo implode("\n", is_dir($thumbDir) ? thumbNamen($thumbDir) : []);
    exit;
}
if (isset($_GET['liste'])) {        // Alle Fotos rekursiv auflisten (relativer Pfad, Änderungszeit in ms, Größe; durch Tab getrennt), so erscheinen neue Bilder automatisch
    $aus = ['@eaDir', '#recycle', 'PhotoLibrary', 'Thumbnail'];
    $iter = new RecursiveIteratorIterator(new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator(__DIR__, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS),
        function ($f) use ($aus) { $n = $f->getFilename(); return $n[0] !== '.' && !in_array($n, $aus, true); }
    ));
    $out = [];
    $len = strlen(__DIR__) + 1;
    foreach ($iter as $f) {
        if ($f->isFile() && preg_match('/\.(jpe?g|png|gif|webp|avif|bmp|dng|tiff?)$/i', $f->getFilename()))
            $out[] = str_replace('\\', '/', substr($f->getPathname(), $len)) . "\t" . ($f->getMTime() * 1000) . "\t" . $f->getSize();
    }
    sort($out);
    echo implode("\n", $out);
    exit;
}
if (isset($_GET['pfad'])) {        // Voller Serverpfad des Foto-Ordners (für die Pfadanzeige in foto.html)
    echo str_replace('\\', '/', realpath(__DIR__) ?: __DIR__);
    exit;
}
if (isset($_GET['ping'])) {
    $ok = file_exists($datei) ? is_writable($datei) : is_writable(__DIR__);
    echo $ok ? 'ok' : 'nicht beschreibbar';
}