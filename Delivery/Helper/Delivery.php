<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 09:28
 */

namespace Magenest\Delivery\Helper;

use Magenest\Delivery\Model\ResourceModel\Delivery\CollectionFactory as DeliveryCollection;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;

class Delivery extends AbstractHelper
{
    const DELIVERY_CONFIG_ENABLE_COMMENT = 'delivery_config/general_config/enable_comment_field';

    const DELIVERY_CONFIG_NOTICE_ADMIN = 'delivery_config/general_config/notice_by_admin';

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var DeliveryCollection
     */
    protected $deliveryCollection;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Delivery constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param ResourceConnection $resource
     * @param DeliveryCollection $deliveryCollection
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        ResourceConnection $resource,
        DeliveryCollection $deliveryCollection,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->resource = $resource;
        $this->deliveryCollection = $deliveryCollection;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDeliveryTime()
    {
        $options = [];
        $customerGroup=$this->customerSession->getCustomer()->getGroupId();
        $currentStore = $this->storeManager->getStore()->getId();
        $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $table = $connection->getTableName('magenest_delivery_time');
        $deliveryCollection = $this->deliveryCollection->create();
        foreach ($deliveryCollection as $model) {
            $row = $model->getData();
            $storeIds = $row['store_id'];
            $cusGroups = $row['customer_group_id'];
            $result = $connection->fetchAll('SELECT delivery_time FROM ' . $table . ' WHERE ' . $currentStore . ' IN (' . $storeIds . ') AND ' . $customerGroup . ' IN (' . $cusGroups . ')');
            foreach ($result as $item) {
                $arr = explode(',', $item['delivery_time']);
                foreach ($arr as $row) {
                    $options[] = ['value' => $row, 'label' => $row];
                }
            }
        }
        return array_unique($options, SORT_REGULAR);
    }

    /**
     * @return mixed
     */
    public function isEnableCommentField()
    {
        return $this->scopeConfig->getValue(
            self::DELIVERY_CONFIG_ENABLE_COMMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return mixed
     */
    public function getNoticeByAdmin()
    {
        return $this->scopeConfig->getValue(
            self::DELIVERY_CONFIG_NOTICE_ADMIN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );
    }
}
