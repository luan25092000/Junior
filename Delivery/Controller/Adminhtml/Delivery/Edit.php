<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 15:24
 */

namespace Magenest\Delivery\Controller\Adminhtml\Delivery;

use Magenest\Delivery\Controller\Adminhtml\Controller;

class Edit extends Controller
{
    const ADMIN_RESOURCE = 'Magenest_Delivery::edit';

    public function execute()
    {
        $id = $this->getRequest()->getParam('id', null);
        if ($id) {
            $this->_setPageData($mode = "Edit");
        } else {
            $this->_setPageData($mode = "Create");
        }

        return $this->getResultPage();
    }
}
