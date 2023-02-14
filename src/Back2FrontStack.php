<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front;

use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Config\Repository as ConfigRepository;

class Back2FrontStack extends Collection implements Back2FrontInterface
{
    /**
     * Date format DateTime object conversion.
     *
     * @var string
     */
    protected $date_format;

    /**
     * Maximum depth of recursive data traversal when converting to scalars.
     *
     * @var int
     */
    protected $max_recursion_depth;

    /**
     * Back2FrontStack constructor.
     *
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config)
    {
        parent::__construct();

        /** @var array{date_format: string|null,max_recursion_depth: int|null, stack_name: string|null} $values */
        $values = $config->get(ServiceProvider::getConfigRootKeyName());

        $this->date_format         = $values['date_format'] ?? 'Y-m-d H:i:s';
        $this->max_recursion_depth = $values['max_recursion_depth'] ?? 3;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \JsonException
     */
    public function toJson($options = 0): string
    {
        return (string) \json_encode($this->toArray(), $options | \JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->clearNoScalarsFromArrayRecursive(
            $this->formatDataRecursive($this->items, 0, $this->max_recursion_depth)
        );
    }

    /**
     * Recompiles the array only with simple data types and arrays.
     *
     * @param mixed[] $data
     *
     * @return array<mixed>
     */
    protected function clearNoScalarsFromArrayRecursive(array $data): array
    {
        $result = [];

        foreach ($data as $key => $item) {
            if (\is_array($item)) {
                $result[$key] = $this->clearNoScalarsFromArrayRecursive($item);
            } elseif (\is_scalar($item) || $item === null) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * Performs a recursive data traversal to the maximum specified level of nesting and converts the values to
     *  * Arrays + formats the date.
     *
     * @param iterable<mixed> $data      Данные
     * @param int             $depth     Текущая глубина обхода
     * @param int             $max_depth Максимальная глубина обхода
     *
     * @return array<mixed>
     */
    protected function formatDataRecursive($data, $depth = 0, $max_depth = 3): array
    {
        $map_closure = function ($value) use ($depth, $max_depth) {
            // Convert to array if available
            if ($value instanceof Arrayable) {
                $value = $value->toArray();
            }

            if ($value instanceof DateTime) {
                return $value->format($this->date_format);
            }

            if (\is_iterable($value) && $depth < $max_depth) {
                return $this->formatDataRecursive($value, ++$depth, $max_depth);
            }

            return $value;
        };

        return \array_map($map_closure, (array) $data);
    }
}
