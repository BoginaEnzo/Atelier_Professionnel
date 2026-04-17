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
print("--------------------------------------------------") 

id = 1
def AfficherUserID(id):
    api_url = f"https://localhost:7026/api/Users/{id}" 

    response = requests.get(api_url, verify=False)
        
    if response.status_code == 200:
        data = response.json()
        print(data)

AfficherUserID(id)
print("--------------------------------------------------") 

def InsererUser(id_crea, nom, prenom, pseudo, mot_de_passe):

    api_url = "https://localhost:7026/api/Users/InsertUser" 

    nouvel_user = {
        "Id": id_crea,
        "FirstName": nom,
        "LastName": prenom,
        "Username": pseudo,
        "Password": mot_de_passe,
        "EnrollmentDate": "2025-12-19T10:00:00"
    }

    try:
        response = requests.post(api_url, json=nouvel_user, verify=False)
            
        if response.status_code == 201 or response.status_code == 200:
            return response.json()
        else:
            print(f"Erreur {response.status_code} : {response.text}")
            return None
    except Exception as e:
        print(f"Erreur de connexion : {e}")

id_crea = 5
nom = "Medhi"
prenom = "B"
pseudo = "UserPython3"
mot_de_passe = "secure12345"
InsererUser(id_crea, nom, prenom, pseudo, mot_de_passe)

AfficherUserID(id_crea)
print("--------------------------------------------------") 

def UpdateUser(id_crea, nom, prenom, pseudo, mot_de_passe):

    api_url = "https://localhost:7026/api/Users/UpdateUser" 

    nouvel_user = {
        "Id": id_crea,
        "FirstName": nom,
        "LastName": prenom,
        "Username": pseudo,
        "Password": mot_de_passe,
        "EnrollmentDate": "2025-12-19T10:00:00"
    }

    try:
        response = requests.put(api_url, json=nouvel_user, verify=False)
            
        if response.status_code == 201 or response.status_code == 200 or response.status_code == 204:
            if response.status_code == 204:
                return "Succès"
            return response.json()
        else:
            print(f"Erreur {response.status_code} : {response.text}")
            return None
    except Exception as e:
        print(f"Erreur de connexion : {e}")

id_update = 5
nom = "Medhi"
prenom = "Boudechicha"
pseudo = "UserPython3"
mot_de_passe = "secure12345"
UpdateUser(id_update, nom, prenom, pseudo, mot_de_passe)
AfficherUserID(id_update)

print("--------------------------------------------------")
print("Avant suppression :")

AfficherUser()
print("--------------------------------------------------")
def DeleteID(id_del):
    api_url = f"https://localhost:7026/api/Users/DeleteUser/{id_del}" 

    response = requests.delete(api_url, verify=False)
        
    if response.status_code == 201 or response.status_code == 200 or response.status_code == 204:
            if response.status_code == 204:
                return "Succès"
            return response.json()

id_del = 5
DeleteID(id_del)
AfficherUser()
print("--------------------------------------------------")