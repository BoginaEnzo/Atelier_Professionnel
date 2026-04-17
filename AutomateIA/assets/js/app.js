/* ==========================================================================
   VARIABLES GLOBALES (La mémoire à court terme du Serveur)
   ========================================================================== */
// L'adresse de notre cuisine (le routeur PHP)
const API = 'api.php';
// Le nom par défaut de l'auteur quand l'IA ou le système agit
const AUTHOR = 'CHAT';

// Le carnet de notes du serveur : il mémorise toute la conversation pour que 
// l'IA se souvienne de ce qu'on a dit juste avant.
let history = [];      
// Le compteur de "mots" utilisés par l'IA (pour des questions de coût/performance)
let totalTokens = 0;   

/* ==========================================================================
   INITIALISATION (Quand le client rentre dans le restaurant)
   ========================================================================== */
// 'DOMContentLoaded' signifie : "Attends que tout le HTML soit dessiné à l'écran 
// avant de lancer ce code". C'est crucial, sinon le JS va chercher des boutons 
// qui n'existent pas encore !
window.addEventListener('DOMContentLoaded', () => {
  
  checkLLM();  // On vérifie si l'IA (le Traducteur) est réveillée
  loadNews();  // On charge les articles depuis la base de données
  
  // -- RÉGLAGE DE LA ZONE DE TEXTE (Textarea) --
  const ta = document.getElementById('chatInput');
  
  // À chaque fois que l'utilisateur tape une lettre ('input') :
  ta.addEventListener('input', () => { 
    ta.style.height = 'auto'; // On réinitialise la hauteur
    // On l'agrandit selon le contenu, mais on la bloque à 120 pixels max
    ta.style.height = Math.min(ta.scrollHeight, 120) + 'px'; 
  });
  
  // Si l'utilisateur appuie sur une touche ('keydown') :
  ta.addEventListener('keydown', e => { 
    // Si c'est la touche "Entrée" ET qu'il ne maintient PAS la touche "Maj" enfoncée
    if (e.key === 'Enter' && !e.shiftKey) { 
      e.preventDefault(); // On annule le saut de ligne naturel
      sendMessage();      // On envoie le message !
    } 
  });
});

/**
 * Envoie le message saisi par l'utilisateur au modèle d'IA
 */
async function sendMessage() {
  const input = document.getElementById('chatInput');
  const msg = input.value.trim(); 
  
  if (!msg) return; // Si le champ est vide, on ne fait rien

  // 1. Affiche ton message immédiatement dans le chat
  addMsg('user', msg); 
  input.value = ''; // Vide le champ de saisie
  input.style.height = 'auto';
  
  // Ajoute ton message à l'historique pour que l'IA s'en souvienne
  history.push({ role: 'user', content: msg });
  
  // 2. Prépare l'envoi vers le serveur
  const sendBtn = document.getElementById('sendBtn');
  sendBtn.disabled = true; // Empêche de cliquer plusieurs fois
  const typingEl = addTyping(); // Affiche les "..." de réflexion
  
  try {
    // C'est ICI que la requête est envoyée au fichier api.php
    const r = await fetch(API + '?action=chat', { 
      method: 'POST', 
      headers: { 'Content-Type': 'application/json' }, 
      body: JSON.stringify({ 
        messages: history, 
        lastby: AUTHOR 
      }) 
    });
    
    const d = await r.json(); // On récupère la réponse du PHP
    
    typingEl.remove(); // On enlève les "..."
    
    if (d.success) {
      const reply = d.reply || '…';
      // Affiche la réponse de l'IA
      addMsg('bot', reply, d.db_action === 'create_news' ? 'success' : '');
      history.push({ role: 'assistant', content: reply });
      
      // Si une action DB a eu lieu (création/modif), on rafraîchit les listes
      if (d.db_action === 'create_news') { 
        setTimeout(loadNews, 500); 
        setTimeout(loadLogs, 600); 
      }
    } else {
      addMsg('sys', '⚠ ' + (d.error || 'Erreur inconnue'), 'error');
    }
  } catch (e) {
    typingEl.remove();
    addMsg('sys', '⚠ Impossible de joindre l\'API PHP : ' + e.message, 'error');
  }
  
  sendBtn.disabled = false; // Réactive le bouton
}

