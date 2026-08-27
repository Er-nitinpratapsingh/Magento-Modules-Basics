<?php
namespace Learning\Allplugins\Plugin;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OrderGridCollection;

class SalesOrderGridFilter
{
    public function beforeAddFieldToFilter(OrderGridCollection $subject, $field, $condition = null)
    {
        // Intercept filter applied to the payment method column
        if ($field === 'payment_method' || $field === 'main_table.payment_method') {
            if (isset($condition['eq'])) {
                $selectedMethod = $condition['eq'];

                // Map duplicate provider codes together
                $methodMapping = [
                    'braintree_applepay' => ['braintree_applepay', 'payment_services_paypal_apple_pay', 'checkoutcom_applepay'],
                    'payment_services_paypal_hosted_fields' => ['payment_services_paypal_hosted_fields', 'braintree_cc', 'vault'],
                ];

                if (array_key_exists($selectedMethod, $methodMapping)) {
                    return [$field, ['in' => $methodMapping[$selectedMethod]]];
                }
            }
        }

        return [$field, $condition];
    }
}