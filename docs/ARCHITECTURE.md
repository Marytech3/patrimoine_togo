# Fiche technique et architecture

## Fiche technique

| Élément | Choix |
| --- | --- |
| Produit | Patrimoine Togo — démonstrateur de gestion d'actifs publics |
| Interface | HTML sémantique, CSS responsive, JavaScript sans dépendance de compilation |
| Backend | Laravel 12, PHP 8.3 dans le conteneur |
| API | REST JSON, version `/api/v1`, validation Laravel |
| Données | PostgreSQL 16, migrations versionnées, identifiants séquentiels et référence métier unique |
| Déploiement local | Docker Compose : Nginx, API Laravel, PostgreSQL |
| Langue et devise | Français, FCFA/XOF |
| Compatibilité | Navigateurs modernes sur ordinateur et mobile |
| Démonstration | Données entièrement fictives, sans compte d'accès configuré |

## Architecture cible en services

Le dépôt met en œuvre le service de gestion des actifs, une passerelle HTTP Nginx et PostgreSQL. Les domaines sont séparés dans le modèle cible pour éviter un monolithe métier. Les services identité, référentiel et audit sont des frontières d'architecture documentées ; ils ne sont pas encore des conteneurs applicatifs livrés. C'est un MVP honnête de l'architecture microservices, pas un système ministériel déjà industrialisé.

```mermaid
flowchart LR
  U[Agent public / navigateur] -->|HTTPS| G[Passerelle Nginx]
  G -->|/ fichiers| UI[Interface web]
  G -->|/api/v1| A[Service des actifs · Laravel]
  A --> DB[(PostgreSQL · patrimoine)]
  A -. futur événement .-> AU[Service d'audit]
  A -. consultation .-> R[Service de référentiel]
  U -. connexion .-> I[Service d'identité]
  I --> IA[(PostgreSQL · identité)]
  R --> RA[(PostgreSQL · référentiel)]
  AU --> AA[(PostgreSQL · audit)]
```

**Choix de déploiement MVP :** le Compose local contient trois conteneurs : Nginx, le service Laravel et PostgreSQL. En production, les domaines identité, référentiel et audit doivent devenir des services indépendamment déployables avec leurs propres bases, politiques d'accès et interfaces de contrat. L'architecture cible est représentée par le diagramme, alors que le périmètre livré est indiqué explicitement.

## Modèle de données (UML)

```mermaid
classDiagram
  class Asset {
    +id: bigint
    +reference: string unique
    +name: string
    +category: string
    +type: string
    +ministry: string
    +location: string
    +status: enum
    +acquired_at: date
    +value_xof: integer
    +description: text
    +created_at: datetime
    +updated_at: datetime
  }
  class User <<service futur>> {
    +id: UUID
    +name: string
    +email: string
    +role: string
    +administrative_scope: string
  }
  class Movement <<service futur>> {
    +id: UUID
    +asset_reference: string
    +from_location: string
    +to_location: string
    +reason: string
    +created_by: UUID
    +occurred_at: datetime
  }
  class AuditEvent <<service futur>> {
    +id: UUID
    +actor_id: UUID
    +entity_reference: string
    +action: string
    +before: JSON
    +after: JSON
    +occurred_at: datetime
  }
  User "1" --> "0..*" Movement : initie
  Asset "1" --> "0..*" Movement : historique
  User "1" --> "0..*" AuditEvent : acteur
```

`Asset` est implémenté par migration Laravel. `User`, `Movement` et `AuditEvent` représentent les services de la cible à construire ; les références entre services sont des identifiants métier/UUID plutôt que des clés étrangères PostgreSQL inter-bases.

## Cas d'utilisation (UML)

```mermaid
flowchart LR
  Admin((Administrateur))
  Gest((Gestionnaire de ministère))
  Aud((Auditeur))
  subgraph Plateforme
    UC1([Consulter le tableau de bord])
    UC2([Rechercher et filtrer l'inventaire])
    UC3([Créer une fiche de bien])
    UC4([Mettre à jour l'état du bien])
    UC5([Exporter l'inventaire CSV])
    UC6([Consulter la piste d'audit])
    UC7([Administrer les rôles et périmètres])
  end
  Admin --> UC1
  Admin --> UC2
  Admin --> UC3
  Admin --> UC4
  Admin --> UC5
  Admin --> UC7
  Gest --> UC1
  Gest --> UC2
  Gest --> UC3
  Gest --> UC4
  Aud --> UC2
  Aud --> UC5
  Aud --> UC6
```

Cas de création :

```mermaid
sequenceDiagram
  actor Agent
  participant UI as Interface
  participant API as API Laravel
  participant DB as PostgreSQL
  Agent->>UI: Saisit les informations du bien
  UI->>API: POST /api/v1/assets (JSON)
  API->>API: Valide les champs, génère la référence
  API->>DB: INSERT asset
  DB-->>API: Fiche créée
  API-->>UI: 201 Created + JSON
  UI-->>Agent: Confirme l'ajout et actualise les indicateurs
```

## API exposée

| Méthode | Route | Usage |
| --- | --- | --- |
| GET | `/api/v1/health` | Vérifier que le service répond |
| GET | `/api/v1/summary` | Résumé agrégé du parc |
| GET | `/api/v1/assets?q=&status=&category=&per_page=` | Recherche et pagination |
| POST | `/api/v1/assets` | Créer une fiche validée |
| GET | `/api/v1/assets/{id}` | Lire une fiche |
| PUT/PATCH | `/api/v1/assets/{id}` | Modifier une fiche |
| DELETE | `/api/v1/assets/{id}` | Supprimer (démonstration seulement ; désactivation recommandée en vrai) |

Toutes les opérations d'écriture devront être protégées par authentification et autorisation avant une mise en service. L'API de démonstration n'implémente pas encore ces contrôles : elle ne doit pas recevoir de données réelles.

## Sécurité et exploitation à compléter avant mise en production

Authentification OIDC/SSO, rôles et périmètres administratifs, contrôle d'accès à chaque requête, TLS, rotation des secrets, journal d'audit à conservation contrôlée, limitation de débit, sauvegarde/restauration testées, logs centralisés, supervision, antivirus des pièces jointes, gestion des vulnérabilités et politique de conservation. Aucun de ces contrôles ne doit être considéré comme validé par le prototype.
