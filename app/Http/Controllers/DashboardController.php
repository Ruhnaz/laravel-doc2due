<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\Document;
use App\Models\ReminderLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $documents = Document::query()->get();
        $filters = request()->only(['search', 'status']);
        $scoped = Document::query()->with(['category', 'contractor'])->filtered($filters);
        $scopedIds = (clone $scoped)->pluck('id');

        $counts = [
            'expired' => $documents->where('status', 'expired')->count(),
            'soon' => $documents->where('status', 'soon')->count(),
            'warning' => $documents->where('status', 'warning')->count(),
            'safe' => $documents->where('status', 'safe')->count(),
        ];

        $companyIds = $documents->pluck('id');
        $alerts = $companyIds->isEmpty()
            ? collect()
            : ReminderLog::query()
                ->whereIn('document_id', $companyIds)
                ->with('document')
                ->latest('sent_at')
                ->limit(4)
                ->get();

        $reminders = $scopedIds->isEmpty()
            ? collect()
            : ReminderLog::query()
                ->whereIn('document_id', $scopedIds)
                ->with('document')
                ->latest('sent_at')
                ->limit(5)
                ->get();

        $uploads = (clone $scoped)->latest()->limit(5)->get();

        $feed = $reminders->map(fn (ReminderLog $log) => [
            'at' => $log->sent_at,
            'title' => $log->document->title,
            'detail' => $log->days_before === 0 ? 'Expired alert sent' : $log->days_before.'-day reminder sent',
        ])->concat($uploads->map(fn (Document $document) => [
            'at' => $document->created_at,
            'title' => $document->title,
            'detail' => 'Document uploaded',
        ]))->sortByDesc('at')->take(6)->values();

        return view('dashboard', [
            'counts' => $counts,
            'total' => $documents->count(),
            'expiringSoon' => $counts['soon'] + $counts['warning'],
            'contractors' => Contractor::query()->count(),
            'watch' => (clone $scoped)->orderBy('expiry_date')->limit(6)->get(),
            'alerts' => $alerts,
            'feed' => $feed,
            'usage' => $documents->count(),
            'limit' => auth()->user()->company->plan->document_limit,
        ]);
    }
}
