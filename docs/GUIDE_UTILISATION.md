# Guide d'utilisation — démonstrateur

## Lancer la plateforme

Installez Docker Desktop avec Compose v2, ouvrez un terminal à la racine du dépôt, puis :

```bash
docker compose up --build
```

Dans un navigateur, ouvrez `http://localhost:8080`. Le premier démarrage télécharge les images et dépendances ; il peut prendre quelques minutes. L'API applique les migrations et charge un petit jeu de données synthétique au démarrage. Pour arrêter : `Ctrl+C`, puis `docker compose down`.

## Consulter le tableau de bord

La vue d'ensemble montre le nombre d'actifs, la valeur enregistrée, leur état et les dernières opérations. Les indicateurs sont recalculés depuis la base de démonstration au chargement. La bannière rappelle que les fiches et montants sont fictifs.

## Rechercher un bien

Ouvrez **Inventaire** dans le menu latéral. Saisissez un nom, une référence, un ministère ou une localisation. Les filtres permettent de limiter les résultats par état ou catégorie. Sur petit écran, ouvrez le menu avec l'icône en haut à gauche.

## Ajouter un bien

Cliquez **Ajouter un bien**, renseignez au minimum le nom, la catégorie, le ministère/service et la localisation, puis sélectionnez un état et enregistrez. Le serveur attribue une référence `TG-...`. En mode API, la fiche est persistée dans PostgreSQL. Si l'API est indisponible, la page reste consultable et annonce l'ajout dans la session de démonstration seulement : cette fiche locale disparaît au rechargement.

## Exporter

Cliquez **Exporter** ou **Exporter CSV** dans l'inventaire. Le navigateur télécharge `inventaire-patrimoine-togo.csv`, encodé en UTF-8 et séparé par des points-virgules. Les tableurs francophones l'ouvrent généralement directement.

## API pour la démonstration

- Santé : `GET http://localhost:8080/api/v1/health`
- Résumé : `GET http://localhost:8080/api/v1/summary`
- Inventaire : `GET http://localhost:8080/api/v1/assets?per_page=50`
- Recherche : `GET http://localhost:8080/api/v1/assets?q=Kara&status=bon`
- Création : `POST http://localhost:8080/api/v1/assets` avec un JSON contenant `name`, `category`, `ministry`, `location`, `status`.

## Réinitialiser la démonstration

`docker compose down -v` supprime les données du volume PostgreSQL. Au prochain démarrage, la base et les 18 exemples fictifs sont recréés. Cette opération supprime toutes les données stockées dans ce projet local.

## Pannes fréquentes

| Symptôme | Action |
| --- | --- |
| La page ne s'ouvre pas | Vérifier Docker Desktop et `docker compose ps` ; vérifier que le port 8080 est libre |
| Les indicateurs ne se chargent pas | Vérifier `docker compose logs asset-api postgres`, puis recharger après le démarrage complet |
| Erreur de connexion PostgreSQL au démarrage | Attendre le healthcheck ; afficher les logs Postgres et vérifier la variable `DB_PASSWORD` |
| Port occupé | Changer la partie gauche de `8080:80` dans Compose (ex. `8081:80`), redémarrer et ouvrir le nouveau port |

## Limites de ce guide

La maquette ne comprend pas d'authentification, de compte personnel, de transfert réellement tracé, de suppression logique, de rôle de validation, de sauvegarde ni de gestion de documents. Ne pas saisir de données personnelles, sensibles ou authentiques du patrimoine public.
