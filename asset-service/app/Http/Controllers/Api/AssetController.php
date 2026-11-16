<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $category = $request->query('category');
        $assets = Asset::query()
            ->when($term !== '', function ($query) use ($term): void {
                $needle = '%'.$term.'%';
                $query->where(fn ($nested) => $nested->where('name', 'ilike', $needle)
                    ->orWhere('reference', 'ilike', $needle)
                    ->orWhere('ministry', 'ilike', $needle)
                    ->orWhere('location', 'ilike', $needle));
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest()->paginate(min((int) $request->input('per_page', 100), 200));

        return response()->json($assets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:80'],
            'type' => ['nullable', 'string', 'max:80'],
            'ministry' => ['required', 'string', 'max:160'],
            'location' => ['required', 'string', 'max:160'],
            'status' => ['required', 'in:bon,maintenance,affectation,reforme'],
            'acquired_at' => ['nullable', 'date'],
            'value_xof' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['reference'] = 'TG-'.now()->format('ym').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $asset = Asset::create($data);

        return response()->json(['data' => $asset], 201);
    }

    public function show(Asset $asset): JsonResponse
    {
        return response()->json(['data' => $asset]);
    }

    public function update(Request $request, Asset $asset): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:160'],
            'category' => ['sometimes', 'required', 'string', 'max:80'],
            'type' => ['nullable', 'string', 'max:80'],
            'ministry' => ['sometimes', 'required', 'string', 'max:160'],
            'location' => ['sometimes', 'required', 'string', 'max:160'],
            'status' => ['sometimes', 'required', 'in:bon,maintenance,affectation,reforme'],
            'acquired_at' => ['nullable', 'date'],
            'value_xof' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
        $asset->update($data);

        return response()->json(['data' => $asset->refresh()]);
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $asset->delete();
        return response()->json([], 204);
    }

    public function summary(): JsonResponse
    {
        $count = Asset::count();
        $value = Asset::sum('value_xof');
        $categories = Asset::query()->selectRaw('category, count(*) as total')
            ->groupBy('category')->orderByDesc('total')->get();
        $statuses = Asset::query()->selectRaw('status, count(*) as total')->groupBy('status')->get();

        return response()->json([
            'total' => $count,
            'value_xof' => (int) $value,
            'in_service' => Asset::where('status', 'bon')->count(),
            'attention' => Asset::whereIn('status', ['maintenance', 'reforme'])->count(),
            'categories' => $categories,
            'statuses' => $statuses,
            'recent' => Asset::latest()->limit(5)->get(),
        ]);
    }
}
