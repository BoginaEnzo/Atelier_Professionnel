// Variables globales de configuration
const API = 'api.php';
const AUTHOR = 'CHAT';

// État de l'application
let history = [];      // Stocke l'historique de la conversation
let totalTokens = 0;   // Compteur de tokens utilisés par l'IA
 
/**
 * Initialisation au chargement de la page
 */
window.addEventListener('DOMContentLoaded', () => {
  checkLLM();  // Vérifie si LM Studio répond
  loadNews();  // Charge la liste des articles
  
  // Configuration de la zone de texte pour qu'elle s'agrandisse automatiquement
  const ta = document.getElementById('chatInput');
  ta.addEventListener('input', () => { 
    ta.style.height = 'auto'; 
    ta.style.height = Math.min(ta.scrollHeight, 120) + 'px'; 
  });
  
  // Permet d'envoyer le message avec la touche "Entrée" (sans Maj)
  ta.addEventListener('keydown', e => { 
    if (e.key === 'Enter' && !e.shiftKey) { 
      e.preventDefault(); 
      sendMessage(); 
    } 
  });
});

/**
 * Ping l'API pour vérifier l'état du serveur LLM local
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
    
    if (d.success) {
      setStatus(true, 'LM Studio connecté');
    } else {
      setStatus(false, 'LLM indisponible');
    }
  } catch { 
    setStatus(false, 'LLM hors ligne'); 
  }
}

/**
 * Met à jour l'indicateur visuel de connexion en haut à droite
 */
function setStatus(ok, txt) {
  document.getElementById('statusDot').className = 'dot ' + (ok ? 'online' : 'error');
  document.getElementById('statusText').textContent = txt;
}

/**
 * Envoie le message saisi par l'utilisateur au modèle d'IA
 */
async function sendMessage() {
  const input = document.getElementById('chatInput');
  const msg = input.value.trim();
  if (!msg) return; // Stoppe si le message est vide
  
  // 1. Affiche le message de l'utilisateur
  addMsg('user', msg);
  input.value = ''; 
  input.style.height = 'auto';
  
  // 2. Ajoute le message à l'historique
  history.push({ role: 'user', content: msg });
  
  // 3. Bloque le bouton d'envoi et affiche l'indicateur "... "
  const sendBtn = document.getElementById('sendBtn');
  sendBtn.disabled = true;
  const typingEl = addTyping();
  
  try {
    // 4. Appel à l'API PHP
    const r = await fetch(API + '?action=chat', { 
      method: 'POST', 
      headers: { 'Content-Type': 'application/json' }, 
      body: JSON.stringify({ 
        messages: history, 
        lastby: AUTHOR 
      }) 
    });
    const d = await r.json();
    
    typingEl.remove(); // Retire l'indicateur "... "
    
    if (d.success) {
      // Succès : Affiche la réponse de l'IA
      const reply = d.reply || '…';
      addMsg('bot', reply, d.db_action === 'create_news' ? 'success' : '');
      history.push({ role: 'assistant', content: reply });
      
      // Mise à jour du compteur de tokens
      if (d.usage) { 
        totalTokens += d.usage.total_tokens || 0; 
        document.getElementById('tokenInfo').textContent = totalTokens + ' tokens'; 
      }
      
      // Si l'IA a créé une news, on rafraîchit automatiquement les listes
      if (d.db_action === 'create_news') { 
        setTimeout(loadNews, 500); 
        setTimeout(loadLogs, 600); 
      }
    } else {
      // Erreur renvoyée par le backend (ex: LM Studio éteint)
      addMsg('sys', '⚠ ' + (d.error || 'Erreur inconnue'), 'error');
    }
  } catch (e) {
    // Erreur réseau critique (PHP planté)
    typingEl.remove();
    addMsg('sys', '⚠ Impossible de joindre l\'API PHP : ' + e.message, 'error');
  }
  
  // Réactive le bouton d'envoi
  sendBtn.disabled = false;
}

/**
 * Remplit la zone de texte avec un raccourci prédéfini (Hints)
 */
function setInput(text) {
  const ta = document.getElementById('chatInput');
  ta.value = text; 
  ta.focus();
  ta.style.height = 'auto'; 
  ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
}

/**
 * Construit et ajoute une bulle de message dans l'interface de chat
 */
function addMsg(role, content, cls = '') {
  const wrap = document.getElementById('messages');
  
  const div = document.createElement('div');
  div.className = 'msg msg-' + role;
  
  const bubble = document.createElement('div');
  bubble.className = 'bubble' + (cls ? ' ' + cls : '');
  bubble.innerHTML = mdToHtml(content); // Convertit le markdown en HTML simple
  
  const meta = document.createElement('div');
  meta.className = 'msg-meta';
  meta.textContent = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
  
  div.appendChild(bubble); 
  div.appendChild(meta);
  wrap.appendChild(div); 
  wrap.scrollTop = wrap.scrollHeight; // Fait défiler vers le bas
  
  return div;
}

/**
 * Ajoute une bulle temporaire indiquant que l'IA réfléchit
 */
function addTyping() {
  const wrap = document.getElementById('messages');
  const div = document.createElement('div');
  div.className = 'msg msg-bot';
  div.innerHTML = '<div class="bubble"><div class="typing"><span></span><span></span><span></span></div></div>';
  wrap.appendChild(div); 
  wrap.scrollTop = wrap.scrollHeight;
  return div;
}

