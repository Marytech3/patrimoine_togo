<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $request->validate([
            'from' => ['nullable', 'date'], 'to' => ['nullable', 'date', 'after_or_equal:from'],
            'category' => ['nullable', 'string', 'max:80'], 'status' => ['nullable', 'in:bon,maintenance,affectation,reforme'],
            'ministry' => ['nullable', 'string', 'max:160'],
        ]);
        $query = $this->filtered($request);
        $items = (clone $query)->orderBy('ministry')->orderBy('name')->get();
        $totalValue = (int) $items->sum(fn (Asset $asset) => (int) $asset->value_xof);

        return response()->json([
            'total' => $items->count(),
            'value_xof' => $totalValue,
            'average_value_xof' => $items->count() ? (int) round($totalValue / $items->count()) : 0,
            'categories' => $items->groupBy('category')->map(fn ($group, $name) => ['name' => $name, 'total' => $group->count(), 'value_xof' => (int) $group->sum('value_xof')])->values(),
            'ministries' => $items->groupBy('ministry')->map(fn ($group, $name) => ['name' => $name, 'total' => $group->count(), 'value_xof' => (int) $group->sum('value_xof')])->values(),
            'statuses' => $items->groupBy('status')->map(fn ($group, $name) => ['name' => $name, 'total' => $group->count()])->values(),
            'data' => $items,
        ]);
    }

    private function filtered(Request $request): Builder
    {
        return Asset::query()
            ->when($request->filled('from'), fn (Builder $query) => $query->whereDate('acquired_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn (Builder $query) => $query->whereDate('acquired_at', '<=', $request->date('to')))
            ->when($request->filled('category'), fn (Builder $query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')))
            ->when($request->filled('ministry'), fn (Builder $query) => $query->where('ministry', 'ilike', '%'.$request->string('ministry').'%'));
    }
}
