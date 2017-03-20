# PromotionalSashes


Add `"Space48/PromotionalSashes": "0.0.1"` to the require section of the composer.json file and run `composer update` from the project root.


In the project composer.json file add the repo:

```javascript
"repositories": [
        {
            "type": "composer",
            "url": "https://repo.magento.com/"
        },
        {   "type": "vcs", 
            "url": "https://github.com/Space48/PromotionalSashes" 
        }
```

Development
---
In order to publish a new version of this module you must tag (see `git tag`) the commit with the appropriate version number.