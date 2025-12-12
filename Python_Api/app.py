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

if __name__ == '__main__':
    app.run(debug=True)