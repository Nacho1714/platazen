<?php

namespace App\Validation;

class ValidateUser
{

    protected array $rules;
    protected array $errors = [];

    /**
     * @param array $rules Las reglas de validación. Cada regla es un array con dos elementos: el primero es el valor a validar y el segundo es la regla a aplicar separada por | (pipe) en caso de que haya más de una regla, por ejemplo: ['name' => [$name, 'required|min:3|max:50']]
     * 
     * REGLAS: required, email, password.
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Valida los datos según las reglas. 
     * 
     * @return bool Devuelve true si la validación fue exitosa, false en caso contrario
     */
    public function passes(): bool
    {

        foreach ($this->rules as $field => $rule) {

            $rules = explode('|', $rule[1]);

            foreach ($rules as $rule) {

                $rule = explode(':', $rule);

                switch ($rule[0]) {
                    case 'required':
                        $this->required($field);
                        break;
                    case 'email':
                        $this->email($field);
                        break;
                    case 'password':
                        $this->password($field);
                        break;
                    case 'passwordConfirm':
                        $this->passwordConfirm($field, $rule[1]);
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
    public function errors(): array
    {
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
     * Valida que el campo $field sea un email válido
     * 
     * @param string $field El nombre del campo a validar
     * @return void
     */
    protected function email(string $field): void
    {
        $email = $this->rules[$field][0];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "El campo debe ser un email válido";
        }
    }

    /**
     * Valida que el campo $field sea un password válido
     * 
     * @param string $field El nombre del campo a validar
     * @return void
     */
    protected function password(string $field): void
    {

        $minLength = 8;
        $password = $this->rules[$field][0];

        switch (true) {
            case strlen($password) < $minLength:
                $this->errors[$field] = 'La contraseña debe tener al menos ' . $minLength . ' caracteres';
                break;

            case !preg_match('/[A-Z]/', $password):
                $this->errors[$field] = 'La contraseña debe tener al menos una letra mayúscula';
                break;

            case !preg_match('/[a-z]/', $password):
                $this->errors[$field] = 'La contraseña debe tener al menos una letra minúscula';
                break;

            case !preg_match('/[0-9]/', $password):
                $this->errors[$field] = 'La contraseña debe tener al menos un número';
                break;

            case !preg_match('/[^A-Za-z0-9]/', $password):
                $this->errors[$field] = 'La contraseña debe tener al menos un caracter especial';
                break;
        }

    }

    /**
     * Valida que el campo $field sea igual al campo $fieldConfirm
     * 
     * @param string $field El nombre del campo a validar
     * @param string $fieldConfirm El nombre del campo a comparar
     * @return void
     */
    protected function passwordConfirm(string $field, string $fieldConfirm): void
    {
        $password = $this->rules[$field][0];
        $passwordConfirm = $this->rules[$fieldConfirm][0];

        if ($password !== $passwordConfirm) {
            $this->errors[$field] = 'Las contraseñas no coinciden';
        }
    }
}
