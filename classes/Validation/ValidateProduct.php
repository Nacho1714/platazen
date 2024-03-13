<?php

namespace App\Validation;

class ValidateProduct {

    protected array $rules;
    protected array $errors = [];

    /**
     * @param array $rules Las reglas de validación. Cada regla es un array con dos elementos: el primero es el valor a validar y el segundo es la regla a aplicar separada por | (pipe) en caso de que haya más de una regla, por ejemplo: ['name' => [$name, 'required|min:3|max:50']]
     * 
     * REGLAS: required, min, max, image, numeric, email.
     */
    public function __construct(array $rules) {
        $this->rules = $rules;
    }

    /**
     * Valida los datos según las reglas. 
     * 
     * @return bool Devuelve true si la validación fue exitosa, false en caso contrario
     */
    public function passes(): bool {

        foreach ($this->rules as $field => $rule) {

            $rules = explode('|', $rule[1]);

            foreach ($rules as $rule) {

                $rule = explode(':', $rule);

                switch ($rule[0]) {
                    case 'required':
                        $this->required($field);
                        break;
                    case 'min':
                        $this->min($field, $rule[1]);
                        break;
                    case 'max':
                        $this->max($field, $rule[1]);
                        break;
                    case 'image':
                        $this->image($field);
                        break;
                    case 'numeric':
                        $this->numeric($field);
                        break;
                    case 'email':
                        $this->email($field);
                        break;
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Devuelve los errores de validación
     * 
     * @return array
     */
    public function errors(): array {
        return $this->errors;
    }

    /**
     * Valida que el campo $field no esté vacío
     * 
     * @param string $field El nombre del campo a validar
     * @param string $rule El nombre de la regla a aplicar
     * @return string
     */
    protected function required(string $field)
    {
        $fieldRequired = $this->rules[$field][0];

        if (empty($fieldRequired)) {
            $this->errors[$field] = "El campo es requerido";
        }
    }

    /**
     * Valida que el campo $field tenga al menos $rule caracteres
     * 
     * @param string $field El nombre del campo a validar
     * @param string $rule El nombre de la regla a aplicar
     * @return void
     */
    protected function min(string $field, string $rule): void {
        if (strlen($this->rules[$field][0]) < $rule) {
            $this->errors[$field] = "El campo debe tener al menos $rule caracteres";
        }
    }

    /**
     * Valida que el campo $field tenga como máximo $rule caracteres
     * 
     * @param string $field El nombre del campo a validar
     * @param string $rule El nombre de la regla a aplicar
     * @return void
     */
    protected function max(string $field, string $rule): void {
        if (strlen($this->rules[$field][0]) > $rule) {
            $this->errors[$field] = "El campo debe tener como máximo $rule caracteres";
        }
    }

    /**
     * Valida que el campo $field sea una imagen
     * 
     * @param string $field El nombre del campo a validar
     * @return void
     */
    protected function image(string $field): void {
        if (!in_array($this->rules[$field][0]['type'], ['image/jpeg', 'image/png'])) {
            $this->errors[$field] = "El campo debe ser una imagen";
        }
        if (strlen($this->rules[$field][0]['name']) < 3) {
            $this->errors[$field] = "El nombre de la imagen debe tener al menos 3 caracter";
        }
        if (strlen($this->rules[$field][0]['name']) > 255) {
            $this->errors[$field] = "El nombre de la imagen debe tener como máximo 255 caracter";
        }
        // TODO: Validar el tamaño de la imagen
    }

    /**
     * Valida que el campo $field sea numérico
     * 
     * @param string $field El nombre del campo a validar
     * @return void
     */
    protected function numeric(string $field): void {
        if (!is_numeric($this->rules[$field][0])) {
            $this->errors[$field] = "El campo debe ser numérico";
        }
    }

    /**
     * Valida que el campo $field sea un email válido
     * 
     * @param string $field El nombre del campo a validar
     * @return void
     */
    protected function email(string $field): void {
        if (!filter_var($this->rules[$field][0], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "El campo debe ser un email válido";
        }
    }

}