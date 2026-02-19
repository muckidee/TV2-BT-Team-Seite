# Team-Seite Redesign: test-vergleiche.com/unser-team/

## Projekt-Uebersicht

Redesign der Team-Seite auf Basis von **Mockup B** (Sidebar-Filter + Grid).  
Zusaetzlich: Produkt-Referenzen-Bereich, vollstaendige SEO-Optimierung, Schema Markup und Mobile-First Design.

**Ziel-URL:** `https://test-vergleiche.com/unser-team/`  
**CMS:** WordPress (Custom Theme)  
**Farben:** Navy (#1B2A4A), Rot (#D63031), Weiss/Offwhite (#F8F9FB)  
**Fonts:** Outfit (Body) + Fraunces (Display) via Google Fonts  
**WICHTIG:** Kein "CrossFit" verwenden – markenrechtlich geschuetzt. Alternativen: Funktionelles Training, Functional Fitness, HIIT  
**WICHTIG:** JEDES Element MUSS mobile-/smartphone-optimiert sein, auch wenn nicht explizit erwaehnt

---

## 1. SEITENSTRUKTUR (HTML5 Semantik)

```
<!DOCTYPE html>
<html lang="de">
<head>
  <!-- Meta, Fonts, CSS, Schema Markup (JSON-LD) -->
</head>
<body>
  <nav>         <!-- Sticky Jump-Links Navigation -->
  <header>      <!-- Hero-Bereich -->
  <main>
    <aside>     <!-- Sidebar Filter (Desktop) / Drawer (Mobile) -->
    <section>   <!-- Autoren-Grid mit Cards -->
    <section>   <!-- Produkt-Referenzen der Autoren -->
  </main>
  <section>     <!-- Redaktionelle Prinzipien / E-E-A-T -->
  <footer>      <!-- Minimaler Footer -->
  <div>         <!-- Compare-Bar (fixed bottom) -->
  <dialog>      <!-- Compare-Modal -->
  <dialog>      <!-- Detail-Modal -->
</body>
</html>
```

---

## 2. HEAD / META / SEO

### 2.1 Title Tag
```html
<title>Unser Team – 60 Fachautoren bei Test-Vergleiche.com | Experten-Redaktion</title>
```
- Max 60 Zeichen, Hauptkeyword vorne, Brand hinten

### 2.2 Meta Description
```html
<meta name="description" content="Lernen Sie die 60 Fachautoren von Test-Vergleiche.com kennen. Unabhaengige Experten fuer Technik, Nachhaltigkeit, Sport, Ernaehrung und mehr. Finden Sie den richtigen Experten fuer Ihre Kaufentscheidung.">
```
- Max 155 Zeichen, CTA enthalten, Keywords natuerlich eingebaut

### 2.3 Open Graph / Social
```html
<meta property="og:title" content="Unser Team – Experten-Redaktion | Test-Vergleiche.com">
<meta property="og:description" content="60 unabhaengige Fachautoren bewerten Produkte transparent und kompetent.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://test-vergleiche.com/unser-team/">
<meta property="og:image" content="https://test-vergleiche.com/wp-content/uploads/team-og-image.webp">
<meta property="og:locale" content="de_DE">
<meta property="og:site_name" content="Test-Vergleiche.com">
```

### 2.4 Canonical + Hreflang
```html
<link rel="canonical" href="https://test-vergleiche.com/unser-team/">
```

### 2.5 Weitere Meta
```html
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
```

---

## 3. SCHEMA MARKUP (JSON-LD)

### 3.1 Organization Schema (Haupt-Schema)
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Test-Vergleiche.com",
  "url": "https://test-vergleiche.com",
  "logo": "https://test-vergleiche.com/wp-content/uploads/2023/12/test-vergleiche-com-logo.webp",
  "description": "Unabhaengiges Vergleichsportal seit 2016. 60 Fachautoren bewerten Produkte anhand von Herstellerangaben, Kundenbewertungen und Testergebnissen Dritter.",
  "foundingDate": "2016",
  "numberOfEmployees": {
    "@type": "QuantitativeValue",
    "value": 60
  },
  "member": [
    {
      "@type": "Person",
      "name": "Tobias W.",
      "url": "https://test-vergleiche.com/team/tobias-w/",
      "image": "https://test-vergleiche.com/wp-content/uploads/2023/12/Tobias-W-100x100.webp",
      "jobTitle": "Fachautor",
      "worksFor": {
        "@type": "Organization",
        "name": "Test-Vergleiche.com"
      },
      "knowsAbout": ["Physik", "Astrophysik", "Outdoor-Fitness", "High-End-Audio"]
    }
  ]
}
```
**WICHTIG:** Fuer JEDEN Autor ein `member`-Objekt anlegen mit `name`, `url`, `image`, `jobTitle`, `knowsAbout`.

### 3.2 BreadcrumbList Schema
```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Startseite", "item": "https://test-vergleiche.com/" },
    { "@type": "ListItem", "position": 2, "name": "Unser Team", "item": "https://test-vergleiche.com/unser-team/" }
  ]
}
```

### 3.3 WebPage Schema
```json
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "Unser Team – Test-Vergleiche.com",
  "description": "Die Fachautoren hinter Test-Vergleiche.com",
  "url": "https://test-vergleiche.com/unser-team/",
  "isPartOf": {
    "@type": "WebSite",
    "name": "Test-Vergleiche.com",
    "url": "https://test-vergleiche.com"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Test-Vergleiche.com"
  }
}
```

### 3.4 ItemList Schema (fuer Autoren-Listing)
```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Fachautoren bei Test-Vergleiche.com",
  "numberOfItems": 17,
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "item": {
        "@type": "Person",
        "name": "Tobias W.",
        "url": "https://test-vergleiche.com/team/tobias-w/"
      }
    }
  ]
}
```

### 3.5 Product Schema (fuer Produkt-Referenzen-Bereich)
Jede Produktkarte im Referenz-Bereich bekommt:
```json
{
  "@type": "Product",
  "name": "Produktname",
  "image": "produkt-bild-url",
  "description": "Kurzbeschreibung",
  "brand": { "@type": "Brand", "name": "Markenname" },
  "review": {
    "@type": "Review",
    "author": {
      "@type": "Person",
      "name": "Tobias W.",
      "url": "https://test-vergleiche.com/team/tobias-w/"
    },
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "4.8",
      "bestRating": "5"
    },
    "publisher": {
      "@type": "Organization",
      "name": "Test-Vergleiche.com"
    }
  },
  "offers": {
    "@type": "AggregateOffer",
    "priceCurrency": "EUR",
    "lowPrice": "29.99",
    "offerCount": "5"
  }
}
```

---

## 4. DETAILLIERTE KOMPONENTEN

### 4.1 Sticky Jump-Links Navigation

```html
<nav class="team-nav" aria-label="Seitennavigation">
  <div class="team-nav__inner">
    <a href="#autoren" class="team-nav__link team-nav__link--active">Alle Autoren</a>
    <a href="#themen" class="team-nav__link">Nach Themen</a>
    <a href="#produkt-referenzen" class="team-nav__link">Produktempfehlungen</a>
    <a href="https://test-vergleiche.com/wie-wir-arbeiten/" class="team-nav__link">So arbeiten wir</a>
    <a href="https://test-vergleiche.com/impressum/" class="team-nav__link">Kontakt</a>
  </div>
