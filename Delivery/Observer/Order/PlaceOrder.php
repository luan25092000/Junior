<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 14:03
 */

namespace Magenest\Delivery\Observer\Order;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class PlaceOrder implements ObserverInterface
{
    protected $checkoutSession;

    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $address = $this->checkoutSession->getQuote()->getShippingAddress();
        $deliveryDate = $address->getData('delivery_date');
        $deliveryTime = $address->getData('delivery_time');
        $deliveryComment = $address->getData('delivery_comment');
        $order->setDeliveryDate($deliveryDate);
        $order->setDeliveryTime($deliveryTime);
        $order->setDeliveryComment($deliveryComment);
    }
}
