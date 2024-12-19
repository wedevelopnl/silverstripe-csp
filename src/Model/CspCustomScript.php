<?php

declare(strict_types=1);

namespace WeDevelop\Csp\Model;

use SilverStripe\ORM\DataObject;

/**
 * @property string $Hash
 * @property string $Code
 */
class CspCustomScript extends DataObject
{
    /** @config */
    private static string $table_name = 'WeDevelop_CspCustomScript';

    /** @config */
    private static string $singular_name = 'CSP Custom Script';

    /** @config */
    private static string $plural_name = 'CSP Custom Scripts';

    /**
     * @config
     * @var array<string, string>
     */
    private static array $db = [
        'Hash' => 'Varchar(32)',
        'Code' => 'Text',
    ];

    /**
     * @config
     * @var array<string, mixed>
     */
    private static array $indexes = [
        'LastEdited' => true,
    ];
}
