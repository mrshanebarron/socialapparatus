<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The official community for SocialApparatus - the open-source, self-hosted social network platform. Get support, share ideas, and connect with developers.">

    <title><?php echo e(config('app.name', 'SocialApparatus')); ?> Community - Connect with Developers</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-deep: #0a0a0f;
            --bg-primary: #0f0f17;
            --bg-elevated: #16161f;
            --border-subtle: rgba(255,255,255,0.06);
            --border-medium: rgba(255,255,255,0.1);
            --accent: #8b5cf6;
            --accent-light: #a78bfa;
            --emerald: #34d399;
            --sky: #38bdf8;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-deep);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
        }

        @media (min-width: 640px) {
            .container { padding: 0 24px; }
        }

        @media (min-width: 1024px) {
            .container { padding: 0 32px; }
        }

        /* Background gradient */
        .bg-gradient {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(139, 92, 246, 0.2), transparent),
                radial-gradient(ellipse 60% 40% at 100% 50%, rgba(56, 189, 248, 0.1), transparent);
            pointer-events: none;
            z-index: 0;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(10, 10, 15, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .nav-logo-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        .nav-logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-logo-badge {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-left: 8px;
        }

        .nav-links {
            display: none;
            align-items: center;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .nav-links { display: flex; }
        }

        .nav-links a {
            font-size: 0.875rem;
            color: var(--text-secondary);
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-ghost {
            color: var(--text-secondary);
            background: transparent;
        }

        .btn-ghost:hover {
            color: var(--text-primary);
        }

        .btn-primary {
            color: white;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
            transform: translateY(-1px);
        }

        .btn-outline {
            color: var(--text-secondary);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
            color: var(--text-primary);
        }

        .btn-large {
            padding: 16px 32px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 16px;
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 64px;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
            padding: 80px 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 100px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            color: var(--accent-light);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 32px;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
        }

        @media (min-width: 640px) {
            .hero h1 { font-size: 3rem; }
        }

        @media (min-width: 1024px) {
            .hero h1 { font-size: 3.75rem; }
        }

        .gradient-text {
            background: linear-gradient(135deg, #8b5cf6 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 640px;
            margin: 0 auto 32px;
        }

        .hero-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            justify-content: center;
            margin-bottom: 48px;
        }

        @media (min-width: 640px) {
            .hero-buttons {
                flex-direction: row;
            }
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            max-width: 768px;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(56, 189, 248, 0.05) 100%);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Sections */
        .section {
            position: relative;
            padding: 96px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        @media (min-width: 640px) {
            .section-title { font-size: 2.5rem; }
        }

        .section-desc {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 640px;
            margin: 0 auto;
        }

        /* About Section */
        .about-grid {
            display: grid;
            gap: 64px;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .about-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .about-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 24px;
        }

        @media (min-width: 640px) {
            .about-content h2 { font-size: 2.5rem; }
        }

        .about-text {
            color: var(--text-secondary);
            font-size: 1.125rem;
            line-height: 1.8;
        }

        .about-text p {
            margin-bottom: 16px;
        }

        .about-text strong {
            color: var(--text-primary);
        }

        .tech-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }

        .tech-tag {
            padding: 8px 16px;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .tech-tag-violet {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            color: #a78bfa;
        }

        .tech-tag-cyan {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.2);
            color: #22d3ee;
        }

        .tech-tag-emerald {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .tech-tag-amber {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .code-card {
            background: linear-gradient(135deg, rgba(22,22,31,0.9) 0%, rgba(22,22,31,0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 0 60px rgba(139, 92, 246, 0.15);
        }

        .code-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 16px;
            font-family: 'JetBrains Mono', monospace;
        }

        .code-block {
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 16px;
            overflow-x: auto;
            margin-bottom: 24px;
        }

        .code-block:last-child {
            margin-bottom: 0;
        }

        .code-block code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            color: #a78bfa;
        }

        .code-block code.cyan {
            color: #22d3ee;
        }

        /* Benefits Grid */
        .benefits-grid {
            display: grid;
            gap: 32px;
        }

        @media (min-width: 768px) {
            .benefits-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .benefits-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .benefit-card {
            background: linear-gradient(135deg, rgba(22,22,31,0.9) 0%, rgba(22,22,31,0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 32px;
            transition: all 0.3s;
        }

        .benefit-card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateY(-4px);
        }

        .benefit-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #8b5cf6 0%, #38bdf8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .benefit-icon svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .benefit-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .benefit-card p {
            color: var(--text-secondary);
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .feature-item {
            text-align: center;
            padding: 24px;
        }

        .feature-emoji {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .feature-item h4 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .feature-item p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .features-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--accent-light);
            font-weight: 500;
            margin-top: 48px;
            transition: color 0.2s;
        }

        .features-link:hover {
            color: #c4b5fd;
        }

        .features-link svg {
            width: 16px;
            height: 16px;
        }

        /* CTA Section */
        .cta-section {
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(6, 182, 212, 0.2) 100%);
        }

        .cta-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(10, 10, 15, 0.8);
        }

        .cta-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 24px;
        }

        @media (min-width: 640px) {
            .cta-content h2 { font-size: 2.5rem; }
        }

        @media (min-width: 1024px) {
            .cta-content h2 { font-size: 3rem; }
        }

        .cta-content p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 40px;
        }

        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
            justify-content: center;
        }

        @media (min-width: 640px) {
            .cta-buttons {
                flex-direction: row;
            }
        }

        /* Footer */
        footer {
            background: var(--bg-deep);
            border-top: 1px solid rgba(255,255,255,0.05);
            padding: 48px 0;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .footer-links a {
            font-size: 0.875rem;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--text-primary);
        }

        .footer-bottom {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.05);
            text-align: center;
        }

        .footer-bottom p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .footer-bottom a {
            color: var(--accent-light);
            transition: color 0.2s;
        }

        .footer-bottom a:hover {
            color: #c4b5fd;
        }

        /* Section backgrounds */
        .section-alt {
            background: linear-gradient(180deg, transparent 0%, rgba(139, 92, 246, 0.03) 50%, transparent 100%);
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="bg-gradient"></div>

    <!-- Navigation -->
    <nav>
        <div class="container nav-container">
            <a href="/" class="nav-logo">
                <img src="/images/logo.svg" alt="SA" class="nav-logo-icon" style="width: 40px; height: 40px; border-radius: 12px;">
                <span class="nav-logo-text">SocialApparatus</span>
                <span class="nav-logo-badge">Community</span>
            </a>

            <div class="nav-links">
                <a href="https://socialapparatus.com">Main Site</a>
                <a href="https://socialapparatus.com/docs">Documentation</a>
                <a href="https://github.com/mrshanebarron/socialapparatus" target="_blank">
                    <svg style="width:16px;height:16px;margin-right:4px;vertical-align:middle;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    GitHub
                </a>
            </div>

            <div class="nav-auth">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="btn btn-ghost">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-ghost">Sign in</a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Join Community</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                Official Community
            </div>

            <h1>The <span class="gradient-text">SocialApparatus</span> Community</h1>

            <p class="hero-desc">
                Connect with developers, get support, share your implementations, and help shape the future of self-hosted social networking.
            </p>

            <div class="hero-buttons">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-large">
                        Join the Community
                        <svg style="width:20px;height:20px;margin-left:8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="https://socialapparatus.com/docs" class="btn btn-outline btn-large">Read the Docs</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">140+</div>
                    <div class="stat-label">Features</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">TALL</div>
                    <div class="stat-label">Stack</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">MIT</div>
                    <div class="stat-label">License</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Self-Hosted</div>
                </div>
            </div>
        </div>
    </section>

    <!-- What is SocialApparatus -->
    <section class="section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2>What is <span class="gradient-text">SocialApparatus</span>?</h2>
                    <div class="about-text">
                        <p>
                            <strong>SocialApparatus</strong> is a powerful, open-source social network platform that you can self-host on your own servers. It gives you all the features of major social networks while keeping you in complete control of your data.
                        </p>
                        <p>
                            Built on the <strong>TALL stack</strong> (Tailwind CSS, Alpine.js, Laravel, Livewire), it's designed for developers who want a solid foundation for building community-driven applications.
                        </p>
                        <p>
                            Whether you're building an internal company network, a niche community platform, or the next big social app - SocialApparatus gives you the tools to make it happen.
                        </p>
                    </div>
                    <div class="tech-tags">
                        <span class="tech-tag tech-tag-violet">Laravel 12</span>
                        <span class="tech-tag tech-tag-cyan">Livewire 3</span>
                        <span class="tech-tag tech-tag-emerald">Tailwind CSS</span>
                        <span class="tech-tag tech-tag-amber">Alpine.js</span>
                    </div>
                </div>
                <div class="code-card">
                    <div class="code-label"># Quick Install</div>
                    <div class="code-block">
                        <code>composer create-project socialapparatus/socialapparatus</code>
                    </div>
                    <div class="code-label"># Or clone from GitHub</div>
                    <div class="code-block">
                        <code class="cyan">git clone https://github.com/mrshanebarron/socialapparatus.git</code>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Join -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Join the Community?</h2>
                <p class="section-desc">Connect with fellow developers, get help, and contribute to the project.</p>
            </div>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3>Get Support</h3>
                    <p>Ask questions, troubleshoot issues, and get help from experienced developers who know the codebase.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3>Share Ideas</h3>
                    <p>Propose new features, discuss implementation approaches, and help shape the roadmap.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3>Showcase Projects</h3>
                    <p>Share what you've built with SocialApparatus and inspire others with your implementations.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3>Contribute Code</h3>
                    <p>Submit pull requests, fix bugs, and add features. Every contribution makes the platform better.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3>Learn & Grow</h3>
                    <p>Improve your Laravel, Livewire, and full-stack skills by working with a real-world codebase.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3>Network</h3>
                    <p>Connect with other developers, find collaborators, and build relationships in the community.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Platform Features</h2>
                <p class="section-desc">A complete social networking toolkit out of the box.</p>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-emoji">👤</div>
                    <h4>User Profiles</h4>
                    <p>Rich profiles with avatars, bios, and custom fields</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">📝</div>
                    <h4>Posts & Feed</h4>
                    <p>Text, images, videos with rich interactions</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">💬</div>
                    <h4>Messaging</h4>
                    <p>Real-time private and group messaging</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">👥</div>
                    <h4>Groups</h4>
                    <p>Public and private community groups</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">📅</div>
                    <h4>Events</h4>
                    <p>Create and manage community events</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">🔔</div>
                    <h4>Notifications</h4>
                    <p>Real-time in-app and email notifications</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">🛡️</div>
                    <h4>Admin Panel</h4>
                    <p>Full moderation and management tools</p>
                </div>
                <div class="feature-item">
                    <div class="feature-emoji">🎨</div>
                    <h4>Theming</h4>
                    <p>Customizable themes and branding</p>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="https://socialapparatus.com/docs/features" class="features-link">
                    See all 140+ features
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section cta-section">
        <div class="container cta-content">
            <h2>Ready to get involved?</h2>
            <p>Join the community, contribute to the project, or just say hello. We're building something great together.</p>
            <div class="cta-buttons">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-large">Join the Community</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="https://github.com/mrshanebarron/socialapparatus" target="_blank" class="btn btn-outline btn-large">
                    <svg style="width:20px;height:20px;margin-right:8px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    Star on GitHub
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <a href="/" class="footer-logo">
                    <img src="/images/logo.svg" alt="SA" class="nav-logo-icon" style="width: 40px; height: 40px; border-radius: 12px;">
                    <span class="nav-logo-text">SocialApparatus</span>
                </a>
                <div class="footer-links">
                    <a href="https://socialapparatus.com">Main Site</a>
                    <a href="https://socialapparatus.com/docs">Docs</a>
                    <a href="https://github.com/mrshanebarron/socialapparatus">GitHub</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>Built with love by <a href="https://sbarron.com">Shane Barron</a>. Open source under the MIT License.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH /var/www/community.socialapparatus.com/resources/views/welcome.blade.php ENDPATH**/ ?>