/* ==========================================================================
   MÉCANIQUES DE CHAT (La prise de commande)
   ========================================================================== */

/**
 * Envoie le message saisi par l'utilisateur au modèle d'IA
 * Le mot-clé 'async' permet d'utiliser 'await' (attendre que le serveur réponde)
 */
async function sendMessage() {
  const input = document.getElementById('chatInput');
  const msg = input.value.trim(); // .trim() enlève les espaces inutiles au début et à la fin
  
  if (!msg) return; // Si le message est vide, on arrête tout.
  
  // 1. AFFICHAGE IMMÉDIAT (Pour que l'application paraisse rapide)
  addMsg('user', msg); // On dessine la bulle bleue de l'utilisateur
  input.value = '';    // On vide la zone de texte
  input.style.height = 'auto'; // On remet sa taille à la normale
  
  // On ajoute le message au carnet de notes pour que l'IA ait le contexte
  history.push({ role: 'user', content: msg });
  
  // 2. VERROUILLAGE DE L'INTERFACE
  const sendBtn = document.getElementById('sendBtn');
  sendBtn.disabled = true;     // On grise le bouton d'envoi pour éviter le spam
  const typingEl = addTyping(); // On affiche l'animation des 3 petits points "..."
  
  // 3. ENVOI EN CUISINE (La requête Fetch)
  try {
    // On envoie une requête POST (une livraison de colis fermé) vers api.php?action=chat
    const r = await fetch(API + '?action=chat', { 
      method: 'POST', 
      headers: { 'Content-Type': 'application/json' }, 
      // JSON.stringify transforme notre objet JS en texte JSON pour le voyage sur le réseau
      body: JSON.stringify({ 
        messages: history, 
        lastby: AUTHOR 
      }) 
    });
    
    // On attend la réponse de la cuisine, et on la décode (du JSON vers JS)
    const d = await r.json();
    /**----------------------------------------------------------------------------------------------- **/
    typingEl.remove(); // On supprime l'animation "..." puisqu'on a la réponse
    
    // 4. TRAITEMENT DE LA RÉPONSE
    if (d.success) { // Si le PHP a dit "success: true"
      const reply = d.reply || '…';
      
      // On dessine la bulle grise de l'IA. Si elle a agi sur la base, on la dessine en vert ('success').
      addMsg('bot', reply, d.db_action === 'create_news' ? 'success' : '');
      
      // On ajoute la réponse au carnet de notes de la conversation
      history.push({ role: 'assistant', content: reply });
      
      // Mise à jour du compteur de tokens visuel en haut
      if (d.usage) { 
        totalTokens += d.usage.total_tokens || 0; 
        document.getElementById('tokenInfo').textContent = totalTokens + ' tokens'; 
      }
      
      // MAGIE : Si le PHP nous dit qu'une action a eu lieu dans la base de données...
      if (d.db_action === 'create_news') { 
        // ... On demande un rafraîchissement des panneaux de droite !
        // setTimeout permet d'attendre une demi-seconde pour que l'animation soit fluide
        setTimeout(loadNews, 500); 
        setTimeout(loadLogs, 600); 
      }
    } else {
      // Si la cuisine a planté proprement (Exception PHP)
      addMsg('sys', '⚠ ' + (d.error || 'Erreur inconnue'), 'error');
    }
  } catch (e) {
    // Si la route vers la cuisine est coupée (ex: Serveur Apache éteint)
    typingEl.remove();
    addMsg('sys', '⚠ Impossible de joindre l\'API PHP : ' + e.message, 'error');
  }
  
  // Quoi qu'il arrive (succès ou erreur), on débloque le bouton d'envoi.
  sendBtn.disabled = false;
}

/**
 * "Ping" l'API pour voir si LM Studio est bien démarré
 */
