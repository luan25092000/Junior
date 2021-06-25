<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 13:37
 */

namespace Magenest\Delivery\Controller\Adminhtml\Delivery;

use Magenest\Delivery\Controller\Adminhtml\Controller;

class Index extends Controller
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_setPageData();

        return $this->getResultPage();
    }
}