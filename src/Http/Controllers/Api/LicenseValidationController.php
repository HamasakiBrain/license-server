<?php

namespace HamasakiBrain\LicenseServer\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

use HamasakiBrain\LicenseServer\Models\License;
use HamasakiBrain\LicenseServer\Events\LicenseChecked;
use HamasakiBrain\LicenseServer\Http\Controllers\BaseController;

class LicenseValidationController extends BaseController
{
    /**
     * Validate given license
     *
     * @return \Illuminate\Http\Response
     */
    public function licenseValidate(Request $request, License $license)
    {
        $_license = $license->select(
            'domain',
            'license_key',
            'status',
            'expiration_date',
            'is_trial',
            'is_lifetime',
            'deleted_at',
            'created_at',
            'updated_at'
        )->where('id', auth()->user()->id)->first();

        $data = $request->input();

        Event::dispatch(new LicenseChecked($_license, $data));

        return $_license;
    }
}
