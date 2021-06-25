<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 14:14
 */

namespace Magenest\Delivery\Block\Order;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class Delivery extends Template
{
    const DELIVERY_DISPLAY_ON = 'delivery_config/general_config/delivery_display_on';

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Delivery constructor.
     * @param Template\Context $context
     * @param OrderFactory $orderFactory
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        OrderFactory $orderFactory,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
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
        return in_array('frontend', $data, true);
    }
}
