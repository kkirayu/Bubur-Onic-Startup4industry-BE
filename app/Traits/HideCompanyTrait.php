<?php

namespace App\Traits;

trait HideCompanyTrait 
{

    public function  getHiddenOnList(): array
    {
        $default =  ["perusahaan_id" , "cabang_id"];
        return array_merge(parent::getHiddenOnList() ?: [],  $default);
    }

    public function  getHiddenOnCreate(): array
    {
        $default =  ["perusahaan_id" , "cabang_id"];
        return array_merge(parent::getHiddenOnList() ?: [],  $default);
    }


    public function  getHiddenOnUpdate(): array
    {
        
        $default =  ["perusahaan_id" , "cabang_id"];
        return array_merge(parent::getHiddenOnList() ?: [],  $default);
    }


    public function  getHiddenOnDetail(): array
    {
        $default =  ["perusahaan_id" , "cabang_id"];
        return array_merge(parent::getHiddenOnList() ?: [],  $default);
    }




}
