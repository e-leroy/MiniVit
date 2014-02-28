MiniVit
=======

Gestionnaire de site-vitrine ultra-minimaliste

Ultra-minimalist "vitrine" website manager

`[ FR ]`

Développé par Emmanuel LEROY et [RTS INFORMATIQUE](http://www.rts-informatique.fr/)

Instances MiniVit existantes:
- [RTS INFORMATIQUE](http://www.rts-informatique.fr/)
- [Garage KUHN SAS Haguenau](http://www.garagekuhn.com/)

Licence
===================

Licence BSD. Merci de se référer [au fichier de licence](LICENSE)

Pré-requis et installation
===================

- hébergement web (minimum 100 Ko d'espace)
- PHP 5.5+ avec support JSON

Envoyez les fichiers par FTP, SFTP ou tout autre moyen

> Alternative avec git:
- git clone https://github.com/rtsinfo/MiniVit .

- si le fichier `admin.ini.php` n'existe pas, le script vous proposera de créer le compte admin
- une fois le fichier `admin.ini.php` généré, le site par défaut s'affiche
- le panneau d'administration permet de changer titre et contenu des pages, couleurs, images et informations

Mise à jour
===================

Remplacez `index.php` et `version`, puis procédez aux éventuelles adaptations.

> Alternative avec git:
- git pull origin master

Fichiers générés
===================

- `admin.ini.php`: identifiant, email et mot de passe chiffré du compte administrateur
- `data.json`: tableau relationnel du contenu et style des pages
- `data-prev.json`: version précédente de `data.json` pour sauvegarde
