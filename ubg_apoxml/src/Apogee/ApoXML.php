<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;
use Drupal\ubg_apoxml\Apogee\ApogeeInterface;
use Drupal\ubg_apoxml\Apogee\Common;
use Drupal\ubg_apoxml\Apogee\Customer;
use Drupal\ubg_apoxml\Apogee\Binding;
use Drupal\ubg_apoxml\Apogee\Part;

/**
 * [Class ApoXML]
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class ApoXML implements ApogeeInterface
{


    /**
     * @param Order $order
     * @param XquantityOrderItem|null $items
     * 
     * @todo Es muss den JobName noch gefixt werden!
     * 
     * @return string
     */
    public static function generate(Order $order, XquantityOrderItem $items = null): array
    {
        $apo = [
            'ns0:ApoXML' => [
                '_attr' => [
                    'xmlns:ns0' => Common::getConfig('apoxml_ns'),
                    'OrderNumber' => $order->getOrderNumber(),
                    'JobName' => 'fwafa', // TO DO FIX
                    'ProductType' => 'Flatwork',
                    'AgentName' => Common::getConfig('apoxml_agent_name'),
                    'AgentVersion' => Common::getConfig('apoxml_agent_version'),
                    'Unit' => Common::getConfig('unit'),
                    'DecimalSeparator' => Common::getConfig('decimal_separator'),
                ]
            ]
        ];

        $apo['ns0:ApoXML']['_childs'] = Customer::generate($order, $items);
        $apo['ns0:ApoXML']['_childs'] = array_merge($apo['ns0:ApoXML']['_childs'], Binding::generate($order, $items));
        $apo['ns0:ApoXML']['_childs'] = array_merge($apo['ns0:ApoXML']['_childs'], Part::generate($order, $items));
        return $apo;
    }


    /**
     * Liefert der JobName auf basis Produkte in einer Bestellung
     * @param XquantityOrderItem $items
     * @todo FIX!!!
     * @throws Exception Falls keine Produkte eingetroffen sind
     * @return string Der JobName
     */
    protected static function getJobName(XquantityOrderItem $items): string
    {
        if (!count($items)) {
            throw new \Exception('Der JobName kann nicht generiert werden.');
        }
        $jobName = 'DADA';
        foreach ($items as $item) {
        }

        return $jobName;
    }
}
