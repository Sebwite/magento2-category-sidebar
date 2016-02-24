<?php namespace Sebwite\Sidebar\Model\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * This plugin fixes issue with filtration by store/website ID in \Magento\Catalog\Model\ProductRepository::getList()
 */
class SidebarFilter
{
    /**
     * aroundAddFieldToFilter method
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Closure                                                $proceed
     * @param                                                         $fields
     * @param null                                                    $condition
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function aroundAddFieldToFilter(ProductCollection $collection, \Closure $proceed, $fields, $condition = null)
    {
        if ( $fields === 'category_ids' )
        {

            // Get Price
            $price = isset($_GET[ 'price' ]) ? $_GET[ 'price' ] : false;

            // Get brand
            $brands = isset($_GET[ 'brands' ]) ? $_GET[ 'brands' ] : false;

            if ( $price )
            {

                $price = explode('-', $price);

                // Check if price is set and is numeric
                if ( isset($price[ 0 ]) && is_numeric($price[ 0 ]) && isset($price[ 1 ]) && is_numeric($price[ 1 ]) )
                {
                    $collection->addAttributeToFilter('price', [ 'gteq' => $price[ 0 ] ]);
                    $collection->addAttributeToFilter('price', [ 'lteq' => $price[ 1 ] ]);
                }
            }

            if ( $brands )
            {
                $brands = array_map(function($brand) {
                    return urldecode($brand);
                }, $brands);

                $collection->addAttributeToFilter('merk', ['in' => $brands]);
            }
        }

        /** Do not try to pass empty $fields to addFieldToFilter, it will cause exception */
        return $fields ? $proceed($fields, $condition) : $collection;
    }
}