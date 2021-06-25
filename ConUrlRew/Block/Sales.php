<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 22/06/2021
 * Time: 17:25
 */

namespace Magenest\ConUrlRew\Block;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\Framework\Pricing\Helper\Data;

class Sales extends Template
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * @var Image
     */
    protected $helperImport;

    /**
     * @var UrlRewriteCollectionFactory
     */
    protected $urlRewriteCollectionFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var Data
     */
    protected $priceHelper;

    /**
     * Sales constructor.
     * @param Template\Context $context
     * @param ProductCollection $productCollection
     * @param ResourceConnection $resourceConnection
     * @param Image $helperImport
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     * @param UrlInterface $url
     * @param Data $priceHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductCollection $productCollection,
        ResourceConnection $resourceConnection,
        Image $helperImport,
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory,
        UrlInterface $url,
        Data $priceHelper,
        array $data = []
    ) {
        $this->productCollection = $productCollection;
        $this->resourceConnection = $resourceConnection;
        $this->helperImport = $helperImport;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
        $this->url = $url;
        $this->priceHelper = $priceHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductSale()
    {
        $connection = $this->resourceConnection->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $table = $connection->getTableName('url_rewrite');
        $productIds = $connection->fetchAll('SELECT entity_id FROM ' . $table . ' WHERE request_path LIKE "sale/%"');
        return $this->productCollection->create()
            ->addAttributeToSelect("*")
            ->addAttributeToFilter('entity_id', ['in' => $productIds]);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getImageUrl(\Magento\Catalog\Model\Product $product)
    {
        $imageUrl = $this->helperImport->init($product, 'product_page_image')
            ->setImageFile($product->getImage())
            ->resize(150, 150)
            ->getUrl();
        return $imageUrl;
    }

    /**
     * @param $productId
     * @return string
     */
    public function getUrlRewrite($productId)
    {
        $urlRwCollection = $this->urlRewriteCollectionFactory->create()
                            ->addFieldToFilter('entity_type', 'product')
                            ->addFieldToFilter('entity_id', $productId)
                            ->addFieldToFilter('store_id', 1)
                            ->addFieldToFilter('request_path', ['like' => 'sale/%']);
        $requestPath = $urlRwCollection->getFirstItem()->getRequestPath();
        return $this->url->getBaseUrl() . $requestPath;
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }
}
