# Analyse du besoin

## Contexte et problème

L'État gère des biens de natures et de localisations variées, affectés à plusieurs ministères et services. Un inventaire fiable doit identifier chaque actif, son état, sa valeur, son responsable et sa localisation, puis garder une trace des changements. Le brief ne fournit ni inventaire source ni règles administratives : le prototype emploie donc des données synthétiques et rend visibles les hypothèses à valider.

## Parties prenantes

| Acteur | Besoin principal |
| --- | --- |
| Administrateur central | Consolider l'inventaire, gérer les comptes et consulter les indicateurs |
| Gestionnaire ministériel | Déclarer un bien, mettre à jour son état et suivre ses mouvements |
| Responsable de service | Consulter les biens affectés à son périmètre |
| Auditeur / contrôleur | Consulter une piste d'audit et produire des états vérifiables |
| Direction financière / tutelle | Contrôler la valorisation et les rapports consolidés |

## Objectifs mesurables du MVP

1. Retrouver un actif par nom, référence, ministère ou localisation.
2. Enregistrer et modifier ses caractéristiques principales avec une référence unique.
3. Consulter un tableau de bord synthétique et exporter un inventaire CSV.
4. Séparer les responsabilités métier par API, documenter la sécurité, les données et la mise en service.

## Périmètre livré

**Inclus :** tableau de bord, inventaire responsive, recherche et filtres, saisie d'un actif, API Laravel REST, PostgreSQL, données de démonstration, indicateurs calculés, export CSV, conteneurisation, documentation de conception et guide opérateur.

**À réaliser dans une phase suivante :** authentification réelle et rôles, cycle de validation, transferts/affectations avec journal d'événements, gestion de documents et photos, inventaire mobile hors ligne, géolocalisation/cartographie, import contrôlé de l'inventaire existant, audit immuable, alertes, rapports PDF, sauvegardes automatisées et supervision.

## Règles de gestion proposées à valider

- La référence d'un actif est unique et n'est jamais réutilisée.
- Chaque actif est rattaché à une catégorie, un ministère/service et une localisation administrative.
- Les états initiaux proposés sont : en service, maintenance, à affecter, à réformer.
- Une réforme est une décision suivie, non une suppression physique de la fiche.
- La valeur historique, la valeur actuelle et la date de valorisation devraient être distinctes dans la version métier.
- Toute création, modification, affectation et réforme doit identifier l'utilisateur, l'heure, le motif et les valeurs avant/après.
- Les droits et la visibilité sont limités au périmètre administratif autorisé.

## Critères d'acceptation de la démonstration

- La page d'accueil présente des indicateurs dérivés des actifs chargés.
- La liste reflète les données PostgreSQL lorsque l'API répond ; elle indique le mode de démonstration sinon.
- Recherche et filtres modifient les lignes de l'inventaire.
- La saisie validée ajoute un actif à la base (ou à la session de démonstration hors API).
- L'export télécharge un CSV UTF-8 avec séparateur `;`.
- L'interface reste utilisable sur petit écran et signale explicitement la nature fictive des données.

## Hypothèses et risques

| Hypothèse / risque | Réponse proposée |
| --- | --- |
| Les ministères, catégories et sites sont inconnus | Référentiels administrables, à construire après atelier métier |
| Données patrimoniales sensibles | Accès par rôle, chiffrement, journalisation, sauvegardes et politique d'hébergement à qualifier avant données réelles |
| Réseau parfois intermittent | Application mobile hors ligne à cadrer dans une phase ultérieure |
| Première livraison courte | Démonstrateur limité à l'inventaire, sans prétendre remplacer le système officiel |
| Gratuité incompatible avec conservation des données | Hébergement gratuit réservé à une présentation temporaire, avec export et avertissement |
