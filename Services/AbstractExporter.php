<?php

namespace Leantime\Plugins\DataExport\Services;

require_once __DIR__ . '/../vendor-plugin/autoload.php';

use Carbon\Carbon;
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

        $data = $this->addMoreFields($data);

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
     * @return \Carbon\Carbon|null
     */
    public function getDateTime(string $value = null, ?Carbon $default = null): ?Carbon
    {
        if (null === $value) {
            return $default;
        }

        if ($format = ($_SESSION['usersettings.language.date_format'] ?? null)) {
            // See https://www.php.net/manual/en/datetimeimmutable.createfromformat.php for details on `!`.
            return Carbon::createFromFormat('!' . $format, $value) ?: $default;
        }

        return new Carbon($value) ?: $default;
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

  /**
   * Add additional fields to dataset.
   *
   * @param $data
   *   The original dataset.
   *
   * @return array
   *   The updated dataset.
   */
    private function addMoreFields(array &$data): array {
      $data[0]['fullName'] = "{$data[0]['firstname']} {$data[0]['lastname']}";

      return $data;
    }
}
