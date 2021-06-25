define(function () {
    'use strict';
    var mixin = {
        isDeliveryDate: function (elem) {
            var result = false;
            if (elem.attribute_code == 'delivery_date' && elem.value) {
                result = true;
            }
            return result;
        },

        isDeliveryTime: function (elem) {
            var result = false;
            if (elem.attribute_code == 'delivery_time' && elem.value) {
                result = true;
            }
            return result;
        },

        isDeliveryComment: function (elem) {
            var result = false;
            if (elem.attribute_code == 'delivery_comment' && elem.value) {
                result = true;
            }
            return result;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});