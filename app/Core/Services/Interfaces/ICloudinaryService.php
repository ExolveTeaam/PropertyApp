<?php

namespace App\Core\Services\Interfaces;

use Illuminate\Http\Request;

 interface ICloudinaryService
{
    public function uploadImage(Request $request): string;
    public function downloadimage(string $imagePath): void;
}
