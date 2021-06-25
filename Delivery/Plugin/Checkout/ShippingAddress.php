<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 13:06
 */

namespace Magenest\Delivery\Plugin\Checkout;

use Magento\Quote\Model\ShippingAddressManagement;

class ShippingAddress
{

    /**
     * @param ShippingAddressManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function beforeAssign(ShippingAddressManagement $subject, $cartId, \Magento\Quote\Api\Data\AddressInterface $address)
    {
        $extAttributes = $address->getExtensionAttributes();
        $address->setDeliveryDate($extAttributes->getDeliveryDate());
        $address->setDeliveryTime($extAttributes->getDeliveryTime());
        $address->setDeliveryComment($extAttributes->getDeliveryComment());
    }
}
