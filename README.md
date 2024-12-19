# WeDevelop Silverstripe CSP Custom Scripts

## Description

This Silverstripe module makes sure that any custom scripts added through Requirements are served from a javascript
file. The goal of this is to be able to drop `script-src: 'unsafe-inline'` from your Content-Security-Policy header.

## Installation

```shell
$ composer require wedevelopnl/silverstripe-csp-custom-scripts
```

Run a dev/build and you should be all set.
If you're using `dorsetdigital/silverstripe-enhanced-requirements` read "Compatibility" for further instructions

## Compatibility

If you're using `dorsetdigital/silverstripe-enhanced-requirements` you will have to do the override yourself, extend
the Enhanced_Requirements class and use the trait to inject the CSP code.

`csp.yml`
```yaml
---
Name: csp-enhanced-requirements
After:
  - '#enhanced-requirements'
  - '#csp-requirements'
---
SilverStripe\Core\Injector\Injector:
  SilverStripe\View\Requirements_Backend:
    class: App\View\RequirementsBackend
```

`src/View/RequirementsBackend.php`
```php
<?php

declare(strict_types=1);

namespace App\View;

use DorsetDigital\EnhancedRequirements\View\Enhanced_Backend;
use WeDevelop\Csp\View\CspBackendTrait;

final class RequirementsBackend extends Enhanced_Backend
{
    use CspBackendTrait;

    public function includeInHTML($content)
    {
        $this->processCspCustomScripts();

        return parent::includeInHTML($content);
    }
}

```
