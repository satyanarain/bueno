<?php namespace Bueno\Validations;

class CreateSmsgupshupTemplateValidator extends Validator{

  /**
   * validation rules for create city form
   *
   * @return array
   */
  public function rules()
  {
    return [
        'template_id' => 'required',
        'template_text' => 'required'
    ];
  }
}
