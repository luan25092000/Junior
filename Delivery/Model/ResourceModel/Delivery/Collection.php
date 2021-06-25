<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 14:53
 */

namespace Magenest\Delivery\Model\ResourceModel\Delivery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'delivery_id';

    protected function _construct()
    {
        $this->_init('Magenest\Delivery\Model\Delivery', 'Magenest\Delivery\Model\ResourceModel\Delivery');
    }
}
