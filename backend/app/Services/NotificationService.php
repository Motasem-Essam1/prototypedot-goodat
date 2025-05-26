<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function fireNotification(array $data) {

        $delete = Notification::where('user_id', $data['user_id'])
                                ->where('user_to_notify', $data['user_to_notify'])
                                ->where('item_id', $data['item_id'])
                                ->where('item_type', $data['item_type'])
                                ->where('action_type', $data['action_type'])
                                ->get()->first();

        if ($delete) {
            $toDelete = Notification::findOrFail($delete->id);
            if ($toDelete) {
                $toDelete->delete();
            }
        }

        try {
            if ($data['user_id'] != $data['user_to_notify']) {
                $notify = new Notification();
                $notify['user_id']            = $data['user_id'];
                $notify['user_to_notify']     = $data['user_to_notify'];
                $notify['message']            = $data['user_name'] . $this->message($data['action_type']) . $data['item_type'];
                $notify['item_id']            = $data['item_id'];
                $notify['item_type']          = $data['item_type'];
                $notify['action_type']        = $data['action_type'];
                $notify->saveOrFail();

                $message = [
                    'title' => $data['user_name'],
                    'body' => $notify['message'],
                    'row' => $notify,
                ];
                $this->fcm($data['user_to_notify'], $message);
            }
        } catch (\Throwable $ex) {

        }
    }

    private function fcm(int $user_id, array $message) {
        $firebaseToken = DeviceToken::where('user_id', $user_id)->whereNotNull('device_token')->pluck('device_token')->toArray();

        fcm()
            ->to($firebaseToken)
            ->priority('high')
            ->timeToLive(0)
            ->data([
                'title' => $message['title'],
                'body' => $message['body'],
                'row' => $message['row']
            ])
            ->send();
    }

    private function message(string $action): string {
        switch ($action){
            case "favorite":
                return " liked your ";
            case "review":
                return " reviewed your ";
            default:
                return "";
        }
    }

    public function removeNotifications($id, $type) {
        $data = Notification::where('item_id', $id)->where('item_type', $type);
        if ($data->count() > 0) {
            $data->delete();
        }
    }

    public function removeDeviceTokens($id) {
        $token_ids = DeviceToken::where('user_id', $id)->pluck('id')->toArray();
        if ($token_ids) {
            DeviceToken::whereIn('id', $token_ids)->delete();
        }
    }

}
