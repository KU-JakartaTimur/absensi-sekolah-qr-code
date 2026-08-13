<?php

namespace App\Models;

class CameraCaptureModel extends BaseModel
{
    protected $jpgQuality;
    protected $webpQuality;
    protected $imgExt;

    public function __construct()
    {
        parent::__construct();
        $this->jpgQuality = 85;
        $this->webpQuality = 80;
        $this->imgExt = '.jpg';
    }
}
