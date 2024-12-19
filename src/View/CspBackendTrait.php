<?php

declare(strict_types=1);

namespace WeDevelop\Csp\View;

use WeDevelop\Csp\Model\CspCustomScript;
use SilverStripe\View\Requirements;

trait CspBackendTrait
{
    public function processCspCustomScripts()
    {
        $customScripts = Requirements::get_custom_scripts();

        $hashes = [];

        foreach ($customScripts as $key => $script) {
            $hash = md5($script);
            $customScript = CspCustomScript::get()->filter(['Hash' => $hash])->first();

            if ($customScript === null) {
                /*
                 * There's a limit of 2 MB for the code, this should be enough. If you're outputting more than 2 MB
                 * of scripts in your HTML you should clean that up instead.
                 */
                $customScript = CspCustomScript::create([
                    'Hash' => $hash,
                    'Code' => $script,
                ]);
                $customScript->write();
            } elseif (mt_rand(0, 50) === 0) {
                // Update the LastEdited column occasionally to indicate the scripts are still in use
                $customScript->write(forceWrite: true);
            }

            $hashes[] = $hash;
            Requirements::block($key);
        }

        if (count($hashes) > 0) {
            Requirements::javascript('/wedevelop/csp/js?hash[]=' . join('&hash[]=', $hashes));
        }
    }
}
