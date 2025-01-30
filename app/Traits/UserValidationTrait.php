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
        } elseif (preg_match('/^[0-9]+$/', $this->name)) {
            $errors['name'] = 'El nombre no puede contener solo números.';
        } elseif (strlen($this->name) < 3) {
            $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Validación de los apellidos
        if (empty($this->surnames)) {
            $errors['surnames'] = 'El nombre es obligatorio.';
        } elseif (strlen($this->surnames) > 50) {
            $errors['surnames'] = 'El nombre no puede tener más de 50 caracteres.';
        } elseif (preg_match('/^[0-9]+$/', $this->surnames)) {
            $errors['surnames'] = 'El nombre no puede contener solo números.';
        } elseif (strlen($this->surnames) < 3) {
            $errors['surnames'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Validación de la dirección (opcional en el formulario)
        if (! empty($this->address) && strlen($this->address) > 200) {
            $errors['address'] = 'La dirección no puede tener más de 200 caracteres.';
        } elseif (preg_match('/^[0-9]+$/', $this->address)) {
            $errors['address'] = 'La dirección no puede contener solo números.';
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
