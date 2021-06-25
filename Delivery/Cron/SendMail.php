<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 25/06/2021
 * Time: 02:19
 */

namespace Magenest\Delivery\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;

class SendMail
{
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * SendMail constructor.
     * @param TransportBuilder $transportBuilder
     * @param CollectionFactory $collectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     * @param LoggerInterface $logger
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger;
    }

    /**
     * Send mail
     */
    public function sendMail()
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('state', ['in' => [Order::STATE_NEW, Order::STATE_PROCESSING, Order::STATE_PENDING_PAYMENT]]);
        foreach ($collection as $order) {
            $this->handle($order);
        }
    }

    /**
     * Handle time to send
     * @param $order
     *
     */
    public function handle($order)
    {
        $deliveryDate = $order->getDeliveryDate();
        $deliveryTime = $order->getDeliveryTime();
        $email = $order->getCustomerEmail();
        if (!is_null($deliveryDate)) {
            $deliveryDate = date('Y-m-d H:i:s', strtotime($deliveryDate));
        }
        if (!is_null($deliveryTime)) {
            $arr = explode(',', $deliveryTime);
            foreach ($arr as $row) {
                $time = str_replace(":00", "", explode('-', $row)[0]);
                if (date('d', strtotime($deliveryDate)) == date('d')) {
                    if ((date('H') + 1) == $time) {
                        $this->execute($email);
                    }
                }
            }
        }
    }

    /**
     * Execute send mail
     * @param $email
     */
    public function execute($email)
    {
        try {
            $this->inlineTranslation->suspend();
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('email_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([])
                ->setFromByScope('general')
                ->addTo($email)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->critical('Send mail error ' . $e->getMessage());
        }
    }
}
