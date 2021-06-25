<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 16:08
 */

namespace Magenest\Delivery\Controller\Adminhtml\Delivery;

use Magenest\Delivery\Controller\Adminhtml\Controller;

class Save extends Controller
{
    const ADMIN_RESOURCE = 'Magenest_Delivery::save';

    public function execute()
    {
        $deliveryTime = '';
        $storeIds = '';
        $customerGroupIds = '';
        $data = $this->getRequest()->getParams();
        foreach ($data['delivery_time'] as $row) {
            $from = $row['from'];
            $to = $row['to'];
            settype($from, 'int');
            settype($to, 'int');
            if ($to >= $from + 1) {
                $deliveryTime .= $row['from'] . '-' . $row['to'] . ',';
                foreach ($data['store_id'] as $row) {
                    $storeIds .= $row . ',';
                }
            }
        }
        foreach ($data['customer_group_id'] as $row) {
            $customerGroupIds .= $row . ',';
        }
        $params = [
            'delivery_time' => rtrim($deliveryTime, ','),
            'store_id' => rtrim($storeIds, ','),
            'customer_group_id' => rtrim($customerGroupIds, ',')
        ];
        $deliveryModel = $this->_deliveryFactory->create();
        if (isset($data['delivery_id'])) {
            $deliveryModel->load($data['delivery_id']);
            $deliveryModel->addData($params);
            $this->_messageManager->addSuccessMessage(__('Update Successfully !'));
        } else {
            $deliveryModel->addData($params);
            $this->_messageManager->addSuccessMessage(__('Insert Successfully !'));
        }
        $deliveryModel->save();
        $this->_redirect('*/*/index');
    }
}
