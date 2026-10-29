<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Life Changers Apps Hub | Download LCMWorld</title>
    <meta name="description" content="Download official Life Changers Church mobile applications directly to your Android device. Fast, secure, and data-friendly.">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_icon.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #FFFFFF;
            --bg-card: #FFFFFF;
            --bg-subtle: #F8FAFC;
            --bg-accent: #F1F5F9;
            --border-light: #E2E8F0;
            --border-focus: #CBD5E1;
            --text-heading: #0F172A;
            --text-body: #334155;
            --text-muted: #64748B;
            --brand-primary: #1E3A8A;
            --brand-gradient: linear-gradient(135deg, #0F172A 0%, #1E3A8A 55%, #2563EB 100%);
            --brand-glow: rgba(37, 99, 235, 0.25);
            --success-color: #059669;
            --success-bg: #ECFDF5;
            --success-border: #A7F3D0;
            --shadow-card: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 1px 3px 0 rgba(0, 0, 0, 0.02);
            --shadow-card-hover: 0 20px 40px -10px rgba(15, 23, 42, 0.08), 0 2px 6px 0 rgba(0, 0, 0, 0.03);
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 24px;
            --radius-full: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-heading);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle modern ambient background glow */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 1100px;
            height: 480px;
            background: radial-gradient(50% 50% at 50% 0%, rgba(37, 99, 235, 0.05) 0%, rgba(248, 250, 252, 0) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 860px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        /* Clean Header */
        header {
            padding: 20px 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.7);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-heading);
            transition: opacity 0.2s ease;
        }

        .brand:hover {
            opacity: 0.9;
        }

        .brand-icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #0B0F19;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            flex-shrink: 0;
        }

        .brand-icon-img {
            width: 26px;
            height: 26px;
            object-fit: contain;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 0.975rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-heading);
            line-height: 1.2;
        }

        .brand-desc {
            font-size: 0.725rem;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: var(--radius-full);
            background: var(--bg-subtle);
            border: 1px solid var(--border-light);
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .header-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--success-color);
        }

        /* Hero Section */
        .hero {
            padding: 52px 0 36px;
            text-align: center;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: var(--radius-full);
            background: rgba(37, 99, 235, 0.06);
            color: #1D4ED8;
            font-size: 0.775rem;
            font-weight: 700;
            margin-bottom: 20px;
            border: 1px solid rgba(37, 99, 235, 0.12);
            letter-spacing: 0.02em;
        }

        .hero-pill-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563EB;
        }

        .hero h1 {
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.18;
            color: var(--text-heading);
            margin-bottom: 12px;
        }

        .hero p.lead {
            font-size: clamp(0.95rem, 1.8vw, 1.05rem);
            color: var(--text-body);
            max-width: 580px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Shared App Dynamic Referral Card */
        .shared-app-banner {
            display: none;
            background: linear-gradient(135deg, #0B0F19 0%, #172554 100%);
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-radius: var(--radius-xl);
            padding: 30px;
            color: #FFFFFF;
            margin-top: 24px;
            margin-bottom: 24px;
            box-shadow: 0 16px 36px -8px rgba(37, 99, 235, 0.35);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.5s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .shared-app-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .shared-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(37, 99, 235, 0.25);
            border: 1px solid rgba(147, 197, 253, 0.3);
            color: #93C5FD;
            font-size: 0.725rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            margin-bottom: 16px;
        }

        .shared-app-content {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 16px;
        }

        .shared-app-icon {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            background: #1E293B;
            border: 1px solid rgba(255, 255, 255, 0.15);
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
        }

        .shared-app-info h3 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .shared-app-info p {
            font-size: 0.85rem;
            color: #94A3B8;
            line-height: 1.4;
        }

        .shared-app-message {
            background: rgba(255, 255, 255, 0.06);
            border-left: 3px solid #3B82F6;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #E2E8F0;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .shared-cta-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .btn-shared-openapp {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
            animation: pulseSubtle 2.5s infinite;
        }

        .btn-shared-openapp:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        @keyframes pulseSubtle {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .btn-shared-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563EB;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.925rem;
            padding: 12px 22px;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }

        .btn-shared-primary:hover {
            background: #1D4ED8;
            transform: translateY(-1px);
        }

        .btn-shared-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #F1F5F9;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s ease;
        }

        .btn-shared-secondary:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #FFFFFF;
        }

        /* Primary App Card */
        .app-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            padding: 36px 32px;
            box-shadow: var(--shadow-card);
            margin-top: 24px;
            margin-bottom: 44px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .app-card:hover {
            box-shadow: var(--shadow-card-hover);
            border-color: var(--border-focus);
        }

        .app-meta-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .app-icon-squircle {
            width: 76px;
            height: 76px;
            border-radius: 18px;
            background: #0B0F19;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px -4px rgba(11, 15, 25, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
            padding: 12px;
        }

        .app-icon-squircle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .app-headings {
            flex: 1;
        }

        .app-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }

        .app-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.025em;
        }

        .version-badge {
            font-size: 0.725rem;
            font-weight: 700;
            padding: 3px 9px;
            background: var(--bg-subtle);
            color: #1E293B;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-light);
            letter-spacing: 0.01em;
        }

        .app-tagline {
            font-size: 0.9rem;
            color: var(--text-body);
            line-height: 1.45;
        }

        /* Clean Specs Grid */
        .specs-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 28px;
        }

        .spec-box {
            background: var(--bg-subtle);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--radius-md);
            padding: 14px 12px;
            text-align: center;
            transition: background 0.2s;
        }

        .spec-box:hover {
            background: var(--bg-accent);
        }

        .spec-label {
            font-size: 0.675rem;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .spec-content {
            font-size: 0.925rem;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .highlight-emerald {
            color: var(--success-color);
        }

        /* Download Action Button */
        .action-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .btn-download-hero {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            max-width: 420px;
            background: var(--brand-gradient);
            color: #FFFFFF;
            text-decoration: none;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 16px 28px;
            border-radius: var(--radius-md);
            box-shadow: 0 6px 20px -2px rgba(30, 58, 138, 0.35);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
        }

        .btn-download-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px -4px rgba(30, 58, 138, 0.45);
        }

        .btn-download-hero:active {
            transform: translateY(0);
        }

        .btn-download-hero svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
            transition: transform 0.2s ease;
        }

        .btn-download-hero:hover svg {
            transform: translateY(2px);
        }

        .btn-caption {
            font-size: 0.8rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-caption svg {
            width: 14px;
            height: 14px;
            color: var(--success-color);
        }

        /* Architecture Selector Dropdown */
        .arch-dropdown-wrap {
            margin-top: 10px;
            width: 100%;
            max-width: 420px;
        }

        .arch-trigger {
            background: none;
            border: none;
            font-size: 0.8rem;
            font-weight: 600;
            color: #2563EB;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            transition: background 0.15s ease;
            margin: 0 auto;
        }

        .arch-trigger:hover {
            background: rgba(37, 99, 235, 0.06);
        }

        .arch-drawer {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            flex-direction: column;
            gap: 8px;
            text-align: left;
        }

        .arch-drawer.active {
            display: flex;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .arch-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #FFFFFF;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-light);
            text-decoration: none;
            color: var(--text-heading);
            font-size: 0.825rem;
            transition: all 0.2s ease;
        }

        .arch-row:hover {
            border-color: #2563EB;
            background: #F8FAFC;
        }

        .arch-row-name {
            font-weight: 600;
        }

        .arch-row-size {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* 3-Step Guide Section */
        .guide-section {
            margin-bottom: 48px;
        }

        .guide-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .guide-title {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-heading);
            margin-bottom: 4px;
        }

        .guide-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .step-tile {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 22px 18px;
            box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s ease;
        }

        .step-tile:hover {
            border-color: var(--border-focus);
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.08);
            color: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            margin-bottom: 14px;
        }

        .step-tile h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: var(--text-heading);
        }

        .step-tile p {
            font-size: 0.825rem;
            color: var(--text-body);
            line-height: 1.5;
        }

        /* Ecosystem Preview */
        .ecosystem-container {
            background: var(--bg-subtle);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-light);
            padding: 28px;
            margin-bottom: 56px;
        }

        .ecosystem-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .ecosystem-title {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-heading);
            margin-bottom: 4px;
        }

        .ecosystem-desc {
            font-size: 0.825rem;
            color: var(--text-muted);
        }

        .ecosystem-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .eco-card {
            background: #FFFFFF;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 18px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .eco-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #0B0F19;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            flex-shrink: 0;
            font-weight: 800;
            font-size: 1rem;
        }

        .eco-details h4 {
            font-size: 0.925rem;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 3px;
        }

        .eco-details p {
            font-size: 0.8rem;
            color: var(--text-body);
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .badge-active {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            background: var(--success-bg);
            color: var(--success-color);
        }

        .badge-beta {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            background: #FEF3C7;
            color: #D97706;
        }

        /* Minimal Footer */
        footer {
            margin-top: auto;
            border-top: 1px solid var(--border-light);
            padding: 28px 0;
            background: #FFFFFF;
            font-size: 0.825rem;
            color: var(--text-muted);
            text-align: center;
        }

        footer p {
            color: var(--text-muted);
        }

        @media (max-width: 680px) {
            .app-card {
                padding: 24px 18px;
            }
            .app-meta-header {
                flex-direction: column;
                text-align: center;
                gap: 14px;
            }
            .app-title-group {
                justify-content: center;
            }
            .specs-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .steps-container {
                grid-template-columns: 1fr;
            }
            .ecosystem-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation (Without Portal Button) -->
    <header>
        <div class="container header-content">
            <a href="{{ url('/') }}" class="brand">
                <div class="brand-icon-wrapper">
                    <img src="{{ asset('images/logo_icon.png') }}" alt="LCM Logo" class="brand-icon-img" onerror="this.src='{{ asset('images/logo.png') }}'">
                </div>
                <div class="brand-info">
                    <span class="brand-name">Life Changers Touch</span>
                    <span class="brand-desc">Apps & Digital Hub</span>
                </div>
            </a>
            <div class="header-badge">
                <span class="header-badge-dot"></span>
                <span>Verified Direct Downloads</span>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-pill">
                <span class="hero-pill-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </span>
                <span>Official Android Releases</span>
            </div>
            <h1>Download Life Changers Apps</h1>
            <p class="lead">
                Install official, verified Life Changers applications directly to your phone. Fast, secure, and optimized for minimal data usage.
            </p>
        </section>

        <!-- Dynamic Shared App Referral Banner (Shown when loaded with ?package=...) -->
        <div id="sharedAppBanner" class="shared-app-banner">
            <div class="shared-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"></circle>
                    <circle cx="6" cy="12" r="3"></circle>
                    <circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
                <span>App Link Shared With You</span>
            </div>
            
            <div class="shared-app-content">
                <img id="sharedAppIcon" src="{{ asset('images/logo_icon.png') }}" alt="App Icon" class="shared-app-icon" onerror="this.src='{{ asset('images/logo.png') }}'">
                <div class="shared-app-info">
                    <h3 id="sharedAppName">Application</h3>
                    <p id="sharedAppCategory">Official Package • Verified Security Scanned</p>
                </div>
            </div>

            <div class="shared-app-message">
                To install and run <strong id="sharedAppNameText">this application</strong> on your Android device, download and install the official <strong>Lifechangers App Hub</strong> below. It manages updates, offline access, and security scanning for all church applications.
            </div>

            <div class="shared-cta-group">
                <a href="#" class="btn-shared-openapp" id="btnSharedOpenApp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span>Open in Lifechangers App</span>
                </a>
                <a href="#btn-download" class="btn-shared-primary" id="btnSharedInstall">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/>
                    </svg>
                    <span>Download App Hub</span>
                </a>
                <a href="#" id="btnSharedDirectApk" class="btn-shared-secondary" style="display:none;" download>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Direct APK Download</span>
                </a>
            </div>
        </div>

        <!-- Primary App Card (LCMWorld) -->
        <div class="app-card">
            <div class="app-meta-header">
                <div class="app-icon-squircle">
                    <img src="{{ asset('images/logo_icon.png') }}" alt="LCMWorld App Icon" onerror="this.src='{{ asset('images/logo.png') }}'">
                </div>
                <div class="app-headings">
                    <div class="app-title-group">
                        <h2 class="app-name">LCMWorld</h2>
                        <span class="version-badge">v1.0.1 Stable</span>
                    </div>
                    <p class="app-tagline">
                        Your direct mobile hub for church announcements, service updates, internal documents, and enterprise ministry tools.
                    </p>
                </div>
            </div>

            <!-- Specs Grid -->
            <div class="specs-container">
                <div class="spec-box">
                    <div class="spec-label">Download Size</div>
                    <div class="spec-content highlight-emerald">⚡ 18.4 MB</div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Architecture</div>
                    <div class="spec-content">ARM64 (64-bit)</div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Compatibility</div>
                    <div class="spec-content">Android 7.0+</div>
                </div>
                <div class="spec-box">
                    <div class="spec-label">Verification</div>
                    <div class="spec-content highlight-emerald">🛡️ Safe & Verified</div>
                </div>
            </div>

            <!-- CTA Download Buttons -->
            <div class="action-area">
                <a href="{{ asset('downloads/LCMWorld-latest.apk') }}" download class="btn-download-hero" id="btn-download">
                    <svg viewBox="0 0 24 24">
                        <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/>
                    </svg>
                    <span>Download LCMWorld APK</span>
                </a>
                
                <span class="btn-caption">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Optimized for Samsung, Tecno, Infinix, Xiaomi, Pixel
                </span>

                <!-- Architecture Selector Dropdown -->
                <div class="arch-dropdown-wrap">
                    <button type="button" class="arch-trigger" onclick="toggleArchDrawer()">
                        <span>Need a different version? (32-bit or Universal)</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="arch-drawer" id="archDrawer">
                        <a href="{{ asset('downloads/LCMWorld-v1.0.1-arm64.apk') }}" download class="arch-row">
                            <span class="arch-row-name">⚡ ARM64-v8a (64-bit Modern Devices)</span>
                            <span class="arch-row-size">18.4 MB (Default)</span>
                        </a>
                        <a href="{{ asset('downloads/LCMWorld-v1.0.1-armv7.apk') }}" download class="arch-row">
                            <span class="arch-row-name">📱 ARMeabi-v7a (32-bit Legacy Devices)</span>
                            <span class="arch-row-size">15.9 MB</span>
                        </a>
                        <a href="{{ asset('downloads/LCMWorld-v1.0.1-universal.apk') }}" download class="arch-row">
                            <span class="arch-row-name">📦 Universal APK (All Android Architectures)</span>
                            <span class="arch-row-size">51.8 MB</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3-Step Simple Install Guide -->
        <section class="guide-section">
            <div class="guide-header">
                <h2 class="guide-title">How to Install in 3 Easy Steps</h2>
                <p class="guide-subtitle">A quick walkthrough for seamless installation on any Android phone</p>
            </div>
            <div class="steps-container">
                <div class="step-tile">
                    <div class="step-circle">1</div>
                    <h3>Download APK</h3>
                    <p>Click the download button above. If Chrome prompts "File might be harmful", tap <strong>Download anyway</strong>.</p>
                </div>
                <div class="step-tile">
                    <div class="step-circle">2</div>
                    <h3>Allow Installation</h3>
                    <p>Open the downloaded file. When prompted by Android, tap <strong>Settings</strong> and toggle on <strong>Allow from this source</strong>.</p>
                </div>
                <div class="step-tile">
                    <div class="step-circle">3</div>
                    <h3>Launch LCMWorld</h3>
                    <p>Return to the installer, tap <strong>Install</strong>, and launch LCMWorld once complete.</p>
                </div>
            </div>
        </section>

        <!-- Life Changers Apps Ecosystem -->
        <section class="ecosystem-container">
            <div class="ecosystem-header">
                <h3 class="ecosystem-title">Life Changers Apps Ecosystem</h3>
                <p class="ecosystem-desc">Purpose-built digital tools to keep the body connected.</p>
            </div>

            <div class="ecosystem-grid">
                <div class="eco-card">
                    <div class="eco-icon" style="background: #0B0F19;">
                        <img src="{{ asset('images/logo_icon.png') }}" alt="W" style="width: 24px; height: 24px; object-fit: contain;">
                    </div>
                    <div class="eco-details">
                        <h4>LCMWorld App</h4>
                        <p>Central hub for announcements, notifications, community feeds, and organizational apps.</p>
                        <span class="badge-active">Available Now (v1.0.1)</span>
                    </div>
                </div>

                <div class="eco-card">
                    <div class="eco-icon" style="background: #1E3A8A;">A</div>
                    <div class="eco-details">
                        <h4>LCM Audios (Sermons)</h4>
                        <p>Listen, stream, and download anointed audio messages, teachings, and podcasts.</p>
                        <span class="badge-beta">In Ecosystem / Beta</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Clean Footer -->
    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Life Changers Touch. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleArchDrawer() {
            var drawer = document.getElementById('archDrawer');
            if (drawer.classList.contains('active')) {
                drawer.classList.remove('active');
            } else {
                drawer.classList.add('active');
            }
        }

        // Handle dynamic shared app links: e.g. ?package=org.lifechangerstouch.lavie.messenger
        document.addEventListener('DOMContentLoaded', function () {
            var params = new URLSearchParams(window.location.search);
            var pkg = params.get('package') || params.get('app') || params.get('id');
            if (!pkg) return;

            var banner = document.getElementById('sharedAppBanner');
            var nameEl = document.getElementById('sharedAppName');
            var nameTextEl = document.getElementById('sharedAppNameText');
            var iconEl = document.getElementById('sharedAppIcon');
            var catEl = document.getElementById('sharedAppCategory');
            var directApkBtn = document.getElementById('btnSharedDirectApk');
            var openAppBtn = document.getElementById('btnSharedOpenApp');

            // Construct Android Intent & Custom Scheme URIs
            var customSchemeUri = 'lcmworld://app?package=' + encodeURIComponent(pkg);
            var intentUri = 'intent://app?package=' + encodeURIComponent(pkg) +
                '#Intent;scheme=lcmworld;package=org.lifechangerstouch.lcmworld;S.browser_fallback_url=' +
                encodeURIComponent(window.location.href) + ';end';

            if (openAppBtn) {
                openAppBtn.href = intentUri;
                openAppBtn.addEventListener('click', function(e) {
                    setTimeout(function() {
                        window.location.href = customSchemeUri;
                    }, 50);
                });
            }

            // If user is on an Android device, attempt auto-opening the installed app immediately
            var isAndroid = /Android/i.test(navigator.userAgent);
            if (isAndroid) {
                try {
                    var iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = customSchemeUri;
                    document.body.appendChild(iframe);
                    setTimeout(function() {
                        if (iframe.parentNode) document.body.removeChild(iframe);
                    }, 2500);
                } catch(e) {}
            }

            if (banner) {
                banner.style.display = 'block';
                // Friendly title fallback from package or query
                var rawSegment = pkg.split('.').pop() || pkg;
                var cleanTitle = rawSegment.charAt(0).toUpperCase() + rawSegment.slice(1);
                nameEl.textContent = cleanTitle;
                nameTextEl.textContent = cleanTitle;

                // Smooth scroll into view so the user sees the app they were shared
                setTimeout(function () {
                    banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);

                // Fetch real app metadata from the LCMWorld catalog API
                fetch('/api/admin/apps')
                    .then(function(res) { return res.json(); })
                    .then(function(apps) {
                        if (!Array.isArray(apps)) return;
                        var target = apps.find(function(a) {
                            return (a.package_name && a.package_name.toLowerCase() === pkg.toLowerCase()) ||
                                   (a.id && a.id.toString() === pkg);
                        });
                        if (target) {
                            nameEl.textContent = target.app_name;
                            nameTextEl.textContent = target.app_name;
                            if (target.icon_url) {
                                iconEl.src = target.icon_url;
                            }
                            if (target.category_name || target.version) {
                                catEl.textContent = (target.category_name || 'Ecosystem App') + ' • v' + (target.version || '1.0.0');
                            }
                            if (target.apk_download_url) {
                                directApkBtn.href = target.apk_download_url;
                                directApkBtn.style.display = 'inline-flex';
                            }
                        }
                    })
                    .catch(function(err) {
                        console.log('App metadata fetch error:', err);
                    });
            }
        });
    </script>
</body>
</html>
