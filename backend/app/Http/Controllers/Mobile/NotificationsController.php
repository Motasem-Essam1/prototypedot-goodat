<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;

class NotificationsController extends BaseController
{

    public function saveToken(Request $request) {
        $check_device = DeviceToken::where('user_id', auth('sanctum')->user()->id)
            ->where('device_token', '=', $request["device_token"])
            ->where('user_agent', '=', request()->userAgent());

        if (!$check_device->first()) {
            $device = new DeviceToken();
            $device['user_id'] = auth('sanctum')->user()->id;
            $device['device_token'] =  $request['device_token'];
            $device['user_agent'] = request()->userAgent();
            $device->save();
            return $this->sendResponse($device, 'Device token saved successfully.');
        } else {
            $this->removeTokens();
            return $this->sendError('failed', 'Device token exists');
        }
    }

    public function isRead(Request $request) {
        $request->validate([
            'id' => 'required|exists:notifications,id',
        ]);
        $data = $request->only('id');
        $notify = Notification::find($request->id);
        $data['is_read'] = 1;
        $notify->update($data);

        return $this->sendResponse($notify->first(), 'Notification has been read');
    }

    public function removeTokens() {
        $user_id = auth('sanctum')->user()->id;
        $multiple_tokens_ids = DeviceToken::where('user_id', $user_id)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->pluck('id')
            ->toArray();

        $data = DeviceToken::whereIn('id', $multiple_tokens_ids)->delete();

        if ($data) {
            return $this->sendResponse($data, 'Successfully delete device tokens');
        }
    }

    public function fetchNotifications() {
        if (auth('sanctum')->check()) {
            $user_authenticated = auth('sanctum')->user()->id;
            $data = Notification::where('user_to_notify', $user_authenticated)->with('user.user_data')->orderBy('created_at', 'DESC')->get()->all();
            $is_read = Notification::where('user_to_notify', $user_authenticated)->where('is_read', 0)->get()->count();

            $result = [
                'notifications'  => $data,
                'is_read'        => $is_read
            ];

            return $this->sendResponse($result, 'Successfully fetched notifications');
        }
    }
}
