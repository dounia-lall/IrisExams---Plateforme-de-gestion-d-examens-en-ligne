# MyExam — Plateforme de gestion d’examens en ligne

MyExam est une application web développée avec Laravel, MySQL et TailwindCSS permettant aux enseignants de créer, gérer et corriger des examens en ligne.

Le projet propose deux espaces distincts :

- 👨‍🏫 Espace Enseignant
- 👨‍🎓 Espace Étudiant

L’application permet la création d’examens interactifs avec différents types de questions ainsi qu’un système de correction automatique.

---

# 🚀 Technologies utilisées

## Backend

- Laravel
- PHP
- MySQL

## Frontend

- TailwindCSS
- Blade
- JavaScript
- Vite

---

# ✨ Fonctionnalités principales

## 👨‍🏫 Enseignant

- Authentification sécurisée
- Création d’examens
- Gestion des étudiants
- Ajout de questions :
  - QCM
  - Vrai / Faux
  - Réponse texte
- Correction automatique
- Attribution d’une note finale
- Consultation des copies
- Export PDF
- Export CSV
- Organisation des étudiants par formation

## 👨‍🎓 Étudiant

- Connexion sécurisée
- Consultation des examens
- Passage des examens en ligne
- Affichage automatique du score
- Consultation des résultats
- Visualisation des corrections

---

# 📸 Captures d’écran

## 🏠 Page d’accueil

![Accueil](./screenshots/01-homepage.png)

---

## 🔐 Connexion

![Connexion](./screenshots/02-login.png)

---

## 📊 Dashboard professeur

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

![Interface examen](./screenshots/11-examInterface.png)

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

---

# ⚙️ Installation

## 1️⃣ Cloner le projet

```bash
git clone https://github.com/dounia-lall/IrisExams---Plateforme-de-gestion-d-examens-en-ligne.git
```

## 2️⃣ Accéder au dossier du projet

```bash
cd IrisExams
```

## 3️⃣ Installer les dépendances PHP

```bash
composer install
```

## 4️⃣ Installer les dépendances frontend

```bash
npm install
```

## 5️⃣ Configurer l’environnement

Créer le fichier `.env` :

```bash
cp .env.example .env
```

Configurer ensuite les informations de connexion MySQL dans le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iris_exams
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6️⃣ Générer la clé Laravel

```bash
php artisan key:generate
```

## 7️⃣ Lancer les migrations

```bash
php artisan migrate
```

## 8️⃣ Compiler les assets frontend

```bash
npm run dev
```

## 9️⃣ Lancer le serveur Laravel

```bash
php artisan serve
```

Accéder ensuite à :

```text
http://127.0.0.1:8000
```

---

# 📁 Structure du projet

```text
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
```

---

# 🔒 Sécurité

- Authentification sécurisée
- Protection CSRF Laravel
- Validation des formulaires
- Gestion des rôles utilisateurs
- Système anti-cheating pendant les examens
- Correction automatique sécurisée

---

# 🎯 Objectif du projet

Ce projet m’a permis de développer mes compétences en développement web fullstack :

- Laravel
- Gestion des rôles utilisateurs
- CRUD avancé
- Relations entre tables
- Authentification sécurisée
- Gestion d’examens en ligne
- Export de données
- Création d’interfaces modernes et responsives

---

# 👩‍💻 Auteur

Dounia Lallouche
