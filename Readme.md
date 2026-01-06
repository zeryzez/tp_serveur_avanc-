Membres du projet

    Defolie Julien

    Blonbou Mathys

    Hermann Vivien

    Haddad Ryad

Architecture


    Langage : PHP 8+

    Framework : Slim 4 (Micro-framework)

    Architecture : Hexagonale (Ports & Adapters)

        Core/Domain : Entités et logique métier pure.

        Application : Cas d'utilisation (Services) et Interfaces (Ports).

        Infrastructure : Implémentation des repositories (PDO), Adaptateurs externes.

        API : Actions (Contrôleurs), Middlewares, DTOs.

Installation et Lancement

Le projet est entièrement conteneurisé avec Docker.

docker-compose up -d --build

Initialisation des Bases de données :

    Les scripts SQL se trouvent dans le dossier /sql.

L'API est accessible à l'adresse : http://localhost:6080
📚 Documentation de l'API

L'API expose une route d'accueil (GET /) documentant les points d'entrée disponibles.
Endpoints Principaux
    Praticiens
Méthode	URI	Description
GET	/praticiens	Lister tous les praticiens (Filtres : ?ville=...&specialite=...)
GET	/praticiens/{id}	Détail d'un praticien
GET	/praticiens/{id}/creneaux	Lister les créneaux occupés
GET	/praticiens/{id}/agenda	Consulter l'agenda complet
    Rendez-vous
Méthode	URI	Description
POST	/rdvs	Créer un rendez-vous
GET	/rdvs/{id}	Consulter un rendez-vous
POST	/rdvs/{id}/annuler	Annuler un rendez-vous
    Patients
Méthode	URI	Description
POST	/inscription	S'inscrire comme nouveau patient
GET	/patients/{id}/consultations	Historique des consultations d'un patient
    Authentification
Méthode	URI	Description
POST	/auth/login	Connexion simple
POST	/auth/signin	Connexion JWT (Retourne Access & Refresh Tokens)
✅ Tableau de Bord des Fonctionnalités

Voici l'état d'avancement par rapport au cahier des charges :
Fonctionnalités Minimales

    [x] 1. Lister les praticiens

    [x] 2. Afficher le détail d’un praticien

    [x] 3. Lister les créneaux occupés

    [x] 4. Consulter un RDV par ID

    [x] 5. Réserver un RDV (Création)

    [x] 6. Annuler un RDV

    [x] 7. Afficher l’agenda d’un praticien

    [x] 8. Authentification (Patient / Praticien)

Fonctionnalités Additionnelles

    [x] 9. Recherche par Spécialité et/ou Ville (GET /praticiens?ville=...)

    [x] 10. Gérer le cycle de vie des RDV (honoré/non honoré)

    [x] 11. Historique des consultations d'un patient

    [x] 12. Inscription patient

    [x] 13. Gérer les indisponibilités temporaires



    Anonyme : Lister praticiens, Détail praticien, Recherche.

    Authentifié (Patient/Praticien) : Consulter ses propres RDV, Annuler ses propres RDV.

    Patient : Réserver un RDV (pour soi-même), Voir son historique.

    Praticien : Voir son agenda, Gérer ses indisponibilités.

📂 Structure du Projet

/src
  /api              
  /core
    /application    
    /domain         
  /infra            
/config             
/public             
docker-compose.yml  


| Membre                | Contributions Principales                                                                                                 |
| --------------------- | ------------------------------------------------------------------------------------------------------------------------- |
| **Mathys-Vivien**        | Architecture hexagonale, Authentification JWT, Middlewares                                                                |
| **Mathys**        | Bases de données, Docker                                                                                                 |
| **Ryad-Mathys-Vivien-Julien**       | Lister les praticiens, Détail praticien, Créneaux occupés, Consulter RDV, Réserver RDV, Annuler RDV, Agenda praticien | Honorer, non honorer
| **Ryad**       | Détail praticien, Status, HATEOAS                                                                                        |