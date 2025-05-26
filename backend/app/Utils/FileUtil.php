<?php

namespace App\Utils;
use Illuminate\Support\Facades\File;


class FileUtil
{
    public function uploadFile($file, string $fileName, string $path): string
    {
        // Define upload path
        $destinationPath = public_path("uploads/".$path); // upload path
        // Upload Orginal Image
        $profileImage = $fileName . "." . $file->getClientOriginalExtension();
        $file->move($destinationPath, $profileImage);
        return  "/uploads/" . $path . "/" . $profileImage;
    }

    public function uploadMultiFiles(array $files, string $fileName, string $path): array
    {
        $listOfPath = array();
        $count = 1;
        foreach ($files as $file){
            if($file != null) {
                $listOfPath[] = $this->uploadFile($file, $fileName. "_". $count, $path);
                $count++;
            }
        }
        return $listOfPath;
    }

    public function deleteFileByPath(string $path): bool
    {
        if(File::exists(public_path($path))){
            File::delete(public_path($path));
            return true;
        }else{
            return false;
        }
    }

    public function renameFileByPath(string $path,string $newName){
        $extension = File::extension("uploads/".$path);
        $newPath = $newName."." . $extension;
        rename(public_path("uploads/".$path), public_path("uploads/".$newPath));
        return $newPath;
    }

}
