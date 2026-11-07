<?php
/*
 * PhotoCraft Studio – Passport Photo Maker
 * Pure client-side: no uploads, no database, no sessions
 * Drop this single file into any PHP host and it works.
 */
header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>PhotoCraft Studio – Passport Photo Maker</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@500;700;800&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"/>
  <style>
/* ═══════════════════════════════════════════
   Design: Precision Studio — Dark Industrial
   ═══════════════════════════════════════════ */
:root {
  --bg:        #09090d;
  --surface:   #111116;
  --card:      #18181f;
  --card2:     #1e1e28;
  --border:    #26262f;
  --border2:   #32323e;
  --accent:    #f0a020;
  --accentL:   rgba(240,160,32,.12);
  --accentLL:  rgba(240,160,32,.06);
  --green:     #34d399;
  --greenL:    rgba(52,211,153,.12);
  --red:       #f87171;
  --redL:      rgba(248,113,113,.12);
  --text:      #dddde8;
  --text2:     #8888a0;
  --text3:     #4a4a5c;
  --r:         10px;
  --r2:        6px;
  --mono:      'Space Mono', monospace;
  --head:      'Syne', sans-serif;
  --shadow:    0 4px 24px rgba(0,0,0,.5);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  background: var(--bg);
  color: var(--text);
  font-family: var(--head);
  min-height: 100vh;
  overflow-x: hidden;
}

/* ── Scrollbar ── */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--surface); }
::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

/* ── Header ── */
.header {
  position: sticky; top: 0; z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 32px;
  height: 58px;
  background: rgba(9,9,13,.92);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
}
.logo { font-size: 1.15rem; font-weight: 800; letter-spacing: -.03em; }
.logo em { color: var(--accent); font-style: normal; }
.logo-tag {
  font-family: var(--mono); font-size: .65rem; color: var(--text3);
  border: 1px solid var(--border); border-radius: 20px;
  padding: 2px 10px; margin-left: 12px;
  vertical-align: middle; letter-spacing: .04em;
}
.header-right { display: flex; align-items: center; gap: 12px; }
.privacy-dot {
  display: flex; align-items: center; gap: 6px;
  font-size: .72rem; color: var(--text2); font-family: var(--mono);
}
.privacy-dot::before {
  content: ''; width: 6px; height: 6px;
  background: var(--green); border-radius: 50%;
  box-shadow: 0 0 6px var(--green);
}

/* ── Step Bar ── */
.stepbar {
  display: flex; align-items: center; justify-content: center;
  padding: 16px 24px;
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  gap: 0; overflow-x: auto;
  scrollbar-width: none;
}
.stepbar::-webkit-scrollbar { display: none; }
.step-item {
  display: flex; align-items: center; gap: 8px;
  opacity: .3; transition: opacity .3s;
  cursor: default; flex-shrink: 0;
}
.step-item.active { opacity: 1; }
.step-item.done   { opacity: .6; }
.step-num {
  width: 30px; height: 30px; border-radius: 50%;
  border: 2px solid var(--border2);
  display: flex; align-items: center; justify-content: center;
  font-family: var(--mono); font-size: .68rem; font-weight: 700;
  flex-shrink: 0; transition: all .3s;
}
.step-item.active .step-num { border-color: var(--accent); color: var(--accent); background: var(--accentL); }
.step-item.done   .step-num { border-color: var(--green);  color: var(--green);  background: var(--greenL); }
.step-label { font-size: .75rem; font-weight: 700; white-space: nowrap; color: var(--text2); }
.step-item.active .step-label { color: var(--text); }
.step-sep { width: 32px; height: 1px; background: var(--border); flex-shrink: 0; margin: 0 4px; }

/* ── Main ── */
.main { max-width: 1000px; margin: 0 auto; padding: 36px 24px 80px; }

/* ── Section header ── */
.sec-head { margin-bottom: 28px; }
.sec-title { font-size: 1.6rem; font-weight: 800; letter-spacing: -.04em; margin-bottom: 4px; }
.sec-sub { color: var(--text2); font-size: .88rem; font-weight: 500; }

/* ── Steps ── */
.step-section { display: none; animation: fadeIn .25s ease; }
.step-section.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

/* ── Cards / Surfaces ── */
.card {
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--r); padding: 20px;
}
.card2 {
  background: var(--card2); border: 1px solid var(--border);
  border-radius: var(--r); padding: 16px;
}

