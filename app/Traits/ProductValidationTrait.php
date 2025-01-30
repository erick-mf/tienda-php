<?php

namespace App\Traits;

trait ProductValidationTrait
{
    public function validate($editProduct = false): array
    {
        $errors = [];

        // Validación de categoría
        if (empty($this->category_id)) {
            $errors['category_id'] = 'La categoría es obligatoria.';
        }

        // Validación del nombre
        if (empty($this->name)) {
            $errors['name'] = 'El nombre del producto es obligatorio.';
        } elseif (strlen($this->name) < 3) {
            $errors['name'] = 'El nombre debe tener al menos 3 caracteres.';
        } elseif (strlen($this->name) > 100) {
            $errors['name'] = 'El nombre no puede tener más de 100 caracteres.';
        } elseif (! preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{1,97}[0-9]{0,3}$/', $this->name)) {
            $errors['name'] = 'El nombre debe contener letras y hasta 3 números al final.';
        } elseif (preg_match('/^[0-9]+$/', $this->name)) {
            $errors['name'] = 'El nombre no pueden ser solo números.';
        }

        // Validación de la descripción
        if (empty($this->description)) {
            $errors['description'] = 'La descripción es obligatoria.';
        } elseif (strlen($this->description) > 100) {
            $errors['description'] = 'La descripción no puede tener más de 100 caracteres.';
        } elseif (preg_match('/^[0-9]+$/', $this->description)) {
            $errors['description'] = 'La descripción no pueden ser solo números.';
        }

        // Validación del precio
        if ($this->price === null || $this->price === '') {
            $errors['price'] = 'El precio es obligatorio.';
        } elseif (! is_numeric($this->price)) {
            $errors['price'] = 'El precio debe ser un número válido.';
        } elseif ($this->price < 0) {
            $errors['price'] = 'El precio no puede ser negativo.';
        }

        // Validación del stock
        if ($this->stock === null || $this->stock === '') {
            $errors['stock'] = 'El stock es obligatorio.';
        } elseif (! is_numeric($this->stock)) {
            $errors['stock'] = 'El stock debe ser un número válido.';
        } elseif ($this->stock < 0) {
            $errors['stock'] = 'El stock no puede ser negativo.';
        }

        // Validación de la oferta (opcional)
        if (! empty($this->offer) && strlen($this->offer) > 100) {
            $errors['offer'] = 'La oferta no puede tener más de 100 caracteres.';
        }

        // Validación de la fecha
        $today = date('Y-m-d'); // Obtiene la fecha actual en formato YYYY-MM-DD

        if (empty($this->date)) {
            $errors['date'] = 'La fecha es obligatoria.';
        } elseif ($this->date < $today && $editProduct === false) {
            $errors['date'] = 'La fecha no puede ser anterior a la fecha actual.';
        }

        return $errors;
    }
}
