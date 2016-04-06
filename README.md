# Magento 2.0 Category Sidebar extension
![Alt text](header.jpg?raw=true "Magento2 Category sidebar")

This extension will add the ability to show one of your root categories in a sidebar. The root category can be selected from the Magento2 admin config page.

## Installation with composer
* Include the repository: `composer require sebwite/magento2-category-sidebar`
* Enable the extension: `php bin/magento --clear-static-content module:enable Sebwite_Sidebar`
* Upgrade db scheme: `php bin/magento setup:upgrade`
* Clear cache

## Installation without composer
* Download zip file of this extension
* Place all the files of the extension in your Magento 2 installation in the folder `app/code/Sebwite/Sidebar`
* Enable the extension: `php bin/magento --clear-static-content module:enable Sebwite_Sidebar`
* Upgrade db scheme: `php bin/magento setup:upgrade`
* Clear cache

## Configuration
* Select the root category you want to use from the config page from the admin panel
* You should implement the block `Sebwite\Sidebar\Block\Sidebar` in your theme to make this extension work. Example
`<block class="Sebwite\Sidebar\Block\Sidebar" name="category-sidebar" template="Sebwite_Sidebar::sidebar.phtml" />`

---
[![Alt text](https://www.sebwite.nl/wp-content/themes/sebwite/assets/images/logo-sebwite.png "Sebwite.nl")](https://sebwite.nl)