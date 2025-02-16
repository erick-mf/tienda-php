<?php

namespace App\Traits;

trait OrderValidationTrait
{
    public function validate(): array
    {
        $errors = [];

        // Validación del state (provincia)
        if (empty($this->state)) {
            $errors['state'] = 'La provincia es obligatoria.';
        } elseif (strlen($this->state) > 100) {
            $errors['state'] = 'La provincia no puede tener más de 100 caracteres.';
        } elseif (! preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->state)) {
            $errors['state'] = 'La provincia solo debe contener letras y espacios.';
        }

        // Validación de la city (localidad)
        if (empty($this->city)) {
            $errors['city'] = 'La localidad es obligatoria.';
        } elseif (strlen($this->city) > 100) {
            $errors['city'] = 'La localidad no puede tener más de 100 caracteres.';
        } elseif (! preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->city)) {
            $errors['city'] = 'La localidad solo debe contener letras y espacios.';
        }

        // Validación de la address
        if (empty($this->address)) {
            $errors['address'] = 'La dirección es obligatoria.';
        } elseif (strlen($this->address) > 255) {
            $errors['address'] = 'La dirección no puede tener más de 255 caracteres.';
        }

        return $errors;
    }
}
