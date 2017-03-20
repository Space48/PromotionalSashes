# PromotionalSashes

**Version 0.0.1**

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
`composer update` from the project root.

Running bin/magento setup:upgrade should present you with a list of modules; you should be able to see 
`Space48_PromotionalSashes` in that list.

Development
---
In order to publish a new version of this module you must tag (see `git tag`) the commit with the appropriate version number.

__Testing a commit before tagging__

In the require section use `"Space48/PromotionalSashes": "dev-NAMEOFTHEBRANCHHERE"`, so for a branch
named `bugfixes` you would need to do `"Space48/PromotionalSashes": "dev-bugfixes"`. If composer is complaining about version
dependencies then use an alias like so `"Space48/PromotionalSashes": "dev-bugfixes as 0.0.2"`.