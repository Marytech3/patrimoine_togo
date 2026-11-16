# Analyse réflexive — première version

## Prévu et réalisé

Le brief visait une solution de gestion du patrimoine en architecture microservices, avec Figma, Laravel, PostgreSQL, Docker, proposition financière et guide. Cette première tranche transforme le besoin en démonstrateur exécutable : un tableau de bord et un inventaire adaptables, une API Laravel versionnée, un schéma PostgreSQL, une conteneurisation locale et plusieurs livrables de conception. Le jeu de données de démonstration comporte 18 fiches synthétiques.

## Écarts et décisions

- La durée du brief (10 au 15 novembre) est déjà écoulée au moment de la réalisation (16 novembre 2026) ; la plateforme se présente donc comme une base de restitution, pas comme une preuve que les échéances pédagogiques ont été respectées.
- La maquette est matérialisée par l'interface et une spécification de reprise Figma ; aucun fichier ni lien Figma n'a été créé car aucun espace Figma n'est connecté.
- La partie microservices décrit la cible et livre son premier service métier avec passerelle et base ; les services d'identité, référentiel et audit ne sont pas encore des applications autonomes.
- L'hébergement public, le dépôt GitHub distant et l'URL de partage restent à produire après raccordement des comptes et clarification sur la persistance acceptable.
- Aucun compte ni contrôle d'autorisation n'est ajouté : la facilité de démonstration prime temporairement, et les limites sont signalées pour éviter toute utilisation réelle.

## Apprentissages et suite

La première priorité a été de clarifier les hypothèses avant de simuler des workflows administratifs. L'interface rend le parcours central visible, mais les référentiels, le cycle de vie des actifs et l'audit doivent être co-conçus avec les utilisateurs. La prochaine itération doit valider les dix questions avec la formatrice, créer la maquette Figma partageable, ajouter les rôles et journaux, établir les critères de recette, puis publier le code et la démo sans données réelles. Remplacer ensuite cette réflexion provisoire par le questionnaire officiel de bilan annoncé dans le brief.