</nav>
```

**CSS-Anforderungen:**
- `position: sticky; top: 0; z-index: 100;`
- Weisser Hintergrund, 1px bottom border
- Horizontal scrollbar auf Mobile (kein Zeilenumbruch)
- Aktiver Link: Navy Farbe + roter Bottom-Border (2px)
- `scrollbar-width: none` / `::-webkit-scrollbar { display: none }`

**Mobile:**
- Font-size: 13px, Padding reduziert
- Touch-scrollbar versteckt aber funktional

---

### 4.2 Hero-Bereich

```html
<header class="hero">
  <div class="hero__inner">
    <div class="hero__text">
      <span class="hero__eyebrow">Experten-Redaktion</span>
      <h1>Unser Team von Test-Vergleiche.com</h1>
      <p class="hero__subtitle">
        Engagierte Autoren und Fachexperten, die Sie bei der Auswahl 
        der besten Produkte unabhaengig und kompetent unterstuetzen.
      </p>
    </div>
    <div class="hero__stats">
      <div class="hero__stat">
        <span class="hero__stat-number">60</span>
        <span class="hero__stat-label">Fachautoren</span>
      </div>
      <div class="hero__stat">
        <span class="hero__stat-number">20.000+</span>
        <span class="hero__stat-label">Vergleiche</span>
      </div>
      <div class="hero__stat">
        <span class="hero__stat-number">120.000</span>
        <span class="hero__stat-label">Produkte</span>
      </div>
    </div>
  </div>
</header>
```

**Design:**
- Hintergrund: Navy (#1B2A4A)
- H1: Fraunces, clamp(28px, 4vw, 42px), weiss
- Eyebrow: Rot (#D63031), uppercase, letter-spacing 0.08em
- Stats: Glasmorphism-Karten (rgba white bg mit border)
- Layout Desktop: Flexbox, Text links, Stats rechts
- Layout Mobile: Stack vertikal, Stats horizontal scrollbar oder 2x2 Grid

**SEO-Hinweise:**
- Nur EINE H1 pro Seite
- H1 enthaelt Hauptkeyword "Unser Team" + Marke
- Eyebrow ist `<span>`, NICHT heading

---

### 4.3 Sidebar-Filter (Desktop) / Drawer (Mobile)

```html
<aside class="sidebar" id="sidebar" aria-label="Filter">
  <!-- Suchfeld -->
  <div class="sidebar__section">
    <label class="sidebar__title" for="searchInput">Suche</label>
    <div class="sidebar__search">
      <svg><!-- Lupe Icon --></svg>
      <input type="search" id="searchInput" class="sidebar__input" 
             placeholder="Name oder Thema..." aria-label="Autoren durchsuchen">
    </div>
  </div>
  
  <!-- Themen-Filter -->
  <div class="sidebar__section">
    <h2 class="sidebar__title">Themen</h2>
    <ul class="sidebar__filter-list" role="listbox" aria-label="Themen filtern">
      <li class="sidebar__filter-item sidebar__filter-item--active" 
          data-filter="all" role="option" aria-selected="true">
        <span class="sidebar__filter-dot"></span>
        Alle
        <span class="sidebar__filter-count">17</span>
      </li>
      <li class="sidebar__filter-item" data-filter="nachhaltigkeit" role="option">
        <span class="sidebar__filter-dot"></span>
        Nachhaltigkeit
        <span class="sidebar__filter-count">12</span>
      </li>
      <!-- Weitere: Outdoor, Technik, Ernaehrung, Wellness, Mode, Sport, Reisen -->
    </ul>
  </div>
  
  <!-- Sortierung -->
  <div class="sidebar__section">
    <label class="sidebar__title" for="sortSelect">Sortierung</label>
    <select id="sortSelect" class="sidebar__sort-select">
      <option value="az">A – Z</option>
      <option value="za">Z – A</option>
    </select>
  </div>
