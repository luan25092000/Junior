<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 23/06/2021
 * Time: 23:45
 */

namespace Magenest\Delivery\Model\Source;

class DateFormat implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $options = [];
        $formats = [
            'yyyy-mm-dd' => 'Y-m-d',
            'mm/dd/yyyy' => 'm/d/Y',
            'dd/mm/yyyy' => 'd/m/Y',
            'd/m/yy' => 'j/n/y',
            'd/m/yyyy' => 'j/n/Y',
            'dd.mm.yyyy' => 'd.m.Y',
            'dd.mm.yy' => 'd.m.y',
            'd.m.yy' => 'j.n.y',
            'd.m.yyyy' => 'j.n.Y',
            'dd-mm-yy' => 'd-m-y',
            'yyyy.mm.dd' => 'Y.m.d',
            'dd-mm-yyyy' => 'd-m-Y',
            'yyyy/mm/dd' => 'Y/m/d',
            'yy/mm/dd' => 'y/m/d',
            'dd/mm/yy' => 'd/m/y',
            'mm/dd/yy' => 'm/d/y',
            'dd/mm yyyy' => 'd/m/Y',
            'yyyy mm dd' => 'Y m d'
        ];
        $date = date_create("2021-03-17");
        foreach ($formats as $key => $format) {
            $options[] = ['value' => $format, 'label' => $key . ' (' . date_format($date, $format) . ')'];
        }
        return $options;
    }
}