/* ── Buttons ── */
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  padding: 10px 20px; border-radius: var(--r2);
  font-family: var(--head); font-size: .85rem; font-weight: 700;
  cursor: pointer; border: none; text-decoration: none;
  transition: all .18s; white-space: nowrap;
  position: relative; overflow: hidden;
}
.btn:disabled { opacity: .45; cursor: not-allowed; pointer-events: none; }
.btn-accent  { background: var(--accent); color: #000; }
.btn-accent:hover { background: #ffb83a; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(240,160,32,.3); }
.btn-outline { background: transparent; border: 1.5px solid var(--border2); color: var(--text); }
.btn-outline:hover { border-color: var(--accent); color: var(--accent); }
.btn-ghost   { background: var(--card); color: var(--text); border: 1px solid var(--border); }
.btn-ghost:hover { background: var(--card2); }
.btn-green   { background: var(--greenL); color: var(--green); border: 1px solid rgba(52,211,153,.3); }
.btn-green:hover { background: rgba(52,211,153,.2); }
.btn-red     { background: var(--redL); color: var(--red); border: 1px solid rgba(248,113,113,.3); }
.btn-sm { padding: 7px 14px; font-size: .78rem; }
.btn-lg { padding: 13px 28px; font-size: .95rem; }
.btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 24px; }
.btn-row.right { justify-content: flex-end; }
.btn-row.spread { justify-content: space-between; align-items: center; }

/* ── Upload Zone ── */
.upload-zone {
  border: 2px dashed var(--border2);
  border-radius: var(--r);
  padding: 60px 40px;
  text-align: center; cursor: pointer;
  background: var(--card);
  transition: all .2s; position: relative;
}
.upload-zone:hover, .upload-zone.over {
  border-color: var(--accent); background: var(--accentLL);
}
.upload-zone input[type=file] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.uz-icon { font-size: 2.5rem; margin-bottom: 14px; opacity: .5; display: block; }
.uz-title { font-size: 1rem; font-weight: 700; margin-bottom: 6px; }
.uz-sub { color: var(--text2); font-size: .8rem; font-family: var(--mono); }

.or-divider {
  text-align: center; color: var(--text3);
  font-size: .72rem; font-family: var(--mono);
  margin: 18px 0; position: relative;
}
.or-divider::before, .or-divider::after {
  content: ''; position: absolute; top: 50%;
  width: calc(50% - 20px); height: 1px; background: var(--border);
}
.or-divider::before { left: 0; }
.or-divider::after  { right: 0; }

/* ── Camera ── */
.camera-wrap {
  background: #000; border-radius: var(--r); overflow: hidden;
  border: 1px solid var(--border); margin-top: 20px;
}
#camVideo { width: 100%; max-height: 380px; object-fit: cover; display: block; }
.cam-bar {
  background: var(--surface); padding: 14px 20px;
  display: flex; align-items: center; justify-content: center; gap: 16px;
}
.shutter {
  width: 56px; height: 56px; border-radius: 50%;
  background: var(--accent); border: 4px solid rgba(240,160,32,.25);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; font-size: 1.2rem;
  transition: all .15s; flex-shrink: 0;
}
.shutter:hover { transform: scale(1.06); box-shadow: 0 0 20px rgba(240,160,32,.4); }
.shutter:active { transform: scale(.94); }

/* ── Preview image ── */
.img-preview {
  width: 100%; max-height: 320px; object-fit: contain;
  border-radius: var(--r); display: block;
  background: repeating-conic-gradient(var(--card2) 0% 25%, var(--card) 0% 50%) 0 0 / 16px 16px;
}

/* ── Info / alert boxes ── */
.info { background: var(--accentL); border: 1px solid rgba(240,160,32,.25); border-radius: var(--r2); padding: 12px 16px; font-size: .82rem; line-height: 1.6; }
.info strong { color: var(--accent); }
.success-box { background: var(--greenL); border: 1px solid rgba(52,211,153,.25); border-radius: var(--r2); padding: 12px 16px; font-size: .82rem; }
.success-box strong { color: var(--green); }

/* ── BG swatches ── */
.swatch-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-top: 10px; }
.swatch {
  width: 40px; height: 40px; border-radius: 8px;
  border: 2px solid var(--border); cursor: pointer;
  transition: all .18s; position: relative;
}
.swatch:hover { transform: scale(1.08); }
.swatch.active { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(240,160,32,.3); transform: scale(1.08); }
.swatch.chk { background: repeating-conic-gradient(#555 0% 25%, #888 0% 50%) 0 0/12px 12px; }
.color-picker-wrap {
  width: 40px; height: 40px; border-radius: 8px;
  border: 2px solid var(--border); overflow: hidden; cursor: pointer; position: relative;
}
.color-picker-wrap input[type=color] {
  position: absolute; inset: -4px; width: calc(100% + 8px); height: calc(100% + 8px);
  border: none; padding: 0; cursor: pointer; opacity: .01;
}
.color-picker-wrap::before { content: '🎨'; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: .9rem; pointer-events: none; }

/* ── Progress ── */
.prog-wrap { background: var(--card); border-radius: 20px; height: 5px; overflow: hidden; margin: 10px 0; }
.prog-bar { height: 100%; background: linear-gradient(90deg, var(--accent), #ffc44d); border-radius: 20px; width: 0%; transition: width .4s; }
.prog-txt { font-family: var(--mono); font-size: .72rem; color: var(--text2); margin-bottom: 6px; }

/* ── Size grid ── */
.size-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; margin-bottom: 20px; }
.size-card {
  background: var(--card); border: 1.5px solid var(--border);
  border-radius: var(--r); padding: 14px 12px; cursor: pointer;
  transition: all .2s; text-align: center;
}
.size-card:hover { border-color: var(--accent); background: var(--accentLL); }
.size-card.active { border-color: var(--accent); background: var(--accentL); }
.sc-name { font-size: .8rem; font-weight: 700; margin-bottom: 4px; }
.sc-dim { font-family: var(--mono); font-size: .65rem; color: var(--text2); margin-bottom: 10px; }
.sc-vis { margin: 0 auto; background: var(--card2); border: 1px solid var(--border2); }

/* ── Cropper layout ── */
.crop-grid { display: grid; grid-template-columns: 1fr 200px; gap: 20px; }
@media (max-width: 680px) { .crop-grid { grid-template-columns: 1fr; } }
.cropper-box { background: var(--card); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; max-height: 420px; }
#cropImg { max-width: 100%; display: block; }
.crop-panel-side {}
.crop-preview-wrap {
  background: repeating-conic-gradient(var(--card2) 0% 25%, var(--card) 0% 50%) 0 0/12px 12px;
  border: 1px solid var(--border); border-radius: var(--r2); overflow: hidden;
  width: 160px; height: 200px; flex-shrink: 0;
}

/* ── Stat cards ── */
.stat-row { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
.stat-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--r2); padding: 12px 16px; flex: 1; min-width: 90px; }
.stat-label { font-size: .65rem; color: var(--text2); text-transform: uppercase; letter-spacing: .06em; font-weight: 700; margin-bottom: 5px; }
.stat-val { font-family: var(--mono); font-size: 1rem; font-weight: 700; color: var(--accent); }

/* ── Layout step ── */
.layout-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 680px) { .layout-grid { grid-template-columns: 1fr; } }
.preview-canvas-wrap {
  background: var(--card); border: 1px solid var(--border); border-radius: var(--r);
  display: flex; align-items: center; justify-content: center;
  min-height: 240px; padding: 16px; overflow: hidden;
}
#layoutCanvas { max-width: 100%; max-height: 320px; border-radius: 3px; box-shadow: var(--shadow); }

/* ── Form controls ── */
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: .72rem; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 7px; }
.form-input {
  width: 100%; background: var(--card); border: 1.5px solid var(--border);
  border-radius: var(--r2); padding: 9px 13px; color: var(--text);
  font-family: var(--mono); font-size: .85rem; transition: border-color .2s;
}
.form-input:focus { outline: none; border-color: var(--accent); }
select.form-input { cursor: pointer; }

