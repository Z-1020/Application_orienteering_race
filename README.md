# Application Orienteering Race

## Présentation du projet
**Application Orienteering Race** est un **projet de groupe réalisé par 8 personnes**.  
Cette application web permet de **gérer des courses d’orientation** (création, organisation et suivi des courses). Elle a été développée en utilisant le framework Laravel.

Le projet a été **développé en une semaine** dans un cadre pédagogique.

---

## Mise en place du projet

## Cloner le projet
```bash
git clone https://github.com/Z-1020/Application_orienteering_race.git
```
## Installer les dépendances back-end

Dans le dossier /Application_orienteering_race, exécutez :

```bash
composer install
```
## Installer les dépendances front-end
```bash
npm install
```
## Démarrer les services

Ouvrez XAMPP

Démarrez Apache et MySQL

## Créer la base de données

Accédez à :
http://localhost/phpmyadmin/

Créez une base de données nommée : g6_db

## Lancer les migrations
```bash
php artisan migrate:fresh
```
## Lancer l’application
```bash
composer run dev
```
## Visualiser l'application
Accéder à : http://localhost:8000/
