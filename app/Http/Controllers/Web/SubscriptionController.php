<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
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

class SubscriptionController extends Controller
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
        $package = Package::where('package_name', $package_name)->first();
        $user_id = Auth::user()['id'];
        $services = $this->services_Service->getUserServices($user_id);

        // Check if package is empty
        if (empty($package)) {
            return redirect('/account/subscription');
        } else {
            // Check if package is status hidden (public)
            if ($package['is_public'] == 0) { return redirect('/account/subscription'); }

            // If user has package
            if($package['id'] == Auth::User()->user_data->package_id) { return redirect('/account/subscription'); }

            // Check number of services
            if(count($services) > $package['number_of_services']) {
                return redirect('/account/subscription')->withErrors(['remove services' => 'you must delete ' . (count($services) - $package['number_of_services']) . ' services to upgrade this package']);
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
                $upgraded = Auth::User()->user_data->update(['package_id' => $package['id'], 'user_type' => 'Service Provider']);
                if ($upgraded) {
                    $this->packageDurationService->upgradePackageDuration($user_id, $package['id'], $this->packageDurationService->package_months);
                    return redirect('/account/subscription');
                }
            }
            else {
                return view('payment.checkout', ['package'=>$package]);
            }
        }
    }

    public function cancel(){
        $package = Package::find(1)->first();
        $user_id = Auth::user()['id'];
        $services = $this->services_Service->getUserServices($user_id);

        //check number of services
        if(count($services) > $package['number_of_services'])
        {
            return redirect('/account/subscription')->withErrors(['remove services' => 'you must delete ' . (count($services) - $package['number_of_services']) . ' services to cancel this package']);
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

        Auth::User()->user_data->update(['package_id' => $package['id']]);
        $this->packageDurationService->cancelPackageDuration($user_id, $package['id']);
        return redirect('/account/subscription');
    }

    public function payment(Request $request){
        if (!isset($request['package']) || !isset($request['amount'])){
            abort(404);
        }

        $data = [
            'package_name' => $request['package'],
            'amount'=> $request['amount'],
            'user_id' => Auth::user()->getAuthIdentifier(),
            'package_id' => $request['package_id'],
        ];
        $response = $this->subscriptionService->upgradeAccount($data);
        if (isset($response['payment'])){
            return Redirect::away($response['payment']->url);
        }
        if ($response['status']) {
            Session::flash('title','Congratulations');
            Session::flash('massage',$response['massage']);
            return redirect()->route('verification.congratulations');
        } else {
            abort(404);
        }
    }

    public function paymentSuccess(Request $request){
        $payment = Payment::Where('local_token', $request['local_token'])->first();
        $this->paymentService->successPayment($request['local_token']);
        Session::flash('title','Congratulations');
        Session::flash('massage', 'thanks for your payment');
        return redirect()->route('status');
    }

    public function paymentFail(Request $request){
        $this->paymentService->failPayment($request['local_token']);
        Session::flash('title','Payment invalid');
//        Session::flash('massage', 'Payment invalid');
        return redirect()->route('status');
    }

    public function pricingList(Request $request){
        $configurations = Configuration::query()->where("key", "currency_symbol")->first();

        $packages = Package::where('is_public', 1)->get();
        return view('pricing', ['packages' => $packages, 'configurations' => $configurations]);
    }
}
