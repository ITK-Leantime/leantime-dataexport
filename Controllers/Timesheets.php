<?php

namespace Leantime\Plugins\DataExport\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Leantime\Core\Controller\Controller;
use Leantime\Core\Controller\Frontcontroller;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\DataExport\Services\AbstractExporter;
use Leantime\Plugins\DataExport\Services\TimesheetsExporter;

/**
 * Timesheets controller.
 */
final class Timesheets extends Controller
{
    private TimesheetsExporter $timesheetsExporter;

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function init(
        TimesheetsExporter $timesheetsExporter
    ): void {
        $this->timesheetsExporter = $timesheetsExporter;
    }

    /**
     * GET: /dataexport/timesheets/all?name=Mikkel&number=87
     * ==>
     * $params: [
     *   'act' => 'dataexport.timesheets',
     *   'id' => 'all',
     *   'name' => 'Mikkel',
     *   'number' => '87',
     * ]
     *
     * @see \Leantime\Core\Frontcontroller::executeAction().
     *
     * @param array<string, mixed> $params
     *
     * @return void
     */
    public function get(array $params): void
    {
        // Cf. \Leantime\Domain\Timesheets\Controllers\ShowAll::run();
        Auth::authOrRedirect([Roles::$owner, Roles::$admin, Roles::$manager], true);
        $id = $params['id'] ?? null;
        $format = $params['format'] ?? AbstractExporter::FORMAT_CSV;

        if ('all' === $id || 'my' === $id) {
            // In "all" timesheets the dates are named dateFrom/dateTo and in "my timesheet the dates are named startDate/endDate
            $fromDate = $params['dateFrom'] ?? $params['startDate'];
            $toDate = $params['dateTo'] ?? $params['endDate'];
            $filename = 'leantime_timesheets';

            if ($date = $this->timesheetsExporter->getDateTime($fromDate ?? null)) {
                $filename .= '_' . $date->format(\DateTimeInterface::ATOM);
            }

            if ($date = $this->timesheetsExporter->getDateTime($toDate ?? null)) {
                $filename .= '_' . $date->format(\DateTimeInterface::ATOM);
            }

            if ('my' === $id) {
                // The "all" timesheets gets the userId from submitting the get request, the
                // "My timesheets" export needs to get the user id from the session object.
                $params['userId'] = session('userdata.id');
            }

            $filename .= '.' . $format;
            $this->timesheetsExporter->export(
                $params,
                options: [
                    'filename' => $filename,
                    'format' => $format,
                ]
            );
        }

        throw new HttpResponseException(Frontcontroller::redirect(BASE_URL . '/errors/error404'));
    }
}
