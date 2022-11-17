<?php

namespace Drupal\ubg_apoxml\Apogee;


use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;
use Drupal\Core\File\FileSystemInterface;

/**
 * [Class ApogeeHandler]
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class ApogeeHandler
{

    /**
     * Das LocationHandler, wo die Dateien gespeichert werden sollen.
     * @var string|null
     */
    private $location;

    /**
     * Das OrderHandler
     * @var Order|null
     */
    private $order;

    /**
     * @param string $location
     * 
     * @throws Exception Falls der $location Parameter leer ist.
     * @return void
     */
    public function setFileExportLocation(string $location): void
    {
        if (empty($location)) {
            throw new \Exception('Der Location-Pfad ist fehlerhaft!');
        }
        $this->location = $location;
    }

    /**
     * @param Order $order
     * 
     * @throws Exception Falls die Bestellung leer ist.
     * @return void
     */
    public function setOrder(Order $order): void
    {
        if (!$order) {
            throw new Exception('Die Bestellung ist fehlehaft!');
        }
        $this->order = $order;
    }


    /**
     * @return [type]
     */
    public function run()
    {
        if (!$this->order || !$this->location) {
            throw new \Exception('Der Prozess ist fehlerhaft!');
        }
        $orderItems = $this->order->getItems();
        if (!count($orderItems)) {
            throw new \Exception('Stehen keine Produkte zur Verfuegung!');
        }
        foreach ($orderItems as $item) {
            $filename = $item->get('field_document')->entity->label();
            $this->_createXml($this->order, $item, $this->location, $filename);
        }
    }

    /**
     * @param Order $order
     * @param XquantityOrderItem $item
     * @param string $location
     * @param string $filename
     * 
     * @return bool
     */
    protected function _createXml(Order $order, XquantityOrderItem $item, string $location, string $filename): bool
    {
        try {
            XmlHandler::createDocument('1.0', 'UTF-8');
            XmlHandler::parseXml(ApoXML::generate($order, $item));
            XmlHandler::dumpXml($location, $filename);

            //PDF Datei kopieren
            //Es muss noch getestet werden.
            // $this->_copyPdfFile($item, $this->location);
            return true;
        } catch (\Exception $exept) {
            Common::log($exept->getMessage());
            return false;
        }
    }

    /**
     * @param string $destination
     * @param  $replace
     * @todo 
     * Es muss weiter entwickelt werden und auf der Live Kreativ-Portal Instanz getestet werden, da man local nicht die PDF-Dataien von Chili bekommt.
     * @return bool
     */
    protected function _copyPdfFile(XquantityOrderItem $item, string $destination, $replace = FileSystemInterface::EXISTS_REPLACE): bool
    {
        if (!$destination) {
            throw new \Exception('Destination Pfad ist fehlerhaft!');
        }
        $source = $item->get('field_document')->entity->getUri();
        if (\Drupal::service('file_system')->copy($source, $destination, $replace)) {
            return true;
        }
        return false;
    }
}
