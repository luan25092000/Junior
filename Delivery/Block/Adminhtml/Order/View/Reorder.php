<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 25/06/2021
 * Time: 01:16
 */

namespace Magenest\Delivery\Block\Adminhtml\Order\View;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Psr\Log\LoggerInterface;
use \Magento\Backend\Block\Template as Template;
use Magento\Sales\Model\OrderFactory;

class Reorder extends Template
{
    const DELIVERY_DISPLAY_ON = 'delivery_config/general_config/delivery_display_on';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * Delivery constructor.
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param Template\Context $context
     * @param OrderFactory $orderFactory
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        OrderFactory $orderFactory,
        array $data = [],
        JsonHelper $jsonHelper = null,
        DirectoryHelper $directoryHelper = null
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->orderFactory = $orderFactory;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * Get delivery information
     * @return mixed
     */
    public function getDeliveryInfo()
    {
        $id = $this->getRequest()->getParam('order_id', null);
        try {
            return $this->orderFactory->create()->load($id)->getData();
        } catch (\Exception $exception) {
            $this->logger->critical(__($exception->getMessage()));
        }
    }

    /**
     * Check display delivery information section
     * @return bool
     */
    public function isDisplayDelivery()
    {
        $data = $this->_scopeConfig->getValue(self::DELIVERY_DISPLAY_ON, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $data = explode(',', $data);
        return in_array('reorder_page', $data, true);
    }
}
