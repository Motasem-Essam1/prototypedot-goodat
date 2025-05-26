<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationSendController extends Controller
{
    public function saveToken(Request $request) {
        $check_device = DeviceToken::where('user_id', auth()->id())
                                    ->where('device_token', '=', $request["device_token"])
                                    ->where('user_agent', '=', request()->userAgent());

        if (!$check_device->first()) {
            $device = new DeviceToken();
            $device['user_id'] = auth()->id();
            $device['device_token'] =  $request['device_token'];
            $device['user_agent'] = request()->userAgent();
            $device->save();
            return response()->json(['Device token saved successfully.']);
        } else {
            return response()->json(['Device token exists']);
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

        return response()->json([
            'data'      => $notify->first(),
            'success'   => true,
            'message'   => 'Notification has been read'
        ]);
    }

    public function fetchNotifications() {
        if (Auth::check()) {
            $user_authenticated = Auth::user()->id;
            $data = Notification::where('user_to_notify', $user_authenticated)->with('user.user_data')->orderBy('created_at', 'DESC')->limit(8)->get()->all();
            $is_read = Notification::where('user_to_notify', $user_authenticated)->where('is_read', 0)->get()->count();

            return response()->json([
                'success' => true,
                'message' => 'successfully fetched notifications',
                'data'    => $data,
                'is_read' => $is_read,
            ]);
        }
    }

    public function notificationsPage() {
        if (Auth::check()) {
            $user_authenticated = Auth::user()->id;
            $data = Notification::where('user_to_notify', $user_authenticated)->with('user.user_data')->orderBy('created_at', 'DESC')->paginate(10, ['*'], 'notifications');
            $is_read = Notification::where('user_to_notify', $user_authenticated)->where('is_read', 0)->get()->count();

            $data = [
                'data'    => $data,
                'is_read' => $is_read
            ];

            return view('notifications', $data);
        }
    }

    public function removeTokens() {
//        $user_id = Auth::user()->id;
//        $multiple_tokens_ids = DeviceToken::where('user_id', $user_id)
//                                        ->where('created_at', '<=', Carbon::now()->subDays(5))
//                                        ->pluck('id')
//                                        ->toArray();
//
//        $data = DeviceToken::whereIn('id', $multiple_tokens_ids)->delete();
//
//        if ($data) {
//            return response()->json([
//                'data' => $data,
//                'message' => 'Successfully delete device tokens',
//                'success' => true
//            ]);
//        }
    }
}
