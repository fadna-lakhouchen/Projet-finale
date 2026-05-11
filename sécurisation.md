# 🔐 Sécurisation MySQL avec MySQL Workbench (Laravel)

## 🎯 Objectif
Créer un utilisateur dédié à ton projet Laravel et limiter ses accès à une seule base de données pour améliorer la sécurité.

---

## 🛠️ Étapes dans MySQL Workbench

### 1️⃣ Créer un nouvel utilisateur

1. Ouvrir MySQL Workbench  
2. Se connecter avec un compte admin (ex: `root`)  
3. Aller dans **Users and Privileges** (menu Server ou sidebar)  
4. Cliquer sur **Add Account**  
5. Remplir :
   - **Login Name** : `immosyndic_app`
   - **Authentication Type** : Standard
   - **Password** : `FADNA@fadna123`
6. Cliquer sur **Apply**

---

### 2️⃣ Attribuer les privilèges (Schema Privileges)

1. Aller dans **Users and Privileges**  
2. Ouvrir l'onglet **Schema Privileges**  
3. Cliquer sur **Add Entry**  
4. Choisir :
   - **Selected Schema**
   - Sélectionner la base de données `immosyndic`
5. Cliquer sur **Select ALL**  
6. Cliquer sur **Apply**

---

## 🔐 Pourquoi c'est important ?

- **Isolation** : accès limité à une seule base de données  
- **Sécurité** : éviter d'utiliser `root`  
- **Bonne pratique** : un utilisateur par application  

---

## 🚀 Configuration dans Laravel

Modifier le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=immosyndic
DB_USERNAME=immosyndic_app
DB_PASSWORD=FADNA@fadna123
```

---

## 💡 Astuce
- Vérifier Server Status
- Garder le serveur en local (127.0.0.1)
- Éviter l'accès public

## ✅ Résultat
✔️ Utilisateur créé
✔️ Accès limité
✔️ Projet sécurisé