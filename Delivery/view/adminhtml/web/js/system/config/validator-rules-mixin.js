define([
    'jquery'
], function ($) {
    'use strict';

    return function (target) {

        $.validator.addMethod(
            'validate-length-character',
            function (value) {
                if(value.length > 500) {
                    return false;
                }
                return true;
            },
            $.mage.__('Character length is not greater than 500')
        );

        return target;
    };
});