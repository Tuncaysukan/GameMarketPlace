<?php

namespace Modules\Slider\Http\Requests;

use Modules\Core\Http\Requests\Request;

class SaveSliderRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'slider::attributes';


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'desktop_background_file_id' => 'nullable|exists:files,id',
            'mobile_background_file_id' => 'nullable|exists:files,id',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Boş string değerleri null'a çevir
        if ($this->has('desktop_background_file_id') && $this->get('desktop_background_file_id') === '') {
            $this->merge(['desktop_background_file_id' => null]);
        }
        
        if ($this->has('mobile_background_file_id') && $this->get('mobile_background_file_id') === '') {
            $this->merge(['mobile_background_file_id' => null]);
        }
    }
}
