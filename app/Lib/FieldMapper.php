<?php

namespace App\Lib;

/**
 * Clase FieldMapper
 *
 * Esta clase proporciona funcionalidad para mapear y normalizar campos de entidades
 * entre nombres en español e inglés.
 */
class FieldMapper
{
    private static $product = [
        'categoria_id' => 'category_id',
        'nombre' => 'name',
        'descripcion' => 'description',
        'precio' => 'price',
        'stock' => 'stock',
        'oferta' => 'offer',
        'fecha' => 'date',
        'imagen' => 'image',
    ];

    /**
     * Normaliza los datos de una entidad específica.
     *
     * Este método toma un array de datos y los normaliza según el mapeo definido
     * para la entidad especificada, convirtiendo nombres de campos en español a inglés.
     *
     * @param  string  $entity  El nombre de la entidad a normalizar.
     * @param  array  $data  Los datos a normalizar.
     * @return array Los datos normalizados.
     */
    public static function normalizeData(string $entity, array $data): array
    {
        if (! property_exists(self::class, $entity)) {
            throw new \InvalidArgumentException("No existe mapeo para la entidad: $entity");
        }

        $mapping = self::$$entity;
        $normalizedData = [];

        foreach ($mapping as $spanishKey => $englishKey) {
            if (isset($data[$spanishKey])) {
                $normalizedData[$englishKey] = $data[$spanishKey];
            } elseif (isset($data[$englishKey])) {
                $normalizedData[$englishKey] = $data[$englishKey];
            }
        }

        return $normalizedData;
    }
}
