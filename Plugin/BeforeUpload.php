<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Plugin;

use Magento\Catalog\Model\Product\Gallery\CreateHandler;

class BeforeUpload
{
    /**
     * Update rotate image
     *
     * @param CreateHandler $subject
     * @param object $product
     * @param array $arguments
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function beforeExecute(CreateHandler $subject, $product, $arguments = [])
    {
        $attrCode = $subject->getAttribute()->getAttributeCode();
        $value = $product->getData($attrCode);
        if (isset($value['images'])) {
            foreach ($value['images'] as $key => $image) {
                if (isset($image['removed']) && !empty($image['removed'])
                    || (isset($image['value_id']) && empty($image['value_id']))) {
                    continue;
                }
                if (isset($image['rotate']) && !empty($image['rotate']) && $image['value_id']) {
                    $value['images'][$key]['recreate'] = true;
                }
            }
        }
        $product->setData(
            $attrCode,
            $value
        );
        return [$product, $arguments];
    }
}
