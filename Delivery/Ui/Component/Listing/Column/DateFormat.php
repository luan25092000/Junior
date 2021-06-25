<?php
/**
 * Created by PhpStorm.
 * User: Luan Nguyen
 * Date: 25/06/2021
 * Time: 01:00
 */

namespace Magenest\Delivery\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class DateFormat extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (!is_null($item['delivery_date'])) {
                    $date = date_create($item['delivery_date']);
                    $item['delivery_date'] = date_format($date, "d-m-Y");
                }
            }
        }
        return $dataSource;
    }
}
