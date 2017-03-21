# PromotionalSashes

**Version 0.0.1**

Usage
---
To configure, log into the Magento 2 admin panel.

![admin_menu](https://cloud.githubusercontent.com/assets/24390251/24150571/f7d35604-0e3d-11e7-9c11-8ff2754ff4c3.png)

Select "Stores" and then "Configuration". You will be presented with the configuration screen.

![configuration](https://cloud.githubusercontent.com/assets/24390251/24150826/cef4cd5c-0e3e-11e7-82d9-540a5070efa6.png)

Each configuration is expandable and relate to different attributes that are configurable on the product edit page.

![configs_expanded](https://cloud.githubusercontent.com/assets/24390251/24150876/fafcc58a-0e3e-11e7-8fa4-93819cb1f19a.png)

Installation
---

In the project's composer.json file add the repo:

```javascript
"repositories": [
        {
            "type": "composer",
            "url": "https://repo.magento.com/"
        },
        
        // other repos
        
        {   "type": "vcs", 
            "url": "https://github.com/Space48/PromotionalSashes" 
        }
]
```

Add `"Space48/PromotionalSashes": "0.0.1"` to the require section of the same composer.json file and run 
`composer install` from the project root.

Running `bin/magento setup:upgrade` should present you with a list of modules; you should be able to see 
`Space48_PromotionalSashes` in that list.

Development
---
In order to publish a new version of this module you must tag (see `git tag`) the commit with the appropriate version number.

__Testing a commit before tagging__

In the require section use `"Space48/PromotionalSashes": "dev-NAMEOFTHEBRANCHHERE"`, so for a branch
named `bugfixes` you would need to do `"Space48/PromotionalSashes": "dev-bugfixes"`. If composer is complaining about version
dependencies then use an alias like so `"Space48/PromotionalSashes": "dev-bugfixes as 0.0.2"`.

__Adding the sash to the frontend__

The sashes will not display automatically, you will need to add the following to your product list template, usually overidden in `app/design/frontend/PROJECT_NAME/default/Magento_Catalog/templates/product/list.phtml`.

```php
    <?php echo $block->getPromotionalSashes(PRODUCT_ID_HERE); ?>
```
