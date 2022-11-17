<?php

namespace Drupal\ubg_apoxml\Apogee;

use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_xquantity\Entity\XquantityOrderItem;
use Drupal\ubg_apoxml\Apogee\ApogeeInterface;
use Drupal\ubg_apoxml\Apogee\Common;
use Drupal\user\Entity\User;

/**
 * [Class Customer]
 * Generiert CustomerContact- Block in der ApoXML Datei.
 * @author Krasimir Bachev <krasimir.bachev@ubgnet.de>
 */
class Customer implements ApogeeInterface
{

    /**
     * Generiert <CustomerContact>- Block in der ApoXML Datei.
     * @param Order $order
     * @param XquantityOrderItem|null $items
     * 
     * @return string
     */
    public static function generate(Order $order, XquantityOrderItem $items = null): array
    {
        $output['CustomerContact'] = [];
        $output['CustomerContact']['_childs'] = self::addPerson($order->getCustomer());
        $output['CustomerContact']['_childs'] = array_merge($output['CustomerContact']['_childs'], self::addCompany($order));
        return $output;
    }

    /**
     * Liefert die <Person/> Information innerhalb <CustomerContact> Block der ApoXML- Datei.
     * @param User $customer
     * 
     * @return string
     */
    protected static function addPerson(User $customer): array
    {
        return [
            'Person' => [
                '_attr' => [
                    'FirstName' => self::getCustomerFirstName($customer),
                    'LastName' => self::getCustomerLastName($customer),
                    'Email' => self::getCustomerMail($customer)
                ]
            ]
        ];
    }


    /**
     * Liefert die <Company/> Information innerhalb <CustomerContact> Block der ApoXML- Datei.
     * 
     * @return string
     */
    protected static function addCompany(Order $order): array
    {
        $customer = $order->getCustomer();
        return [
            'Company' => [
                '_attr' => [
                    'Company' => self::getCustomerFirstName($customer) . '.' . self::getCustomerLastName($customer)
                ]
            ]
        ];
    }

    /**
     * Liefert der Vorname des Kundes
     * @param User $customer
     * @throws Exception Falls der Vorname des Kundes leer ist.
     * @return string Der Vorname
     */
    protected static function getCustomerFirstName(User $customer): string
    {
        return Common::getCustomerField('field_user_firstname', $customer);
    }

    /**
     * Liefert der Name des Kundes
     * @param User $customer
     * @throws Exception Falls der Name des Kundes leer ist.
     * @return string Der Name
     */
    protected static function getCustomerLastName(User $customer): string
    {
        return Common::getCustomerField('field_user_lastname', $customer);
    }

    /**
     * Liefert die E-Mail des Kundes
     * @param User $customer
     * @throws Exception Falls die Mail des Kundes leer ist.
     * @return string Die E-Mail
     */
    protected static function getCustomerMail(User $customer): string
    {
        return Common::getCustomerField('mail', $customer);
    }
}
