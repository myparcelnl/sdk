<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Entity\Consignment;

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Model\BaseModel;

/**
 * @property int    $id
 * @property string $name
 */
abstract class AbstractEntity extends BaseModel
{
    /**
     * @var array
     */
    protected $attributes = [
        'id'   => null,
        'name' => null,
    ];

    /**
     * @param  mixed $input
     */
    public function __construct($input)
    {
        parent::__construct($this->create($input));
    }

    /**
     * @return array
     */
    abstract protected function getNamesIdsMap(): array;

    /**
     * @param  int|string $input
     *
     * @return void
     */
    private function create($input): array
    {
        $map   = $this->getNamesIdsMap();
        $ids   = array_values($map);
        $names = array_keys($map);

        if (is_numeric($input) && in_array((int) $input, $ids, true)) {
            return [
                'id'   => (int) $input,
                'name' => array_flip($map)[$input],
            ];
        }

        if (is_string($input) && in_array((string) $input, $names, true)) {
            return [
                'id'   => $map[$input],
                'name' => $input,
            ];
        }

        throw new InvalidArgumentException('Argument 1 is not a valid argument for ' . static::class);
    }
}