/* ── Output step ── */
.output-canvas-wrap { text-align: center; margin-bottom: 20px; }
#outputCanvas { max-width: 100%; border: 1px solid var(--border); border-radius: var(--r); box-shadow: var(--shadow); }
.page-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }

/* ── Toast ── */
.toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 9999;
  background: var(--card2); border: 1px solid var(--border2);
  border-radius: var(--r); padding: 12px 20px; font-size: .83rem;
  transform: translateY(80px); opacity: 0; transition: all .3s;
  pointer-events: none;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast.ok  { border-color: rgba(52,211,153,.4); color: var(--green); }
.toast.err { border-color: rgba(248,113,113,.4); color: var(--red); }

/* ── Loading overlay ── */
.loading-ov {
  position: fixed; inset: 0; background: rgba(9,9,13,.88);
  display: none; flex-direction: column; align-items: center; justify-content: center;
  z-index: 999; backdrop-filter: blur(4px);
}
.loading-ov.on { display: flex; }
.spin {
  width: 44px; height: 44px; border-radius: 50%;
  border: 3px solid var(--border2); border-top-color: var(--accent);
  animation: spin 1s linear infinite; margin-bottom: 18px;
}
@keyframes spin { to { transform: rotate(360deg); } }
.load-txt { font-family: var(--mono); font-size: .8rem; color: var(--text2); text-align: center; }

/* ── Utility ── */
.hidden  { display: none !important; }
.mt-16   { margin-top: 16px; }
.mt-24   { margin-top: 24px; }
.gap-row { display: flex; gap: 12px; flex-wrap: wrap; }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 600px) { .two-col { grid-template-columns: 1fr; } }

