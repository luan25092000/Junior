<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 16:08
 */

namespace Magenest\Delivery\Controller\Adminhtml\Delivery;

use Magenest\Delivery\Controller\Adminhtml\Controller;

class Delete extends Controller
{
    public function execute()
    {
        $count = 0;
        try {
            $collection = $this->_filter->getCollection($this->_deliveryCollection->create());
            foreach ($collection->getItems() as $item) {
                $model = $this->_deliveryFactory->create()->load($item->getData('delivery_id'));
                $model->delete();
                $count++;
            }
        } catch (LocalizedException $e) {
            throwException($e);
        }
        if ($count) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        }
        $this->_redirect('*/*/index');
    }
}