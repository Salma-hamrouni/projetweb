import os
import google.generativeai as genai
from dotenv import load_dotenv
import customtkinter as ctk
import json
import re

# Charger les variables d'environnement à partir du fichier .env
load_dotenv()

# Configurer l'API key pour Google Gemini
api_key = os.getenv("GOOGLE_API_KEY")
if not api_key:
    raise ValueError("La clé API Google est manquante dans le fichier .env")

genai.configure(api_key=api_key)

# Configuration du modèle
generation_config = {
    "temperature": 1,
    "top_p": 0.95,
    "top_k": 40,
    "max_output_tokens": 8192,
    "response_mime_type": "text/plain",
}

model = genai.GenerativeModel(
    model_name="gemini-2.0-flash-exp",
    generation_config=generation_config,
    system_instruction="Vous êtes un chatbot qui résout n'importe quel problème et est spécialiste dans tous les domaines.",
)

# Démarrer une session de chat
chat_session = model.start_chat(history=[])

def generer_mindmap_depuis_texte(texte):
    mindmap = {"goal": "Objectif", "steps": []}
    lignes = texte.strip().split("\n")

    if len(lignes) == 1 and len(lignes[0]) < 100:
        mindmap["goal"] = lignes[0]
        return mindmap

    for ligne in lignes:
        ligne = ligne.strip()
        if not ligne:
            continue
        if any(c.isdigit() for c in ligne[:3]) or ligne.lower().startswith("étape"):
            parts = ligne.split(":", 1)
            if len(parts) == 2:
                titre = parts[0].strip()
                sous = [s.strip() for s in parts[1].split(",") if s.strip()]
                mindmap["steps"].append({
                    "title": titre,
                    "substeps": [{"title": s} for s in sous]
                })
        else:
            mindmap["goal"] = ligne
    return mindmap

def reformuler_en_mindmap(reponse_textuelle, objectif_initial):
    """
    Reformule une nouvelle demande à partir des conseils textuels.
    """
    prompt = f"""
Tu es un assistant pédagogique. Tu viens de donner ces conseils pour atteindre cet objectif :

\"\"\"{reponse_textuelle}\"\"\"

Transforme-les en une mindmap JSON avec la structure suivante :
{{
  "goal": "{objectif_initial}",
  "steps": [
    {{
      "title": "...",
      "substeps": [{{ "title": "..." }}, ...]
    }},
    ...
  ]
}}

Ne retourne que du JSON valide.
"""
    nouvelle_reponse = model.generate_content(prompt)
    try:
        json_str = re.search(r'\{[\s\S]+\}', nouvelle_reponse.text).group(0)
        mindmap = json.loads(json_str)
        return mindmap
    except:
        return {"goal": objectif_initial, "steps": []}

def send_message(user_input):
    """
    Envoie un message à Gemini et génère une mindmap.
    """
    try:
        response = chat_session.send_message(user_input)
        texte_reponse = response.text
        print(f"Réponse brute : {texte_reponse}\n")

        mindmap_data = generer_mindmap_depuis_texte(texte_reponse)

        # 🧠 Si aucune étape détectée, relancer une demande structurée
        if not mindmap_data["steps"]:
            print("Aucune étape détectée, tentative de reformulation automatique...")
            mindmap_data = reformuler_en_mindmap(texte_reponse, user_input)

        return mindmap_data

    except Exception as e:
        print(f"Erreur lors de l'envoi à Gemini : {e}")
        return {"goal": "Erreur", "steps": []}

def on_button_click():
    user_input = user_input_entry.get()
    if not user_input.strip():
        print("Veuillez entrer un message.")
        return

    mindmap_data = send_message(user_input)
    user_input_entry.delete(0, ctk.END)

    texte_affichage = f"🎯 Objectif : {mindmap_data['goal']}\n"
    for step in mindmap_data.get("steps", []):
        texte_affichage += f"\n➡ {step['title']}\n"
        for sub in step.get("substeps", []):
            texte_affichage += f"   - {sub['title']}\n"

    print("Mindmap générée :", mindmap_data)
    chat_box.insert('end', texte_affichage + "\n\n")
    chat_box.see('end')

# --- Interface graphique ---
ctk.set_appearance_mode("System")
ctk.set_default_color_theme("blue")

root = ctk.CTk()
root.title("Chatbot Mindmap avec Gemini")

chat_box = ctk.CTkTextbox(root, width=500, height=300, corner_radius=10)
chat_box.grid(row=0, column=0, columnspan=2, padx=20, pady=20)

user_input_entry = ctk.CTkEntry(root, width=400, placeholder_text="Tapez votre objectif ici...")
user_input_entry.grid(row=1, column=0, padx=20, pady=10)

send_button = ctk.CTkButton(root, text="Envoyer", command=on_button_click)
send_button.grid(row=1, column=1, padx=10, pady=10)

root.mainloop()
