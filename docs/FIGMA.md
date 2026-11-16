# Maquette Figma — spécification et reprise

## Écrans

1. **Vue d'ensemble — bureau** (1440 × 960) : navigation latérale 248 px, barre supérieure, titre et actions, bannière de démonstration, quatre cartes d'indicateurs, deux cartes d'analyse et table des derniers mouvements.
2. **Inventaire — bureau** (1440 × 960) : titre, filtres recherche/état/catégorie, tableau et pagination.
3. **Ajout d'un bien** (dialogue 650 px) : champs en deux colonnes, validation et actions Enregistrer/Annuler.
4. **Vue mobile** (390 × 844) : navigation repliée, cartes sur deux colonnes, graphiques empilés, tableaux défilants horizontalement.

## Style guide

- Palette : vert profond `#153E33`, vert secondaire `#225D49`, ivoire `#F8F9F6`, texte `#202C26`, or `#D4AE63`, alerte `#D95450`.
- Typographie : Manrope (titres et chiffres) + DM Sans (interface). Repli sans-serif.
- Espacement sur une échelle de 4/8/12/16/24/32 px ; bordures fines `#E9ECE7`, coins de 5 à 8 px.
- Couleurs d'état toujours combinées à un libellé, jamais transmises par la couleur seule.
- Variantes à prévoir dans le prototype Figma : vide, chargement, erreur API, recherche sans résultat, formulaire invalide et écran mobile.

## Reprendre dans Figma

La maquette de cette livraison est implémentée dans `../web/index.html` et `../web/styles.css`, et le frame de référence bureau est livré en SVG importable : [maquette-vue-ensemble.svg](maquette-vue-ensemble.svg). Aucun espace Figma n'est connecté ; ce SVG est donc le support de conception à reprendre, pas un fichier Figma publié. Pour la reprise, importez le SVG dans un fichier Figma, créez une page **01 · Produit**, un frame mobile 390 × 844, puis reconstruisez les éléments comme frames/Auto Layout avec les tokens ci-dessus. Conserver les états d'erreur et vide comme variantes de composants.

## Justification UX

Le vert et l'or rappellent le contexte national sans reprendre un écran institutionnel existant. Les indicateurs résument le parc, tandis que la table conserve les informations utiles à une première action. Le formulaire demande peu de champs obligatoires pour permettre le recensement initial, et la bannière évite de confondre les données d'exercice avec un inventaire officiel.
