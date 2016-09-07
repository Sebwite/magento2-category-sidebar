# Magento 2.0 Category Sidebar extension
![Alt text](header.jpg?raw=true "Magento2 Category sidebar")

This extension will add the ability to show category tree from a parent or current category in a sidebar. The category can be selected from the Magento2 admin config page.

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
* Select the root category or current category option you want to use from the config page from the admin panel
* Select children depth level
* Categories will appear in col.left sidebar of the theme

---
[![Alt text](https://www.sebwite.nl/wp-content/themes/sebwite/assets/images/logo-sebwite.png "Sebwite.nl")](https://sebwite.nl)