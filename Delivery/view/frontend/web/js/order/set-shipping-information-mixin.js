define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            shippingAddress['extension_attributes']['delivery_date'] = jQuery('[name="custom_attributes[delivery_date]"]').val();
            shippingAddress['extension_attributes']['delivery_time'] = jQuery('[name="custom_attributes[delivery_time]"]').val();
            shippingAddress['extension_attributes']['delivery_comment'] = jQuery('[name="custom_attributes[delivery_comment]"]').val();
            return originalAction();
        });
    };
});