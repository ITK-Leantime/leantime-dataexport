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

        [$context, $query] = $this->getContext($params);
        $id = $context['id'] ?? null;
        $format = $query['format'] ?? AbstractExporter::FORMAT_CSV;
        unset($query['format']);

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
                $query,
                options: [
                    'filename' => $filename,
                    'format' => $format,
                ]
            );
        }

        throw new HttpResponseException(Frontcontroller::redirect(BASE_URL . '/errors/error404'));
    }

    /**
     * Get context from query string parameters.
     *
     * @return array
     *   id: id
     *   format: format
     *   query: the rest of the parameters
     */
    private function getContext(array $params): array
    {
        $leantime = array_filter(
            $params,
            static fn (mixed $key) => in_array($key, ['id', 'act', 'request_parts'], true),
            ARRAY_FILTER_USE_KEY
        );
        $query  = array_diff_key($params, $leantime);

        return [$leantime, $query];
    }
}
