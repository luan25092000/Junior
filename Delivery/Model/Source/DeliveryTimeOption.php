<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 15:53
 */

namespace Magenest\Delivery\Model\Source;

class DeliveryTimeOption implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $options = [];
        for ($i = 1;$i<=23;$i++) {
            if ($i <= 9) {
                $options[] = ['value' => '0' . $i . ':00','label' => '0' . $i . ':00'];
            } else {
                $options[] = ['value' =>  $i . ':00','label' =>  $i . ':00'];
            }
        }
        return $options;
    }
}
