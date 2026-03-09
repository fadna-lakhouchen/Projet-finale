---
marp: true
theme: default
_class: lead
_paginate: false
paginate: true
backgroundColor: #ffffff
style: |
  section {
    font-size: 22px;
    color: #333;
    line-height: 1.6;
    padding: 60px 80px;
  }
  footer { width: 100%; text-align: right; font-size: 14px; color: #888; }
  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 40px;   
    left: 60px;
    right: 60px;
  }
  .logo-header img { height: 140px; margin: 0; margin-left:10px; margin-right:10px }
  h1 { color: #088dc7; font-size: 2.8em; margin-top: 100px; text-align: left; }
  h2 { color: #088dc7; font-size: 2em; border-bottom: 2px solid #088dc7; margin-bottom: 40px;}
  h3 { text-align: left; color: #444; margin-top: 0; }

  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-item {
    display: flex;
    align-items: center;
    background: #f4faff;
    border-radius: 12px;
    padding: 15px 20px;
    border-left: 5px solid #088dc7;
  }
  .sommaire-num {
    background: #088dc7; color: white; width: 35px; height: 35px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
  }
  
  .img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
  }
  .img-methodo {
    width: 85%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .dt-card {
    background: #f0f7fa;
    padding: 30px;
    border-radius: 10px;
    border-top: 6px solid #088dc7;
    text-align: left;
    margin-top: 20px;
    width: 100%;
  }

  /* --- FIX COULEURS TECH STACK --- */
  .tech-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
  }
  .badge-simple {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 600;
    background-color: #545353ff; /* Gris foncé unique */
    color: #ffffff !important;
    font-size: 0.85em;
    border: 1px solid #222;
  }
  .maquette-grid {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: flex-start;
    height: 350px;
  }

---

<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="Logo Left">
  <img src="images/logo-solicode.png" alt="Logo Right">
</div>

# **Projet de Fin de Formation**
### ImmoSyndic : Digitalisation de la Gestion du Syndic et des Immeubles

**Réalisé par :** <span class="highlight">FADNA LAKHOUCHEN</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile et Web

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie de travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception</div></div>
    <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Conclusion</div></div>
</div>

---

## 1. Contexte du projet

<div class="img-container">
  <img src="images/contexte.png" class="img-methodo" alt="Design Thinking">
</div>

---

## 2. Méthodologie : Design Thinking



<div class="img-container">
  <img src="images/designThinking.png" class="img-methodo" alt="Design Thinking">
</div>

---

## Méthodologie : Scrum (Agile)



<div class="img-container">
  <img src="images/scrum.jpg" class="img-methodo" alt="Scrum">
</div>

---

## Méthodologie : Processus 2TUP



<div class="img-container">
  <img src="images/2TUP.png" class="img-methodo" alt="2TUP">
</div>

---
## 3. Branche Fonctionnelle : Design Thinking
### 1. EMPATHIE : Résident (Hassan)

<div class="img-container">
  <img src="images/empathie_hassan.png" class="img-methodo" alt="Empathie Hassan">
</div>

---

## Branche Fonctionnelle : Design Thinking
### 1. EMPATHIE : Résidente & Copropriétaire (Hasnae)

<div class="img-container">
  <img src="images/empathie_hasnae.png" class="img-methodo" alt="Empathie Hasnae">
</div>

---

## Branche Fonctionnelle : Design Thinking
### 1. EMPATHIE : Syndic (Youssef)

<div class="img-container">
  <img src="images/empathie_youssef.png" class="img-methodo" alt="Empathie Youssef">
</div>

---

## Branche Fonctionnelle : Design Thinking
### 1. EMPATHIE : Administrateur (Mohamed)

<div class="img-container">
  <img src="images/empathie_mohamed.png" class="img-methodo" alt="Empathie Mohamed">
</div>

---

## Branche Fonctionnelle : Design Thinking
### 2. DÉFINITION

<div class="img-container">
  <div class="dt-card" style="border-top-color: #f39c12;">
    <h4>Cadrage du problème</h4>
    <blockquote style="font-style: italic; background: white; padding: 15px; border-radius: 8px;">
     <p> - Comment pouvons-nous améliorer la gestion des immeubles et des paiements tout en garantissant la transparence, la communication efficace et la réduction des conflits entre résidents et syndic ? </p>
    </blockquote>
  </div>
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### Diagramme de cas d'utilisation global : Partie Administrateur 
### Espace Admin
<div class="img-container">
  <img src="images/use_case_global_admin.png" class="img-methodo" alt="Use Case Admin">
  
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### Diagramme de cas d'utilisation global : Partie Syndic
### Espace Syndic
<div class="img-container">
  <img src="images/use_case_global_syndic.png" class="img-methodo" alt="Use Case Syndic">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### Diagramme de cas d'utilisation global : Partie Résident
### Espace Résident
<div class="img-container">
  <img src="images/use_case_global_resident.png" class="img-methodo" alt="Use Case Resident">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

### Diagramme de cas d'utilisation global : Partie Mobile

<div class="img-container">
  <img src="images/use_case_global_mobile.png" class="img-methodo" alt="Use Case Mobile">
  
</div>

---

## Branche Fonctionnelle : Cas d'utilisation - Sprint 1


<div class="img-container">
  <img src="images/use_case_mvp.png" class="img-methodo" alt="Use Case Sprint 1">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation - Sprint 2 

<div class="img-container">
  <img src="images/use_case_avance.png" class="img-methodo" alt="Use Case Sprint 2">
</div>

---
## Branche Fonctionnelle : Maquettes (UI/UX)


<p style="font-size: 0.3rem; color: #666;">Interface Administration</p>
<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/dashbord_admin.png" class="img-methodo" style="height: 360px; width: auto;" alt="Maquette Desktop">
    
  </div>
</div>

---
## Branche Fonctionnelle : Maquettes (UI/UX)


<p style="font-size: 0.3rem; color: #666;">Interface Syndic</p>
<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/dashboard_syndic.png" class="img-methodo" style="height: 360px; width: auto;" alt="Maquette Desktop">
  </div>
</div>

---
## Branche Fonctionnelle : Maquettes (UI/UX)


<p style="font-size: 0.3rem; color: #666;">Interface Résident</p>
<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/dashboard_resident.png" class="img-methodo" style="height: 360px; width: auto;" alt="Maquette Desktop">
    
  </div>
</div>

---

## 4. Branche Technique : Tech Stack
<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Back-end & Architecture</h4>
    <ul>
      <li><strong>Framework :</strong> PHP 8 / Laravel 12</li>
      <li><strong>Database :</strong> MySQL / Eloquent</li>
      <li><strong>Architecture :</strong> MVC / 3-Tiers</li>
      <li><strong>Sécurité :</strong> Spatie (Rôles)</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <h4>Front-end & Outils</h4>
    <ul>
      <li><strong>Styling :</strong> Blade / Tailwind CSS</li>
      <li><strong>UI / Icons :</strong> Preline UI / Lucide</li>
      <li><strong>Dynamic :</strong> JavaScript / Alpine.js</li>
      <li><strong>Build :</strong> Vite</li>
    </ul>
  </div>
</div>

---


## 5. Conception : Diagramme de classe


 <h3>Modélisation des données (MLD)</h3>
<div class="img-container">
 
  <img src="images/diagramme-class.png" style="width: 100%;" alt="Diagramme de classe">
</div>

---
## 6. Conclusion


### Merci pour votre attention !