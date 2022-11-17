<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;

/**
 * [Interface ApogeeInterface]
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
interface ApogeeInterface
{
    /**
     * Muss dafuer sorgen, einen Teil in der ApoXML- Datei zu generieren.
     * @param Order $order
     * @param XquantityOrderItem|null $items
     * 
     * @return array
     */
    public static function generate(Order $order, XquantityOrderItem $items = null): array;
    // public static function generate(Order $order, XquantityOrderItem $items = null): string;
}
