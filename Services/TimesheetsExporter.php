<?php

namespace Leantime\Plugins\DataExport\Services;

use Carbon\Carbon;
use Leantime\Domain\Setting\Services\Setting;
use Leantime\Domain\Timesheets\Repositories\Timesheets;

/**
 * Timesheets exporter.
 */
final class TimesheetsExporter extends AbstractExporter
{
    /**
     * Constructor.
     */
    public function __construct(
        private readonly Timesheets $timesheets,
        Setting $setting
    ) {
        parent::__construct($setting);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function generateData(array $criteria): array
    {
        $projectId = (int) ($criteria['project'] ?? 0);
        $kind = $criteria['kind'] ?? null;
        $dateFrom = $this->getDateTime($criteria['dateFrom'] ?? null, new Carbon('2000-01-01'));
        $dateTo = $this->getDateTime($criteria['dateTo'] ?? null, new Carbon('2100-01-01'));
        $userId = (int) ($criteria['userId'] ?? 0);
        $invEmpl = filter_var($criteria['invEmpl'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $invComp = filter_var($criteria['invComp'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $paid = filter_var($criteria['paid'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $clientId = (int) ($criteria['clientId'] ?? 0);

        return $this->timesheets->getAll($projectId, $kind, $dateFrom, $dateTo, $userId, $invEmpl, $invComp, $paid, $clientId, 0);
    }
}
