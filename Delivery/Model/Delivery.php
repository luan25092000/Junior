<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 14:54
 */

namespace Magenest\Delivery\Model;

class Delivery extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Magenest\Delivery\Model\ResourceModel\Delivery');
    }
}
