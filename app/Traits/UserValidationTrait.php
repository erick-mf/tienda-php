<?php

namespace App\Traits;

trait UserValidationTrait
{
    public function validate(): array
    {
        $errors = [];

        // Validación del nombre
        if (empty($this->name)) {
            $errors['name'] = 'El nombre es obligatorio.';
        } elseif (strlen($this->name) > 50) {
            $errors['name'] = 'El nombre no puede tener más de 50 caracteres.';
        }

        // Validación de los apellidos
        if (empty($this->surnames)) {
            $errors['surnames'] = 'Los apellidos son obligatorios.';
        } elseif (strlen($this->surnames) > 100) {
            $errors['surnames'] = 'Los apellidos no pueden tener más de 100 caracteres.';
        }

        // Validación de la dirección (opcional en el formulario)
        if (! empty($this->address) && strlen($this->address) > 200) {
            $errors['address'] = 'La dirección no puede tener más de 200 caracteres.';
        }

        // Validación del email
        if (empty($this->email)) {
            $errors['email'] = 'El correo electrónico es obligatorio.';
        } elseif (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El correo electrónico no es válido.';
        }

        // Validación del teléfono (opcional en el formulario)
        if (! empty($this->phone) && ! preg_match('/^[0-9]{9}$/', $this->phone)) {
            $errors['phone'] = 'El teléfono debe tener 9 dígitos numéricos.';
        }

        // Validación de la contraseña
        if (empty($this->password)) {
            $errors['password'] = 'La contraseña es obligatoria.';
        } elseif (strlen($this->password) < 8) {
            $errors['password'] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        return $errors;
    }
}
