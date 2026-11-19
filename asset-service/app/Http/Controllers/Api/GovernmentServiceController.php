<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\GovernmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GovernmentServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $services = GovernmentService::query()
            ->when($request->has('active'), fn ($query) => $query->where('is_active', $request->boolean('active')))
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'ilike', '%'.$request->string('q').'%'))
            ->orderBy('name')->get();

        $services->each(function (GovernmentService $service): void {
            $count = Asset::query()->where(fn ($query) => $query->where('ministry', $service->name)
                ->orWhere('ministry', $service->ministry))->count();
            $service->setAttribute('assets_count', $count);
        });

        return response()->json(['data' => $services]);
    }

    public function store(Request $request): JsonResponse
    {
        $service = GovernmentService::create($this->validated($request));
        return response()->json(['data' => $service], 201);
    }

    public function update(Request $request, GovernmentService $service): JsonResponse
    {
        $service->update($this->validated($request, $service));
        return response()->json(['data' => $service->refresh()]);
    }

    private function validated(Request $request, ?GovernmentService $service = null): array
    {
        $required = $service ? 'sometimes|required' : 'required';
        return $request->validate([
            'name' => [$required, 'string', 'max:160', 'unique:government_services,name'.($service ? ','.$service->id : '')],
            'ministry' => [$required, 'string', 'max:160'],
            'region' => [$service ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:40'],
            'prefecture' => [$service ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:100'],
            'address' => [$service ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:180'],
            'manager' => [$service ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:120'],
            'contact' => [$service ? 'sometimes' : 'nullable', 'nullable', 'string', 'max:120'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
