# Yasync - Plateforme Unifiée de Gestion eCommerce

**Version :** 1.0.0  
**Auteur :** Yassir ZBIDA  
**Superviseur :** Saad HAIMEUR  
**Institution :** Youcode - UM6P  
**Date :** 14/01/2025  

## Table des Matières
1. Présentation du Projet
2. Fonctionnalités
3. Technologies Utilisées
4. Installation
5. Configuration
6. Utilisation
7. Intégrations API
8. Contribution
9. Licence
10. Contact

---

## 1. Présentation du Projet
**Yasync** est une plateforme centralisée permettant aux PME de gérer leurs boutiques physiques et en ligne de manière fluide. Elle s'intègre aux plateformes eCommerce comme **WooCommerce** et **Shopify**, offrant un **tableau de bord unique** pour la gestion des produits, stocks, commandes, factures et support client.

L'objectif est d'éliminer les obstacles liés aux systèmes de gestion fragmentés et de proposer une **interface intuitive** pour toutes les opérations commerciales.

## 2. Fonctionnalités

### ✅ Tableau de Bord Unifié
- Synchronisation en temps réel avec WooCommerce et Shopify.
- Gestion centralisée des produits, commandes et stocks.

### ✅ Gestion des Produits (CRUD)
- Ajouter, modifier, supprimer et afficher des produits.
- Synchronisation des produits avec WooCommerce et Shopify.

### ✅ Gestion des Stocks
- Mise à jour en temps réel.
- Alertes de stock faible et ajustement manuel.

### ✅ Gestion des Commandes
- Suivi et mise à jour des statuts (en attente, complétée, annulée).
- Synchronisation des commandes avec les plateformes eCommerce.

### ✅ Gestion des Factures
- Génération automatique et archivage de factures PDF.

### ✅ Point de Vente (POS)
- Scan de codes-barres et impression de tickets.

### ✅ Support Client
- Système de tickets pour le service client.

### ✅ Analyse et Reporting
- Rapports sur les ventes et stocks pour une meilleure prise de décision.

## 3. Technologies Utilisées
- **Frontend :** React.js (interface réactive et moderne)
- **Backend :** Laravel (API robuste et logique métier)
- **Base de données :** PostgreSQL
- **Hébergement :** Hostinger (hébergement cloud sécurisé)

### 📡 API utilisées :
- WooCommerce REST API
- Shopify GraphQL API

### 🔐 Sécurité :
- OAuth2 et JWT pour l'authentification.
- Chiffrement SSL/TLS pour la protection des données.

## 4. Installation

### 📌 Prérequis
- PHP >= 8.0
- Composer (gestionnaire de dépendances Laravel)
- Node.js et npm (pour React.js)
- PostgreSQL

### 📌 Étapes
1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/yourusername/yasync.git
   cd yasync
   ```

2. **Installer les dépendances backend** :
   ```bash
   composer install
   ```

3. **Installer les dépendances frontend** :
   ```bash
   cd frontend
   npm install
   ```

4. **Configurer la base de données** :
   - Créer une base de données PostgreSQL.
   - Mettre à jour le fichier `.env` avec vos identifiants :
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=yasync_db
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

5. **Exécuter les migrations et seeders** :
   ```bash
   php artisan migrate --seed
   ```

6. **Lancer le serveur backend** :
   ```bash
   php artisan serve
   ```

7. **Lancer le serveur frontend** :
   ```bash
   cd frontend
   npm start
   ```

👉 Accédez à la plateforme : **http://localhost:3000**

## 5. Configuration

### 🔗 Intégration WooCommerce
- Récupérer les clés API WooCommerce.
- Mettre à jour le fichier `.env` :
  ```env
  WOOCOMMERCE_URL=https://yourstore.com
  WOOCOMMERCE_CONSUMER_KEY=your_consumer_key
  WOOCOMMERCE_CONSUMER_SECRET=your_consumer_secret
  ```

### 🔗 Intégration Shopify
- Récupérer les identifiants API Shopify.
- Mettre à jour le fichier `.env` :
  ```env
  SHOPIFY_STORE_DOMAIN=yourstore.myshopify.com
  SHOPIFY_ACCESS_TOKEN=your_access_token
  ```

## 6. Utilisation

### 🎯 Tableau de Bord
- Visualisation des ventes, commandes et stocks en temps réel.

### 🎯 Gestion des Produits
- Ajout, mise à jour ou suppression de produits.
- Synchronisation automatique avec WooCommerce et Shopify.

### 🎯 Gestion des Commandes
- Suivi et mise à jour des statuts des commandes.

### 🎯 Point de Vente (POS)
- Interface dédiée pour la vente en magasin physique.

### 🎯 Support Client
- Gestion des tickets et des demandes clients.

## 7. Intégrations API
- **WooCommerce REST API** : Synchronisation des produits, commandes et stocks.
- **Shopify GraphQL API** : Gestion des produits et commandes.

## 8. Contribution
Les contributions sont les bienvenues ! Suivez ces étapes :
1. **Fork** le dépôt.
2. **Créer une branche** pour vos modifications.
3. **Commit** vos changements avec un message clair.
4. **Soumettre une pull request.**

## 9. Licence
Ce projet est sous licence **MIT**. Consultez le fichier `LICENSE` pour plus d'informations.

## 10. Contact
📧 **Email :** zbidayassir10@gmail.com
🐙 **GitHub :** [yassir-zbida](https://github.com/yassir-zbida)
