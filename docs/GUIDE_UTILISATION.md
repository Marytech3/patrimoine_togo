# Guide d'utilisation — démonstrateur

## Lancer la plateforme

Installez Docker Desktop avec Compose v2, ouvrez un terminal à la racine du dépôt, puis :

```bash
docker compose up --build
```

Dans un navigateur, ouvrez `http://localhost:8081`. Le port 8080 étant déjà pris par une autre passerelle Docker de cette machine, le prototype utilise 8081. Le premier démarrage télécharge les images et dépendances ; il peut prendre quelques minutes. L'API applique les migrations et charge un petit jeu de données synthétique au démarrage. Pour arrêter : `Ctrl+C`, puis `docker compose down`.

## Consulter le tableau de bord

La vue d'ensemble montre le nombre d'actifs, la valeur enregistrée, leur état et les dernières opérations. Les indicateurs sont recalculés depuis la base de démonstration au chargement. La bannière rappelle que les fiches et montants sont fictifs.

## Rechercher un bien

Ouvrez **Inventaire** dans le menu latéral. Saisissez un nom, une référence, un ministère ou une localisation. Les filtres permettent de limiter les résultats par état ou catégorie. Sur petit écran, ouvrez le menu avec l'icône en haut à gauche.

## Gérer les services de l'État

Dans **Services de l'État**, consultez le répertoire, recherchez un service et créez une fiche avec son ministère de rattachement, sa région, sa préfecture, son adresse, son responsable et son contact. Une fiche peut être modifiée, désactivée ou réactivée ; elle reste dans le répertoire afin de conserver le contexte des biens déjà rattachés. Les biens sont rattachés à un ministère/service par le libellé utilisé dans leur fiche.

## Ajouter un bien

Cliquez **Ajouter un bien**, renseignez au minimum le nom, la catégorie, le ministère/service et la localisation, puis sélectionnez un état et enregistrez. Le serveur attribue une référence `TG-...`. En mode API, la fiche est persistée dans PostgreSQL. Si l'API est indisponible, la page reste consultable et annonce l'ajout dans la session de démonstration seulement : cette fiche locale disparaît au rechargement.

## Exporter

Cliquez **Exporter** ou **Exporter CSV** dans l'inventaire. Le navigateur télécharge `inventaire-patrimoine-togo.csv`, encodé en UTF-8 et séparé par des points-virgules. Les tableurs francophones l'ouvrent généralement directement.

## Tracer un mouvement

Dans **Mouvements**, choisissez le bien, le type d'opération, la date et un motif. Pour une affectation ou un transfert, choisissez également un service actif et la localisation de destination. L'enregistrement conserve l'origine dans le registre et met à jour le ministère/service, la localisation et l'état du bien. Les opérations de maintenance, retour de maintenance et réforme mettent à jour l'état sans déplacer le bien. Le registre peut être filtré par type et par texte.

## Consulter les rapports

Dans **Rapports**, filtrez les biens par date d'acquisition, catégorie, état ou ministère, puis appliquez les critères. Les indicateurs et tableaux par catégorie/ministère reflètent le résultat filtré. **Exporter le rapport CSV** télécharge uniquement les biens du résultat courant.

## Consulter la carte du territoire

La carte regroupe les biens par région à partir des noms de villes présents dans le champ de localisation (par exemple Lomé, Atakpamé, Sokodé, Kara ou Dapaong). Sélectionnez une région pour voir les biens correspondants. Ce schéma est indicatif : les fiches actuelles ne contiennent pas de coordonnées GPS et ne donnent pas une position précise.

## API pour la démonstration

- Santé : `GET http://localhost:8081/api/v1/health`
- Résumé : `GET http://localhost:8081/api/v1/summary`
- Inventaire : `GET http://localhost:8081/api/v1/assets?per_page=50`
- Recherche : `GET http://localhost:8081/api/v1/assets?q=Kara&status=bon`
- Création : `POST http://localhost:8081/api/v1/assets` avec un JSON contenant `name`, `category`, `ministry`, `location`, `status`.
- Services : `GET/POST /api/v1/services`, `PUT/PATCH /api/v1/services/{id}`.
- Mouvements : `GET /api/v1/movements`, `POST /api/v1/movements`.
- Rapport agrégé et détails : `GET /api/v1/reports/summary` avec les filtres `from`, `to`, `category`, `status`, `ministry`.

## Réinitialiser la démonstration

`docker compose down -v` supprime les données du volume PostgreSQL. Au prochain démarrage, la base et les 18 exemples fictifs sont recréés. Cette opération supprime toutes les données stockées dans ce projet local.

## Pannes fréquentes

| Symptôme | Action |
| --- | --- |
| La page ne s'ouvre pas | Vérifier Docker Desktop et `docker compose ps` ; vérifier que le port 8081 est libre |
| Les indicateurs ne se chargent pas | Vérifier `docker compose logs asset-api postgres`, puis recharger après le démarrage complet |
| Erreur de connexion PostgreSQL au démarrage | Attendre le healthcheck ; afficher les logs Postgres et vérifier la variable `DB_PASSWORD` |
| Port occupé | Changer la partie gauche de `8081:80` dans Compose (ex. `8082:80`), redémarrer et ouvrir le nouveau port |

## Limites de la démonstration

La plateforme ne comprend pas d'authentification, de compte personnel, de rôle de validation, de sauvegarde ni de gestion de documents. La carte est indicative et les données fournies sont fictives. Ne pas saisir de données personnelles, sensibles ou authentiques du patrimoine public.
