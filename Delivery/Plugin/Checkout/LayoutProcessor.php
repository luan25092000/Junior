<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 24/06/2021
 * Time: 09:25
 */

namespace Magenest\Delivery\Plugin\Checkout;

use Magenest\Delivery\Helper\Delivery;

class LayoutProcessor
{
    /**
     * @var Delivery
     */
    protected $helper;

    /**
     * LayoutProcessor constructor.
     * @param Delivery $helper
     */
    public function __construct(Delivery $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param $jsLayout
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, $jsLayout)
    {
        $children = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['before-shipping-method-form']['children']['shippingAdditional']['children'];
        $isComment = ($this->helper->isEnableCommentField()) ? true : false;
        $notice = $this->helper->getNoticeByAdmin();
        $children['delivery_date'] = array_merge($children, [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'dataScope' => 'shippingAddress.custom_attributes.delivery_date',
            'provider' => 'checkoutProvider',
            'label' => 'Delivery Date',
            'visible' => true,
            'notice' => $notice,
            'validation' => ['required-entry' => true],
            'sortOrder' => 200,
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/date',
                'options' => [],
                'id' => 'delivery-date'
            ]
        ]);

        $children['delivery_time'] = array_merge($children, [
            'component' => 'Magento_Ui/js/form/element/select',
            'dataScope' => 'shippingAddress.custom_attributes.delivery_time',
            'provider' => 'checkoutProvider',
            'label' => 'Delivery Time Interval',
            'visible' => true,
            'sortOrder' => 250,
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'options' => $this->helper->getDeliveryTime(),
                'id' => 'delivery-time'
            ],
            'validation' => [
                'required-entry' => true
            ]
        ]);

        $children['comment'] = array_merge($children, [
            'component' => 'Magento_Ui/js/form/element/textarea',
            'dataScope' => 'shippingAddress.custom_attributes.delivery_comment',
            'provider' => 'checkoutProvider',
            'label' => 'Comment',
            'visible' => $isComment,
            'sortOrder' => 300,
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'id' => 'comment'
            ],
            'validation' => [
                'required-entry' => true,
                'max_text_length' => 500
            ]
        ]);

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['before-shipping-method-form']['children']['shippingAdditional']['children'] = $children;
        return $jsLayout;
    }
}
