<?php

namespace Drupal\ubg_apoxml\Apogee;

use DOMDocument;

/**
 * [Class XmlHandler]
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class XmlHandler
{

    /**
     * @var \DOMDocument|null
     */
    private static $document = null;


    /**
     * Generiert das DOMDokument objekt
     * @param string $version
     * @param string $encoding
     * @throws Exception Falls das Array fehlerhaft ist.
     * @return void
     */
    public static function createDocument(string $version = '1.0', string $encoding = 'UTF-8'): void
    {
        self::$document = new \DOMDocument($version, $encoding);
    }

    /**
     * Futtert das Dokument mit Inhalt.
     * @param array $data
     * @param null $node
     * 
     * @throws Exception Falls das Array mit dem Data fehlerhaft ist.
     * @return void
     */
    public static function parseXml(array $data, $node = null): void
    {
        if (!count($data)) {
            throw new \Exception('Data ist fehlerhaft!');
        }
        $document = self::getDocument();
        foreach ($data as $element => $value) {
            if ($node) {
                $domElement = $node->appendChild($document->createElement($element));
            } else {
                $domElement = $document->appendChild($document->createElement($element));
            }
            foreach ($value as $kk  => $vv) {
                if ($kk == '_childs') {
                    self::parseXml($vv, $domElement);
                }
                if ($kk == '_attr') {
                    foreach ($vv as $attrK => $attrV) {
                        $domElement->setAttribute($attrK, $attrV);
                    }
                }
            }
        }
    }


    /**
     * @param string $location Der Pfad, wo die Datei gespeichert werden soll.
     * @param string $filename Der Name der Datei.
     * 
     * @throws Exception Falls die Datei nicht gespeichert werden kann, oder wenn das XML output nicht korrekt ist.
     * @return void
     */
    public static function dumpXml(string $location, string $filename): void
    {
        if (empty($location) || empty($filename)) {
            throw new \Exception('Etwas ist schief gelaufen!');
        }
        if (substr($location, -1) !== DIRECTORY_SEPARATOR) {
            $location .= DIRECTORY_SEPARATOR;
        }
        // if (!self::validateXml()) {
        //     throw new \Exception('Die XML Datei ist nicht korrekt!');
        // }
        $document = self::getDocument(true);
        $file = $location . $filename . '.xml';
        if (!$document->save($file)) {
            throw new \Exception("Die Datei {$file} konnte nicht exportiert werden.");
        }
    }

    /**
     * Liefert das XML-Dokument
     * @param bool $formated Ob das Dokument formatiert wird.
     * 
     * @return DOMDocument
     */
    public static function getDocument(bool $formated = true): DOMDocument
    {
        self::$document->formatOutput = $formated;
        return self::$document;
    }

    /**
     * Checkt, ob das XML Source in Ordnung ist.
     * @param DOMDocument $document
     * @return bool
     */
    public static function validateXml(DOMDocument $document): bool
    {
        if ($document->schemaValidateSource($document->saveXML())) {
            return true;
        }
        return false;
    }
}
