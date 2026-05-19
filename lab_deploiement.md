# 🚀 Lab : Déploiement d’une application Laravel sur Ubuntu

---

## 🧰 1. Mise à jour du système

```bash
sudo apt update
sudo apt upgrade
```

---

## 🐘 2. Installation de PHP et extensions

```bash
sudo apt install php php-cli php-mbstring php-xml php-bcmath php-curl php-mysql php-sqlite3 unzip curl
```

---

## 🎼 3. Installation de Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Vérification :

```bash
composer -v
```

---

## 🗄️ 4. Installation de MySQL

```bash
sudo apt install mysql-server
```

---

## 🌱 5. Création du projet Laravel

```bash
composer create-project laravel/laravel hello_world
```

---

## 📂 6. Accéder au projet

```bash
cd hello_world
```

---

## ▶️ 7. Lancer le serveur Laravel (test)

```bash
php artisan serve
```

Accès :

```
http://127.0.0.1:8000
```

---

## ⚙️ 8. Configuration du fichier .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hello_world
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🌐 9. Installation Apache

```bash
sudo apt install apache2
```

---

## 📁 10. Déplacement du projet

```bash
sudo mv ~/hello_world /var/www/
```

---

## 🔐 11. Permissions

```bash
sudo chown -R www-data:www-data /var/www/hello_world
sudo chmod -R 755 /var/www/hello_world
sudo chmod -R 775 /var/www/hello_world/storage
sudo chmod -R 775 /var/www/hello_world/bootstrap/cache
```

---

## ⚙️ 12. Configuration Apache (VirtualHost)

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Contenu :

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/hello_world/public

    <Directory /var/www/hello_world/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 🔧 13. Activer le module rewrite

```bash
sudo a2enmod rewrite
```

---

## 🔄 14. Redémarrer Apache

```bash
sudo systemctl restart apache2
```

---

## 🌍 15. Accès au projet

Depuis Ubuntu :

```
http://localhost
```

Depuis Windows (machine hôte) :

```
http://192.168.xxx.xxx
```

---

## 🧪 16. Vérifier l’adresse IP

```bash
ip a
```

---

## 🎨 17. Création d’une page Hello World

Dans `routes/web.php` :

```php
Route::get('/', function () {
    return view('welcome');
});
```

Modifier le fichier :

```
resources/views/welcome.blade.php
```

---

# ⚠️ Problèmes rencontrés et solutions

---

## ❌ Erreur : could not find driver

**Cause :** extension SQLite manquante

**Solution :**

```bash
sudo apt install php-sqlite3
```

---

## ❌ Erreur : no such table: sessions

**Cause :** base de données non configurée ou session mal configurée

**Solution :**

* Vérifier `.env`
* Utiliser `SESSION_DRIVER=file`

---

## ❌ Apache affiche la page par défaut

**Cause :** Apache pointe vers `/var/www/html`

**Solution :**

Modifier :

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Changer :

```apache
DocumentRoot /var/www/hello_world/public
```

Puis :

```bash
sudo systemctl restart apache2
```

---

# 🎉 Résultat final

✔ Application Laravel fonctionnelle
✔ Déployée sur Apache
✔ Accessible via navigateur
✔ Communication entre VM et machine hôte réussie

---

# 🧠 Conclusion

Ce lab m’a permis de :

* Installer et configurer Laravel sur Ubuntu
* Comprendre le rôle du dossier `/public`
* Configurer Apache pour servir une application web
* Résoudre des erreurs liées aux extensions PHP et à la base de données
* Déployer une application accessible via réseau local

---

# 🚀 Fin du Lab
