<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetMovementController extends Controller
{
    private const TYPES = ['affectation', 'transfert', 'maintenance', 'retour_maintenance', 'reforme'];

    public function index(Request $request): JsonResponse
    {
        $rows = AssetMovement::query()->with('asset:id,reference,name,category,status')
            ->when($request->filled('type'), fn (Builder $query) => $query->where('type', $request->string('type')))
            ->when($request->filled('from'), fn (Builder $query) => $query->whereDate('occurred_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn (Builder $query) => $query->whereDate('occurred_at', '<=', $request->date('to')))
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.$request->string('q').'%';
                $query->where(fn (Builder $nested) => $nested->where('reference', 'ilike', $term)
                    ->orWhere('reason', 'ilike', $term)
                    ->orWhereHas('asset', fn (Builder $asset) => $asset->where('name', 'ilike', $term)->orWhere('reference', 'ilike', $term)));
            })->latest('occurred_at')->paginate(min(max((int) $request->query('per_page', 100), 1), 200));

        return response()->json($rows);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'asset_id' => ['required', 'integer', 'exists:assets,id'],
            'type' => ['required', 'in:'.implode(',', self::TYPES)],
            'to_ministry' => ['nullable', 'string', 'max:160'],
            'to_location' => ['nullable', 'string', 'max:160'],
            'occurred_at' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:500'],
            'recorded_by' => ['nullable', 'string', 'max:120'],
        ]);

        $movement = DB::transaction(function () use ($data): AssetMovement {
            $asset = Asset::query()->lockForUpdate()->findOrFail($data['asset_id']);
            $type = $data['type'];
            $toMinistry = $data['to_ministry'] ?? null;
            $toLocation = $data['to_location'] ?? null;
            if (in_array($type, ['affectation', 'transfert'], true) && (!$toMinistry || !$toLocation)) {
                abort(422, 'Un service et une localisation de destination sont obligatoires pour une affectation ou un transfert.');
            }

            $movement = AssetMovement::create([
                'reference' => 'MV-'.now()->format('ym').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)),
                'asset_id' => $asset->id,
                'asset_reference' => $asset->reference,
                'type' => $type,
                'from_ministry' => $asset->ministry,
                'to_ministry' => $toMinistry,
                'from_location' => $asset->location,
                'to_location' => $toLocation,
                'occurred_at' => $data['occurred_at'],
                'reason' => $data['reason'],
                'recorded_by' => $data['recorded_by'] ?? 'Administrateur de démonstration',
            ]);

            $changes = [];
            if (in_array($type, ['affectation', 'transfert'], true)) {
                $changes = ['ministry' => $toMinistry, 'location' => $toLocation, 'status' => 'bon'];
            } elseif ($type === 'maintenance') {
                $changes = ['status' => 'maintenance'];
            } elseif ($type === 'retour_maintenance') {
                $changes = ['status' => 'bon'];
            } elseif ($type === 'reforme') {
                $changes = ['status' => 'reforme'];
            }
            $asset->update($changes);

            return $movement->load('asset:id,reference,name,category,status,ministry,location');
        });

        return response()->json(['data' => $movement], 201);
    }
}
