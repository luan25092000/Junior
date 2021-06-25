<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 15:43
 */

namespace Magenest\Delivery\Model\Source;

use Magenest\Delivery\Model\ResourceModel\Delivery\CollectionFactory as DeliveryCollection;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var
     */
    protected $deliveryCollection;

    /**
     * DataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param DeliveryCollection $deliveryCollection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DeliveryCollection $deliveryCollection,
        array $meta = [],
        array $data = []
    ) {
        $this->deliveryCollection = $deliveryCollection->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        } else {
            $this->_loadedData = [];
        }
        $items = $this->deliveryCollection->getItems();
        foreach ($items as $item) {
            $param = $this->convertDataToArr($item->getData());
            $this->_loadedData[$item->getId()] = $param;
        }
        return $this->_loadedData;
    }

    public function convertDataToArr($data)
    {
        $timeArr = explode(',', $data['delivery_time']);
        $storeArr = explode(',', $data['store_id']);
        $customerArr = explode(',', $data['customer_group_id']);
        $data['store_id'] = [];
        $data['customer_group_id'] = [];
        $data['delivery_time'] = [];
        foreach ($storeArr as $value) {
            $data['store_id'][] = $value;
        }
        foreach ($customerArr as $value) {
            $data['customer_group_id'][] = $value;
        }
        foreach ($timeArr as $key => $value) {
            $fromTo = explode('-', $value);
            settype($key, "string");
            $data['delivery_time'][] = ['from' => $fromTo[0], 'to' => $fromTo[1], 'record_id' => $key];
        }
        return $data;
    }

    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return mixed|void|null
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        return null;
    }
}
