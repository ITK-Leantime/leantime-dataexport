<?php

namespace Leantime\Plugins\Dataexport\Services;

use League\Csv\Writer;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Domain\Setting\Services\Setting;

/**
 * Abstract exporter.
 */
abstract class AbstractExporter
{
    /**
     * Constructor.
     */
    public function __construct(
        protected readonly Setting $setting
    ) {
    }

    /**
     * Generate data.
     *
     * @return array
     */
    abstract public function generateData(array $criteria): array;

    /**
     * Run export of generated data.
     *
     * @return void
     */
    public function export(array $criteria = [], string $format = 'csv', array $options = []): void
    {
        $data = $this->generateData($criteria);

        // We want to keep only non-numeric keys.
        $data = array_map(
            static fn(array $row) => array_filter($row, static fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY),
            $data
        );

        $csv = Writer::createFromString();

        $format = $options['format'] ?? 'csv';
        if ('excel' === $format) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "sep=," . PHP_EOL);
            fclose($handle);
            // @see https://csv.thephpleague.com/8.0/bom/#ms-excel-on-windows
            $csv
                ->setOutputBOM(Writer::BOM_UTF8)
                ->setDelimiter(';');
        }


        if (!empty($data)) {
            $csv->insertOne(array_keys(reset($data)));
            $csv->insertAll($data);
        }

        $filename = $options['filename'] ?? 'leantime-dataexport.csv';

        $csv->output($filename);
        exit;
    }

    /**
     * Get datetime based on user's date format.
     *
     * @param  string|null $value
     * @return \DateTimeInterface|null
     */
    public function getDateTime(string $value = null): ?\DateTimeInterface
    {
        if (null === $value) {
            return null;
        }
        if ($userId = Auth::getUserId()) {
            $format = $this->setting->getSetting('usersettings.' . $userId . '.date_format');
            try {
                return \DateTimeImmutable::createFromFormat($format, $value) ?: null;
            } catch (\Exception $exception) {
                return null;
            }
        }

        try {
            return new \DateTimeImmutable($value) ?: null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
