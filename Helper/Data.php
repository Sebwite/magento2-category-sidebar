<?php
/**
 * Class:Categories
 * Sebwite\Sidebar\Model\Config\Source
 *
 * @author      Vasilis Vasiloudis
 * @package     Sebwite\Sidebar
 * @copyright   Copyright (c) 2016, vvasiloud. All rights reserved
 */
namespace Sebwite\Sidebar\Helper;

use Magento\Framework\Module\ModuleListInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	const XML_PATH_ENABLED    		 			= 'general/enabled';
	const XML_PATH_CATEGORY    		 			= 'general/category';
	const XML_PATH_CATEGORY_DEPTH_LEVEL    		= 'general/categorydepth';
	
	
	/**
     * @var ModuleListInterface
     */
    protected $_moduleList;
	
	/**
     * @param \Magento\Framework\App\Helper\Context $context
	 * @param ModuleListInterface $moduleList
     */
	public function __construct(\Magento\Framework\App\Helper\Context $context, ModuleListInterface $moduleList
	) {
		$this->_moduleList              = $moduleList;
		parent::__construct($context);
	}

    /**
     * @param $xmlPath
     * @param string $section
     *
     * @return string
     */
    public function getConfigPath(
        $xmlPath,
        $section = 'sebwitete_sidebar'
    ) {
        return $section . '/' . $xmlPath;
    }
	
	 /**
     * Check if enabled
     *
     * @return string|null
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            $this->getConfigPath(self::XML_PATH_ENABLED),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

	 /**
     * Get sidebar category
     *
     * @return string|null
     */
    public function getSidebarCategory()
    {
        return $this->scopeConfig->getValue(
            $this->getConfigPath(self::XML_PATH_CATEGORY),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
	
	 /**
     * Get category depth level
     *
     * @return string|null
     */
    public function getCategoryDepthLevel()
    {
        return $this->scopeConfig->getValue(
            $this->getConfigPath(self::XML_PATH_CATEGORY_DEPTH_LEVEL),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }	
	
}