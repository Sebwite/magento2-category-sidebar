<?php namespace Sebwite\Sidebar\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\View\Element\Template;

/**
 * Class:Sidebar
 * Sebwite\Sidebar\Block
 *
 * @author      Sebwite
 * @package     Sebwite\Sidebar
 * @copyright   Copyright (c) 2015, Sebwite. All rights reserved
 */
class Sidebar extends Template
{

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $categoryFlatConfig;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection */
    protected $_productCollectionFactory;

    /**
     * @var \Sebwite\Sidebar\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var array
     */
    protected $_storeCategories;

    /**
     * @param Template\Context                                        $context
     * @param \Magento\Catalog\Helper\Category                        $categoryHelper
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State      $categoryFlatState
     * @param \Magento\Catalog\Model\CategoryFactory                  $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollectionFactory
     * @param array                                                   $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollectionFactory,
        \Sebwite\Sidebar\Helper\Data $dataHelper,
        $data = []
    ) {
        $this->_categoryHelper           = $categoryHelper;
        $this->_coreRegistry             = $registry;
        $this->categoryFlatConfig        = $categoryFlatState;
        $this->_categoryFactory          = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_dataHelper               = $dataHelper;
        parent::__construct($context, $data);
    }

    /*
    * Get owner name
    * @return string
    */

    /**
     * Get all categories
     *
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Category\Collection|\Magento\Framework\Data\Tree\Node\Collection
     */
    public function getCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        $cacheKey = sprintf('%d-%d-%d-%d', $this->getSelectedRootCategory(), $sorted, $asCollection, $toLoad);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         */
        $category = $this->_categoryFactory->create();

        $categoryDepthLevel = $this->_dataHelper->getCategoryDepthLevel();

        $storeCategories = $category->getCategories($this->getSelectedRootCategory(), (int) $categoryDepthLevel, $sorted,
            $asCollection, $toLoad);

        $this->_storeCategories[$cacheKey] = $storeCategories;

        return $storeCategories;
    }

    /**
     * getSelectedRootCategory method
     *
     * @return int|mixed
     */
    public function getSelectedRootCategory()
    {
        $category = $this->_scopeConfig->getValue(
            'sebwite_sidebar/general/category'
        );

        if ($category == 'current_category_children') {
            $currentCategory = $this->_coreRegistry->registry('current_category');
            if ($currentCategory) {
                return $currentCategory->getId();
            }

            return 1;
        }

        if ($category == 'current_category_parent_children') {
            $currentCategory = $this->_coreRegistry->registry('current_category');
            if ($currentCategory) {
                $topLevelParent      = $currentCategory->getPath();
                $topLevelParentArray = explode("/", $topLevelParent);
                if (isset($topLevelParent)) {
                    return $topLevelParentArray[2];
                }
            }

            return 1;
        }

        if ($category === null) {
            return 1;
        }

        return $category;
    }

    /**
     * @param        $category
     * @param string $html
     * @param int    $level
     *
     * @return string
     */
    public function getChildCategoryView(Category $category, $html = '', $level = 1)
    {
        // Check if category has children
        if ($category->hasChildren()) {

            $childCategories = $this->getSubcategories($category);

            if (count($childCategories) > 0) {

                $html .= '<ul class="o-list o-list--unstyled">';

                // Loop through children categories
                foreach ($childCategories as $childCategory) {

                    $html .= '<li class="level' . $level . ($this->isActive($childCategory) ? ' active' : '') . '">';
                    $html .= '<a href="' . $this->getCategoryUrl($childCategory) . '" title="'
                        . $childCategory->getName() . '" class="' . ($this->isActive($childCategory) ? 'is-active' : '')
                        . '">' . $childCategory->getName() . '</a>';

                    if ($childCategory->hasChildren()) {
                        if ($this->isActive($childCategory)) {
                            $html .= '<span class="expanded"><i class="fa fa-minus"></i></span>';
                        } else {
                            $html .= '<span class="expand"><i class="fa fa-plus"></i></span>';
                        }
                    }

                    if ($childCategory->hasChildren()) {
                        $html .= $this->getChildCategoryView($childCategory, '', ($level + 1));
                    }

                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }

        return $html;
    }

    /**
     * Retrieve subcategories
     * DEPRECATED
     *
     * @param $category
     *
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection|\Magento\Catalog\Model\Category[]
     */

    public function getSubcategories(Category $category)
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            return (array)$category->getChildrenNodes();
        }

        return $category->getChildrenCategories();
    }

    /**
     * Get current category
     *
     * @param Category $category
     *
     */
    public function isActive(Category $category): bool
    {
        $activeCategory = $this->_coreRegistry->registry('current_category');
        $activeProduct  = $this->_coreRegistry->registry('current_product');

        if (!$activeCategory) {

            // Check if we're on a product page
            if ($activeProduct !== null) {
                return in_array($category->getId(), $activeProduct->getCategoryIds());
            }

            return false;
        }

        // Check if this is the active category
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource() and
            $category->getId() == $activeCategory->getId()
        ) {
            return true;
        }

        // Check if a subcategory of this category is active
        $childrenIds = $category->getAllChildren(true);
        if (!empty($childrenIds) && is_array($childrenIds) && in_array($activeCategory->getId(), $childrenIds)) {
            return true;
        }

        // Fallback - If Flat categories is not enabled the active category does not give an id
        return (($category->getName() == $activeCategory->getName()) ? true : false);
    }

    /**
     * Return Category Id for $category object
     *
     * @param $category
     *
     * @return string
     */
    public function getCategoryUrl(Category $category)
    {
        return $this->_categoryHelper->getCategoryUrl($category);
    }
}
