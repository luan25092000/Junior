<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 25/06/2021
 * Time: 09:11
 */

namespace Magenest\Delivery\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Template as Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Sales\Model\Order\InvoiceRepository;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Info extends Template
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
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Delivery constructor.
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param Template\Context $context
     * @param OrderFactory $orderFactory
     * @param InvoiceRepository $invoiceRepository
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        Template\Context $context,
        OrderFactory $orderFactory,
        InvoiceRepository $invoiceRepository,
        array $data = [],
        JsonHelper $jsonHelper = null,
        DirectoryHelper $directoryHelper = null
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->orderFactory = $orderFactory;
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    /**
     * Get delivery information
     * @return mixed
     */
    public function getDeliveryInfo()
    {
        $invoiceId = $this->getRequest()->getParam('invoice_id', null);
        try {
            $id = $this->invoiceRepository->get($invoiceId)->getOrderId();
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
        return in_array('invoice', $data, true);
    }
}
