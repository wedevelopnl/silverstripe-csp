<?php

declare(strict_types=1);

namespace WeDevelop\Csp\View;

use DorsetDigital\EnhancedRequirements\View\Enhanced_Backend;
use SilverStripe\View\Requirements_Backend;

final class RequirementsBackend extends Enhanced_Backend
{
    use CspBackendTrait;

    public function includeInHTML($content)
    {
        $this->processCspCustomScripts();

        return parent::includeInHTML($content);
    }
}
