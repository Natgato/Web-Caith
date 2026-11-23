<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ParticipationRequest;
use App\Services\EditorialRegistry;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $modules = [];
        $queue = collect();
        foreach (EditorialRegistry::modules() as $key => $spec) {
            $query = $spec['model']::query();
            if ($key === 'galeria') {
                $query->where('restricted_to_testimonial', false);
            }
            $counts = (clone $query)->whereIn('status', ['draft', 'review', 'published'])
                ->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

            $modules[$key] = [
                'label' => $spec['label'],
                'draft' => (int) $counts->get('draft', 0),
                'review' => (int) $counts->get('review', 0),
                'published' => (int) $counts->get('published', 0),
            ];

            $queue = $queue->concat(
                $this->tasks($query->where('status', 'review'), $spec['label'], 'admin.content.edit', $key)
            );
        }

        $inboxes = [
            'contacto' => ['label' => 'Mensajes de contacto', 'query' => ContactMessage::query()],
            'participacion' => ['label' => 'Solicitudes de participación', 'query' => ParticipationRequest::query()],
        ];
        $month = now()->startOfMonth();
        $months = collect(range(5, 0))->map(fn ($ago) => $month->copy()->subMonths($ago));
        $series = [];
        $totals = [];
        foreach ($inboxes as $key => ['label' => $label, 'query' => $query]) {
            $rows = (clone $query)->where('created_at', '>=', $months->first())
                ->where('created_at', '<', $month->copy()->addMonth())->pluck('created_at');
            $counts = $rows->countBy(fn ($date) => $date->format('Y-m'));
            $series[$key] = $months->map(fn ($date) => $counts->get($date->format('Y-m'), 0));

            $pending = $query->where('status', 'pending');
            $totals[$key] = (clone $pending)->count();
            $queue = $queue->concat($this->tasks($pending, $label, 'admin.inbox.show', $key));
        }

        return view('admin.dashboard', [
            'modules' => collect($modules),
            'months' => $months,
            'series' => $series,
            'totals' => $totals,
            'queue' => $queue->sortBy('date')->take(8),
        ]);
    }

    private function tasks(Builder $query, string $label, string $route, string $key)
    {
        return $query->orderBy('updated_at')->take(8)->get()->map(fn ($record) => [
            'date' => $record->updated_at,
            'label' => $label,
            'title' => $record->title ?? 'Solicitud '.substr($record->reference, 0, 8),
            'url' => route($route, [$key, $record->id]),
        ]);
    }
}
