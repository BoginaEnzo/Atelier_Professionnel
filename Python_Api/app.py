import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

def AfficherUser():
    api_url = "https://localhost:7026/api/Users" 

    response = requests.get(api_url, verify=False)
        
    if response.status_code == 200:
        data = response.json()
        print(data)

AfficherUser()

id = 1
def AfficherUserID(id):
    api_url = f"https://localhost:7026/api/Users/{id}" 

    response = requests.get(api_url, verify=False)
        
    if response.status_code == 200:
        data = response.json()
        print(data)

AfficherUserID(id)

def InsererUser(id_crea, nom, prenom, pseudo, mot_de_passe):

    api_url = "https://localhost:7026/api/Users/InsertUser" 

    nouvel_user = {
        "Id": id_crea,
        "FirstName": nom,
        "LastName": prenom,
        "Botname": pseudo,
        "Password": mot_de_passe,
        "EnrollmentDate": "2025-12-19T10:00:00"
    }

    try:
        response = requests.post(api_url, json=nouvel_user, verify=False)
            
        if response.status_code == 201 or response.status_code == 200:
            print("Succès : Utilisateur créé !")
            return response.json()
        else:
            print(f"Erreur {response.status_code} : {response.text}")
            return None
    except Exception as e:
        print(f"Erreur de connexion : {e}")

id_crea = 3
nom = "Enzo"
prenom = "B"
pseudo = "UserPython1"
mot_de_passe = "secure123"
InsererUser(id_crea, nom, prenom, pseudo, mot_de_passe)

AfficherUserID(id_crea)