</aside>
```

**Desktop (min-width: 901px):**
- Breite: 260px, `position: sticky; top: 64px;`
- Max-height: `calc(100vh - 80px)`, overflow-y: auto
- Weisse Karten mit subtle Shadow je Section

**Mobile (max-width: 900px):**
- `position: fixed; left: 0; top: 0; bottom: 0;`
- `transform: translateX(-100%)` → `.sidebar--open: translateX(0)`
- Backdrop-Overlay (rgba Navy 40%), klickbar zum Schliessen
- Drawer-Toggle-Button im Content-Bereich sichtbar
- `z-index: 250`

**Filter-Items:**
- Jeder Eintrag zeigt farbigen Dot (grau default, rot bei aktiv)
- Count-Zahl rechts (aus Daten berechnet)
- Hover: leichtes Grau-BG
- Aktiv: Navy-Pale BG, Navy Text, fetter Font

---

### 4.4 Autoren-Grid (Hauptbereich)

**Grid-Layout:**
- Desktop (>=1101px): `grid-template-columns: repeat(3, 1fr)`
- Tablet (901-1100px): `repeat(2, 1fr)`
- Mobile (<=900px): `1fr`
- Gap: 20px (Desktop), 14px (Mobile)

**Autoren-Card Struktur:**
```html
<article class="author-card" data-id="tobias-w" itemscope itemtype="https://schema.org/Person">
  <!-- Compare Button -->
  <button class="author-card__compare" aria-label="Zum Vergleich hinzufuegen"
          data-author-id="tobias-w">
    <svg><!-- Vergleich-Icon --></svg>
  </button>
  
  <!-- Klickbarer Header -->
  <div class="author-card__top" role="button" tabindex="0" 
       aria-label="Details zu Tobias W. anzeigen">
    <img class="author-card__photo" 
         src="tobias-w-100x100.webp" 
         alt="Tobias W. – Fachautor bei Test-Vergleiche.com"
         width="64" height="64" loading="lazy"
         itemprop="image">
    <div class="author-card__info">
      <h3 class="author-card__name" itemprop="name">Tobias W.</h3>
      <p class="author-card__role" itemprop="jobTitle">Fachautor</p>
    </div>
  </div>
  
  <!-- Beschreibung -->
  <div class="author-card__body">
    <p class="author-card__desc" itemprop="description">
      Ein Kenner der angewandten Physik und Astrophysik...
    </p>
    <button class="author-card__toggle" aria-expanded="false">mehr</button>
  </div>
  
  <!-- Tags -->
  <div class="author-card__tags">
    <span class="author-card__tag" itemprop="knowsAbout">Technik</span>
    <span class="author-card__tag" itemprop="knowsAbout">Outdoor</span>
    <span class="author-card__tag" itemprop="knowsAbout">Audio</span>
  </div>
  
  <!-- CTAs -->
  <div class="author-card__footer">
    <a href="/team/tobias-w/" class="author-card__cta author-card__cta--primary"
       itemprop="url">Zum Profil</a>
    <button class="author-card__cta author-card__cta--ghost"
            aria-label="Schnellansicht Tobias W.">Details</button>
  </div>
  
  <link itemprop="sameAs" href="https://test-vergleiche.com/team/tobias-w/">
</article>
```

**Card Design:**
- Background: Weiss, Border-radius: 18px
- Border: 1px solid gray-100, hover: gray-200
- Shadow: subtle (sm), hover: medium
- Hover: `transform: translateY(-2px)`
- Foto: 64x64px, border-radius 14px (abgerundet, nicht rund)
- Name: Fraunces, 17px, 600, Navy
- Beschreibung: 13.5px, gray-600, `-webkit-line-clamp: 2`
- Tags: Kleine Chips (11px), gray-50 bg, gray-200 border, rounded full
- Buttons: Navy primary (volle Breite), Ghost outline

**Compare-Button:**
- Oben rechts (absolute), 32x32px
- Icon: Vergleich-Pfeile SVG
- Default: grauer Border, weisser BG
- Active: Navy BG, weisser Icon-Stroke
- z-index: 2

---

### 4.5 PRODUKT-REFERENZEN BEREICH (NEU & WICHTIG)

Dieser Bereich zeigt Produkte, die von den Team-Autoren empfohlen/getestet wurden.  
Er erscheint UNTERHALB des Autoren-Grids als eigene Section.

#### 4.5.1 Section-Header

```html
<section class="product-refs" id="produkt-referenzen" aria-labelledby="product-refs-title">
  <div class="product-refs__header">
    <div class="product-refs__header-text">
      <h2 class="product-refs__title" id="product-refs-title">
        Aktuelle Produktempfehlungen unserer Autoren
      </h2>
      <p class="product-refs__subtitle">
        Von unseren Fachautoren getestet und empfohlen – unabhaengig und transparent.
      </p>
    </div>
    <!-- Kategorie-Tabs -->
    <div class="product-refs__tabs" role="tablist">
      <button class="product-refs__tab product-refs__tab--active" 
              role="tab" aria-selected="true" data-category="all">
        Alle
      </button>
      <button class="product-refs__tab" role="tab" data-category="technik">
        Technik
      </button>
      <button class="product-refs__tab" role="tab" data-category="sport">
        Sport &amp; Fitness
      </button>
      <button class="product-refs__tab" role="tab" data-category="haushalt">
        Haushalt
      </button>
      <button class="product-refs__tab" role="tab" data-category="beauty">
        Beauty &amp; Pflege
      </button>
      <button class="product-refs__tab" role="tab" data-category="outdoor">
        Outdoor
      </button>
    </div>
  </div>
```

#### 4.5.2 Produkt-Karten Grid

```html
  <div class="product-refs__grid">
    <!-- PRODUKT-KARTE -->
    <article class="product-card" itemscope itemtype="https://schema.org/Product">
      <!-- Badge (Vergleichssieger / Bestseller / Empfehlung) -->
      <div class="product-card__badge product-card__badge--winner">
        Vergleichssieger
      </div>
      
      <!-- Bild -->
      <div class="product-card__image-wrap">
        <img class="product-card__image" 
             src="produkt-bild.webp" 
             alt="Produktname – Testsieger bei Test-Vergleiche.com"
             width="280" height="200" loading="lazy"
             itemprop="image">
      </div>
      
      <!-- Kategorie-Label -->
      <span class="product-card__category">Laufband</span>
      
      <!-- Name -->
      <h3 class="product-card__name" itemprop="name">
        Sportstech F37 Profi Laufband
      </h3>
      
      <!-- Bewertung -->
      <div class="product-card__rating" itemprop="aggregateRating" 
           itemscope itemtype="https://schema.org/AggregateRating">
        <div class="product-card__stars" aria-label="4.8 von 5 Sternen">
          <!-- 5 SVG Sterne, gefuellt nach Rating -->
          <svg><!-- Stern 1 gefuellt --></svg>
          <svg><!-- Stern 2 gefuellt --></svg>
          <svg><!-- Stern 3 gefuellt --></svg>
          <svg><!-- Stern 4 gefuellt --></svg>
          <svg><!-- Stern 5 halb --></svg>
        </div>
        <span class="product-card__rating-value" itemprop="ratingValue">4.8</span>
        <span class="product-card__rating-count">
          (<span itemprop="reviewCount">245</span> Bewertungen)
        </span>
        <meta itemprop="bestRating" content="5">
        <meta itemprop="worstRating" content="1">
      </div>
      
      <!-- Highlights / Features -->
      <ul class="product-card__features">
        <li class="product-card__feature">
          <svg><!-- Check Icon --></svg>
          7 PS Motor, bis 20 km/h
        </li>
        <li class="product-card__feature">
          <svg><!-- Check Icon --></svg>
          Klappbar, platzsparend
        </li>
        <li class="product-card__feature">
          <svg><!-- Check Icon --></svg>
          App-Steuerung via Bluetooth
        </li>
      </ul>
      
      <!-- Autor-Referenz -->
      <div class="product-card__author">
        <img class="product-card__author-img" 
             src="tobias-w-100x100.webp" 
             alt="Tobias W." width="28" height="28" loading="lazy">
        <div class="product-card__author-info">
          <span class="product-card__author-label">Getestet von</span>
          <a href="/team/tobias-w/" class="product-card__author-name"
             itemprop="author" itemscope itemtype="https://schema.org/Person">
            <span itemprop="name">Tobias W.</span>
          </a>
        </div>
      </div>
      
      <!-- Preis -->
      <div class="product-card__price" itemprop="offers" 
           itemscope itemtype="https://schema.org/Offer">
        <span class="product-card__price-current" itemprop="price" content="599.99">
          599,99 EUR
        </span>
        <meta itemprop="priceCurrency" content="EUR">
        <meta itemprop="availability" content="https://schema.org/InStock">
        <span class="product-card__price-old">699,99 EUR</span>
      </div>
      
      <!-- CTAs -->
      <div class="product-card__actions">
        <a href="/laufband-test/" class="product-card__btn product-card__btn--primary">
          Zum Vergleich
        </a>
        <a href="#" class="product-card__btn product-card__btn--secondary"
           rel="nofollow sponsored">
          Zum Angebot
        </a>
      </div>
    </article>
    <!-- Weitere Produkt-Karten -->
  </div>
  
  <!-- Mehr laden -->
  <div class="product-refs__more">
    <a href="https://test-vergleiche.com/sitemap-pages/" 
       class="product-refs__more-btn">
      Alle 20.000+ Vergleiche ansehen
    </a>
  </div>
