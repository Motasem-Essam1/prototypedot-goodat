<?php

namespace App\Services;

use App\Models\Configuration;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class ConfigurationService
{

    public function __construct()
    {

    }

    public function index(): Collection
    {
        return Configuration::query()->get();
    }


    public function addNewConfiguration(array $data): array
    {
        $configuration = new Configuration;
        $configuration['key'] = $data['key'];
        $configuration['value'] = $data['value'];
        $configuration->save();
        return $configuration->toArray();
    }

    public function show(int $id): array
    {
        try{
            $configuration = Configuration::query()->findOrFail($id);
            $data['configuration-found'] = true;
            $data['configuration'] = $configuration;
            return $data;
        }
        catch(Exception $ex)
        {
            $data['configuration-found'] = false;
            return $data;
        }
    }

    public function update(array $request,int $id): array|Builder|Collection|Model
    {
        $configuration = Configuration::query()->find($id);
        $configuration['key'] = $request['key'];
        $configuration['value'] = $request['value'];
        $configuration->update();
        return  $configuration;
    }

    public function delete(int $id): void
    {
        $configuration = Configuration::query()->find($id);
        $configuration->delete();
    }

    public function showByKey(String $Key): array
    {
        $configuration = Configuration::query()->where("key" , $Key)->first();
        if($configuration)
        {
            $data['configuration-found'] = true;
            $data['configuration'] = $configuration;
            return $data;
        }
        else
        {
            $data['configuration-found'] = false;
            return $data;
        }
    }

    public function updateByKey($request): array|Builder|Collection|Model
    {
        $configuration = Configuration::query()->where("key" , $request['key'])->first();
        $configuration['value'] = $request['value'];
        $configuration->update();
        return  $configuration;
    }

    public function deleteByKey(String $Key): void
    {
        $configuration = Configuration::query()->where("key" , $Key)->first();
        $configuration->delete();
    }



}
