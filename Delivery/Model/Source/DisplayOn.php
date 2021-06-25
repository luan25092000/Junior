<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 00:11
 */

namespace Magenest\Delivery\Model\Source;

class DisplayOn implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'order_view_page', 'label' => __('Order View Page (Backend)')],
            ['value' => 'reorder_page', 'label' => __('New/Edit/Reorder Order Page (Backend)')],
            ['value' => 'invoice', 'label' => __('Invoice View Page (Backend)')],
            ['value' => 'payment', 'label' => __('Shipment View Page (Backend)')],
            ['value' => 'frontend', 'label' => __('Order Info Page (Frontend)')]
        ];
    }
}
