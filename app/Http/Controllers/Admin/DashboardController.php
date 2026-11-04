<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ParticipationRequest;
use App\Services\EditorialRegistry;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $modules = [];
        foreach (EditorialRegistry::modules() as $key => $spec) {
            $modules[$key] = ['label' => $spec['label'], 'count' => $spec['model']::count(), 'review' => $spec['model']::whereIn('status', ['draft', 'review'])->count()];
        }

        return view('admin.dashboard', ['modules' => $modules, 'participations' => ParticipationRequest::where('status', 'pending')->count(), 'contacts' => ContactMessage::where('status', 'pending')->count()]);
    }
}
