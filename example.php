<?php
require_once 'inc.ErrorExceptionHandler.php';
require_once 'class.KiidPdfExtract.php';

if (php_sapi_name() !== 'cli') {
    echo '<pre>';
}

try {
    $pdf_file_path = 'sample.pdf';
    $config_file_path = 'patterns.json';
    // $config_file_path = 'https://lb.fww.de/remote-code-bridge/fww-code/fww-kiid-patterns/master/patterns.json';

    $kiid_extract = new KiidPdfExtract($pdf_file_path);
    $kiid_extract->loadConfigurationsFromJsonFile($config_file_path);

    // fuer den Import in die FWW Datenbank wollen wir von einem KIID
    // wissen: ISIN, SRRI, Stand, Laufende Kosten, Sprache, Land (Region)
    $record_set_complete = KiidPdfExtract::RECORD_SET_ISIN_SRRI_ASOF_CHARGES_LANGUAGE_REGION;
    $has_all_we_want = $kiid_extract->hasFullRecordSet($record_set_complete);

    // wenn wir alles kriegen auser den SRRI, importieren wir das KIID
    // auch und der SRRI muss haendisch nachgetragen werden
    $record_set_no_srri = KiidPdfExtract::RECORD_SET_ISIN_ASOF_CHARGES_LANGUAGE_REGION;
    $has_all_we_want_but_srri = $kiid_extract->hasFullRecordSet($record_set_no_srri);

    $has_not_enough = !($has_all_we_want or $has_all_we_want_but_srri);

    if ($has_not_enough) {
        echo 'Nix is!';
    } else {

        if ($has_all_we_want) {
            echo 'Werte aus KIID auslesbar!', PHP_EOL;
        } else if ($has_all_we_want_but_srri) {
            echo 'Werte aus KIID auslesbar, ausser dem SRRI!', PHP_EOL;
        }

        echo 'Konfiguration, die gepasst hat: ', print_r($kiid_extract->getUsedConfigurationAndPatterns(), true), PHP_EOL;

        $values = $kiid_extract->getValuesAsArray($record_set_complete);
        echo 'Ausgelesene Werte:', PHP_EOL;
        var_dump($values);

        echo 'Stand: ', $values['valid_as_of_date']->format('d.m.Y'), PHP_EOL;
    }

} catch (Exception $e) {
    echo 'FATAL ERROR! ', $e, PHP_EOL;
}
