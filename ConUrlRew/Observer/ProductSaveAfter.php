<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 22/06/2021
 * Time: 15:51
 */

namespace Magenest\ConUrlRew\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\UrlRewrite\Model\UrlRewriteFactory;

class ProductSaveAfter implements ObserverInterface
{
    /**
     * @var UrlRewriteFactory
     */
    protected $_urlRewriteFactory;

    /**
     * @var UrlRewriteCollectionFactory
     */
    protected $_urlRewriteCollectionFactory;

    /**
     * ProductSaveAfter constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     */
    public function __construct(UrlRewriteFactory $urlRewriteFactory, UrlRewriteCollectionFactory $urlRewriteCollectionFactory)
    {
        $this->_urlRewriteFactory = $urlRewriteFactory;
        $this->_urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $specialPrice = $product->getSpecialPrice();
        $basePrice = $product->getPrice();
        if ($specialPrice < $basePrice) {
            $urlRewriteModel = $this->_urlRewriteCollectionFactory->create()
                ->addFieldToFilter('entity_type', 'product')
                ->addFieldToFilter('request_path', ['like' => '%sale/' . $product->getSku() . '%'])
                ->addFieldToFilter('store_id', 0)
                ->getFirstItem();
            if (is_null($urlRewriteModel->getUrlRewriteId())) {
                $urlRewriteModel = $this->_urlRewriteFactory->create();
                $urlRewriteModel->setStoreId(1);
                $urlRewriteModel->setEntityType('product');
                $urlRewriteModel->setEntityId($product->getId());
                $urlRewriteModel->setTargetPath('catalog/product/view/id/' . $product->getId());
                $urlRewriteModel->setRequestPath('sale/' . $product->getSku());
                $urlRewriteModel->save();
            }
        }
    }
}
