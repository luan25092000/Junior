<?php

namespace Magenest\Handle404Page\Plugin;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Router\DefaultRouter;
use Magento\Search\Model\ResourceModel\Query\CollectionFactory as QueryCollection;

class CustomRouter
{
    /**
     * @var CategoryCollection
     */
    protected $_categoryCollection;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var QueryCollection
     */
    protected $_queryCollection;

    /**
     * @var ProductCollection
     */
    protected $_productCollection;

    /**
     * CustomRouter constructor.
     * @param CategoryCollection $categoryCollection
     * @param ActionFactory $actionFactory
     * @param QueryCollection $queryCollection
     * @param ProductCollection $productCollection
     */
    public function __construct(
        CategoryCollection $categoryCollection,
        ActionFactory $actionFactory,
        QueryCollection $queryCollection,
        ProductCollection $productCollection
    ) {
        $this->_categoryCollection = $categoryCollection;
        $this->actionFactory = $actionFactory;
        $this->_queryCollection = $queryCollection;
        $this->_productCollection = $productCollection;
    }

    /**
     * @param DefaultRouter $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface
     */
    public function aroundMatch(DefaultRouter $subject, callable $proceed, RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        // Handle category
        $category = $this->searchCategory($identifier);
        if (!is_null($category->getId())) {
            $request->setModuleName('catalog')->setControllerName('category')->setActionName('view')
                ->setParams(['id' => $category->getId()]);
            echo "<script>alert('The path " . $request->getPathInfo() . ' does not exist. Are you looking for ' . $category->getName() . "');</script>";
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        // Handle searchterm
        $searchTerm = $this->getSearchTerm(urldecode($identifier));
        if (!is_null($searchTerm->getId())) {
            $request->setModuleName('catalogsearch')->setControllerName('result')->setActionName('index')
                ->setParams(['q' => urlencode($searchTerm->getQueryText())]);
            return $this->actionFactory->create(Forward::class, ['request' => $request]);
        }
        // Handle product
        if (strlen($identifier) > 3) {
            $product = $this->searchProduct($identifier);
            if (!is_null($product->getId())) {
                $request->setModuleName('catalog')->setControllerName('product')->setActionName('view')
                    ->setParams(['id' => $product->getId()]);
                return $this->actionFactory->create(Forward::class, ['request' => $request]);
            }
        }
        return $proceed($request);
    }

    /**
     * @param $q
     * @return \Magento\Framework\DataObject
     */
    public function searchCategory($q)
    {
        $collection = $this->_categoryCollection->create();
        $collection->addFieldToFilter('name', ['like' => '%' . $q . '%']);
        return $collection->getFirstItem();
    }

    /**
     * @param $q
     * @return mixed
     */
    public function getSearchTerm($q)
    {
        $collection = $this->_queryCollection->create();
        $collection->addFieldToFilter('query_text', ['like' => '%' . $q . '%']);
        $collection->addFieldToFilter('is_active', 1);
        return $collection->getFirstItem();
    }

    /**
     * @param $q
     * @return \Magento\Framework\DataObject
     */
    public function searchProduct($q)
    {
        $collection = $this->_productCollection->create();
        $collection->addFieldToFilter('name', ['like' => '%' . $q . '%']);
        return $collection->getFirstItem();
    }
}
