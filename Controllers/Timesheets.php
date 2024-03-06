<?php

namespace Leantime\Plugins\Dataexport\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Leantime\Core\Controller;
use Leantime\Core\Frontcontroller;
use Leantime\Core\IncomingRequest;
use Leantime\Core\Language;
use Leantime\Core\Template;
use Leantime\Domain\Auth\Models\Roles;
use Leantime\Domain\Auth\Services\Auth;
use Leantime\Plugins\Dataexport\Services\AbstractExporter;
use Leantime\Plugins\Dataexport\Services\TimesheetsExporter;

/**
 * Timesheets controller.
 */
final class Timesheets extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        private readonly TimesheetsExporter $timesheetsExporter,
        IncomingRequest $incomingRequest,
        Template $tpl,
        Language $language
    ) {
        parent::__construct($incomingRequest, $tpl, $language);
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
     * @return void
     */
    public function get(array $params): void
    {
        // Cf. \Leantime\Domain\Timesheets\Controllers\ShowAll::run();
        Auth::authOrRedirect([Roles::$owner, Roles::$admin, Roles::$manager], true);

        $id = $params['id'] ?? null;
        $format = $params['format'] ?? AbstractExporter::FORMAT_CSV;

        if ('all' === $id) {
            $filename = 'leantime_timesheets';
            if ($date = $this->timesheetsExporter->getDateTime($criteria['dateFrom'] ?? null)) {
                $filename .= '_' . $date->format(\DateTimeInterface::ATOM);
            }
            if ($date = $this->timesheetsExporter->getDateTime($criteria['dateTo'] ?? null)) {
                $filename .= '_' . $date->format(\DateTimeInterface::ATOM);
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