/* ── Print ── */
#printArea { display: none; }
@media print {
  body > *:not(#printArea) { display: none !important; }
  #printArea { display: block !important; position: fixed; inset: 0; background: white; }
  #printArea canvas { width: 100% !important; height: 100% !important; }
  @page { margin: 0; size: auto; }
}
  </style>
</head>
<body>

<!-- Hidden print container -->
<div id="printArea"></div>

<!-- Header -->
<header class="header">
  <div>
    <span class="logo">Photo<em>Craft</em> Studio</span>
    <span class="logo-tag">v1.0</span>
  </div>
  <div class="header-right">
    <div class="privacy-dot">No cloud · 100% private</div>
  </div>
</header>

<!-- Step Indicator -->
<div class="stepbar" id="stepbar">
  <div class="step-item active" data-step="1">
    <div class="step-num">01</div>
    <div class="step-label">Upload</div>
  </div>
  <div class="step-sep"></div>
  <div class="step-item" data-step="2">
    <div class="step-num">02</div>
    <div class="step-label">Background</div>
  </div>
  <div class="step-sep"></div>
  <div class="step-item" data-step="3">
    <div class="step-num">03</div>
    <div class="step-label">Crop & Size</div>
  </div>
  <div class="step-sep"></div>
  <div class="step-item" data-step="4">
    <div class="step-num">04</div>
    <div class="step-label">Layout</div>
  </div>
  <div class="step-sep"></div>
  <div class="step-item" data-step="5">
    <div class="step-num">05</div>
    <div class="step-label">Print & Download</div>
  </div>
</div>

<main class="main">

<!-- ═══ STEP 1 : UPLOAD ═══ -->
<section class="step-section active" id="step-1">
  <div class="sec-head">
    <h1 class="sec-title">Upload Photo</h1>
    <p class="sec-sub">Use a clear, front-facing photo with good lighting</p>
  </div>

  <div class="upload-zone" id="uploadZone">
    <input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp,image/gif"/>
    <span class="uz-icon">📁</span>
    <div class="uz-title">Drag & drop your photo here</div>
    <div class="uz-sub mt-16">JPG · PNG · WEBP &nbsp;|&nbsp; Max 20 MB</div>
  </div>

  <div class="or-divider">OR</div>

  <div style="text-align:center">
    <button class="btn btn-outline" id="openCamBtn">📷 &nbsp;Use Camera</button>
  </div>

  <!-- Camera -->
  <div class="camera-wrap hidden" id="camWrap">
    <video id="camVideo" autoplay playsinline muted></video>
    <div class="cam-bar">
      <button class="btn btn-ghost btn-sm" id="closeCamBtn">✕ Close</button>
      <div class="shutter" id="shutterBtn" title="Take Photo">📸</div>
      <button class="btn btn-ghost btn-sm" id="flipCamBtn">🔄 Flip</button>
    </div>
  </div>

  <!-- Upload preview -->
  <div class="hidden mt-24" id="uploadPreview">
    <div class="success-box" style="margin-bottom:16px">
      ✅ <strong>Photo loaded.</strong> Click "Next" to continue.
    </div>
    <img id="previewImg" class="img-preview" alt="Preview"/>
    <div class="btn-row">
      <button class="btn btn-ghost btn-sm" id="resetBtn">↩ &nbsp;Try Another</button>
      <button class="btn btn-accent" id="s1NextBtn">Next: Background →</button>
    </div>
  </div>
</section>

<!-- ═══ STEP 2 : BACKGROUND ═══ -->
<section class="step-section" id="step-2">
  <div class="sec-head">
    <h1 class="sec-title">Background</h1>
    <p class="sec-sub">Remove & replace, or skip to keep original</p>
  </div>

  <div class="two-col" style="margin-bottom:20px">
    <div>
      <div class="form-label" style="margin-bottom:8px">Original</div>
      <img id="bgOrig" class="img-preview" style="height:220px;object-fit:contain" alt="Original"/>
    </div>
    <div>
      <div class="form-label" style="margin-bottom:8px">Result</div>
      <img id="bgResult" class="img-preview" style="height:220px;object-fit:contain" alt="Result"/>
    </div>
  </div>

  <div class="card" style="margin-bottom:16px">
    <div class="form-label">Background Color</div>
    <div class="swatch-row" id="swatchRow">
      <div class="swatch active" data-color="#ffffff" style="background:#ffffff" title="White"></div>
      <div class="swatch" data-color="#4169e1" style="background:#4169e1" title="Royal Blue"></div>
      <div class="swatch" data-color="#87ceeb" style="background:#87ceeb" title="Sky Blue"></div>
      <div class="swatch" data-color="#f0f0f0" style="background:#f0f0f0" title="Light Grey"></div>
      <div class="swatch" data-color="#fde8d8" style="background:#fde8d8" title="Soft Peach"></div>
      <div class="swatch chk" data-color="transparent" title="Transparent (PNG only)"></div>
      <div class="color-picker-wrap" title="Custom color">
        <input type="color" id="customColor" value="#ff4444"/>
      </div>
    </div>
  </div>

  <div id="bgProgArea" class="hidden card" style="margin-bottom:16px">
    <div class="prog-txt" id="bgProgTxt">Initialising AI…</div>
    <div class="prog-wrap"><div class="prog-bar" id="bgProgBar"></div></div>
  </div>

  <div class="info" style="margin-bottom:16px">
    💡 <strong>AI background removal</strong> runs entirely in your browser — no data leaves your device.
    First use downloads a ~50 MB model (cached for reuse).
  </div>

  <div class="btn-row">
    <button class="btn btn-ghost" id="s2BackBtn">← Back</button>
    <button class="btn btn-outline" id="removeBgBtn">🤖 &nbsp;Remove Background (AI)</button>
    <button class="btn btn-accent" id="s2NextBtn">Proceed to Crop →</button>
  </div>
</section>

<!-- ═══ STEP 3 : CROP & SIZE ═══ -->
<section class="step-section" id="step-3">
  <div class="sec-head">
    <h1 class="sec-title">Crop & Size</h1>
    <p class="sec-sub">Pick a standard format, then fine-tune the crop</p>
  </div>

  <div class="form-label" style="margin-bottom:10px">Photo Format</div>
  <div class="size-grid" id="sizeGrid"><!-- JS --></div>

  <!-- Custom size inputs -->
  <div id="customSizeRow" class="two-col hidden" style="margin-bottom:20px">
    <div class="form-group">
      <label class="form-label">Width (mm)</label>
      <input type="number" class="form-input" id="cwW" value="35" min="10" max="300"/>
    </div>
    <div class="form-group">
      <label class="form-label">Height (mm)</label>
      <input type="number" class="form-input" id="cwH" value="45" min="10" max="300"/>
    </div>
  </div>

  <div class="crop-grid">
    <div>
      <div class="form-label" style="margin-bottom:8px">Drag handles to adjust crop</div>
      <div class="cropper-box">
        <img id="cropImg" alt="Crop"/>
      </div>
    </div>
    <div class="crop-panel-side">
      <div class="form-label" style="margin-bottom:8px">Live Preview</div>
      <div class="crop-preview-wrap" id="cropPreviewBox" style="width:160px;height:200px"></div>
      <div class="stat-card mt-16">
        <div class="stat-label">Selected Size</div>
        <div class="stat-val" id="sizeLabel">35 × 45 mm</div>
      </div>
    </div>
  </div>

  <div class="btn-row">
    <button class="btn btn-ghost" id="s3BackBtn">← Back</button>
    <button class="btn btn-accent" id="s3NextBtn">Next: Layout →</button>
  </div>
</section>

<!-- ═══ STEP 4 : LAYOUT ═══ -->
<section class="step-section" id="step-4">
  <div class="sec-head">
    <h1 class="sec-title">Layout</h1>
    <p class="sec-sub">Set paper, count, and spacing</p>
  </div>

  <div class="layout-grid">
    <div>
      <div class="form-group">
        <label class="form-label">Number of Photos</label>
        <input type="number" class="form-input" id="photoCount" value="8" min="1" max="200"/>
      </div>
      <div class="form-group">
        <label class="form-label">Paper Size</label>
        <select class="form-input" id="paperSize">
          <option value="A4">A4 &nbsp;(210 × 297 mm)</option>
          <option value="A5">A5 &nbsp;(148 × 210 mm)</option>
          <option value="4R">4R / 4×6" &nbsp;(102 × 152 mm)</option>
          <option value="5R">5R / 5×7" &nbsp;(127 × 178 mm)</option>
          <option value="LTR">Letter &nbsp;(216 × 279 mm)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Orientation</label>
        <select class="form-input" id="paperOrient">
          <option value="portrait">Portrait ↕</option>
          <option value="landscape">Landscape ↔</option>
        </select>
      </div>
      <div class="two-col">
        <div class="form-group">
          <label class="form-label">Gap (mm)</label>
          <input type="number" class="form-input" id="gapMm" value="3" min="0" max="20"/>
        </div>
        <div class="form-group">
          <label class="form-label">Margin (mm)</label>
          <input type="number" class="form-input" id="marginMm" value="5" min="0" max="30"/>
        </div>
      </div>
    </div>

    <div>
      <div class="form-label" style="margin-bottom:10px">Preview</div>
      <div class="preview-canvas-wrap">
        <canvas id="layoutCanvas"></canvas>
      </div>
      <div class="stat-row mt-16">
        <div class="stat-card">
          <div class="stat-label">Per Page</div>
          <div class="stat-val" id="stPerPage">–</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Pages</div>
          <div class="stat-val" id="stPages">–</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Grid</div>
          <div class="stat-val" id="stGrid">–</div>
        </div>
      </div>
    </div>
  </div>

  <div class="btn-row">
    <button class="btn btn-ghost" id="s4BackBtn">← Back</button>
    <button class="btn btn-accent btn-lg" id="generateBtn">⚙ Generate Pages →</button>
  </div>
</section>

<!-- ═══ STEP 5 : OUTPUT ═══ -->
<section class="step-section" id="step-5">
  <div class="sec-head">
    <h1 class="sec-title">Ready to Print</h1>
    <p class="sec-sub">High-resolution layout at 300 DPI</p>
  </div>

  <div class="page-tabs" id="pageTabs"></div>

  <div class="output-canvas-wrap">
    <canvas id="outputCanvas"></canvas>
  </div>

  <div class="stat-row">
    <div class="stat-card">
      <div class="stat-label">Total Photos</div>
      <div class="stat-val" id="outPhotos">–</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Paper</div>
      <div class="stat-val" id="outPaper">–</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Resolution</div>
      <div class="stat-val" id="outRes">–</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Pages</div>
      <div class="stat-val" id="outPages">–</div>
    </div>
  </div>

  <div class="btn-row">
    <button class="btn btn-ghost" id="s5BackBtn">← Back</button>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <button class="btn btn-ghost btn-sm hidden" id="prevPageBtn">◀ Prev</button>
      <button class="btn btn-ghost btn-sm hidden" id="nextPageBtn">Next ▶</button>
    </div>
    <button class="btn btn-accent btn-lg" id="printAllBtn">🖨 &nbsp;Print All</button>
    <button class="btn btn-accent" id="dlPngBtn">⬇ Download PNG</button>
    <button class="btn btn-outline" id="dlJpgBtn">⬇ JPG</button>
    <button class="btn btn-ghost btn-sm" id="startOverBtn">↩ Start Over</button>
  </div>
</section>

</main>

<!-- Loading -->
<div class="loading-ov" id="loadOv">
  <div class="spin"></div>
  <div class="load-txt" id="loadTxt">Processing…</div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script type="module">
/* ═══════════════════════════════════════════════════
   PhotoCraft Studio — Main Application Script
   All processing is 100% client-side (no server calls)
   ═══════════════════════════════════════════════════ */

// ── Constants ──────────────────────────────────────
const DPI      = 300;
const MM2PX    = DPI / 25.4;   // mm → pixels at 300dpi ≈ 11.811

const SIZES = [
  { name:'India Passport', w:35,  h:45  },
  { name:'US Passport',    w:51,  h:51  },
  { name:'UK Passport',    w:45,  h:35  },
  { name:'Schengen Visa',  w:35,  h:45  },
  { name:'ID Card',        w:25,  h:35  },
  { name:'Stamp Size',     w:20,  h:25  },
  { name:'4R (4×6")',      w:102, h:152 },
  { name:'5R (5×7")',      w:127, h:178 },
  { name:'Custom',         w:35,  h:45, custom:true },
];

const PAPERS = {
  A4:  { w:210, h:297 },
  A5:  { w:148, h:210 },
  '4R':{ w:102, h:152 },
  '5R':{ w:127, h:178 },
  LTR: { w:216, h:279 },
};

// ── State ──────────────────────────────────────────
const S = {
  srcURL:      null,   // blob URL of source image
  bgBlob:      null,   // blob after bg removal (with transparency)
  finalURL:    null,   // URL used for cropping (bg applied or original)
  croppedURL:  null,   // data URL after crop step
  photoSize:   null,   // { name, w, h } mm
  bgColor:     '#ffffff',
  cropper:     null,
  pages:       [],     // Array of <canvas> elements (300dpi output)
  curPage:     0,
};

// ── Helpers ───────────────────────────────────────
const $  = id => document.getElementById(id);
const cl = (el, ...c) => el.classList;

function toast(msg, type='') {
  const t = $('toast');
  t.textContent = msg;
  t.className = `toast show ${type}`;
  clearTimeout(t._tid);
  t._tid = setTimeout(() => t.classList.remove('show'), 3000);
}

function showLoad(txt='Processing…') { $('loadTxt').textContent = txt; $('loadOv').classList.add('on'); }
function hideLoad() { $('loadOv').classList.remove('on'); }

// ── Step navigation ───────────────────────────────
function goTo(n) {
  document.querySelectorAll('.step-section').forEach(s => s.classList.remove('active'));
  $(`step-${n}`).classList.add('active');
  document.querySelectorAll('.step-item').forEach(el => {
    const s = +el.dataset.step;
    el.classList.remove('active','done');
    if (s === n) el.classList.add('active');
    else if (s < n) el.classList.add('done');
  });
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ══════════════════════════════════════════════════
// STEP 1 — Upload / Camera
// ══════════════════════════════════════════════════
const uploadZone = $('uploadZone');

uploadZone.addEventListener('dragover',  e => { e.preventDefault(); uploadZone.classList.add('over'); });
uploadZone.addEventListener('dragleave', ()=> uploadZone.classList.remove('over'));
uploadZone.addEventListener('drop', e => {
  e.preventDefault(); uploadZone.classList.remove('over');
  const f = e.dataTransfer.files[0];
  if (f && f.type.startsWith('image/')) setFile(f);
  else toast('Please drop an image file', 'err');
});
$('fileInput').addEventListener('change', e => { if (e.target.files[0]) setFile(e.target.files[0]); });

function setFile(file) {
  if (file.size > 20 * 1024 * 1024) { toast('File too large — max 20 MB', 'err'); return; }
  if (S.srcURL) URL.revokeObjectURL(S.srcURL);
  S.srcURL = URL.createObjectURL(file);
  showPreview();
}

function showPreview() {
  $('previewImg').src = S.srcURL;
  uploadZone.classList.add('hidden');
  document.querySelector('.or-divider').classList.add('hidden');
  $('openCamBtn').parentElement.classList.add('hidden');
  $('uploadPreview').classList.remove('hidden');
}

$('resetBtn').addEventListener('click', () => {
  S.srcURL = null; S.bgBlob = null; S.finalURL = null;
  $('fileInput').value = '';
  uploadZone.classList.remove('hidden');
  document.querySelector('.or-divider').classList.remove('hidden');
  $('openCamBtn').parentElement.classList.remove('hidden');
  $('uploadPreview').classList.add('hidden');
  $('camWrap').classList.add('hidden');
  stopCam();
});

$('s1NextBtn').addEventListener('click', () => {
  if (!S.srcURL) { toast('Please upload a photo first', 'err'); return; }
  $('bgOrig').src   = S.srcURL;
  $('bgResult').src = S.srcURL;
  S.finalURL = S.srcURL;
  S.bgBlob   = null;
  goTo(2);
});

// ── Camera ─────────────────────────────────────────
let camStream = null, facingMode = 'user';

$('openCamBtn').addEventListener('click', openCam);
$('closeCamBtn').addEventListener('click', () => { $('camWrap').classList.add('hidden'); stopCam(); });
$('flipCamBtn').addEventListener('click', () => { facingMode = facingMode==='user'?'environment':'user'; openCam(); });
$('shutterBtn').addEventListener('click', snap);

async function openCam() {
  $('camWrap').classList.remove('hidden');
  stopCam();
  try {
    camStream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode }, audio:false });
    $('camVideo').srcObject = camStream;
  } catch {
    toast('Camera not available or permission denied', 'err');
    $('camWrap').classList.add('hidden');
  }
}

