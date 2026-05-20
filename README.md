MyExam — Plateforme de gestion d’examens en ligne

MyExam est une application web développée avec Laravel, MySQL et TailwindCSS permettant aux enseignants de créer, gérer et corriger des examens en ligne.

Le projet propose deux espaces distincts :

👨‍🏫 Espace Enseignant
👨‍🎓 Espace Étudiant

L’application permet la création d’examens interactifs avec différents types de questions et un système de correction automatique.

🚀 Technologies utilisées
Laravel
PHP
MySQL
TailwindCSS
Vite
Blade
JavaScript
✨ Fonctionnalités principales
👨‍🏫 Enseignant
Authentification sécurisée
Création d’examens
Gestion des étudiants
Ajout de questions :
QCM
Vrai / Faux
Réponse texte
Correction automatique
Attribution d’une note finale
Consultation des copies
Export PDF
Export CSV
Organisation des étudiants par formation
👨‍🎓 Étudiant
Connexion sécurisée
Consultation des examens
Passage des examens en ligne
Affichage du score automatique
Consultation des résultats
Visualisation des corrections

# 📸 Captures d’écran

## 🏠 Page d’accueil

![Accueil](./screenshots/01-homepage.png)

---

## 🔐 Connexion

![Connexion](./screenshots/02-login.png)

---

## 📊 Dashboard administrateur

![Dashboard](./screenshots/04-admin-dashboard.png)

---

## 📝 Création d’examen

![Création examen](./screenshots/05-create-exam.png)

---

## ❓ Gestion des questions

![Questions](./screenshots/07-question-management.png)

---

## ☑️ Question QCM

![QCM](./screenshots/08-qcm-question.png)

---

## 👨‍🎓 Dashboard étudiant

![Dashboard étudiant](./screenshots/10-student-dashboard.png)

---

## 📚 Interface examen étudiant

![Interface examen](./screenshots/11-exam-interface.png)

---

## ✍️ Réponses étudiant

![Réponses étudiant](./screenshots/12-student-answers.png)

---

## 📈 Statistiques

![Statistiques](./screenshots/14-statistics.png)

---

## 📄 Export PDF des copies

![Export PDF](./screenshots/15-pdf-export.png)

---

## 🛡️ Système anti-cheating

![Anti Cheat](./screenshots/16-anti-cheat.png)

---

## ⏳ Résultat en attente

![Résultat attente](./screenshots/18-resultat-en-attente-etudiant.png)

---

## 📄 Copies corrigées par le professeur

![Copies corrigées](./screenshots/19-copies-corrigees-prof.png)

---

## 👨‍🎓 Liste des étudiants

![Liste étudiants](./screenshots/20-liste-etudiants.png)

---

## ✅ Résultat final étudiant

![Résultat final](./screenshots/21-resultat-final-etudiant.png)

---

## 📋 Détail des réponses et correction

![Détail réponses](./screenshots/22-detail-resultat-examen.png)
⚙️ Installation
1. Cloner le projet
git clone https://github.com/votre-compte/IrisExams.git
2. Accéder au dossier
cd IrisExams
3. Installer les dépendances PHP
composer install
4. Installer les dépendances frontend
npm install
5. Configurer l’environnement

Créer le fichier .env :

cp .env.example .env

Configurer la base de données dans .env.

6. Générer la clé Laravel
php artisan key:generate
7. Lancer les migrations
php artisan migrate
8. Lancer le serveur
php artisan serve
9. Compiler les assets
npm run dev

📁 Structure du projet
app/
bootstrap/
config/
database/
public/
resources/
routes/
screenshots/
storage/
tests/
🎯 Objectif du projet

Ce projet a été réalisé dans le cadre d’un portfolio de développement web afin de mettre en pratique :

Laravel
Gestion des rôles utilisateurs
CRUD avancé
Relations entre tables
Authentification
Gestion d’examens en ligne
Export de données
Interface moderne responsive
👩‍💻 Auteur

Dounia Lallouche
Master Développement & Base de Données