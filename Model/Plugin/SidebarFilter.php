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
            // Get brand
            $brands = isset($_GET[ 'brands' ]) ? $_GET[ 'brands' ] : false;

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