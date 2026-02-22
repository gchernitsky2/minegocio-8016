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
                        .fi-sidebar .fi-sidebar-group-label {
                            color: rgba(167, 139, 250, 0.5) !important;
                            font-size: 0.6rem !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.1em !important;
                            font-weight: 700 !important;
                        }
                        .fi-sidebar .fi-sidebar-item a {
                            color: rgba(203, 213, 225, 0.7) !important;
                            border-radius: 0.6rem !important;
                            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            margin: 0 0.35rem !important;
                        }
                        .fi-sidebar .fi-sidebar-item a:hover {
                            background: rgba(139, 92, 246, 0.12) !important;
                            color: #e2e8f0 !important;
                        }
                        .fi-sidebar .fi-sidebar-item a.fi-active {
                            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(139, 92, 246, 0.2) 100%) !important;
                            color: #fff !important;
                            font-weight: 600 !important;
                            box-shadow: 0 0 20px rgba(99, 102, 241, 0.1), inset 0 0 0 1px rgba(139, 92, 246, 0.2) !important;
                        }
                        .fi-sidebar .fi-sidebar-item a .fi-sidebar-item-icon {
                            color: rgba(148, 163, 184, 0.5) !important;
                        }
                        .fi-sidebar .fi-sidebar-item a.fi-active .fi-sidebar-item-icon {
                            color: #a78bfa !important;
                        }
                        .fi-sidebar .fi-sidebar-item a:hover .fi-sidebar-item-icon {
                            color: #c4b5fd !important;
                        }
                        .fi-sidebar .fi-sidebar-group-toggle-button {
                            color: rgba(148, 163, 184, 0.4) !important;
                        }
                        .fi-sidebar-nav-groups {
                            --c-50: 241 245 249;
                            --c-200: 148 163 184;
                            --c-400: 99 102 241;
                            --c-600: 79 70 229;
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

                        /* ── Stats widgets: glassmorphism ── */
                        .fi-wi-stats-overview-stat {
                            border-radius: 1rem !important;
                            border: 1px solid rgba(99, 102, 241, 0.08) !important;
                            background: white !important;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04) !important;
                            transition: all 0.2s ease !important;
                        }
                        .fi-wi-stats-overview-stat:hover {
                            box-shadow: 0 4px 12px rgba(99,102,241,0.08), 0 12px 32px rgba(99,102,241,0.06) !important;
                            transform: translateY(-1px) !important;
                        }

                        /* ── Charts ── */
                        .fi-wi-chart {
                            border-radius: 1rem !important;
                            border: 1px solid rgba(99, 102, 241, 0.08) !important;
                            background: white !important;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04) !important;
                        }

                        /* ── Tables: elegante con header gradient ── */
                        .fi-ta {
                            border-radius: 1rem !important;
                            overflow: hidden !important;
                            border: 1px solid rgba(99, 102, 241, 0.08) !important;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04) !important;
                            background: white !important;
                        }
                        .fi-ta-header-cell {
                            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%) !important;
                            color: #e0e7ff !important;
                            font-weight: 600 !important;
                            text-transform: uppercase !important;
                            font-size: 0.68rem !important;
                            letter-spacing: 0.06em !important;
                        }
                        .fi-ta-row:nth-child(even) {
                            background: #fafaff !important;
                        }
                        .fi-ta-row:nth-child(odd) {
                            background: #ffffff !important;
                        }
                        .fi-ta-row {
                            transition: all 0.15s ease !important;
                        }
                        .fi-ta-row:hover {
                            background: #eef2ff !important;
                        }

                        /* ── Forms ── */
                        .fi-section {
                            border: 1px solid rgba(99, 102, 241, 0.08) !important;
                            border-radius: 1rem !important;
                            box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 8px 24px rgba(99,102,241,0.04) !important;
                            background: white !important;
                        }

                        /* ── Buttons: gradient premium ── */
                        .fi-btn-primary {
                            border-radius: 0.6rem !important;
                            font-weight: 600 !important;
                            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25) !important;
                            transition: all 0.2s ease !important;
                        }
                        .fi-btn-primary:hover {
                            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.35) !important;
                            transform: translateY(-1px) !important;
                        }

                        /* ── Pagination ── */
                        .fi-pagination-item-btn {
                            border-radius: 0.5rem !important;
                        }

                        /* ── Scrollbar personalizado ── */
                        .fi-sidebar::-webkit-scrollbar {
                            width: 4px;
                        }
                        .fi-sidebar::-webkit-scrollbar-track {
                            background: transparent;
                        }
                        .fi-sidebar::-webkit-scrollbar-thumb {
                            background: rgba(139, 92, 246, 0.2);
                            border-radius: 4px;
                        }

                        /* ── Modal ── */
                        .fi-modal-window {
                            border-radius: 1rem !important;
                        }

                        /* ── Badges ── */
                        .fi-badge {
                            border-radius: 0.5rem !important;
                            font-weight: 600 !important;
                        }

                        /* ── Tabs ── */
                        .fi-tabs-tab {
                            transition: all 0.15s ease !important;
                        }
                    </style>
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