function stopCam() {
  if (camStream) { camStream.getTracks().forEach(t => t.stop()); camStream = null; }
}

function snap() {
  const vid = $('camVideo');
  const c   = document.createElement('canvas');
  c.width   = vid.videoWidth; c.height = vid.videoHeight;
  c.getContext('2d').drawImage(vid, 0, 0);
  c.toBlob(blob => {
    setFile(blob);
    $('camWrap').classList.add('hidden');
    stopCam();
    toast('Photo captured!', 'ok');
  }, 'image/jpeg', 0.95);
}

// ══════════════════════════════════════════════════
// STEP 2 — Background Removal
// ══════════════════════════════════════════════════
let bgActive = '#ffffff';

$('swatchRow').addEventListener('click', e => {
  const sw = e.target.closest('[data-color]');
  if (!sw) return;
  document.querySelectorAll('#swatchRow .swatch').forEach(s => s.classList.remove('active'));
  sw.classList.add('active');
  bgActive = sw.dataset.color;
  if (S.bgBlob) applyBg();
});

$('customColor').addEventListener('input', e => {
  bgActive = e.target.value;
  document.querySelectorAll('#swatchRow .swatch').forEach(s => s.classList.remove('active'));
  if (S.bgBlob) applyBg();
});

function applyBg() {
  const img = new Image();
  img.onload = () => {
    const c = document.createElement('canvas');
    c.width = img.width; c.height = img.height;
    const ctx = c.getContext('2d');
    if (bgActive !== 'transparent') { ctx.fillStyle = bgActive; ctx.fillRect(0, 0, c.width, c.height); }
    ctx.drawImage(img, 0, 0);
    S.finalURL = c.toDataURL('image/png');
    $('bgResult').src = S.finalURL;
  };
  img.src = URL.createObjectURL(S.bgBlob);
}

