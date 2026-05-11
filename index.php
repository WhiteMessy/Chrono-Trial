<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Chrono Trials</title>
    <link rel="shortcut icon" href="TemplateData/favicon.ico">
    <link rel="stylesheet" href="TemplateData/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold: #c9a84c;
            --gold-bright: #f0c84a;
            --gold-dim: #7a6330;
            --dark: #080c10;
            --darker: #040608;
            --surface: #0d1117;
            --surface2: #131920;
            --line: rgba(201,168,76,0.2);
            --text: #e8dfc8;
            --text-dim: #7a7060;
        }

        html, body {
            height: 100%;
            background: var(--darker);
            color: var(--text);
            font-family: 'Rajdhani', sans-serif;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-image:
                    repeating-linear-gradient(0deg, transparent, transparent 59px, rgba(201,168,76,0.04) 60px),
                    repeating-linear-gradient(90deg, transparent, transparent 59px, rgba(201,168,76,0.04) 60px);
            background-size: 60px 60px;
        }

        header {
            width: 100%;
            padding: 2rem 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .eyebrow {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            letter-spacing: 0.4em;
            color: var(--gold-dim);
            text-transform: uppercase;
            margin-bottom: 0.6rem;
        }

        h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: var(--gold-bright);
            letter-spacing: 0.08em;
            text-shadow:
                    0 0 40px rgba(240,200,74,0.3),
                    0 0 80px rgba(240,200,74,0.1);
            line-height: 1;
        }

        .title-line {
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 1rem auto;
        }

        .subtitle {
            font-size: 12px;
            letter-spacing: 0.25em;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        main {
            width: 100%;
            max-width: 1000px;
            padding: 0 1.5rem 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .game-frame {
            position: relative;
            width: 100%;
            max-width: 960px;
        }

        .corner {
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: var(--gold);
            border-style: solid;
            z-index: 2;
        }
        .corner-tl { top: -4px; left: -4px; border-width: 2px 0 0 2px; }
        .corner-tr { top: -4px; right: -4px; border-width: 2px 2px 0 0; }
        .corner-bl { bottom: -4px; left: -4px; border-width: 0 0 2px 2px; }
        .corner-br { bottom: -4px; right: -4px; border-width: 0 2px 2px 0; }

        #unity-container {
            width: 100%;
            position: relative;
            background: #000;
            border: 1px solid var(--line);
        }

        #unity-canvas {
            display: block;
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 960/600;
        }

        #unity-loading-bar {
            display: none;
            position: absolute;
            inset: 0;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--darker);
            gap: 2rem;
        }

        #unity-logo {
            background: url('TemplateData/unity-logo-dark.png') center/contain no-repeat;
            width: 154px;
            height: 130px;
            opacity: 0.5;
        }

        #unity-progress-bar-empty {
            width: 280px;
            height: 3px;
            background: rgba(201,168,76,0.15);
            border-radius: 2px;
            overflow: hidden;
        }

        #unity-progress-bar-full {
            height: 100%;
            width: 0;
            background: var(--gold-bright);
            transition: width 0.2s;
            box-shadow: 0 0 8px var(--gold-bright);
        }

        #unity-warning {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
        }

        #unity-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface);
            border-top: 1px solid var(--line);
            padding: 0.5rem 1rem;
            height: 40px;
        }

        #unity-logo-title-footer {
            background: url('TemplateData/unity-logo-dark.png') left center/auto 100% no-repeat;
            width: 80px;
            height: 24px;
            opacity: 0.35;
        }

        #unity-build-title {
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            letter-spacing: 0.15em;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        #unity-fullscreen-button {
            background: url('TemplateData/fullscreen-button.png') center/contain no-repeat;
            width: 38px;
            height: 38px;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        #unity-fullscreen-button:hover { opacity: 1; }

        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-decoration: none;
            padding: 0.75rem 2rem;
            border: 1px solid var(--gold-dim);
            background: transparent;
            color: var(--gold);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s;
            position: relative;
            clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
        }

        .btn:hover {
            border-color: var(--gold-bright);
            color: var(--gold-bright);
            background: rgba(240,200,74,0.06);
            text-shadow: 0 0 12px rgba(240,200,74,0.5);
        }

        .btn-primary {
            border-color: var(--gold);
            color: var(--dark);
            background: var(--gold);
        }

        .btn-primary:hover {
            background: var(--gold-bright);
            border-color: var(--gold-bright);
            color: var(--darker);
            box-shadow: 0 0 24px rgba(240,200,74,0.3);
        }

        .btn svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        .stats-row {
            display: flex;
            gap: 1px;
            background: var(--line);
            border: 1px solid var(--line);
            width: 100%;
            max-width: 540px;
        }

        .stat {
            flex: 1;
            background: var(--surface);
            padding: 0.8rem 1rem;
            text-align: center;
        }

        .stat-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            color: var(--text-dim);
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-family: 'Orbitron', monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--gold);
        }

        footer {
            margin-top: auto;
            padding: 1.5rem;
            font-size: 11px;
            letter-spacing: 0.15em;
            color: var(--text-dim);
            text-align: center;
            text-transform: uppercase;
            border-top: 1px solid var(--line);
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Chrono Trials</h1>
    <div class="title-line"></div>
</header>

<main>
    <div class="game-frame">
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <div id="unity-container" class="unity-desktop">
            <canvas id="unity-canvas" width=960 height=600 tabindex="-1"></canvas>
            <div id="unity-loading-bar">
                <div id="unity-logo"></div>
                <div id="unity-progress-bar-empty">
                    <div id="unity-progress-bar-full"></div>
                </div>
            </div>
            <div id="unity-warning"></div>
            <div id="unity-footer">
                <div id="unity-logo-title-footer"></div>
                <div id="unity-build-title">Chrono Trials</div>

            </div>
        </div>
    </div>

    <div class="actions">
        <a href="leaderboard.php" class="btn btn-primary">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="1" y="7" width="4" height="8" rx="0.5"/>
                <rect x="6" y="4" width="4" height="11" rx="0.5"/>
                <rect x="11" y="1" width="4" height="14" rx="0.5"/>
            </svg>
            Leaderboard
        </a>
        <button id="fullscreen-btn" class="btn">
            <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 6V1h5M10 1h5v5M15 10v5h-5M6 15H1v-5"/>
            </svg>
            Fullscreen
        </button>
    </div>

    <div class="stats-row">
        <div class="stat">
            <span class="stat-label">Version</span>
            <span class="stat-value">1.0</span>
        </div>
        <div class="stat">
            <span class="stat-label">Platform</span>
            <span class="stat-value">WebGL</span>
        </div>
        <div class="stat">
            <span class="stat-label">Engine</span>
            <span class="stat-value">Unity</span>
        </div>
    </div>
</main>

<footer>
    &copy; 2025 Chrono Trials &nbsp;
</footer>

<script>
    var canvas = document.querySelector("#unity-canvas");
    var fullscreenBtn = document.querySelector("#fullscreen-btn");
    var unityInstanceRef = null;

    function unityShowBanner(msg, type) {
        var warningBanner = document.querySelector("#unity-warning");
        function updateBannerVisibility() {
            warningBanner.style.display = warningBanner.children.length ? 'block' : 'none';
        }
        var div = document.createElement('div');
        div.innerHTML = msg;
        warningBanner.appendChild(div);
        if (type == 'error') div.style = 'background: #8b0000; color: #ffd; padding: 10px; font-family: Rajdhani, sans-serif;';
        else {
            if (type == 'warning') div.style = 'background: #5a4a00; color: #ffc; padding: 10px; font-family: Rajdhani, sans-serif;';
            setTimeout(function() {
                warningBanner.removeChild(div);
                updateBannerVisibility();
            }, 5000);
        }
        updateBannerVisibility();
    }

    var buildUrl = "Build";
    var loaderUrl = buildUrl + "/chrono trials.loader.js";
    var config = {
        arguments: [],
        dataUrl: buildUrl + "/chrono trials.data",
        frameworkUrl: buildUrl + "/chrono trials.framework.js",
        codeUrl: buildUrl + "/chrono trials.wasm",
        streamingAssetsUrl: "StreamingAssets",
        companyName: "DefaultCompany",
        productName: "Amura's water world",
        productVersion: "1.0",
        showBanner: unityShowBanner,
    };

    if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
        var meta = document.createElement('meta');
        meta.name = 'viewport';
        meta.content = 'width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, shrink-to-fit=yes';
        document.getElementsByTagName('head')[0].appendChild(meta);
        document.querySelector("#unity-container").className = "unity-mobile";
        canvas.className = "unity-mobile";
    }

    document.querySelector("#unity-loading-bar").style.display = "flex";

    var script = document.createElement("script");
    script.src = loaderUrl;
    script.onload = () => {
        createUnityInstance(canvas, config, (progress) => {
            document.querySelector("#unity-progress-bar-full").style.width = 100 * progress + "%";
        }).then((unityInstance) => {
            unityInstanceRef = unityInstance;
            document.querySelector("#unity-loading-bar").style.display = "none";

            fullscreenBtn.onclick = () => unityInstance.SetFullscreen(1);
        }).catch((message) => { alert(message); });
    };

    document.body.appendChild(script);
</script>
</body>
</html>