{{-- Google Fonts: Plus Jakarta Sans --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

<style>
    /* Global Typography */
    body, html, * {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Minimalist Futuristic Scrollbars */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(100, 116, 139, 0.3);
        border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(100, 116, 139, 0.6);
    }

    /* Modern Card & Table Elevation */
    .fi-ta-ctn, .fi-section, .fi-wi-stats-overview-stat {
        border-radius: 1rem !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* Subtle Glassmorphism on Header & Topbar */
    .fi-topbar {
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
    }

    /* Futuristic glowing accent on focused inputs */
    input:focus, select:focus, textarea:focus {
        transition: box-shadow 0.2s ease, border-color 0.2s ease !important;
    }

    /* Auth / Login Page Background Ambient Glow */
    .fi-simple-layout {
        position: relative;
        overflow: hidden;
    }
    .fi-simple-layout::before {
        content: "";
        position: absolute;
        top: -20%;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 500px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(14, 165, 233, 0.08) 50%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }
    .dark .fi-simple-layout::before {
        background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(14, 165, 233, 0.15) 50%, transparent 70%);
    }

    /* Auth Card Glass Elevation */
    .fi-simple-main-ctn {
        position: relative;
        z-index: 1;
    }
    .fi-simple-main {
        border-radius: 1.25rem !important;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(148, 163, 184, 0.15) !important;
    }
    .dark .fi-simple-main {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.08) !important;
    }

    /* Stats Widget Hover Polish */
    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px);
    }
</style>