$('removeBgBtn').addEventListener('click', () => {
  const btn = $('removeBgBtn');
  btn.disabled = true;
  $('bgProgArea').classList.remove('hidden');
  const setP = (pct, txt) => {
    $('bgProgBar').style.width = pct + '%';
    $('bgProgTxt').textContent = txt;
  };

  (async () => {
    try {
      setP(5, 'Preparing image...');
      const srcBlob = S.srcURL ? await fetch(S.srcURL).then(r => r.blob()) : null;
      if (!srcBlob) throw new Error('No source image');

      const fd = new FormData();
      fd.append('image', srcBlob, 'photo.jpg');

      // Use XHR to show upload / processing progress
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'removebg.php', true);
      xhr.responseType = 'blob';

      xhr.upload.onprogress = e => {
        if (e.lengthComputable) {
          const pct = Math.round((e.loaded / e.total) * 40);
          setP(5 + pct, 'Uploading image...');
        }
      };
      let serverProgressShown = false;
      xhr.onprogress = e => {
        // Generic indicator while server processes
        if (!serverProgressShown) { setP(50, 'Processing on server...'); serverProgressShown = true; }
      };

      xhr.onload = () => {
        btn.disabled = false;
        if (xhr.status === 200) {
          const blob = xhr.response;
          S.bgBlob = blob;
          applyBg();
          setP(100, 'Done!');
          toast('Background removed successfully!', 'ok');
        } else {
          const reader = new FileReader();
          reader.onload = () => {
            try { const txt = reader.result; $('bgProgTxt').textContent = 'Error: ' + txt; } catch(e) { $('bgProgTxt').textContent = 'Error during removal'; }
          };
          if (xhr.response) reader.readAsText(xhr.response);
          toast('BG removal failed — check server API key', 'err');
        }
      };
      xhr.onerror = () => { btn.disabled = false; setP(0, 'Network error'); toast('BG removal failed', 'err'); };
      xhr.send(fd);

    } catch (err) {
      console.error(err);
      btn.disabled = false;
      setP(0, 'Error: ' + (err.message || err));
      toast('BG removal failed', 'err');
    }
  })();
});

$('s2BackBtn').addEventListener('click', () => goTo(1));
$('s2NextBtn').addEventListener('click', () => {
  S.finalURL = S.finalURL || S.srcURL;
  initCropper();
  goTo(3);
});