async function checkLLM() {
  try {
    const r = await fetch(API + '?action=chat', { 
      method: 'POST', 
      headers: { 'Content-Type': 'application/json' }, 
      body: JSON.stringify({ 
        messages: [{ role: 'user', content: 'ping' }], 
        lastby: AUTHOR 
      }) 
    });
    const d = await r.json();
    
    if (d.success) setStatus(true, 'LM Studio connecté');
    else setStatus(false, 'LLM indisponible');
  } catch { 
    setStatus(false, 'LLM hors ligne'); 
  }
}

/**
 * Gère le point rouge/vert en haut à droite
 */
function setStatus(ok, txt) {
  document.getElementById('statusDot').className = 'dot ' + (ok ? 'online' : 'error');
  document.getElementById('statusText').textContent = txt;
}

/**
 * Insère le texte des raccourcis cliquables directement dans la zone de texte
 */
function setInput(text) {
  const ta = document.getElementById('chatInput');
  ta.value = text; 
  ta.focus(); // On met le curseur clignotant dedans
  ta.style.height = 'auto'; 
  ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
}

/* ==========================================================================
   MANIPULATION DU DOM (Dessiner les bulles de chat)
   ========================================================================== */

/**
 * Crée les éléments HTML de toutes pièces pour fabriquer une bulle de message
 */
function addMsg(role, content, cls = '') {
  const wrap = document.getElementById('messages'); // La zone du chat
  
  // On crée un conteneur générique <div>
  const div = document.createElement('div');
  div.className = 'msg msg-' + role; // ex: class="msg msg-bot"
  
  // On crée la bulle elle-même
  const bubble = document.createElement('div');
  bubble.className = 'bubble' + (cls ? ' ' + cls : '');
  bubble.innerHTML = mdToHtml(content); // On injecte le texte après l'avoir sécurisé et mis en gras
  
  // On crée la petite date en dessous de la bulle
  const meta = document.createElement('div');
  meta.className = 'msg-meta';
  meta.textContent = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
  
  // On assemble les pièces (comme des Lego)
  div.appendChild(bubble); 
  div.appendChild(meta);
  wrap.appendChild(div); // On colle l'assemblage dans la fenêtre de chat
  
  // On force la fenêtre de chat à faire défiler l'ascenseur tout en bas
  wrap.scrollTop = wrap.scrollHeight; 
  
  return div; // On retourne la bulle au cas où on voudrait la supprimer plus tard (utile pour le "...")
}

/**
 * Ajoute l'animation de frappe de l'IA
 */
function addTyping() {
  const wrap = document.getElementById('messages');
  const div = document.createElement('div');
  div.className = 'msg msg-bot';
  // On insère le code HTML exact de l'animation CSS
  div.innerHTML = '<div class="bubble"><div class="typing"><span></span><span></span><span></span></div></div>';
  wrap.appendChild(div); 
  wrap.scrollTop = wrap.scrollHeight;
  return div;
}

/* ==========================================================================
   MÉCANIQUES DES PANNEAUX (News & Logs)
   ========================================================================== */

/**
 * Va chercher la liste des articles en cuisine et les affiche
 */
async function loadNews() {
  // On affiche un chargeur en attendant
  document.getElementById('newsList').innerHTML = '<div class="loader">Chargement</div>';
  try {
    // Cette fois on fait un simple 'GET' (c'est le comportement par défaut de fetch)
    const r = await fetch(API + '?action=news');
    const d = await r.json();
    
    if (d.success) renderNews(d.data); // Si on a les données, on les dessine
    else document.getElementById('newsList').innerHTML = '<div class="loader">Erreur: ' + d.error + '</div>';
  } catch { 
    document.getElementById('newsList').innerHTML = '<div class="loader">API inaccessible</div>'; 
  }
}

/**
 * Dessine les cartes (Articles) dans le panneau de droite
 */
