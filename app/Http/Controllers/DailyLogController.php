<?php


namespace App\Http\Controllers;

use App\Services\DailyLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function __construct(private DailyLogService $logs)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'metric' => ['required', 'string', 'max:64'],
            'value' => ['required', 'numeric'],
            'meta' => ['nullable', 'array'],
            'logged_at' => ['nullable', 'date'],
        ]);

        $log = $this->logs->log(
            auth()->id(),
            $data['metric'],
            (float) $data['value'],
            $data['meta'] ?? null,
            isset($data['logged_at']) ? Carbon::parse($data['logged_at']) : null,
        );

        return response()->json($log, 201);
    }

    public function daily(Request $request)
    {
        $data = $request->validate([
            'metric' => ['required', 'string'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        return $this->logs->dailyForUser(
            auth()->id(),
            $data['metric'],
            Carbon::parse($data['from']),
            Carbon::parse($data['to']),
        );
    }
}