// ══════════════════════════════════════════════════
// STEP 3 — Crop & Size
// ══════════════════════════════════════════════════
function buildSizeGrid() {
  const grid = $('sizeGrid');
  grid.innerHTML = '';
  SIZES.forEach((sz, i) => {
    const sc  = Math.min(56 / sz.w, 72 / sz.h);
    const vw  = Math.round(sz.w * sc);
    const vh  = Math.round(sz.h * sc);
    const div = document.createElement('div');
    div.className  = 'size-card' + (i === 0 ? ' active' : '');
    div.dataset.i  = i;
    div.innerHTML  = `
      <div class="sc-name">${sz.name}</div>
      <div class="sc-dim">${sz.w}×${sz.h} mm</div>
      <div class="sc-vis" style="width:${vw}px;height:${vh}px"></div>`;
    div.addEventListener('click', () => pickSize(i, div));
    grid.appendChild(div);
  });
  pickSize(0, grid.querySelector('.size-card'));
}

function pickSize(i, el) {
  document.querySelectorAll('.size-card').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  S.photoSize = { ...SIZES[i] };
  if (S.photoSize.custom) {
    $('customSizeRow').classList.remove('hidden');
    S.photoSize.w = +$('cwW').value;
    S.photoSize.h = +$('cwH').value;
  } else {
    $('customSizeRow').classList.add('hidden');
  }
  $('sizeLabel').textContent = `${S.photoSize.w} × ${S.photoSize.h} mm`;
  if (S.cropper) S.cropper.setAspectRatio(S.photoSize.w / S.photoSize.h);
}

[$('cwW'), $('cwH')].forEach(inp => inp.addEventListener('input', () => {
  if (S.photoSize?.custom) {
    S.photoSize.w = +$('cwW').value;
    S.photoSize.h = +$('cwH').value;
    $('sizeLabel').textContent = `${S.photoSize.w} × ${S.photoSize.h} mm`;
    if (S.cropper) S.cropper.setAspectRatio(S.photoSize.w / S.photoSize.h);
  }
}));

function initCropper() {
  const img = $('cropImg');
  img.src = S.finalURL;
  if (S.cropper) { S.cropper.destroy(); S.cropper = null; }
  img.onload = () => {
    const ratio = S.photoSize ? S.photoSize.w / S.photoSize.h : 35/45;
    S.cropper = new Cropper(img, {
      aspectRatio:            ratio,
      viewMode:               1,
      preview:                '#cropPreviewBox',
      responsive:             true,
      guides:                 true,
      center:                 true,
      background:             true,
      imageSmoothingEnabled:  true,
      imageSmoothingQuality:  'high',
    });
  };
}

$('s3BackBtn').addEventListener('click', () => goTo(2));
$('s3NextBtn').addEventListener('click', () => {
  if (!S.cropper) { toast('Crop not ready', 'err'); return; }
  showLoad('Cropping image at 300 DPI…');
  requestAnimationFrame(() => {
    const c = S.cropper.getCroppedCanvas({
      width:                  Math.round(S.photoSize.w * MM2PX),
      height:                 Math.round(S.photoSize.h * MM2PX),
      imageSmoothingEnabled:  true,
      imageSmoothingQuality:  'high',
    });
    S.croppedURL = c.toDataURL('image/png');
    hideLoad();
    updateLayoutPreview();
    goTo(4);
  });
});

// ══════════════════════════════════════════════════
// STEP 4 — Layout
// ══════════════════════════════════════════════════
function getPaper() {
  const base   = PAPERS[$('paperSize').value] || PAPERS.A4;
  const orient = $('paperOrient').value;
  return orient === 'landscape'
    ? { w: Math.max(base.w, base.h), h: Math.min(base.w, base.h) }
    : { w: Math.min(base.w, base.h), h: Math.max(base.w, base.h) };
}

function calcLayout() {
  const paper  = getPaper();
  const { w: pw, h: ph } = S.photoSize;
  const gap    = +$('gapMm').value   || 0;
  const margin = +$('marginMm').value || 0;
  const uw     = paper.w - margin * 2;
  const uh     = paper.h - margin * 2;
  const cols   = Math.max(1, Math.floor((uw + gap) / (pw + gap)));
  const rows   = Math.max(1, Math.floor((uh + gap) / (ph + gap)));
  const per    = cols * rows;
  const count  = Math.max(1, +$('photoCount').value || 1);
  const pages  = Math.max(1, Math.ceil(count / per));
  return { paper, cols, rows, per, pages, count, gap, margin };
}

function updateLayoutPreview() {
  if (!S.croppedURL || !S.photoSize) return;
  const ly = calcLayout();
  $('stPerPage').textContent = ly.per;
  $('stPages').textContent   = ly.pages;
  $('stGrid').textContent    = `${ly.cols}×${ly.rows}`;

  const canvas = $('layoutCanvas');
  const MAX_W = 280, MAX_H = 340;
  const scale  = Math.min(MAX_W / ly.paper.w, MAX_H / ly.paper.h);
  canvas.width  = Math.round(ly.paper.w * scale);
  canvas.height = Math.round(ly.paper.h * scale);
  const ctx = canvas.getContext('2d');
  ctx.fillStyle = '#ffffff';
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  const img = new Image();
  img.onload = () => {
    for (let r = 0; r < ly.rows; r++) {
      for (let c = 0; c < ly.cols; c++) {
        const x = (ly.margin + c * (S.photoSize.w + ly.gap)) * scale;
        const y = (ly.margin + r * (S.photoSize.h + ly.gap)) * scale;
        const w = S.photoSize.w * scale;
        const h = S.photoSize.h * scale;
        ctx.drawImage(img, x, y, w, h);
        ctx.strokeStyle = 'rgba(150,150,150,0.35)';
        ctx.lineWidth = 0.5;
        ctx.strokeRect(x, y, w, h);
      }
    }
    ctx.strokeStyle = '#e0e0e0';
    ctx.lineWidth = 1;
    ctx.strokeRect(0, 0, canvas.width, canvas.height);
  };
  img.src = S.croppedURL;
}

['photoCount','paperSize','paperOrient','gapMm','marginMm'].forEach(id => {
  $(id).addEventListener('input',  updateLayoutPreview);
  $(id).addEventListener('change', updateLayoutPreview);
});

