<?php
namespace Learning\Allplugins\Plugin;

class DeduplicatePaymentMethods
{
    /**
     * Deduplicate payment method filter options by label
     *
     * @param \Magento\Payment\Model\Config\Source\Allmethods $subject
     * @param array $result
     * @return array
     */
    public function afterToOptionArray(\Magento\Payment\Model\Config\Source\Allmethods $subject, array $result): array
    {
        $uniqueOptions = [];
        $seenLabels = [];

        foreach ($result as $key => $option) {
            if (empty($option['label'])) {
                continue;
            }

            // Convert Phrase objects to string if needed
            $labelString = (string)$option['label'];

            // Clean string: remove trailing "-test", strip extra spaces, lower-case for comparison
            $cleanLabel = preg_replace('/\s*-\s*test$/i', '', $labelString);
            $normalizedKey = strtolower(trim($cleanLabel));

            // Keep only the first occurrence of each unique payment title
            if (!isset($seenLabels[$normalizedKey])) {
                $seenLabels[$normalizedKey] = true;
                
                // Keep clean label format for the UI dropdown
                $option['label'] = ucwords($cleanLabel); 
                $uniqueOptions[] = $option;
            }
        }

        return $uniqueOptions;
    }
}