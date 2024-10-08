<?php

namespace Leantime\Plugins\DataExport\Services;

require_once __DIR__ . '/../vendor/autoload.php';

use Carbon\CarbonImmutable;
use OpenSpout\Common\Entity\Row;
use Leantime\Core\Support\DateTimeHelper;
use OpenSpout\Writer\AbstractWriter;
use OpenSpout\Writer\CSV\Writer as CSVWriter;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;

/**
 * Abstract exporter.
 */
abstract class AbstractExporter
{
    const FORMAT_CSV = 'csv';
    const FORMAT_XLSX = 'xlsx';

    /**
     * Constructor.
     */
    public function __construct(
        protected readonly DateTimeHelper $dateTimeHelper
    ) {
    }

    /**
     * Generate data.
     *
     * @param array<string, mixed> $criteria
     *
     * @return array<string, mixed>
     */
    abstract public function generateData(array $criteria): array;

    /**
     * Run export of generated data.
     *
     * @param array<string, mixed> $criteria
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function export(array $criteria = [], array $options = []): void
    {
        $data = $this->generateData($criteria);

        // We want to keep only non-numeric keys.
        $data = array_map(
            static fn(array $row) => array_filter($row, static fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY),
            $data
        );

        $filename = $options['filename'] ?? 'leantime-dataexport.csv';
        $writer = $this->getWriter($filename);
        $writer->openToBrowser($filename);
        if (!empty($data)) {
            $writer->addRow(Row::fromValues(array_keys(reset($data))));
            /* @phpstan-ignore-next-line */
            $writer->addRows(array_map(Row::fromValues(...), $data));
        }
        $writer->close();
        exit;
    }

    /**
     * Get datetime based on user's date format.
     *
     * @return \Carbon\CarbonImmutable|null
     */
    public function getDateTime(string $value = null, string $default = null): ?CarbonImmutable
    {
        $value ??= $default;

        return null !== $value ? $this->dateTimeHelper->parseUserDateTime($value)->setToDbTimezone() : null; // @phpstan-ignore method.notFound (After Laravel upgrade it cannot find methods that exist)
    }

    /**
     * Get writer for a file path.
     *
     * @param string $path
     * @return AbstractWriter
     */
    private function getWriter(string $path): AbstractWriter
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            self::FORMAT_XLSX => new XLSXWriter(),
            self::FORMAT_CSV => new CSVWriter(),
            default => throw new \RuntimeException(sprintf('Unsupported writer type: %s', $extension))
        };
    }
}
