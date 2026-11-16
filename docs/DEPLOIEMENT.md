# Mise en ligne et dépôt distant

## État de la livraison

Le projet est lançable localement avec Docker. Aucun dépôt GitHub ni compte d'hébergeur n'est associé à ce workspace : aucun lien de dépôt ou URL publique ne peut être donné comme s'il existait déjà. Avant de partager la démonstration, pousser le code vers un dépôt Git, puis connecter ce dépôt à l'hébergeur et suivre l'état du déploiement.

## Option de démonstration gratuite

Render documente le déploiement gratuit d'un service Web depuis un fournisseur Git et la création d'un service basé sur un Dockerfile. Ses limites rendent l'option adaptée à une présentation temporaire uniquement : le service Web s'arrête après 15 minutes d'inactivité, le redémarrage peut prendre environ une minute, le système de fichiers du service est éphémère et la base PostgreSQL gratuite expire après 30 jours. La base gratuite est limitée à 1 Go et n'a pas de sauvegardes. Ces contraintes rendent ce profil impropre aux données officielles ou à une conservation durable. [Documentation officielle Render — offre gratuite](https://render.com/docs/free), [premier déploiement](https://render.com/docs/your-first-deploy).

### Procédure

1. Créer un dépôt GitHub et y pousser le projet sans fichier `.env`.
2. Dans Render, connecter le dépôt Git, puis créer une base PostgreSQL gratuite (uniquement pour la démo temporaire).
3. Le dépôt contient [`render.yaml`](../render.yaml), un Blueprint qui décrit une application Laravel conteneurisée et PostgreSQL dans la région Frankfurt. Dans Render, choisir **New → Blueprint**, sélectionner le dépôt et valider les deux ressources gratuites. Le Dockerfile sert l'interface à la racine et l'API sous `/api` sur le même domaine ; le port HTTP suit la variable `PORT` de la plateforme.
4. Vérifier que Render génère une `APP_KEY`, que `APP_ENV=production`, `APP_DEBUG=false`, et que les paramètres PostgreSQL sont liés à la base par le Blueprint. Ne pas ajouter de secret au dépôt.
5. Suivre les logs du build et du démarrage, ouvrir `/api/v1/health`, puis vérifier l'interface publique et l'ajout d'une fiche fictive.
6. Avant la date d'expiration de la base temporaire, exporter les données utiles ou détruire la démonstration. Ne pas y charger de données non publiques.

## Limite à traiter avant le déploiement

Le Blueprint et l'adaptation au port `PORT` sont préparés, mais ne sont pas publiés : aucun dépôt distant ni compte Render n'est connecté. L'application ne comporte pas d'authentification ; la démo doit rester composée de données fictives.
