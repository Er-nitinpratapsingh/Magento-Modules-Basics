<?php

namespace Learning\CustomEavName\Plugin;

use Magento\Catalog\Model\Product;

class ProductNamePlugin
{
    public function afterGetName(Product $subject, $result)
    {
        $customValue = $subject->getData('custom_eav');

        if (!empty($customValue)) {
            $result .= ' - ' . $customValue;
        }

        return $result;
    }
}