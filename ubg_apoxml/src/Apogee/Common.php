<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\user\Entity\User;

/**
 * [Class Common]
 * Sammlung von paar statische Funktionen, die mehrmals verwendet werden müssen.
 * 
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class Common
{

    /**
     * Liefert den Wert eines Feldes vom Typ User
     * @param string $field
     * @param User $customer
     * 
     * @throws Exception Falls einen Feld vom Typ User leer ist.
     * @return string
     */
    public static function getCustomerField(string $field, User $customer): string
    {
        $output = $customer->get($field);
        if ($output->isEmpty()) {
            throw new \Exception("Der {$field} ist fehlerhaft!");
        }
        return $output->value;
    }


    /**
     * @param string $configType Was fuer ein Config- Typ zurueck gegeben wird.
     * 
     * @return ImmutableConfig Das Config- Objekt
     */
    protected static function getConfigObject(string $configType = 'ubg_apoxml.apoxml'): ImmutableConfig
    {
        return \Drupal::config($configType);
    }

    /**
     * @param string $field
     * @param string $configType
     * 
     * @return string|null
     */
    public static function getConfig(string $field, string $configType = 'ubg_apoxml.apoxml'): ?string
    {
        $cfg = self::getConfigObject($configType)->get($field);
        if (!$cfg || empty($cfg)) {
            self::log("Der Konfigurations- Feld {$field} ist fehlerhaft!");
            return null;
        }
        return $cfg;
    }

    /**
     * Generiert logs innerhalb Drupal
     * @param string $msg Das Message, was geloggt werden muss.
     * @param string $type Was fuer ein Log- Typ das ist: critical | info | aler | debug | error | notice | warning
     * @param string $channel Aus welchem Modul diesen log gemacht worden ist.
     * @throws Exception Falls das Message leer ist.
     * @return void
     */
    public static function log(string $msg, string $type = 'critical', string $channel = 'UBG ApoXML'): void
    {
        if (!$msg) {
            throw new \Exception('Die Log- Nachricht ist fehlerhaft!');
        }
        \Drupal::logger($channel)->{$type}($msg);
    }
}