</section>
```

#### 4.5.3 Produkt-Card Design-Spezifikationen

**Card Container:**
```css
.product-card {
  background: var(--white);
  border-radius: 16px;
  border: 1px solid var(--gray-100);
  box-shadow: 0 1px 3px rgba(27,42,74,0.04), 0 4px 16px rgba(27,42,74,0.03);
  padding: 0;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: all 0.25s ease;
  position: relative;
}
.product-card:hover {
  box-shadow: 0 4px 12px rgba(27,42,74,0.08), 0 12px 36px rgba(27,42,74,0.06);
  transform: translateY(-3px);
}
```

**Badge:**
```css
.product-card__badge {
  position: absolute;
  top: 12px;
  left: 12px;
  z-index: 2;
  padding: 4px 12px;
  border-radius: 100px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.product-card__badge--winner {
  background: #FEF3C7;  /* Warm Gold */
  color: #92400E;
  border: 1px solid #FCD34D;
}
.product-card__badge--bestseller {
  background: var(--red);
  color: white;
}
.product-card__badge--empfehlung {
  background: var(--navy-pale);
  color: var(--navy);
  border: 1px solid rgba(27,42,74,0.1);
}
```

**Bild-Bereich:**
```css
.product-card__image-wrap {
  background: var(--gray-50);
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 180px;
  border-bottom: 1px solid var(--gray-100);
}
.product-card__image {
  max-width: 100%;
  max-height: 160px;
  object-fit: contain;
}
```

**Features-Liste:**
```css
.product-card__features {
  list-style: none;
  padding: 0 20px;
  margin: 12px 0;
}
.product-card__feature {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 13px;
  color: var(--gray-600);
  padding: 4px 0;
  line-height: 1.5;
}
.product-card__feature svg {
  width: 16px;
  height: 16px;
  color: #10B981; /* Gruen fuer Check */
  flex-shrink: 0;
  margin-top: 2px;
}
```

**Autor-Referenz:**
```css
.product-card__author {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: var(--gray-50);
  border-top: 1px solid var(--gray-100);
  border-bottom: 1px solid var(--gray-100);
}
.product-card__author-img {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  object-fit: cover;
}
.product-card__author-label {
  font-size: 11px;
  color: var(--gray-400);
  display: block;
}
.product-card__author-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--navy);
  text-decoration: none;
}
.product-card__author-name:hover {
  text-decoration: underline;
}
```

**Preis:**
```css
.product-card__price {
  padding: 12px 20px 0;
  display: flex;
  align-items: baseline;
  gap: 8px;
}
.product-card__price-current {
  font-size: 22px;
  font-weight: 700;
  font-family: var(--font-display);
  color: var(--navy);
}
.product-card__price-old {
  font-size: 14px;
  color: var(--gray-400);
  text-decoration: line-through;
}
```

**Action Buttons:**
```css
.product-card__actions {
  padding: 14px 20px 20px;
  display: flex;
  gap: 8px;
}
.product-card__btn {
  flex: 1;
  text-align: center;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
  border: none;
  cursor: pointer;
}
.product-card__btn--primary {
  background: var(--navy);
  color: white;
}
.product-card__btn--primary:hover {
  background: var(--navy-light);
}
.product-card__btn--secondary {
  background: var(--red);
  color: white;
}
.product-card__btn--secondary:hover {
  background: var(--red-hover);
}
```

#### 4.5.4 Produkt-Grid Responsive

```css
.product-refs__grid {
  display: grid;
  gap: 20px;
}

/* Mobile */
@media (max-width: 640px) {
  .product-refs__grid {
    grid-template-columns: 1fr;
    gap: 14px;
  }
}

