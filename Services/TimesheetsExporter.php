<?php

namespace Leantime\Plugins\DataExport\Services;

use Carbon\Carbon;
use Leantime\Domain\Setting\Services\Setting;
use Leantime\Domain\Timesheets\Repositories\Timesheets;
use Leantime\Core\Support\DateTimeHelper;

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
        DateTimeHelper $dateTimeHelper
    ) {
        parent::__construct($dateTimeHelper);
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, mixed>
     */
    public function generateData(array $criteria): array
    {
        $projectId = (int) ($criteria['project'] ?? 0);
        $kind = $criteria['kind'] ?? 'all';
        // In "all" timesheets the dates are named dateFrom/dateTo and in "my timesheet the dates are named startDate/endDate
        $dateFrom = $this->getDateTime($criteria['dateFrom'] ?? $criteria['startDate'], '2000-01-01');
        $dateTo = $this->getDateTime($criteria['dateTo'] ?? $criteria['endDate'], '2100-01-01');
        $userId = (int) ($criteria['userId'] ?? 0);
        $invEmpl = filter_var($criteria['invEmpl'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $invComp = filter_var($criteria['invComp'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $paid = filter_var($criteria['paid'] ?? null, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
        $clientId = (int) ($criteria['clientId'] ?? 0);

        $data = $this->timesheets->getAll($projectId, $kind, $dateFrom, $dateTo, $userId, $invEmpl, $invComp, $paid, $clientId, 0);

        foreach ($data as &$row) {
            $row['fullname'] = $row['firstname'] . ' ' . $row['lastname'];
            $row['workDate'] = format($row['workDate'])->date();
        }

        return $data;
    }
}
