<?php

namespace Modules\Slider\Entities;

use Modules\Support\Eloquent\TranslationModel;

class SliderSlideTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'mobile_file_id',
        'logo_file_id',
        'caption_1',
        'caption_2',
        'direction',
        'call_to_action_text',
    ];
}
