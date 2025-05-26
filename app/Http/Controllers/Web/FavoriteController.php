<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Services;
use App\Models\Task;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    private $notification_service;
    public function __construct(NotificationService $notification_service)
    {
        $this->notification_service = $notification_service;
    }

    public function toggleFavorite(Request $request): JsonResponse
    {
        $data = new Favorite();

        $user_to_notify = null;
        $item_type = null;

        $validator = Validator::make($request->all(),
            [
                'favorite_id'   => 'required|integer',
                'item_type'     => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request->item_type == 'service') {
            $user_to_notify = Services::where('id', $request->favorite_id)->with('user')->get()->first();
            $item_type = 'service';
        }

        if ($request->item_type == 'task') {
            $user_to_notify = Task::where('id', $request->favorite_id)->with('user')->get()->first();
            $item_type = 'task';
        }

        if ($request->item_type == 'provider') {
            $user_to_notify = User::where('id', $request->favorite_id)->get()->first();
            $item_type = 'provider';
        }

        $check_item = Favorite::where('user_id', Auth()->user()->id)
                                ->where('favorite_id', $request->favorite_id)
                                ->where('item_type', $request->item_type);

        if ($check_item->exists()) {
            $item = $check_item->get()->first();
            $id_to_delete = Favorite::find($item->id);
            $id_to_delete->delete();
            return response()->json([
                "success"   => true,
                "unliked"   => true,
                "message"   => "Item unliked successfully",
                "status"    => 200,
                "data"      => $item,
                "user"      => Auth()->user()
            ]);
        } else {
            $data->user_id        = Auth()->user()->id;
            $data->favorite_id    = $request->favorite_id;
            $data->item_type      = $request->item_type;
            $data->save();

            if ($data->save()) {
                $noty = [];
                $user_notify_id = null;
                if ($item_type == 'provider') {
                    $user_notify_id = $user_to_notify->id;
                } else {
                    $user_notify_id = $user_to_notify->user->id;
                }
                $noty["user_id"]            = Auth()->user()->id;
                $noty["user_to_notify"]     = $user_notify_id;
                $noty["user_name"]          = Auth()->user()->name;
                $noty["item_type"]          = $item_type;
                $noty["item_id"]            = $user_to_notify->id;
                $noty["action_type"]        = 'favorite';
                $this->notification_service->fireNotification($noty);
            }

            return response()->json([
                "success"   => true,
                "liked"     => true,
                "message"   => "Item liked successfully",
                "status"    => 200,
                "data"      => $data,
                "user"      => Auth()->user()
            ]);
        }
    }

}
