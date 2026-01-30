# Bastien59 Banners — Extension phpBB

Gestion des bannières du header avec support images (jpg, png, gif, webp) et vidéos (mp4, webm).

## Fonctionnalités

- Affichage aléatoire d'une bannière dans le header (pondéré par poids)
- Module ACP sous **Extensions → Gestion des bannières**
- Toggle global activer/désactiver
- Chemin des images configurable
- Édition inline : lien, titre, poids, actif/inactif
- Bouton « Scanner le dossier » pour importer automatiquement les nouveaux fichiers
- Support jpg, jpeg, png, gif, webp, mp4, webm
- Migration automatique des 76 bannières existantes avec leurs liens

## Installation

1. Copier le dossier dans `ext/bastien59960/banners/`
2. Aller dans **ACP → Personnaliser → Extensions**
3. Activer **Bastien59 Banners**

## Configuration

Le module ACP se trouve dans **ACP → Extensions → Gestion des bannières**.

- **Activer les bannières** : active/désactive l'affichage global
- **Chemin des images** : chemin relatif à la racine phpBB (par défaut `images/bannieres`)
- **Poids** : un poids de 2 donne 2× plus de chances d'apparaître qu'un poids de 1

## Templates

L'extension injecte les variables suivantes via l'event `core.page_header_after` :

| Variable | Description |
|----------|-------------|
| `BANNER_ENABLED` | `true` si une bannière est disponible |
| `BANNER_FILE` | Chemin relatif du fichier |
| `BANNER_LINK` | URL de destination |
| `BANNER_EXT` | Extension du fichier (jpg, mp4…) |

Exemple de code template :

```html
<!-- IF BANNER_ENABLED -->
<div class="headerbar">
<!-- IF BANNER_EXT == "mp4" -->
    <a href="{BANNER_LINK}"><video autoplay muted loop width="100%">
        <source src="{BANNER_FILE}" type="video/mp4"></source>
    </video></a>
<!-- ELSE -->
    <a href="{BANNER_LINK}"><img class="banniere" src="{BANNER_FILE}" alt=""></a>
<!-- ENDIF -->
</div>
<!-- ENDIF -->
```

## Prérequis

- PHP ≥ 7.1.3
- phpBB ≥ 3.3.0

## Licence

GPL-2.0-only
