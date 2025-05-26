<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ProviderContact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;


class ProviderContactService
{

    public function __construct()
    {

    }

    /**
     * get Categories
     * @param Request $request
     * @return void
     */
    public function createProviderContact(Request $request): bool
    {

        if ($request['user_id'] != auth()->user()['id']) {
            $providerContact = new ProviderContact();
            $providerContact['user_id'] = $request['user_id'];
            $providerContact['visitor_id'] = auth()->user()['id'];
            $providerContact['visitor_type'] = auth()->user()['user_data']['user_type'];
            $providerContact['item_id'] = $request['item_id'];
            $providerContact['item_type'] = $request['item_type'];
            $providerContact->save();
            return true;
        }
        return false;
    }
}
