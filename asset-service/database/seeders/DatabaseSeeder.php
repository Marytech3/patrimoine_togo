<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Asset::exists()) return;
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
        foreach ($items as $i => [$name,$category,$type,$ministry,$location,$status,$date,$value]) {
            Asset::create(['reference'=>'TG-'.str_pad((string)($i+1),4,'0',STR_PAD_LEFT),'name'=>$name,'category'=>$category,'type'=>$type,'ministry'=>$ministry,'location'=>$location,'status'=>$status,'acquired_at'=>$date,'value_xof'=>$value,'description'=>'Donnée fictive de démonstration.']);
        }
    }
}
