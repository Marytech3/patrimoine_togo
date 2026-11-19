<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\GovernmentService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Bâtiment administratif de Lomé-Centre','Bâtiments','Immeuble','Ministère de l’Économie et des Finances','Lomé, Centre administratif','bon','2018-04-12',480000000],
            ['Toyota Land Cruiser TG-0421','Véhicules','Véhicule tout-terrain','Ministère de la Santé','Lomé, Garage central','maintenance','2020-09-06',34500000],
            ['Ordinateurs portables — lot 14','Équipements','Matériel informatique','Ministère de l’Éducation nationale','Kara, Direction régionale','bon','2023-02-18',18200000],
            ['Parcelle cadastrale n° 08-214','Terrains','Parcelle','Ministère de l’Urbanisme','Tsévié, Zio 1','bon','2016-11-24',125000000],
            ['Groupe électrogène 100 kVA','Équipements','Énergie','Ministère de la Santé','Atakpamé, CHR','affectation','2021-06-15',12750000],
            ['Centre de santé communautaire','Bâtiments','Établissement public','Ministère de la Santé','Sokodé, Tchaoudjo','bon','2019-12-09',96000000],
            ['Toyota Hilux TG-0186','Véhicules','Véhicule utilitaire','Ministère de l’Agriculture','Lomé, Port','reforme','2012-03-20',17500000],
            ['Serveur de données — lot informatique','Équipements','Serveur','Ministère de l’Économie numérique','Lomé, Centre de données','bon','2024-01-27',24800000],
            ['Terrain agricole expérimental','Terrains','Parcelle agricole','Ministère de l’Agriculture','Kpalimé, Kloto 1','affectation','2017-08-11',67500000],
            ['Imprimerie nationale — presse offset','Équipements','Équipement industriel','Ministère de la Communication','Lomé, Tokoin','maintenance','2015-05-04',41500000],
            ['Bâtiment de la préfecture','Bâtiments','Bâtiment administratif','Ministère de l’Administration territoriale','Dapaong, Tône','bon','2014-10-16',73500000],
            ['Minibus Hyundai TG-0712','Véhicules','Transport collectif','Ministère des Enseignements','Kara, Campus','bon','2022-07-21',28900000],
            ['Salle de classe — bloc B','Bâtiments','Établissement scolaire','Ministère de l’Éducation nationale','Bassar, Bassar 1','bon','2020-02-02',52000000],
            ['Radio de communication — lot 07','Équipements','Télécommunication','Ministère de la Sécurité','Lomé, Direction générale','maintenance','2019-11-19',5800000],
            ['Parcelle publique n° 12-087','Terrains','Parcelle','Ministère de l’Administration territoriale','Aného, Lacs 1','bon','2018-01-13',43800000],
            ['Ambulance Toyota Hiace TG-0529','Véhicules','Véhicule sanitaire','Ministère de la Santé','Kara, CHR','bon','2021-04-28',31600000],
            ['Centre de formation technique','Bâtiments','Établissement public','Ministère des Enseignements','Lomé, Agoè-Nyivé','bon','2022-09-03',142000000],
            ['Kit solaire — lot 23','Équipements','Énergie renouvelable','Ministère de l’Énergie','Mango, Oti','affectation','2023-05-30',9900000],
        ];
        if (!Asset::exists()) {
            foreach ($items as $i => [$name,$category,$type,$ministry,$location,$status,$date,$value]) {
                Asset::create(['reference'=>'TG-'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT),'name'=>$name,'category'=>$category,'type'=>$type,'ministry'=>$ministry,'location'=>$location,'status'=>$status,'acquired_at'=>$date,'value_xof'=>$value,'description'=>'Donnée fictive de démonstration.']);
            }
        }

        $regionByCity = [
            'Lomé' => 'Maritime', 'Tsévié' => 'Maritime', 'Aného' => 'Maritime',
            'Kpalimé' => 'Plateaux', 'Atakpamé' => 'Plateaux',
            'Sokodé' => 'Centrale', 'Kara' => 'Kara', 'Bassar' => 'Kara',
            'Dapaong' => 'Savanes', 'Mango' => 'Savanes',
        ];
        foreach (Asset::query()->select('ministry')->distinct()->pluck('ministry') as $ministry) {
            $asset = Asset::where('ministry', $ministry)->first();
            $city = collect(array_keys($regionByCity))->first(fn (string $name) => str_contains(strtolower($asset->location), strtolower($name)));
            GovernmentService::firstOrCreate(
                ['name' => $ministry],
                ['ministry' => $ministry, 'region' => $city ? $regionByCity[$city] : null, 'address' => $asset->location, 'is_active' => true],
            );
        }

        if (!AssetMovement::exists()) {
            $history = [
                ['TG-0001','transfert','Ministère des Finances','Ministère de l’Économie et des Finances','Lomé, ancien site','Lomé, Centre administratif','2021-06-14','Regroupement des services centraux.'],
                ['TG-0002','maintenance',null,null,'Lomé, Garage central',null,'2026-10-08','Révision périodique du véhicule.'],
                ['TG-0003','affectation','Administration centrale','Ministère de l’Éducation nationale','Lomé, magasin central','Kara, Direction régionale','2024-01-15','Dotation de matériel à la direction régionale.'],
                ['TG-0005','affectation','Ministère de la Santé','Ministère de la Santé','Atakpamé, dépôt régional','Atakpamé, CHR','2022-02-02','Affectation au centre hospitalier régional.'],
                ['TG-0007','reforme',null,null,'Lomé, Port',null,'2025-05-20','Mise en réforme à la suite du constat technique.'],
                ['TG-0016','transfert','Ministère de la Santé','Ministère de la Santé','Lomé, Garage central','Kara, CHR','2023-03-11','Renforcement du parc d’urgence régional.'],
                ['TG-0018','affectation','Ministère de l’Énergie','Ministère de l’Énergie','Mango, dépôt','Mango, Oti','2023-06-12','Affectation du lot au service territorial.'],
            ];
            foreach ($history as [$assetReference,$type,$fromMinistry,$toMinistry,$fromLocation,$toLocation,$date,$reason]) {
                $asset = Asset::where('reference', $assetReference)->first();
                if (!$asset) continue;
                AssetMovement::create([
                    'reference' => 'MV-DEMO-'.str_pad((string) $asset->id, 4, '0', STR_PAD_LEFT),
                    'asset_id' => $asset->id, 'asset_reference' => $asset->reference, 'type' => $type,
                    'from_ministry' => $fromMinistry ?? $asset->ministry,
                    'to_ministry' => $toMinistry, 'from_location' => $fromLocation,
                    'to_location' => $toLocation, 'occurred_at' => $date, 'reason' => $reason,
                    'recorded_by' => 'Données fictives de démonstration',
                ]);
            }
        }
    }
}
