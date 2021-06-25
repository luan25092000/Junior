<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 13:25
 */

namespace Magenest\Delivery\Plugin\Checkout;

use Magento\Quote\Model\BillingAddressManagement;

class BillingAddress
{
    /**
     * @param BillingAddressManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     * @param bool $useForShipping
     */
    public function beforeAssign(
        BillingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address,
        $useForShipping = false
    ) {
        $extAttributes = $address->getExtensionAttributes();
        $address->setDeliveryDate($extAttributes->getDeliveryDate());
        $address->setDeliveryTime($extAttributes->getDeliveryTime());
        $address->setDeliveryComment($extAttributes->getDeliveryComment());
    }
}
