# Mise en ligne et dépôt distant

## État de la livraison

Le code est publié sur [GitHub](https://github.com/Marytech3/patrimoine_togo), branche `master`. Le Blueprint Render est prêt dans [`render.yaml`](../render.yaml), mais aucun service Render public n'a encore été créé. Utilisez le bouton **Déployer sur Render** dans le README ou ouvrez [le formulaire Render pour ce dépôt](https://render.com/deploy?repo=https://github.com/Marytech3/patrimoine_togo), connectez votre compte GitHub/Render et validez la création du Blueprint.

## Option de démonstration gratuite

Render documente le déploiement gratuit d'un service Web depuis un fournisseur Git et la création d'un service basé sur un Dockerfile. Ses limites rendent l'option adaptée à une présentation temporaire uniquement : le service Web s'arrête après 15 minutes d'inactivité, le redémarrage peut prendre environ une minute, le système de fichiers du service est éphémère et la base PostgreSQL gratuite expire après 30 jours. La base gratuite est limitée à 1 Go et n'a pas de sauvegardes. Ces contraintes rendent ce profil impropre aux données officielles ou à une conservation durable. [Documentation officielle Render — offre gratuite](https://render.com/docs/free), [premier déploiement](https://render.com/docs/your-first-deploy).

### Procédure

1. Ouvrir le formulaire Render, se connecter et autoriser l'accès au dépôt GitHub si Render le demande.
2. Sélectionner la branche `master` et vérifier que l'aperçu contient le service Web `patrimoine-togo` et la base `patrimoine-db`, tous deux au palier gratuit et dans la région Frankfurt.
3. Vérifier que Render génère une `APP_KEY`, garde `APP_ENV=production` et `APP_DEBUG=false`, puis valider **Deploy Blueprint**. Ne pas ajouter de secret au dépôt.
4. Suivre le build et les journaux de démarrage. L'application applique ses migrations et charge les exemples synthétiques au démarrage. Ouvrir `/api/v1/health`, puis l'URL publique et vérifier les écrans, la lecture et la création d'une fiche fictive.
5. Copier l'URL publique dans le README et la fiche de rendu après validation. Exporter les données utiles ou supprimer la base avant son expiration. Ne jamais saisir de données officielles ou personnelles.

## Limite à traiter avant le déploiement

Le Blueprint et l'adaptation au port `PORT` sont publiés avec le dépôt, mais la création du service demande une session Render et une validation dans son tableau de bord. Les services Web gratuits s'arrêtent après 15 minutes sans trafic et la base PostgreSQL gratuite expire au bout de 30 jours ; cette option convient à une présentation temporaire, pas à la conservation de données. L'application ne comporte pas d'authentification ; la démo doit rester composée de données fictives. [Limites du palier gratuit Render](https://render.com/docs/free).
