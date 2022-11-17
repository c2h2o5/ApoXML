<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;
use Drupal\ubg_apoxml\Apogee\ApogeeInterface;

/**
 * [Class Part]
 * Generiert <Part>- Block in der ApoXML Datei.
 * 
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class Part implements ApogeeInterface
{

    /**
     * Generiert <Part>- Block in der ApoXML Datei.
     * @param Order $order
     * @param XquantityOrderItem $items
     * 
     * @todo 
     * Innerhalb Part > PartType gibt es mehrere Optionen, die ausgewaehlt werden koennen.
     * Zum Beispiel:
     * 1.Body - Main content/text part of brochure, book, magazine
     * 2.Cover - wraps around the Body
     * 3.FlatWork - Unbound Not folded
     * 4.Folded - Unbound folded
     * 5.Insert - (special bound body part with page numbering starting at 1), independent of Body Part
     * Es wird FlatWork hardkodiert. Falls was anders benoetigt wird, muss weiter entwickelt werden.
     * 
     * Es muss auch Amount richtig gesetzt werden.
     * 
     * @return string
     */
    public static function generate(Order $order, XquantityOrderItem $items = null): array
    {
        $part = [
            'Part' => [
                '_attr' => [
                    'PartType' => 'FlatWork',
                    'PartName' => 'Test', // TO DO: muss der richtige PartName geholt werden.
                    'Amount' => 100, // TO DO: muss der richtige Amount geholt werden.
                ]
            ]
        ];

        $part['Part']['_childs'] = self::addPages($items);
        $part['Part']['_childs'] = array_merge($part['Part']['_childs'], self::addColor());
        $part['Part']['_childs'] = array_merge($part['Part']['_childs'], self::addPaperStock());

        return $part;
    }

    /**
     * Liefert das <Color>- Block innerhalb des <Part> Blocks in der ApoXML- Datei.
     * @todo
     * Es muss weiterentwickelt werden, weil die Farbnummern in dem Fall hardkodiert sind, weil es noch nicht klar ist
     * wie das innerhalb Kreativ-Portal definiert werden soll.
     * @return array
     */
    protected static function addColor(): array
    {
        $color = [
            'Color' => [
                '_attr' => [
                    'NrColors' => 4
                ]
            ]
        ];
        return $color;
    }

    /**
     *  Liefert das <PaperStock>- Block innerhalb des <Part> Blocks in der ApoXML- Datei.
     * @return array
     */
    protected static function addPaperStock(): array
    {
        $stock = [
            'PaperStock' => [
                '_attr' => [
                    'StockName' => '',
                    'Weight' => '',
                    'Grade' => '',
                    'Thickness' => '',
                ]
            ]
        ];

        return $stock;
    }

    /**
     *  Liefert das <Pages>- Block innerhalb des <Part> Blocks in der ApoXML- Datei.
     * @return array
     */
    protected static function addPages(XquantityOrderItem $item): array
    {
        $pages = [
            'Pages' => [
                '_attr' => [
                    'URL' => $item->get('field_document')->entity->label(),
                    'PageCount' => self::_getItemField('pageCount', $item),
                    'PageWidth' => self::_getItemField('pageWidth', $item),
                    'PageHeight' => self::_getItemField('pageHeight', $item),
                ]
            ]
        ];
        return $pages;
    }

    /**
     * @param string $name Mögliche Werte: pageCount, pageWidth, pageHeight
     * @param XquantityOrderItem $item
     * 
     * @throws Exception Falls der Feld nicht korrekt definiert ist.
     * @return string
     */
    private static function _getItemField(string $name, XquantityOrderItem $item): string
    {
        switch ($name) {
            case 'pageCount':
                $name = 'field_document_number_of_pages';
                break;

            case 'pageWidth':
                $name = 'field_document_width';
                break;

            case 'pageHeight':
                $name = 'field_document_height';
                break;

            default:
                throw new \Exception('Invalid name!');
                break;
        }
        $field = $item->get($name);
        if ($field->isEmpty()) {
            Common::log("Der Feld {$name} ist leer!");
            return '';
        }
        return $field->value;
    }
}
