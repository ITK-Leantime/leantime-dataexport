<?php

namespace Leantime\Plugins\Dataexport\Services;

require_once __DIR__ . '/../vendor-plugin/autoload.php';

use Leantime\Domain\Auth\Services\Auth;
use Leantime\Domain\Setting\Services\Setting;
use OpenSpout\Common\Entity\Row;
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
            $writer->addRows(array_map(Row::fromValues(...), $data));
        }
        $writer->close();
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
