# Patrimoine Togo — plateforme de gestion

Prototype pédagogique full stack pour l'inventaire, la traçabilité et le pilotage du patrimoine public togolais. L'application est en français et utilise une identité visuelle inspirée du drapeau togolais.

## Démarrage rapide

Prérequis : Docker Desktop et Docker Compose v2.

```bash
docker compose up --build
```

Ouvrir [http://localhost:8080](http://localhost:8080). L'interface charge les données de démonstration de l'API quand elle est disponible et propose un mode de démonstration autonome si l'API est arrêtée. Arrêt : `docker compose down`. Réinitialisation des données (destructif) : `docker compose down -v`.

Le démarrage complet recommandé est Docker Compose. Pour travailler sur l'API seule sans Docker : `cd asset-service`, copier `.env.example` en `.env`, configurer PostgreSQL, exécuter `composer install`, `php artisan key:generate`, `php artisan migrate --seed`, puis `php artisan serve`. Le serveur Laravel seul ne fournit pas le proxy web de la plateforme complète.

## Ce que couvre le MVP

- Tableau de bord avec indicateurs, parc par catégorie, état des biens et dernières opérations.
- Inventaire filtrable et consultable ; création de biens via le formulaire « Ajouter un bien ».
- Consultation d'un bien, export CSV, recherche et filtre par état/catégorie.
- API REST versionnée, validation serveur, PostgreSQL et jeu de données fictif de démonstration.
- Architecture de départ en services : service Laravel de gestion des actifs et passerelle web ; les domaines identité, référentiel et audit sont explicités comme prochains services dans `docs/ARCHITECTURE.md`.

Les données affichées sont fictives et ne représentent pas un inventaire officiel de l'État togolais.

## Dépôt et mise en ligne

Code source : [github.com/Marytech3/patrimoine_togo](https://github.com/Marytech3/patrimoine_togo). Maquette cible : [document Figma partagé](https://www.figma.com/design/waAztuRniXMVQR7QKW6JIx/Sans-titre?node-id=0-1&t=u1zzY7pK4oXFmrRX-1). Fichier de maquette de secours/import : [SVG bureau](docs/maquette-vue-ensemble.svg). Voir `docs/DEPLOIEMENT.md` pour le parcours de mise en ligne et les limites du palier gratuit.

## Livrables pédagogiques

| Livrable | Emplacement |
| --- | --- |
| Plateforme exécutable | `docker-compose.yml`, `asset-service/`, `web/` |
| Conception, UML, fiche technique, estimation et guide | `docs/` |
| Maquette et reprise Figma | `docs/maquette-vue-ensemble.svg`, `docs/FIGMA.md` (importer le SVG dans le document partagé) |

## Qualité

Depuis `asset-service` : `php artisan route:list --path=api` et `php artisan migrate:status`. Les tests applicatifs peuvent être lancés par `php artisan test`.

## Normalisation de travail

- Noms de branches : `feat/`, `fix/`, `docs/` suivis d'un sujet court.
- Commits : `feat:`, `fix:`, `docs:`, `refactor:`, `test:`.
- PHP : PSR-12, validation à l'entrée, migrations pour toute évolution de schéma.
- API : préfixe `/api/v1`, JSON, pagination et codes HTTP explicites.
- Secrets dans `.env`, jamais dans Git ; ne pas utiliser de données réelles dans les démonstrations.
