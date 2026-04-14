# Bastien59 Banners — Extension phpBB

Gestion complète des bannières du header pour phpBB 3.3+, avec support images et vidéos, sélection aléatoire pondérée et interface d'administration.

## Fonctionnalités

### Affichage frontend
- **Sélection aléatoire pondérée** : une bannière est tirée au sort à chaque chargement de page parmi les bannières actives
- **Système de poids** : chaque bannière possède un poids (1 par défaut). Un poids de 2 donne 2x plus de chances d'apparaître qu'un poids de 1
- **Support multi-format** : images (jpg, jpeg, png, gif, webp) et vidéos (mp4, webm)
- **Vidéos en autoplay** : les bannières vidéo sont lues automatiquement en boucle, sans son
- **Liens cliquables** : chaque bannière peut pointer vers une URL de destination

### Administration (ACP)
- Module accessible dans **ACP > Extensions > Gestion des bannières**
- **Toggle global** : activer ou désactiver l'affichage de toutes les bannières en un clic
- **Chemin configurable** : le dossier des bannières est paramétrable (par défaut `images/bannieres`)
- **Scanner le dossier** : importe automatiquement les nouveaux fichiers détectés dans le dossier des bannières
- **Aperçu visuel** : chaque bannière est affichée en grand dans l'ACP pour un contrôle visuel immédiat
- **Edition par bannière** :
  - Lien URL de destination
  - Titre / description
  - Poids (1 à 100)
  - Activer / désactiver individuellement
- **Suppression** : supprime l'entrée de la base de données (le fichier image/vidéo sur le disque n'est jamais supprimé)

### Migration automatique
- Lors de la première activation, l'extension scanne le dossier des bannières et importe tous les fichiers existants
- Les 76 bannières historiques sont importées avec leurs liens et titres pré-configurés

## Installation

1. Copier le dossier dans `ext/bastien59960/banners/`
2. Aller dans **ACP > Personnaliser > Extensions**
3. Activer **Bastien59 Banners**

L'extension scanne automatiquement le dossier `images/bannieres/` et importe les fichiers trouvés dans la base de données.

## Désinstallation

1. **Désactiver** l'extension dans ACP > Personnaliser > Extensions
2. **Supprimer les données** (purge) si souhaité

La purge supprime la table de la base de données et les paramètres de configuration. **Les fichiers images et vidéos ne sont jamais supprimés du disque.**

## Configuration

Le module ACP se trouve dans **ACP > Extensions > Gestion des bannières**.

### Paramètres globaux

| Paramètre | Description | Valeur par défaut |
|-----------|-------------|-------------------|
| **Activer les bannières** | Active ou désactive l'affichage global des bannières | Oui |
| **Chemin des images** | Chemin relatif à la racine phpBB du dossier contenant les bannières | `images/bannieres` |

### Paramètres par bannière

| Paramètre | Description |
|-----------|-------------|
| **Lien** | URL de destination au clic sur la bannière (vide = pas de lien) |
| **Titre** | Description pour l'administration |
| **Poids** | Probabilité relative d'apparition (1–100, défaut 1) |
| **Actif** | Active ou désactive individuellement la bannière |

## Intégration dans les templates

L'extension utilise l'event phpBB `core.page_header_after` pour injecter les variables template suivantes :

| Variable | Type | Description |
|----------|------|-------------|
| `BANNER_ENABLED` | bool | `true` si une bannière active a été sélectionnée |
| `BANNER_FILE` | string | Chemin relatif du fichier (ex: `images/bannieres/forumbanniere1.jpg`) |
| `BANNER_LINK` | string | URL de destination |
| `BANNER_EXT` | string | Extension du fichier en minuscules (jpg, png, mp4, etc.) |

### Code template utilisé

Le code suivant doit être présent dans le template `overall_header.html` de chaque style, à l'intérieur de `<div class="headerbar">` :

```html
<!-- IF BANNER_ENABLED -->
<!-- IF BANNER_EXT == "mp4" -->
    <a href="{BANNER_LINK}"><video autoplay muted loop width="100%">
        <source src="{BANNER_FILE}" type="video/mp4"></source>
    </video></a>
<!-- ELSE -->
    <a href="{BANNER_LINK}"><img class="banniere" src="{BANNER_FILE}" alt=""></a>
<!-- ENDIF -->
<!-- ENDIF -->
```

Le `<div class="headerbar">` doit rester toujours présent (non conditionnel) car il fait partie de la structure CSS de prosilver. Seul le contenu à l'intérieur est conditionnel.

## Structure de l'extension

```
ext/bastien59960/banners/
├── composer.json              # Métadonnées de l'extension
├── ext.php                    # Classe de base de l'extension
├── README.md                  # Cette documentation
├── config/
│   └── services.yml           # Définition des services (DI container)
├── migrations/
│   └── release_1_0_0.php      # Migration : table, config, module ACP, import initial
├── acp/
│   ├── main_info.php          # Informations du module ACP
│   └── main_module.php        # Point d'entrée du module ACP
├── controller/
│   └── acp_controller.php     # Contrôleur ACP (CRUD bannières, scan, settings)
├── event/
│   └── listener.php           # Event listener (sélection aléatoire pondérée)
├── adm/
│   └── style/
│       └── acp_banners_body.html  # Template ACP avec aperçus
└── language/
    ├── en/
    │   └── acp/
    │       └── info_acp_banners.php  # Traduction anglaise
    └── fr/
        └── acp/
            └── info_acp_banners.php  # Traduction française
```

## Base de données

### Table `{prefix}bastien59_banners`

| Colonne | Type | Description |
|---------|------|-------------|
| `banner_id` | UINT auto_increment | Clé primaire |
| `filename` | VARCHAR(255) | Nom du fichier (ex: `forumbanniere1.jpg`) |
| `link_url` | TEXT | URL de destination |
| `title` | VARCHAR(255) | Titre / description pour l'admin |
| `enabled` | BOOL | 1 = active, 0 = inactive |
| `weight` | UINT | Poids pour la sélection pondérée (défaut: 1) |
| `sort_order` | UINT | Ordre d'affichage dans l'ACP |

### Entrées config

| Clé | Description |
|-----|-------------|
| `bastien59_banners_enabled` | Activation globale (1/0) |
| `bastien59_banners_path` | Chemin du dossier des bannières |

## Formats supportés

| Type | Extensions |
|------|-----------|
| Images | jpg, jpeg, png, gif, webp |
| Vidéos | mp4, webm |

## Algorithme de sélection

La sélection utilise un algorithme de tirage pondéré en PHP :

1. Chargement de toutes les bannières actives (`enabled = 1`)
2. Construction d'un pool où chaque bannière est répétée selon son poids
3. Tirage aléatoire uniforme dans le pool avec `array_rand()`

Exemple : si la bannière A a un poids de 3 et la bannière B un poids de 1, A apparaitra en moyenne 3 fois sur 4.

## Prérequis

- PHP >= 7.1.3
- phpBB >= 3.3.0
- MariaDB / MySQL

## Inter-extension dependencies

`banners` is **fully standalone**. It has no dependency on any other extension in this project, and no other extension reads its tables or services directly.

## Licence

GPL-2.0-only
