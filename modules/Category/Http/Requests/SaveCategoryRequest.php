<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Category\Entities\Category;
use Modules\Core\Http\Requests\Request;

class SaveCategoryRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'category::attributes';


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'slug' => $this->getSlugRules(),
            'is_active' => 'required|boolean',
            'background_color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'hover_background_color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'text_color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'hover_text_color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
        ];
    }


    private function getSlugRules()
    {
        $rules = $this->route()->getName() === 'admin.categories.update'
            ? ['required']
            : ['nullable'];

        $slug = Category::withoutGlobalScope('active')->where('id', $this->id)->value('slug');

        $rules[] = Rule::unique('categories', 'slug')->ignore($slug, 'slug');

        return $rules;
    }
}
