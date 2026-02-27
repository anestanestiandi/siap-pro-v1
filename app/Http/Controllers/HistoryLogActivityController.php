<?php

namespace App\Http\Controllers;

use App\Models\HistoryLog;
use Illuminate\Http\Request;

class HistoryLogActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = HistoryLog::with('user')->latest();

        // Filter by Search (User Name, Action, Description, IP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Filter by Activity Type (Action)
        if ($request->filled('type') && $request->type !== 'all') {
            $type = $request->type;
            // Support both old format ('created','updated') and new format ('create','update')
            $map = [
                'create' => ['create', 'created'],
                'update' => ['update', 'updated'],
                'delete' => ['delete', 'deleted'],
                'login'  => ['login'],
                'logout' => ['logout'],
            ];
            $values = $map[$type] ?? [$type];
            $query->whereIn('action', $values);
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Date Range
        if ($request->filled('date')) {
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->subDays(7), now()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [now()->subDays(30), now()]);
                    break;
            }
        }

        $logs = $query->paginate(10)->withQueryString();

        return view('history-log.index', compact('logs'));
    }
}
