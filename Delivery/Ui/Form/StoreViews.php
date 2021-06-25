<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 16:48
 */

namespace Magenest\Delivery\Ui\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;

class StoreViews extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * StoreViews constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!empty($item['delivery_id'])) {
                    $item['store_id'] = $this->getStoreName($item['store_id']);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param $storeIds
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreName($storeIds)
    {
        $result = '';
        $data = explode(',', $storeIds);
        if (is_array($data)) {
            foreach ($data as $item) {
                $storeName = $this->storeManager->getStore($item)->getName();
                $result .= "<p>" . $storeName . "</p>";
            }
        }
        return $result;
    }
}
