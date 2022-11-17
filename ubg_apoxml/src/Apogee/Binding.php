<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;
use Drupal\ubg_apoxml\Apogee\ApogeeInterface;

/**
 * [Class Binding]
 * Generiert das <Binding> Block in der ApoXML- Datei.
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class Binding implements ApogeeInterface
{

    /**
     * @param Order $order
     * @param XquantityOrderItem $items
     * @todo Es wird als Binding Method - Unbound hardkodiert. 
     * Mögliche Werte: Unbound, Nested, Stacked, falls das erweitert werden muss.
     * 
     * @return string
     */
    public static function generate(Order $order, XquantityOrderItem $items = null): array
    {
        return [
            'Binding' => [
                '_attr' => [
                    'Method' => 'Unbound'
                ]
            ]
        ];
    }
}
