from flask import Flask, jsonify
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

# --- Configuration de l'application Flask ---
app = Flask(__name__)

# --- Configuration de la connexion à la BDD (Équivalent appsettings.json/connectionString) ---
# Format : 'mysql://utilisateur:mot_de_passe@hote_du_serveur/nom_de_la_base'
# Récupérez les informations de connexion utilisées dans la commande Scaffold-DbContext de votre tuto .NET
# Scaffold-DbContext "server=localhost;port=3306;user=root;password=;database=demo" ... 

# ATTENTION : Remplacez 'root', 'votre_mot_de_passe' et '3306' si nécessaire
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql://root:@localhost:3306/demo'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

# --- Définition du modèle de données (Équivalent de votre classe User.cs) ---
class User(db.Model):
    # Le nom de la table doit correspondre exactement à celui que vous avez créé dans MySQL
    __tablename__ = 'user'
    
    # Définition des colonnes basées sur votre script CREATE TABLE [cite: 43-50]
    Id = db.Column(db.Integer, primary_key=True)
    FirstName = db.Column(db.String(45), nullable=False)
    LastName = db.Column(db.String(45), nullable=False)
    Username = db.Column(db.String(45), nullable=False)
    Password = db.Column(db.String(45), nullable=False)
    # Dans Python, on utilise généralement DateTime pour les champs datetime
    EnrollmentDate = db.Column(db.DateTime, nullable=False, default=datetime.utcnow) 

    def to_dict(self):
        return {
            'Id': self.Id,
            'FirstName': self.FirstName,
            'LastName': self.LastName,
            'Username': self.Username,
            'EnrollmentDate': self.EnrollmentDate.isoformat() if self.EnrollmentDate else None
        }

# app.py (Suite)

@app.route('/api/user', methods=['GET'])
def get_all_users():
    """
    Récupère la liste de tous les utilisateurs enregistrés.
    (Équivalent de GetBots dans le tuto .NET)
    """
    try:
        # 1. Requête à la BDD (Équivalent de DBContext.Bots.Select(...).ToListAsync())
        users = User.query.all()

        # 2. Vérification si la liste est vide (Équivalent de if (List.Count < 0))
        if not users:
            # Code de réponse 404 - Not Found
            return jsonify({"message": "Aucun utilisateur trouvé."}), 404

        # 3. Sérialisation des données
        # Utilisation de la méthode to_dict() définie dans la classe User
        users_list = [user.to_dict() for user in users]

        # 4. Retourner la réponse JSON (Équivalent de return List)
        return jsonify(users_list), 200 # Code de réponse 200 - OK

    except Exception as e:
        # Gérer les erreurs de connexion à la BDD
        print(f"Erreur lors de la récupération des utilisateurs: {e}")
        return jsonify({"message": "Erreur interne du serveur."}), 500 # Code de réponse 500

if __name__ == '__main__':
    app.run(debug=True)