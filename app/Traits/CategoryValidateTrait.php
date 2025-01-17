<?php

namespace App\Traits;

trait CategoryValidateTrait
{
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'El nombre de la categoría es obligatorio.';
        } elseif (strlen($this->name) < 3) {
            $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
        } elseif (strlen($this->name) > 10) {
            $errors['name'] = 'El nombre no puede tener más de 10 caracteres.';
        } elseif (! preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $this->name)) {
            $errors['name'] = 'El nombre solo puede contener letras y espacios.';
        }

        return $errors;
    }
}
