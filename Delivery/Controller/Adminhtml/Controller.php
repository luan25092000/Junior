<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 13:38
 */

namespace Magenest\Delivery\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magenest\Delivery\Model\DeliveryFactory;
use Magento\Framework\Message\ManagerInterface;
use Magenest\Delivery\Model\ResourceModel\Delivery\CollectionFactory as DeliveryCollection;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Controller
 * @package Magenest\Delivery\Controller\Adminhtml
 */
abstract class Controller extends Action
{
    /**
     * Page factory
     *
     * @var Page
     */
    protected $_resultPage;

    /**
     * Page result factory
     *
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var DeliveryFactory
     */
    protected $_deliveryFactory;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var DeliveryCollection
     */
    protected $_deliveryCollection;

    /**
     * @var Filter
     */
    protected $_filter;

    /**
     * Controller constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param DeliveryFactory $deliveryFactory
     * @param ManagerInterface $messageManager
     * @param DeliveryCollection $deliveryCollection
     * @param Filter $filter
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DeliveryFactory $deliveryFactory,
        ManagerInterface $messageManager,
        DeliveryCollection $deliveryCollection,
        Filter $filter
    ) {
        $this->_deliveryFactory = $deliveryFactory;
        $this->_messageManager = $messageManager;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_deliveryCollection = $deliveryCollection;
        $this->_filter = $filter;
        parent::__construct($context);
    }

    /**
     * instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->_resultPageFactory->create();
        }

        return $this->_resultPage;
    }

    /**
     * @param null $mode
     * @return $this
     */
    protected function _setPageData($mode = null)
    {
        $resultPage = $this->getResultPage();
        if ($mode === "Edit") {
            $title = __('Edit Delivery Time');
            $resultPage->setActiveMenu('Magenest_Delivery::edit');
            $resultPage->getConfig()->getTitle()->prepend($title);
        } elseif ($mode === "Create") {
            $title = __('Add Delivery Time');
            $resultPage->setActiveMenu('Magenest_Delivery::edit');
            $resultPage->getConfig()->getTitle()->prepend($title);
        } else {
            $title = __('Delivery Time');
            $resultPage->setActiveMenu('Magenest_Delivery::manager');
            $resultPage->getConfig()->getTitle()->prepend($title);
        }

        return $this;
    }

    /**
     * Check ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Delivery::delivery');
    }
}