function renderNews(list) {
  // Met à jour le badge du compteur
  document.getElementById('newsCount').textContent = list.length + ' article' + (list.length > 1 ? 's' : '');
  const el = document.getElementById('newsList');
  
  if (!list.length) { 
    el.innerHTML = '<div class="loader">Aucune news</div>'; 
    return; 
  }
  
  el.innerHTML = ''; // On vide complètement la liste avant de la repeupler
  
  // Pour chaque article trouvé dans la base de données...
  list.forEach((n, i) => {
    const card = document.createElement('div');
    card.className = 'news-card'; 
    // Petite astuce CSS : On décale l'apparition de chaque carte de 40ms pour faire un effet cascade
    card.style.animationDelay = (i * 40) + 'ms'; 
    
    // Formatage de la date (ex: 12 nov. 2024)
    const date = n.new_date ? new Date(n.new_date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
    const initials = (n.new_lastby || '?').substring(0, 2).toUpperCase(); // Extrait les 2 premières lettres
    const color = stringToColor(n.new_lastby || '?'); // Génère une couleur unique selon l'auteur
    
    // On écrit le HTML de la carte avec les fameux "Template Literals" (les backticks ` `)
    // Cela permet d'injecter des variables Javascript directement dans le HTML avec ${}
    card.innerHTML = `
      <div class="news-card-head">
        <div class="news-title">${esc(n.new_title || '—')}</div>
        <div class="news-id">#${n.new_id}</div>
      </div>
      <div class="news-excerpt">${esc(n.new_article || '')}</div>
      <div class="news-meta">
        <div class="news-by">
          <div class="avatar" style="background:${color}">${initials}</div>
          ${esc(n.new_lastby || '?')} · ${date}
        </div>
        <button class="btn-del" onclick="deleteNews(${n.new_id}, this)">✕ Suppr.</button>
      </div>`;
      
    el.appendChild(card); // On ajoute la carte finalisée à l'écran
  });
}

/**
 * Demande au PHP de supprimer une news
 */
async function deleteNews(id, btn) {
  // window.confirm ouvre une petite fenêtre système demandant "Ok" ou "Annuler"
  if (!confirm('Supprimer la news #' + id + ' ?')) return; 
  
  btn.disabled = true; // On grise le bouton de suppression pour éviter de cliquer 10 fois
  
  const r = await fetch(API + '?action=delete_news', { 
    method: 'POST', 
    headers: { 'Content-Type': 'application/json' }, 
    body: JSON.stringify({ id, lastby: 'JFE' }) // JFE = L'utilisateur humain simulé
  });
  
  const d = await r.json();
  if (d.success) { 
    // Si c'est supprimé, on rafraîchit visuellement les deux listes !
    loadNews(); 
    loadLogs(); 
  } else {
    alert('Erreur : ' + d.error);
  }
}

/**
 * Va chercher la liste des Logs (L'historique des actions)
 */
async function loadLogs() {
  document.getElementById('logsList').innerHTML = '<div class="loader">Chargement</div>';
  try {
    const r = await fetch(API + '?action=logs');
    const d = await r.json();
    if (d.success) renderLogs(d.data);
    else document.getElementById('logsList').innerHTML = '<div class="loader">Erreur: ' + d.error + '</div>';
  } catch { 
    document.getElementById('logsList').innerHTML = '<div class="loader">API inaccessible</div>'; 
  }
}

/**
 * Dessine les lignes du journal de bord (Logs)
 */
function renderLogs(list) {
  document.getElementById('logsCount').textContent = list.length + ' entrée' + (list.length > 1 ? 's' : '');
  const el = document.getElementById('logsList');
  
  if (!list.length) { 
    el.innerHTML = '<div class="loader">Aucun log</div>'; 
    return; 
  }
  
  el.innerHTML = '';
  list.forEach((l, i) => {
    const row = document.createElement('div');
    row.className = 'log-row'; 
    row.style.animationDelay = (i * 30) + 'ms';
    
    const date = l.logn_date ? new Date(l.logn_date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }) : '—';
    const type = (l.logn_typeev || 'UNKNOWN').toUpperCase();
    
    // Selon le "type" (CREATION, MODIFTY), la CSS appliquera des couleurs différentes
    row.innerHTML = `
      <div class="log-type ${type}">${type}</div>
      <div class="log-info">
        <div class="log-title">${esc(l.logn_title || '—')}</div>
        <div class="log-meta">#${l.logn_idnews} · ${esc(l.logn_lastby || '?')} · ${date}</div>
      </div>`;
    el.appendChild(row);
  });
}

