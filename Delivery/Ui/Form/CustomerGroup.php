<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 17:09
 */

namespace Magenest\Delivery\Ui\Form;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class CustomerGroup extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * CustomerGroup constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        GroupRepositoryInterface $groupRepository,
        array $components = [],
        array $data = []
    ) {
        $this->groupRepository = $groupRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!empty($item['delivery_id'])) {
                    $item['customer_group_id'] = $this->getCustomerGroup($item['customer_group_id']);
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param $customerGroupIds
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerGroup($customerGroupIds)
    {
        $result = '';
        $data = explode(',', $customerGroupIds);
        if (is_array($data)) {
            foreach ($data as $item) {
                $name = $this->groupRepository->getById($item)->getCode();
                $result .= "<p>" . $name . "</p>";
            }
        }
        return $result;
    }
}