/**
 * Convertit sommairement du Markdown (gras, italique, sauts de ligne) en HTML sécurisé
 */
function mdToHtml(text) {
  return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
             .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
             .replace(/\*(.*?)\*/g, '<em>$1</em>')
             .replace(/\n/g, '<br>');
}

/**
 * Récupère la liste des News depuis le serveur
 */
async function loadNews() {
  document.getElementById('newsList').innerHTML = '<div class="loader">Chargement</div>';
  try {
    const r = await fetch(API + '?action=news');
    const d = await r.json();
    
    if (d.success) {
      renderNews(d.data);
    } else {
      document.getElementById('newsList').innerHTML = '<div class="loader">Erreur: ' + d.error + '</div>';
    }
  } catch { 
    document.getElementById('newsList').innerHTML = '<div class="loader">API inaccessible</div>'; 
  }
}

/**
 * Affiche la liste des News dans le panneau de droite
 */
function renderNews(list) {
  document.getElementById('newsCount').textContent = list.length + ' article' + (list.length > 1 ? 's' : '');
  const el = document.getElementById('newsList');
  
  if (!list.length) { 
    el.innerHTML = '<div class="loader">Aucune news</div>'; 
    return; 
  }
  
  el.innerHTML = '';
  list.forEach((n, i) => {
    const card = document.createElement('div');
    card.className = 'news-card'; 
    card.style.animationDelay = (i * 40) + 'ms'; // Effet d'apparition en cascade
    
    const date = n.new_date ? new Date(n.new_date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
    const initials = (n.new_lastby || '?').substring(0, 2).toUpperCase();
    const color = stringToColor(n.new_lastby || '?'); // Génère une couleur unique basée sur le nom de l'auteur
    
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
    el.appendChild(card);
  });
}

/**
 * Supprime une news en appelant l'API PHP
 */
async function deleteNews(id, btn) {
  if (!confirm('Supprimer la news #' + id + ' ?')) return; // Demande confirmation
  
  btn.disabled = true; // Empêche le double-clic
  
  const r = await fetch(API + '?action=delete_news', { 
    method: 'POST', 
    headers: { 'Content-Type': 'application/json' }, 
    body: JSON.stringify({ id, lastby: 'JFE' }) 
  });
  
  const d = await r.json();
  if (d.success) { 
    loadNews(); 
    loadLogs(); 
  } else {
    alert('Erreur : ' + d.error);
  }
}

/**
 * Récupère la liste des Logs (historique) depuis le serveur
 */
async function loadLogs() {
  document.getElementById('logsList').innerHTML = '<div class="loader">Chargement</div>';
  try {
    const r = await fetch(API + '?action=logs');
    const d = await r.json();
    
    if (d.success) {
      renderLogs(d.data);
    } else {
      document.getElementById('logsList').innerHTML = '<div class="loader">Erreur: ' + d.error + '</div>';
    }
  } catch { 
    document.getElementById('logsList').innerHTML = '<div class="loader">API inaccessible</div>'; 
  }
}

/**
 * Affiche la liste des Logs dans le panneau de droite
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
    
    row.innerHTML = `
      <div class="log-type ${type}">${type}</div>
      <div class="log-info">
        <div class="log-title">${esc(l.logn_title || '—')}</div>
        <div class="log-meta">#${l.logn_idnews} · ${esc(l.logn_lastby || '?')} · ${date}</div>
      </div>`;
    el.appendChild(row);
  });
}

/* Gestion de l'affichage de la fenêtre modale (Création manuelle) */
function openModal()  { document.getElementById('modal').classList.add('open'); }
function closeModal() { document.getElementById('modal').classList.remove('open'); }

/**
 * Fonction pour créer une news manuellement (sans utiliser l'IA) via le formulaire
 */
async function createNewsManual() {
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
    closeModal(); 
    // Réinitialise le formulaire
    document.getElementById('m_title').value = ''; 
    document.getElementById('m_article').value = ''; 
    
    // Rafraîchit les données
    loadNews(); 
    loadLogs(); 
    
    // Ajoute une confirmation dans le chat
    addMsg('sys', '✅ ' + d.message, 'success'); 
  } else {
    alert('Erreur : ' + d.error);
  }
}

/**
 * Gère la navigation entre l'onglet "News" et "Logs"
 */
function switchTab(name) {
  // Gère l'apparence des boutons du menu
  document.querySelectorAll('.tab').forEach((t, i) => {
    t.classList.toggle('active', (i === 0 && name === 'news') || (i === 1 && name === 'logs'));
  });
  
  // Affiche ou masque les conteneurs
  document.getElementById('tab-news').classList.toggle('active', name === 'news');
  document.getElementById('tab-logs').classList.toggle('active', name === 'logs');
  
  // Recharge les logs si on clique sur l'onglet Logs
  if (name === 'logs') loadLogs();
}

/**
 * Sécurise une chaîne de caractères pour éviter les failles XSS (Injection HTML)
 */
function esc(s) { 
  return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); 
}

/**
 * Génère une couleur unique (HSL) à partir d'une chaîne de caractères (pour les avatars)
 */
function stringToColor(str) { 
  let h = 0; 
  for(let i = 0; i < str.length; i++) {
    h = str.charCodeAt(i) + ((h << 5) - h); 
  }
  return `hsl(${Math.abs(h) % 360}, 60%, 45%)`; 
}