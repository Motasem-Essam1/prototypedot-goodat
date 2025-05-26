<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;

use App\Models\Package;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\ServicesService;
use App\Services\SubscriptionService;
use App\Services\PackageDurationService;
use App\Utils\FileUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends BaseController
{
    private $subscriptionService;
    private $paymentService;
    private $services_Service;
    private $fileUtil;
    private $packageDurationService;
    public function __construct(SubscriptionService $subscriptionService,
                                PaymentService $paymentService,
                                ServicesService $services_Service,
                                FileUtil $fileUtil,
                                PackageDurationService $packageDurationService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->paymentService = $paymentService;
        $this->services_Service = $services_Service;
        $this->fileUtil = $fileUtil;
        $this->packageDurationService = $packageDurationService;
    }

    public function subscribe(string $package_name) {
        $package = Package::query()->where('package_name', $package_name)->first();
        $user_id = auth('sanctum')->id();
        $services = $this->services_Service->getUserServices($user_id);

        // Check if package is empty
        if (empty($package)) {
            return $this->sendError('something went wrong', 'package not found');
        }
        else{
            // Check if package is un public
            if ($package['is_public'] == 0) {
                return $this->sendError('something went wrong', 'package not found');
            }

            // If user has package
            if(auth('sanctum')->user()['user_data']['package_id'] != null) {
                $current_package = Package::query()->where('id', auth('sanctum')->user()['user_data']['package_id'])->first();
                if($current_package['id'] == $package['id']) {
                    return $this->sendError('something went wrong', 'you subscribed same package');
                }
            }

            // Check number of services
            if(count($services) > $package['number_of_services']) {
                return $this->sendError('something went wrong', 'you must delete ' . (count($services) - $package['number_of_services']) . ' services to upgrade this package');
            }

            // Delete number_of_images_per_service
            foreach ($services as $service) {
                if ($package['number_of_images_per_service'] < count($service->images)) {
                    $Num_images_service = count($service->images) - $package['number_of_images_per_service'];
                    $last_image = count($service->images) - 1;

                    for ($x = $Num_images_service; $x > 0; $x--) {
                        $this->fileUtil->deleteFileByPath($service->images[$last_image]['image_path']);
                        $service->images()->detach($service->images[$last_image]['id']);
                        $service->images[$last_image]->delete();
                        $last_image--;
                    }
                }
            }

            if ($package['price'] == 0) {
                $upgraded = auth('sanctum')->user()['user_data']->update(['package_id' => $package['id']]);
                if ($upgraded) {
                    $this->packageDurationService->upgradePackageDuration($user_id, $package['id'], $this->packageDurationService->package_months);
                    return $this->sendResponse([], 'success subscribed free package');
                }
            }
            else {
                return $this->sendResponse([], 'success subscribe start checkout payment');
            }
        }
    }

    public function cancel(){
        $package = Package::find(1)->first();
        $user_id = auth('sanctum')->id();
        $services = $this->services_Service->getUserServices($user_id);

        //check number of services
        if(count($services) > $package['number_of_services'])
        {
            return $this->sendError('something went wrong', 'you must delete ' . (count($services) - $package['number_of_services']) . ' services to upgrade this package');
        }

        //delete number_of_images_per_service
        foreach ($services as $service)
        {
            if($package['number_of_images_per_service'] < count($service->images))
            {
                $Num_images_service = count($service->images) - $package['number_of_images_per_service'];
                $last_image = count($service->images) -1;

                for ($x = $Num_images_service; $x > 0; $x--)
                {
                    $this->fileUtil->deleteFileByPath($service->images[$last_image]['image_path']);
                    $service->images()->detach($service->images[$last_image]['id']);
                    $service->images[$last_image]->delete();
                    $last_image--;
                }
            }
        }

        auth('sanctum')->user()['user_data']->update(['package_id' => $package['id']]);
        $this->packageDurationService->cancelPackageDuration($user_id, $package['id']);
        return $this->sendResponse([], 'success cancel package');
    }

    public function payment(Request $request){
        if (!isset($request['package']) || !isset($request['amount'])){
            return $this->sendError('something went wrong', 'package not found or amout not enough');
        }

        $data = [
            'package_name' => $request['package'],
            'amount'=> $request['amount'],
            'user_id' => auth('sanctum')->user()->getAuthIdentifier(),
            'package_id' => $request['package_id'],
        ];
        $response = $this->subscriptionService->upgradeAccount($data);
        if (isset($response['payment'])){

            //return Redirect::away($response['payment']->url);
            return $this->sendResponse([], 'payment url');

        }
        if ($response['status']) {
            return $this->sendResponse([], 'verification.congratulations');
        } else {
            return $this->sendError('something went wrong', 'payment is failed');
        }
    }
}