/* Tablet */
@media (min-width: 641px) and (max-width: 1024px) {
  .product-refs__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Desktop */
@media (min-width: 1025px) {
  .product-refs__grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Grosser Desktop */
@media (min-width: 1400px) {
  .product-refs__grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

#### 4.5.5 Kategorie-Tabs Design

```css
.product-refs__tabs {
  display: flex;
  gap: 6px;
  overflow-x: auto;
  scrollbar-width: none;
  padding-bottom: 4px;
  -webkit-overflow-scrolling: touch;
}
.product-refs__tabs::-webkit-scrollbar { display: none; }

.product-refs__tab {
  display: inline-flex;
  padding: 8px 18px;
  border: 1.5px solid var(--gray-200);
  border-radius: 100px;
  font-size: 13px;
  font-weight: 500;
  color: var(--gray-600);
  background: var(--white);
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.2s ease;
}
.product-refs__tab:hover {
  border-color: var(--navy);
  color: var(--navy);
}
.product-refs__tab--active {
  background: var(--navy);
  border-color: var(--navy);
  color: white;
}
```

---

### 4.6 Vergleichs-Funktion (Compare)

**Compare-Bar (Sticky Bottom):**
- Position: fixed, bottom: 0
- `transform: translateY(100%)` → `--visible: translateY(0)`
- Roter Top-Border (2px)
- Max 3 Autoren
- Pill-Avatare mit Name + X-Button
- Buttons: "Vergleichen" (Rot, disabled bei <2) + "Leeren" (Grau)

**Compare-Modal:**
- Overlay: Navy 50% + backdrop-blur
- Modal: Weiss, max-width 860px, border-radius 18px
- Spalten-Layout: grid-template-columns basierend auf Anzahl (2 oder 3)
- Jede Spalte: Foto, Name, Tags, Kurztext, Profil-Link
- Scrollbar bei Overflow

**Mobile Compare:**
- Bar: volle Breite, gestackt (Avatare oben, Buttons unten)
- Modal: fast Fullscreen (margin: 16px), scrollbar

---

### 4.7 Detail-Modal (Autor Quick-View)

```html
<dialog class="detail-overlay" id="detailOverlay">
  <div class="detail-modal" role="dialog" aria-modal="true"
       aria-labelledby="detail-name">
    <button class="detail-modal__close" aria-label="Schliessen">&times;</button>
    
    <div class="detail-modal__top">
      <img class="detail-modal__photo" 
           src="" alt="" width="90" height="90" loading="lazy">
      <div>
        <h2 class="detail-modal__name" id="detail-name"></h2>
        <p class="detail-modal__role">Autor bei Test-Vergleiche.com</p>
      </div>
    </div>
    
    <div class="detail-modal__body">
      <p class="detail-modal__bio"></p>
      <p class="detail-modal__label">Schwerpunkte</p>
      <div class="detail-modal__tags"></div>
      <div class="detail-modal__actions">
        <a href="" class="detail-modal__btn detail-modal__btn--primary">Zum Profil</a>
        <button class="detail-modal__btn detail-modal__btn--outline">Artikel ansehen</button>
      </div>
    </div>
  </div>
</dialog>
```

**Design:**
- Max-width: 540px
- Foto: 90x90px, border-radius: 20px (abgerundet quadratisch)
- Body: padding 20px 28px 28px
- Labels: 11px uppercase, gray-400
- Actions: 2 Buttons nebeneinander, flex

**Mobile:**
- Margin: 16px
- Full-width Buttons stacked

---

### 4.8 Redaktionelle Prinzipien (E-E-A-T)

```html
<section class="principles" aria-labelledby="principles-title">
  <div class="principles__inner">
    <div class="principles__text-wrap">
      <h2 class="principles__title" id="principles-title">
        Unsere redaktionellen Prinzipien
      </h2>
      <p class="principles__text">
        Unabhaengiges Vergleichsportal seit 2016 – transparent und 
        ohne Herstellereinfluss.
      </p>
    </div>
    <a href="https://test-vergleiche.com/wie-wir-arbeiten/" 
       class="principles__link">
      So arbeiten wir &rarr;
    </a>
  </div>
</section>
```

---

## 5. SEO CHECKLISTE

### 5.1 On-Page SEO
- [x] Einzige H1: "Unser Team von Test-Vergleiche.com"
- [x] H2 Struktur: "Themen" (Sidebar), "Aktuelle Produktempfehlungen unserer Autoren", "Unsere redaktionellen Prinzipien"
- [x] H3: Autorennamen in Cards, Produktnamen in Produkt-Cards
- [x] Alt-Texte: Beschreibend mit Kontext (z.B. "Tobias W. – Fachautor bei Test-Vergleiche.com")
- [x] Interne Verlinkung: Jeder Autor → Profil-Seite, jedes Produkt → Vergleichsseite
- [x] Semantisches HTML: article, section, nav, aside, header, main
- [x] Breadcrumb: Startseite > Unser Team

### 5.2 Technische SEO
- [x] Canonical Tag
- [x] Meta Robots: index, follow
- [x] Responsive (Mobile-First)
- [x] Loading="lazy" fuer Bilder unterhalb des Folds
- [x] Width/Height Attribute auf allen Bildern (kein CLS)
- [x] WebP Bildformat
- [x] Schema Markup: Organization, Person, BreadcrumbList, AboutPage, ItemList, Product

### 5.3 E-E-A-T Signale
- [x] Autorennamen sichtbar und verlinkt
- [x] Autorenbilder vorhanden
- [x] Expertise-Tags/Skills pro Autor
- [x] "knowsAbout" im Schema Markup
- [x] Verlinkung zu individuellen Autorenprofilen
- [x] "So arbeiten wir" prominent verlinkt
- [x] Statistiken zur Glaubwuerdigkeit (60 Fachautoren, 20.000+ Vergleiche)
- [x] Klare Kennzeichnung: "Unabhaengig", "Kein Herstellereinfluss"
- [x] Autorenreferenz bei Produktempfehlungen ("Getestet von [Name]")

### 5.4 Performance
- [x] Fonts: display=swap, preconnect
- [x] CSS: Inline Critical CSS, rest defer
- [x] JS: Vanilla JS only, kein jQuery/Framework
- [x] Bilder: WebP, width/height, lazy loading
- [x] Kein Layout Shift (explizite Dimensions)

### 5.5 Accessibility
- [x] ARIA Labels auf interaktiven Elementen
- [x] role="dialog" + aria-modal auf Modals
- [x] role="tablist" + role="tab" auf Tabs
- [x] Fokus-Management beim Oeffnen/Schliessen von Modals
- [x] Escape-Key schliesst Modals
- [x] Skip-to-content Link
- [x] Kontrast-Ratio >= 4.5:1 fuer Text
- [x] Touch-Targets >= 44px auf Mobile

---

## 6. AUTOREN-DATEN (PLATZHALTER-STRUKTUR)

Fuer die WordPress-Integration als JS-Objekt oder PHP-Array:

```javascript
const authors = [
  {
    id: 'tobias-w',
    name: 'Tobias W.',
    photo: '/wp-content/uploads/2023/12/Tobias-W-100x100.webp',
    profileUrl: '/team/tobias-w/',
    desc: 'Ein Kenner der angewandten Physik und Astrophysik, begeistert sich fuer Trailrunning und Outdoor-Fitness und hat ein tiefes Interesse an High-End-Audio und dystopischer Literatur.',
    tags: ['Technik', 'Outdoor', 'Audio', 'Physik']
  },
  {
    id: 'simon-s',
    name: 'Simon S.',
    photo: '/wp-content/uploads/2023/12/Simon-S-100x100.webp',
    profileUrl: '/team/simon-s/',
    desc: 'Spezialisiert auf digitale Transformation und IT-Sicherheit, mit einer Leidenschaft fuer Mountainbiking und Extremsportarten sowie einem starken Interesse an Jazzmusik und kognitiver Psychologie.',
    tags: ['Technik', 'Sport', 'IT-Sicherheit']
  },
  {
    id: 'sophie-b',
    name: 'Sophie B.',
    photo: '/wp-content/uploads/2023/12/Sophie-B-100x100.webp',
    profileUrl: '/team/sophie-b/',
    desc: 'Fokussiert sich auf nachhaltige Mode und Ethik in der Textilindustrie, hat eine Leidenschaft fuer vegetarische Ernaehrung und Reisefotografie und engagiert sich in Themen rund um Wellness und Naturheilkunde.',
    tags: ['Nachhaltigkeit', 'Mode', 'Ernaehrung', 'Wellness', 'Reisen']
  },
  {
    id: 'tim-h',
    name: 'Tim H.',
    photo: '/wp-content/uploads/2023/12/Tim-H-100x100.webp',
    profileUrl: '/team/tim-h/',
    desc: 'Kombiniert seine Expertise in erneuerbaren Energien und Umwelttechnik mit einer Begeisterung fuer Trailrunning und Outdoor-Fitness, ergaenzt durch ein starkes Interesse an digitalen Medien und kognitiver Psychologie.',
    tags: ['Nachhaltigkeit', 'Outdoor', 'Technik']
  },
  {
    id: 'sebastian-b',
    name: 'Sebastian B.',
    photo: '/wp-content/uploads/2023/12/Sebastian-B-100x100.webp',
    profileUrl: '/team/sebastian-b/',
    desc: 'Ein Experte in Hochleistungssport und Athletiktraining, verbindet seine sportlichen Interessen mit einem tiefgehenden Wissen in nachhaltiger Architektur. Zudem hat er eine besondere Leidenschaft fuer Gourmetkueche und Food-Pairing.',
    tags: ['Sport', 'Nachhaltigkeit', 'Ernaehrung']
  },
  {
    id: 'sarah-m',
    name: 'Sarah M.',
    photo: '/wp-content/uploads/2023/12/Sarah-M-100x100.webp',
    profileUrl: '/team/sarah-m/',
    desc: 'Eine engagierte Autorin, die sich auf oekologische Nachhaltigkeit und Umweltbildung konzentriert. Ihre Artikel reflektieren ihre Leidenschaft fuer vegetarische und vegane Lebensweisen sowie ihre Liebe zur Reisefotografie und globalen Kulturen.',
    tags: ['Nachhaltigkeit', 'Ernaehrung', 'Reisen']
  },
  {
    id: 'maximilian-b',
    name: 'Maximilian B.',
    photo: '/wp-content/uploads/2023/12/Maximilian-B-100x100.webp',
    profileUrl: '/team/maximilian-b/',
    desc: 'Ein Autor mit einem starken Fokus auf erneuerbare Energien und Astrophysik, der seine Freizeit leidenschaftlich dem Bergsteigen und Outdoor-Fitness widmet. Versiert in High-End-Audio und Sound-Engineering.',
    tags: ['Nachhaltigkeit', 'Outdoor', 'Technik', 'Audio']
  },
  {
    id: 'marcel-r',
    name: 'Marcel R.',
    photo: '/wp-content/uploads/2023/12/Marcel-R-100x100.webp',
    profileUrl: '/team/marcel-r/',
    desc: 'Vereint profundes Wissen ueber Hochleistungssport und Training mit einer tiefen Begeisterung fuer innovative Kochtechniken und die Welt der Gourmetkueche. Starke Affinitaet fuer Kino und moderne Architektur.',
    tags: ['Sport', 'Ernaehrung', 'Nachhaltigkeit']
  },
  {
    id: 'marie-w',
    name: 'Marie W.',
    photo: '/wp-content/uploads/2023/12/Marie-W-100x100.webp',
    profileUrl: '/team/marie-w/',
    desc: 'Verbindet in ihren Beitraegen das Engagement fuer eine nachhaltige Lebensweise mit einer tiefen Hingabe an vegetarische Ernaehrung und Bio-Lebensmittel. Starkes Interesse an Reisefotografie und Innenarchitektur.',
    tags: ['Nachhaltigkeit', 'Ernaehrung', 'Reisen']
  },
  {
    id: 'lukas-s',
    name: 'Lukas S.',
    photo: '/wp-content/uploads/2023/12/Lukas-S-100x100.webp',
    profileUrl: '/team/lukas-s/',
    desc: 'Faszination fuer fortschrittliche Robotik und KI mit einer Vorliebe fuer Outdoor-Aktivitaeten und Extremsportarten. Engagement fuer Nachhaltigkeit und Leidenschaft fuer Elektromobilitaet und zukunftsorientierte Technologien.',
    tags: ['Technik', 'Outdoor', 'Sport', 'Nachhaltigkeit']
  },
  {
    id: 'lisa-r',
    name: 'Lisa R.',
    photo: '/wp-content/uploads/2023/12/Lisa-R-100x100.webp',
    profileUrl: '/team/lisa-r/',
    desc: 'Kombiniert Engagement fuer Umweltbewusstsein und Nachhaltigkeit mit einer Leidenschaft fuer Gourmet-Kueche und internationale Rezepte. Starkes Interesse an Reisejournalismus, moderner Kunst und einem ganzheitlichen Wellness-Ansatz.',
    tags: ['Nachhaltigkeit', 'Ernaehrung', 'Reisen', 'Wellness']
  },
  {
    id: 'lena-f',
    name: 'Lena F.',
    photo: '/wp-content/uploads/2023/12/Lena-F-100x100.webp',
    profileUrl: '/team/lena-f/',
    desc: 'Bringt ihre Leidenschaft fuer Mode-Design und urbane Architektur ein, ergaenzt durch ein tiefes Interesse an nachhaltigem Reisen und kulinarischen Entdeckungen. Hingabe an zeitgenoessisches Theater und Grafikdesign.',
    tags: ['Mode', 'Reisen', 'Ernaehrung', 'Nachhaltigkeit']
  },
  {
    id: 'laura-s',
    name: 'Laura S.',
    photo: '/wp-content/uploads/2023/12/Laura-S-100x100.webp',
    profileUrl: '/team/laura-s/',
    desc: 'Verbindet ein starkes Bewusstsein fuer Yoga und Wellness mit einem ausgespraegten Interesse an biologischem Kochen und nachhaltigen Lebensmitteln. Leidenschaft fuer Innenarchitektur und kreative Schreibkunst.',
    tags: ['Wellness', 'Ernaehrung', 'Nachhaltigkeit']
  },
  {
    id: 'julian-w',
    name: 'Julian W.',
    photo: '/wp-content/uploads/2023/12/Julian-W-100x100.webp',
    profileUrl: '/team/julian-w/',
    desc: 'Verknuepft Begeisterung fuer nachhaltige Technologien und Umweltschutz mit einer Vorliebe fuer Abenteuerreisen und Outdoor-Aktivitaeten. Kenner der Kaffee- und Teekultur.',
    tags: ['Nachhaltigkeit', 'Outdoor', 'Reisen', 'Technik']
  },
  {
    id: 'julia-s',
    name: 'Julia S.',
    photo: '/wp-content/uploads/2023/12/Julia-S-100x100.webp',
    profileUrl: '/team/julia-s/',
    desc: 'Kombiniert ein starkes Interesse an Innenarchitektur und Wohnkultur mit ihrer Begeisterung fuer gesunde Ernaehrung und Superfoods. Engagiert sich fuer Yoga und Achtsamkeit und bringt Erfahrungen aus der Welt des Reisens ein.',
    tags: ['Wellness', 'Ernaehrung', 'Reisen']
  },
  {
    id: 'frank-b',
    name: 'Frank B.',
    photo: '/wp-content/uploads/2023/12/Frank-B--100x100.webp',
    profileUrl: '/team/frank-b/',
    desc: 'Ein vielseitiger Autor, spezialisiert auf Heimautomation und Smart Home, sowie Reisen und Outdoor-Abenteuer. Starke Affinitaet zu Technik und Elektronik, verbunden mit einer Leidenschaft fuer Fotografie und Kunst.',
    tags: ['Technik', 'Outdoor', 'Reisen']
  },
  {
    id: 'florian-k',
    name: 'Florian K.',
    photo: '/wp-content/uploads/2023/12/Florian-K-100x100.webp',
    profileUrl: '/team/florian-k/',
    desc: 'Verbindet eine Leidenschaft fuer Elektronik und Computer mit einem ausgepraegte Interesse an Mode und Accessoires. Kenner von Heimwerken und Gartenarbeit, mit Erfahrungen in Haustierbedarf und Sport & Fitness.',
    tags: ['Technik', 'Mode', 'Sport']
  }
];
```

---

## 7. PRODUKT-REFERENZ-DATEN (PLATZHALTER-STRUKTUR)

```javascript
const productReferences = [
  {
    id: 'laufband-sportstech',
    name: 'Sportstech F37 Profi Laufband',
    image: '/wp-content/uploads/produkte/laufband-sportstech.webp',
    category: 'sport',
    badge: 'winner', // winner | bestseller | empfehlung
    rating: 4.8,
    reviewCount: 245,
    features: [
      '7 PS Motor, bis 20 km/h',
      'Klappbar, platzsparend',
      'App-Steuerung via Bluetooth'
    ],
    authorId: 'tobias-w',
    price: 599.99,
    priceOld: 699.99,
    compareUrl: '/laufband-test/',
    offerUrl: '#' // Affiliate-Link Platzhalter
  },
  {
    id: 'vitamin-c-serum',
    name: 'Junglück Vitamin C Serum',
    image: '/wp-content/uploads/produkte/vitamin-c-serum.webp',
    category: 'beauty',
    badge: 'bestseller',
    rating: 4.7,
    reviewCount: 1823,
    features: [
      '20% Vitamin C Konzentration',
      'Vegan & tierversuchsfrei',
      'Made in Germany'
    ],
    authorId: 'sophie-b',
    price: 24.99,
    priceOld: null,
    compareUrl: '/vitamin-c-serum/',
    offerUrl: '#'
  }
  // Weitere Produkte nach Bedarf
];
```

---

## 8. JAVASCRIPT-FUNKTIONALITAET

### 8.1 Implementierung als Vanilla JS

```
Erforderliche Features:
1. Autoren-Suche (clientseitig, sofort bei Eingabe)
2. Filter-Chips / Sidebar-Filter
3. Sortierung (A-Z, Z-A)
4. Compare-Bar (max 3 Autoren)
5. Compare-Modal
6. Detail-Modal (Autor Quick-View)
7. Produkt-Kategorie-Tabs
8. "mehr anzeigen" Toggle fuer Beschreibungen
9. Mobile Drawer Toggle
10. Escape-Key schliesst alle Modals
11. URL Hash-Updates bei Filter (optional, gut fuer SEO)
```

### 8.2 Event-Delegation Pattern
```javascript
// Verwende Event Delegation statt einzelner Event Listener
document.getElementById('teamGrid').addEventListener('click', (e) => {
  const card = e.target.closest('.author-card');
  const compareBtn = e.target.closest('.author-card__compare');
  const toggleBtn = e.target.closest('.author-card__toggle');
  // ... Handle je nach Target
});
```

### 8.3 Accessibility JS
```javascript
// Modal Fokus-Trap
function trapFocus(modal) {
  const focusable = modal.querySelectorAll('button, a, input, select, [tabindex]');
  const first = focusable[0];
  const last = focusable[focusable.length - 1];
  
  modal.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal(modal);
    if (e.key === 'Tab') {
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault(); last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault(); first.focus();
      }
    }
  });
}
```

---

## 9. CSS VARIABLEN (VOLLSTAENDIG)

```css
:root {
  /* Farben */
  --navy: #1B2A4A;
  --navy-light: #2C3E6B;
  --navy-pale: #EDF0F7;
  --red: #D63031;
  --red-hover: #B71C1C;
  --red-soft: #FFF0F0;
  --green: #10B981;
  --green-soft: #ECFDF5;
  --gold: #F59E0B;
  --gold-soft: #FEF3C7;
  --white: #FFFFFF;
  --off-white: #F8F9FB;
  --gray-50: #FAFBFC;
  --gray-100: #F3F4F6;
  --gray-200: #E5E7EB;
  --gray-300: #D1D5DB;
  --gray-400: #9CA3AF;
  --gray-500: #6B7280;
  --gray-600: #4B5563;
  --gray-700: #374151;
  --gray-800: #1F2937;
  
  /* Radien */
  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 18px;
  --radius-full: 100px;
  
  /* Schatten */
  --shadow-sm: 0 1px 2px rgba(27,42,74,0.04), 0 4px 16px rgba(27,42,74,0.04);
  --shadow-md: 0 2px 8px rgba(27,42,74,0.06), 0 8px 32px rgba(27,42,74,0.06);
  --shadow-lg: 0 4px 12px rgba(27,42,74,0.1), 0 16px 48px rgba(27,42,74,0.08);
  --shadow-bar: 0 -4px 24px rgba(27,42,74,0.12);
  
  /* Fonts */
  --font-body: 'Outfit', sans-serif;
  --font-display: 'Fraunces', serif;
  
  /* Transitions */
  --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  --transition-fast: 0.15s ease;
  
  /* Layout */
  --max-width: 1360px;
  --sidebar-width: 260px;
  --gap: 28px;
}
```

---

## 10. MOBILE BREAKPOINTS

```css
/* Mobile First */
/* Base: 0 - 640px (Smartphone) */
/* SM:   641px - 768px (Grosses Smartphone / kleines Tablet) */
/* MD:   769px - 900px (Tablet) */
/* LG:   901px - 1100px (Kleiner Desktop / Tablet Landscape) */
/* XL:   1101px - 1400px (Desktop) */
/* 2XL:  1401px+ (Grosser Desktop) */

@media (max-width: 640px) {
  /* Smartphone: 1 Spalte Grid, Sidebar als Drawer */
  /* Hamburger-Menu fuer Filter */
  /* Stacked Buttons, volle Breite */
  /* Font-sizes etwas kleiner */
  /* Padding: 16px statt 24px */
}

@media (min-width: 641px) and (max-width: 900px) {
  /* Tablet: 2 Spalten Grid, Sidebar noch Drawer */
}

@media (min-width: 901px) {
  /* Desktop: Sidebar permanent sichtbar, 3 Spalten Grid */
}

@media (min-width: 1101px) {
  /* Grosser Desktop: 3 Spalten Grid */
}

@media (min-width: 1401px) {
  /* Grosser Desktop: 4 Spalten Produkt-Grid */
}
```

---

## 11. BEM NAMENSKONVENTION

Alle Klassen folgen BEM (Block__Element--Modifier):

```
Bloecke:
- .team-nav        (Jump-Links Navigation)
- .hero            (Hero-Bereich)
- .sidebar         (Filter-Sidebar)
- .author-card     (Autoren-Karte)
- .product-refs    (Produkt-Referenzen Section)
- .product-card    (Produkt-Karte)
- .compare-bar     (Vergleichs-Leiste)
- .compare-modal   (Vergleichs-Modal)
- .detail-modal    (Detail-Modal)
- .principles      (Redaktionelle Prinzipien)

Modifier:
- --active         (aktiver Zustand)
- --visible        (sichtbar)
- --open           (geoeffnet, z.B. Drawer)
- --primary        (primaerer Button)
- --ghost          (Ghost-Button)
- --winner         (Vergleichssieger Badge)
- --bestseller     (Bestseller Badge)
- --empfehlung     (Empfehlung Badge)
```

---

## 12. KOMMENTAR-BEREICH

Die existierenden Kommentare werden im neuen Design NICHT im Hauptbereich angezeigt.  
Optional: Als ausklappbarer Bereich ganz unten im Footer, standardmaessig eingeklappt.

```html
<details class="comments-section">
  <summary class="comments-section__toggle">
    Kommentare anzeigen (2)
  </summary>
  <div class="comments-section__body">
    <!-- Existierende WordPress-Kommentare -->
  </div>
</details>
```

---

## 13. ZUSAMMENFASSUNG: WAS CLAUDE CODE TUN SOLL

1. **Erstelle ein vollstaendiges HTML-Dokument** basierend auf Mockup B mit allen oben beschriebenen Komponenten
2. **Eingebettetes CSS** (kein externes Stylesheet) mit allen CSS-Variablen und responsive Breakpoints
3. **Vanilla JS** fuer alle interaktiven Features (Suche, Filter, Compare, Modal, Tabs, Drawer)
4. **Schema Markup** als JSON-LD im `<head>` (Organization, BreadcrumbList, AboutPage, ItemList, Person-Array)
5. **Produkt-Referenzen-Bereich** mit modernem Card-Design, Kategorie-Tabs, Bewertungssternen, Autor-Referenz und Preis
6. **Product Schema** fuer jedes Produkt im Referenz-Bereich
7. **SEO Meta Tags** vollstaendig im `<head>`
8. **Accessibility** ARIA-Labels, Rollen, Fokus-Management
9. **BEM Klassen** durchgehend konsistent
10. **Mobile-First** responsive Design mit expliziten Breakpoints
11. **Keine externen Libraries** (kein jQuery, kein Bootstrap, kein Tailwind)
12. **Alle 17 Autoren** mit echten Daten (Namen, Bilder, URLs, Tags)
13. **6-8 Beispiel-Produkte** als Platzhalter im Produkt-Bereich
14. **NIEMALS "CrossFit"** verwenden

---

## 14. DATEIEN-OUTPUT

```
/unser-team/
  index.html          ← Vollstaendiges HTML mit eingebettetem CSS + JS
```

Alles in EINER Datei. Spaeter wird das in WordPress-Template aufgetrennt.
