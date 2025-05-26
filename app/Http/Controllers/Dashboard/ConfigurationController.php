<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Services\ConfigurationService;
use App\Http\Requests\Dashboard\Configuration\AddConfigurationRequest;
use App\Http\Requests\Dashboard\Configuration\UpdateConfigurationRequest;

use Illuminate\Http\JsonResponse;

class ConfigurationController extends BaseController
{
    public function __construct(private readonly ConfigurationService $configurationService){

    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $configurations = $this->configurationService->index();
        return $this->sendResponse($configurations,'Task data fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddConfigurationRequest $request
     * @return JsonResponse
     */
    public function store(AddConfigurationRequest $request): JsonResponse
    {
        $request = $request->only('key', 'value');
        $response = $this->configurationService->addNewConfiguration($request);
        return $this->sendResponse($response, 'Configuration create success');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $data = $this->configurationService->show($id);

        if($data['configuration-found'])
        {
            return $this->sendResponse($data['configuration'], 'success show Configuration');

        }else{
            return $this->sendError('failed',['Configuration element does not exist to show']);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateConfigurationRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateConfigurationRequest $request, int $id): JsonResponse
    {
        $data = $this->configurationService->show($id);

        if(!$data['configuration-found'])
        {
            return $this->sendError('failed',['Configuration element does not exist to update']);
        }

        $request = $request->only('key', 'value');
        $configuration = $this->configurationService->update($request, $id);

        return $this->sendResponse($configuration,'Configuration updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $data = $this->configurationService->show($id);

        if(!$data['configuration-found'])
        {
            return $this->sendError('failed',['Configuration element does not exist to delete']);
        }

        $this->configurationService->delete($id);

        return $this->sendResponse([],'Configuration deleted successfully');
    }

    public function updateByKey(UpdateConfigurationRequest $request): JsonResponse
    {
        $data = $this->configurationService->showByKey($request['key']);

        if(!$data['configuration-found'])
        {
            return $this->sendError('failed',['Configuration element does not exist to update']);
        }

        $request = $request->only('key', 'value');
        $configuration = $this->configurationService->updateByKey($request);

        return $this->sendResponse($configuration,'Configuration updated successfully');
    }

    public function getByKey(String $key): JsonResponse
    {
        $configurations = $this->configurationService->showByKey($key);

        if($configurations['configuration-found'])
        {
            return $this->sendResponse($configurations['configuration'], 'success show Configuration');

        }else{
            return $this->sendError('failed',['Configuration element does not exist to show']);
        }
    }

    public function deleteByKey(String $key): JsonResponse
    {

        $data = $this->configurationService->showByKey($key);

        if(!$data['configuration-found'])
        {
            return $this->sendError('failed',['Configuration element does not exist to delete']);
        }

        $this->configurationService->deleteByKey($key);

        return $this->sendResponse([],'Configuration deleted successfully');
    }
}