/* ==========================================================================
   LA FENÊTRE MODALE & CRÉATION MANUELLE (Sans l'IA)
   ========================================================================== */

// Ajouter la classe CSS "open" affiche la popup, l'enlever la cache.
function openModal()  { document.getElementById('modal').classList.add('open'); }
function closeModal() { document.getElementById('modal').classList.remove('open'); }

/**
 * Créer un article à la main via le formulaire
 */
async function createNewsManual() {
  // On récupère ce que l'utilisateur a tapé dans les inputs
  const title   = document.getElementById('m_title').value.trim();
  const article = document.getElementById('m_article').value.trim();
  const lastby  = document.getElementById('m_lastby').value.trim() || 'CHAT';
  
  if (!title || !article) { 
    alert('Titre et article requis.'); 
    return; 
  }
  
  const r = await fetch(API + '?action=create_news', { 
    method: 'POST', 
    headers: { 'Content-Type': 'application/json' }, 
    body: JSON.stringify({ title, article, lastby }) 
  });
  
  const d = await r.json();
  
  if (d.success) { 
    closeModal(); // On ferme la fenêtre
    // On nettoie les champs de texte pour la prochaine fois
    document.getElementById('m_title').value = ''; 
    document.getElementById('m_article').value = ''; 
    
    loadNews(); // On recharge les données
    loadLogs(); 
    
    // On simule une bulle de chat système pour dire que tout s'est bien passé
    addMsg('sys', '✅ ' + d.message, 'success'); 
  } else {
    alert('Erreur : ' + d.error);
  }
}

/* ==========================================================================
   UTILITAIRES VISUELS
   ========================================================================== */

/**
 * Gère le changement entre l'onglet News et l'onglet Logs
 */
function switchTab(name) {
  // Sélectionne tous les éléments avec la classe "tab" (Les 2 boutons en haut)
  document.querySelectorAll('.tab').forEach((t, i) => {
    // La méthode 'toggle' ajoute une classe si la condition est Vraie, et l'enlève si Fausse.
    t.classList.toggle('active', (i === 0 && name === 'news') || (i === 1 && name === 'logs'));
  });
  
  // Affiche le conteneur du contenu choisi et masque l'autre
  document.getElementById('tab-news').classList.toggle('active', name === 'news');
  document.getElementById('tab-logs').classList.toggle('active', name === 'logs');
  
  // Si on clique sur les logs, on rafraîchit la base au cas où il y a eu du nouveau !
  if (name === 'logs') loadLogs();
}

/**
 * PARSAGE MARKDOWN : Convertit *texte* en italique, et **texte** en gras
 * + SÉCURITÉ (XSS) : Échappe les balises <script> pour que le navigateur ne les lise pas comme du code.
 */
function mdToHtml(text) {
  return text
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;') // Échappement HTML (XSS)
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')                   // Le Gras
    .replace(/\*(.*?)\*/g, '<em>$1</em>')                               // L'Italique
    .replace(/\n/g, '<br>');                                            // Les sauts de ligne
}

/**
 * Sécurise une chaîne (Version courte de mdToHtml pour les titres de news)
 */
function esc(s) { 
  return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); 
}

/**
 * MAGIE VISUELLE : Transforme un nom ("CHAT", "JFE") en couleur.
 * Ainsi, l'auteur "JFE" aura toujours la même couleur aléatoire sans qu'on ait besoin de la stocker !
 */
function stringToColor(str) { 
  let h = 0; 
  // Algorithme de hachage simple
  for(let i = 0; i < str.length; i++) {
    h = str.charCodeAt(i) + ((h << 5) - h); 
  }
  // On retourne une couleur HSL (Teinte, Saturation, Luminosité)
  // Math.abs(h) % 360 garantit que la teinte est entre 0 et 360 degrés sur la roue chromatique.
  return `hsl(${Math.abs(h) % 360}, 60%, 45%)`; 
}