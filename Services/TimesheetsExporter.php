<?php

namespace Leantime\Plugins\Dataexport\Services;

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
        $dateFrom = $this->getDateTime($criteria['dateFrom'] ?? '2000-01-01')->format('Y-m-d H:i:s');
        $dateTo = $this->getDateTime($criteria['dateTo'] ?? '2100-01-01')->format('Y-m-d H:i:s');
        $userId = (int) ($criteria['userId'] ?? 0);
        $invEmpl = 'on' === ($criteria['invEmpl'] ?? null) ? '1' : '';
        $invComp = 'on' === ($criteria['invComp'] ?? null) ? '1' : '';
        $paid = 'on' === ($criteria['paid'] ?? null) ? '1' : '';
        $clientId = (int) ($criteria['clientId'] ?? 0);

        return $this->timesheets->getAll($projectId, $kind, $dateFrom, $dateTo, $userId, $invEmpl, $invComp, $paid, $clientId, 0);
    }
}
