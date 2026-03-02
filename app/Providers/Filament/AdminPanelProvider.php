<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Indigo,
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Sky,
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->brandName('MiNegocio')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(''),
                NavigationGroup::make()
                    ->label('Finanzas')
                    ->icon('heroicon-o-banknotes')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Inventario')
                    ->icon('heroicon-o-cube')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Configuracion')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed(),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <link rel="preconnect" href="https://fonts.bunny.net">
                    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
                    <style>
                        /* ══════════════════════════════════════
                           MINEGOCIO – Premium Dark Theme
                           ══════════════════════════════════════ */

                        /* ── Sidebar: gradient oscuro con accent indigo ── */
                        .fi-sidebar {
                            background: linear-gradient(180deg, #0c0a1d 0%, #1a1145 50%, #0f0d2e 100%) !important;
                            border-right: 1px solid rgba(139, 92, 246, 0.1) !important;
                        }
                        .fi-sidebar .fi-sidebar-header {
                            border-color: rgba(139, 92, 246, 0.12) !important;
                        }
                        .fi-sidebar .fi-brand-name {
                            color: #fff !important;
                            font-weight: 800 !important;
                            font-size: 1.15rem !important;
                            letter-spacing: -0.01em !important;
                        }
                        /* ALL sidebar text + icons: ALWAYS white */
                        .fi-sidebar .fi-sidebar-group-label {
                            color: rgba(255, 255, 255, 0.65) !important;
                            font-size: 0.6rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.1em !important;
                            font-weight: 700 !important;
                        }
                        .fi-sidebar .fi-sidebar-item a,
                        .fi-sidebar .fi-sidebar-item a span,
                        .fi-sidebar .fi-sidebar-item a .fi-sidebar-item-label {
                            color: #ffffff !important;
                            border-radius: 0.6rem !important;
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            margin: 0 0.35rem !important;
                        }
                        .fi-sidebar .fi-sidebar-item a .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }
                        .fi-sidebar .fi-sidebar-item a:hover {
                            background: rgba(139, 92, 246, 0.12) !important;
                            color: #ffffff !important;
                        }
                        .fi-sidebar .fi-sidebar-item a:hover .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }
                        /* Active/selected item: white bg, dark text + icon */
                        .fi-sidebar .fi-sidebar-item a.fi-active {
                            background: rgba(255, 255, 255, 0.95) !important;
                            font-weight: 600 !important;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
                        }
                        .fi-sidebar .fi-sidebar-item a.fi-active,
                        .fi-sidebar .fi-sidebar-item a.fi-active span,
                        .fi-sidebar .fi-sidebar-item a.fi-active .fi-sidebar-item-label {
                            color: #1e1b4b !important;
                        }
                        .fi-sidebar .fi-sidebar-item a.fi-active .fi-sidebar-item-icon {
                            color: #1e1b4b !important;
                        }
                        .fi-sidebar .fi-sidebar-group-toggle-button {
                            color: #ffffff !important;
                        }
                        .fi-sidebar-nav-groups {
                            --c-50: 241 245 249;
                            --c-200: 148 163 184;
                            --c-400: 99 102 241;
                            --c-600: 79 70 229;
                        }
                        /* Keep sidebar open after navigation */
                        .fi-sidebar {
                            transition: none !important;
                        }

                        /* ── Top bar: blanca limpia con shadow sutil ── */
                        .fi-topbar {
                            background: #ffffff !important;
                            border-bottom: none !important;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02) !important;
                        }

                        /* ── Content area: gris muy suave ── */
                        .fi-body {
                            background: #f4f6fb !important;
                        }

                        /* ── Page header ── */
                        .fi-header-heading {
                            font-weight: 800 !important;
                            color: #1e1b4b !important;
                            letter-spacing: -0.02em !important;
                        }
                        .fi-header-subheading {
                            color: #6b7280 !important;
                        }

                        /* ── Stats widgets: blue gradient cards ── */
                        .fi-wi-stats-overview-stat {
                            border-radius: 1rem !important;
                            border: none !important;
                            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%) !important;
                            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1), 0 1px 3px rgba(0,0,0,0.04) !important;
                            padding: 0.6rem 0.5rem !important;
                            overflow: hidden !important;
                            transition: transform 0.15s ease, box-shadow 0.15s ease !important;
                            text-align: center !important;
                            display: flex !important;
                            flex-direction: column !important;
                            align-items: center !important;
                            justify-content: center !important;
                        }
                        .fi-wi-stats-overview-stat:hover {
                            transform: translateY(-2px) !important;
                            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.18), 0 2px 6px rgba(0,0,0,0.06) !important;
                            cursor: pointer !important;
                        }
                        /* Center inner content */
                        .fi-wi-stats-overview-stat > * {
                            justify-content: center !important;
                            text-align: center !important;
                            width: 100% !important;
                            padding: 0 !important;
                            margin: 0 !important;
                        }
                        /* Stat label */
                        .fi-wi-stats-overview-stat-label {
                            font-weight: 700 !important;
                            font-size: 0.72rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.04em !important;
                            color: #1E40AF !important;
                            text-align: center !important;
                            width: 100% !important;
                        }
                        /* Stat value (amount) — always fit inside card */
                        .fi-wi-stats-overview-stat-value {
                            font-weight: 800 !important;
                            font-size: clamp(0.75rem, 2vw, 1.3rem) !important;
                            color: #1E3A5F !important;
                            line-height: 1.2 !important;
                            white-space: nowrap !important;
                            overflow: hidden !important;
                            text-overflow: ellipsis !important;
                            max-width: 100% !important;
                            text-align: center !important;
                            width: 100% !important;
                            display: block !important;
                            box-sizing: border-box !important;
                        }
                        /* Stat description */
                        .fi-wi-stats-overview-stat-description {
                            font-weight: 600 !important;
                            font-size: 0.68rem !important;
                            color: #3B82F6 !important;
                            text-align: center !important;
                            width: 100% !important;
                            justify-content: center !important;
                            white-space: nowrap !important;
                            overflow: hidden !important;
                            text-overflow: ellipsis !important;
                        }
                        /* Stat icon */
                        .fi-wi-stats-overview-stat-icon {
                            color: #3B82F6 !important;
                            opacity: 0.7 !important;
                        }
                        /* Chart inside stat */
                        .fi-wi-stats-overview-stat-chart {
                            opacity: 0.6 !important;
                        }

                        /* ── Charts ── */
                        .fi-wi-chart {
                            border-radius: 1rem !important;
                            border: none !important;
                            background: linear-gradient(135deg, #ffffff 0%, #F0F5FF 100%) !important;
                            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.08), 0 1px 3px rgba(0,0,0,0.04) !important;
                        }

                        /* ── Tables ── */
                        .fi-ta {
                            border-radius: 1rem !important;
                            overflow: hidden !important;
                            border: none !important;
                            background: white !important;
                            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.06), 0 1px 3px rgba(0,0,0,0.03) !important;
                        }
                        .fi-ta-header-cell {
                            background: linear-gradient(135deg, #EFF6FF, #DBEAFE) !important;
                            color: #1E40AF !important;
                            font-weight: 700 !important;
                            text-transform: uppercase !important;
                            font-size: 0.7rem !important;
                            letter-spacing: 0.05em !important;
                            border-bottom: 1px solid #BFDBFE !important;
                        }
                        .fi-ta-row:nth-child(even) {
                            background: #F0F5FF !important;
                        }
                        .fi-ta-row:nth-child(odd) {
                            background: #ffffff !important;
                        }
                        .fi-ta-row {
                            transition: background 0.15s ease !important;
                        }
                        .fi-ta-row:hover {
                            background: #DBEAFE !important;
                        }

                        /* ── Forms ── */
                        .fi-section {
                            border: none !important;
                            border-radius: 1rem !important;
                            background: linear-gradient(135deg, #ffffff 0%, #F0F5FF 100%) !important;
                            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.06), 0 1px 3px rgba(0,0,0,0.03) !important;
                        }

                        /* ── Compact form fields ── */
                        .fi-fo-field-wrp {
                            gap: 0.25rem !important;
                        }
                        .fi-input, .fi-select-input, .fi-fo-textarea textarea {
                            padding-top: 0.45rem !important;
                            padding-bottom: 0.45rem !important;
                            font-size: 0.85rem !important;
                        }
                        .fi-fo-field-wrp label {
                            font-size: 0.78rem !important;
                            font-weight: 600 !important;
                            color: #475569 !important;
                        }
                        .fi-section-content {
                            padding: 0.75rem !important;
                        }
                        .fi-section-header {
                            padding: 0.6rem 0.75rem !important;
                        }
                        .fi-compact .fi-section-content {
                            padding: 0.5rem 0.75rem !important;
                        }

                        /* ── Infolist entries (view modal) ── */
                        .fi-in-entry-wrp {
                            padding: 0.5rem 0 !important;
                        }

                        /* ── Modal improvements ── */
                        .fi-modal-window {
                            border-radius: 0.75rem !important;
                            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
                        }
                        .fi-modal-header {
                            border-bottom: 1px solid #e2e8f0 !important;
                            padding: 0.85rem 1.25rem !important;
                        }
                        .fi-modal-heading {
                            font-size: 1rem !important;
                            font-weight: 700 !important;
                            color: #1e293b !important;
                        }
                        .fi-modal-footer {
                            border-top: 1px solid #e2e8f0 !important;
                            padding: 0.65rem 1.25rem !important;
                            background: #f8fafc !important;
                        }

                        /* ── Action buttons in table ── */
                        .fi-ta-actions {
                            gap: 0.15rem !important;
                        }
                        .fi-icon-btn {
                            transition: all 0.15s ease !important;
                        }
                        .fi-icon-btn:hover {
                            transform: scale(1.08) !important;
                        }

                        /* ── Buttons ── */
                        .fi-btn-primary {
                            border-radius: 0.5rem !important;
                            font-weight: 600 !important;
                        }

                        /* ── Scrollbar ── */
                        .fi-sidebar::-webkit-scrollbar {
                            width: 4px;
                        }
                        .fi-sidebar::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        .fi-sidebar::-webkit-scrollbar-thumb {
                            background: rgba(255, 255, 255, 0.15);
                            border-radius: 4px;
                        }

                        /* ── Modal ── */
                        .fi-modal-window {
                            border-radius: 0.75rem !important;
                        }
                    </style>
                    <script>
                        // Keep sidebar expanded after Livewire/Turbolinks navigation
                        document.addEventListener("livewire:navigated", function () {
                            if (window.Alpine && Alpine.store("sidebar")) {
                                Alpine.store("sidebar").open();
                            }
                        });
                    </script>
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ]);
    }
}