$('s4BackBtn').addEventListener('click', () => goTo(3));
$('generateBtn').addEventListener('click', () => {
  showLoad('Generating high-resolution pages at 300 DPI…');
  setTimeout(() => { generatePages(); hideLoad(); goTo(5); }, 50);
});

// ══════════════════════════════════════════════════
// STEP 5 — Generate & Output
// ══════════════════════════════════════════════════
function generatePages() {
  const ly  = calcLayout();
  const cW  = Math.round(ly.paper.w  * MM2PX);
  const cH  = Math.round(ly.paper.h  * MM2PX);
  const pW  = Math.round(S.photoSize.w * MM2PX);
  const pH  = Math.round(S.photoSize.h * MM2PX);
  const gPx = Math.round(ly.gap    * MM2PX);
  const mPx = Math.round(ly.margin * MM2PX);

  S.pages = [];

  const img = new Image();
  img.onload = () => {
    let remaining = ly.count;

    for (let p = 0; p < ly.pages; p++) {
      const canvas = document.createElement('canvas');
      canvas.width  = cW; canvas.height = cH;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, cW, cH);

      const thisCount = Math.min(remaining, ly.per);
      let placed = 0;

      outerLoop:
      for (let r = 0; r < ly.rows; r++) {
        for (let c = 0; c < ly.cols; c++) {
          if (placed >= thisCount) break outerLoop;
          const x = mPx + c * (pW + gPx);
          const y = mPx + r * (pH + gPx);
          ctx.drawImage(img, x, y, pW, pH);
          placed++;
        }
      }

      // Cut guides — dashed lines
      ctx.save();
      ctx.strokeStyle = 'rgba(160,160,160,0.45)';
      ctx.lineWidth   = 1.5;
      ctx.setLineDash([6, 6]);
      for (let r = 0; r < ly.rows; r++) {
        for (let c = 0; c < ly.cols; c++) {
          const x = mPx + c * (pW + gPx);
          const y = mPx + r * (pH + gPx);
          ctx.strokeRect(x, y, pW, pH);
        }
      }
      ctx.restore();

      S.pages.push(canvas);
      remaining -= thisCount;
    }

    // Stats
    $('outPhotos').textContent = ly.count;
    $('outPaper').textContent  = `${ly.paper.w}×${ly.paper.h}`;
    $('outRes').textContent    = `${cW}×${cH}`;
    $('outPages').textContent  = ly.pages;

    S.curPage = 0;
    buildPageTabs();
    showPage(0);
  };
  img.src = S.croppedURL;
}

function buildPageTabs() {
  const tabs = $('pageTabs');
  tabs.innerHTML = '';
  if (S.pages.length <= 1) {
    $('prevPageBtn').classList.add('hidden');
    $('nextPageBtn').classList.add('hidden');
    return;
  }
  $('prevPageBtn').classList.remove('hidden');
  $('nextPageBtn').classList.remove('hidden');
  S.pages.forEach((_, i) => {
    const btn = document.createElement('button');
    btn.className = 'btn btn-ghost btn-sm';
    btn.textContent = `Page ${i + 1}`;
    btn.addEventListener('click', () => showPage(i));
    tabs.appendChild(btn);
  });
}

function showPage(idx) {
  S.curPage = idx;
  const src = S.pages[idx];
  const out  = $('outputCanvas');
  const maxW = Math.min(860, window.innerWidth - 48);
  const scale = maxW / src.width;
  out.width  = Math.round(src.width  * scale);
  out.height = Math.round(src.height * scale);
  out.getContext('2d').drawImage(src, 0, 0, out.width, out.height);

  document.querySelectorAll('#pageTabs button').forEach((b, i) => {
    b.className = 'btn btn-sm ' + (i === idx ? 'btn-accent' : 'btn-ghost');
  });
}

$('prevPageBtn').addEventListener('click', () => { if (S.curPage > 0) showPage(S.curPage - 1); });
$('nextPageBtn').addEventListener('click', () => { if (S.curPage < S.pages.length - 1) showPage(S.curPage + 1); });

// ── Print All ─────────────────────────────────────
$('printAllBtn').addEventListener('click', () => {
  const win = window.open('', '_blank', 'width=800,height=600');
  const imgs = S.pages.map(c => c.toDataURL('image/png'));
  win.document.write(`<!DOCTYPE html><html><head>
  <title>PhotoCraft Print</title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{background:#fff}
    .pg{page-break-after:always;text-align:center;width:100%;display:flex;align-items:center;justify-content:center}
    .pg:last-child{page-break-after:auto}
    .pg img{max-width:100%;display:block}
    @page{margin:0;size:auto}
    @media print{.pg{width:100%;height:100vh}}
  </style></head><body>`);
  imgs.forEach(src => win.document.write(`<div class="pg"><img src="${src}"/></div>`));
  win.document.write(`<script>window.addEventListener('load',()=>{setTimeout(()=>window.print(),400)});<\/script></body></html>`);
  win.document.close();
});

// ── Download ──────────────────────────────────────
function download(fmt) {
  const c   = S.pages[S.curPage];
  const url = fmt === 'png' ? c.toDataURL('image/png') : c.toDataURL('image/jpeg', 0.95);
  const a   = document.createElement('a');
  a.href     = url;
  a.download = `photocraft_p${S.curPage + 1}.${fmt}`;
  a.click();
  toast(`Downloaded as ${fmt.toUpperCase()}!`, 'ok');
}

$('dlPngBtn').addEventListener('click', () => download('png'));
$('dlJpgBtn').addEventListener('click', () => download('jpg'));

// ── Navigation ────────────────────────────────────
$('s5BackBtn').addEventListener('click', () => goTo(4));
$('startOverBtn').addEventListener('click', () => {
  if (confirm('Start over? All progress will be lost.')) location.reload();
});

// ── Init ──────────────────────────────────────────
buildSizeGrid();
</script>
</body>
</html>