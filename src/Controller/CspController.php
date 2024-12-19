<?php

declare(strict_types=1);

namespace WeDevelop\Csp\Controller;

use WeDevelop\Csp\Model\CspCustomScript;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLDelete;

final class CspController extends Controller
{
    /**
     * @config
     * @var array<string>
     */
    private static array $allowed_actions = [
        'js',
    ];

    public function js(): HTTPResponse
    {
        $request = $this->getRequest();

        $hashes = $request->requestVar('hash') ?? [];
        $scriptPerHash = [];

        $scripts = CspCustomScript::get()->filter(['Hash' => $hashes]);
        foreach ($scripts as $script) {
            $scriptPerHash[$script->Hash] = $script->Code;
        }

        // Add javascript in the order that the hashes were passed
        $js = '';
        foreach ($hashes as $hash) {
            if (!array_key_exists($hash, $scriptPerHash)) {
                continue;
            }

            $js .= $scriptPerHash[$hash] . "\n";
        }

        if (mt_rand(0, 50) === 0) {
            // Occasionally delete expired scripts
            $this->deleteExpiredScripts();
        }

        if ($js === '') {
            return $this->httpError(404);
        }

        $response = new HTTPResponse($js, 200);
        $response->addHeader('Content-Type', 'application/javascript');
        $response->addHeader('Content-Length', strlen($js));

        return $response;
    }

    private function deleteExpiredScripts(): void
    {
        $threshold = date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 30)); // 30 days

        $schema = DataObject::getSchema();
        $delete = SQLDelete::create()
            ->setFrom(DB::get_conn()->escapeIdentifier($schema->tableName(CspCustomScript::class)))
            ->setWhere([$schema->sqlColumnForField(CspCustomScript::class, 'LastEdited') . ' < ?' => $threshold]);

        $delete->execute();
    }
}
