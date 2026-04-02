@extends('layouts.app')

@section('title', 'Dashboard — Hospital Bed Monitoring')

@section('content')
<style>
    /* ── Page header ── */
    .dash-header {
        margin-bottom: 2rem;
    }
    .dash-greeting {
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.2;
    }
    .dash-subtitle {
        font-size: .9rem;
        color: #6b7280;
        margin-top: .3rem;
    }

    /* ── Stat Cards ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem 1.5rem 1.25rem;
        color: #fff;
        display: flex;
        flex-direction: column;
        gap: .5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.12);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,.18);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
        pointer-events: none;
    }
    .stat-card.indigo { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); }
    .stat-card.green  { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .stat-card.red    { background: linear-gradient(135deg, #dc2626 0%, #f87171 100%); }
    .stat-card.blue   { background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); }
    .stat-card.violet { background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); }

    .stat-icon {
        font-size: 2.2rem;
        line-height: 1;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
    }
    .stat-value {
        font-size: 2.8rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -.02em;
    }
    .stat-label {
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .07em;
        opacity: .85;
    }

    /* ── Two-column layout ── */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 700px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 420px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    /* ── Card ── */
    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .card-header {
        padding: 1.1rem 1.4rem;
        border-bottom: 1px solid #f0f0f5;
        font-size: .95rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: .5rem;
        background: #fafafa;
    }
    .card-body { padding: 1.1rem 1.4rem; }

    /* ── Recent reports list ── */
    .report-list { list-style: none; }
    .report-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .7rem 0;
        border-bottom: 1px solid #f3f4f6;
        gap: .5rem;
    }
    .report-item:last-child { border-bottom: none; }
    .report-room {
        font-weight: 600;
        color: #111827;
        font-size: .875rem;
    }
    .report-meta {
        font-size: .78rem;
        color: #6b7280;
        margin-top: .15rem;
    }
    .shift-badge {
        display: inline-block;
        font-size: .7rem;
        font-weight: 700;
        padding: .15rem .55rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    .shift-pagi   { background: #fef9c3; color: #92400e; }
    .shift-sore   { background: #ffedd5; color: #9a3412; }
    .shift-malam  { background: #ede9fe; color: #4c1d95; }
    .report-time  { font-size: .78rem; color: #9ca3af; white-space: nowrap; }
    .empty-list   { text-align: center; color: #9ca3af; font-size: .875rem; padding: 1.5rem 0; }

    /* ── Quick Actions ── */
    .quick-actions { display: flex; flex-direction: column; gap: .85rem; }
    .qa-btn {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: .9rem;
        font-weight: 700;
        transition: transform .18s, box-shadow .18s, filter .18s;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .qa-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0);
        transition: background .18s;
    }
    .qa-btn:hover::before { background: rgba(255,255,255,.1); }
    .qa-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,.2);
    }
    .qa-btn:active { transform: translateY(0); }
    .qa-btn .qa-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
        filter: drop-shadow(0 1px 3px rgba(0,0,0,.2));
    }
    .qa-btn .qa-text { flex: 1; }
    .qa-btn .qa-arrow {
        font-size: .85rem;
        opacity: .7;
        transition: transform .18s;
    }
    .qa-btn:hover .qa-arrow { transform: translateX(4px); opacity: 1; }
    .qa-btn.indigo { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); box-shadow: 0 3px 12px rgba(79,70,229,.35); }
    .qa-btn.green  { background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 3px 12px rgba(5,150,105,.35); }
    .qa-btn.amber  { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); box-shadow: 0 3px 12px rgba(217,119,6,.35); }

    .qa-badge {
        background: rgba(255,255,255,.25);
        border: 1px solid rgba(255,255,255,.4);
        border-radius: 20px;
        padding: .1rem .55rem;
        font-size: .72rem;
        font-weight: 700;
        white-space: nowrap;
    }
</style>

{{-- Page Header --}}
<div class="dash-header">
    <div class="dash-greeting">👋 Selamat datang, {{ auth()->user()->name }}</div>
    <div class="dash-subtitle">Berikut ringkasan kondisi bed rumah sakit hari ini.</div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card indigo">
        <div class="stat-icon">🏥</div>
        <div class="stat-value">{{ $stats['total_rooms'] }}</div>
        <div class="stat-label">Total Ruangan</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">🛏️</div>
        <div class="stat-value">{{ $stats['total_available'] }}</div>
        <div class="stat-label">Bed Tersedia</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">🔴</div>
        <div class="stat-value">{{ $stats['total_occupied'] }}</div>
        <div class="stat-label">Bed Terisi</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">📋</div>
        <div class="stat-value">{{ $stats['reports_today'] }}</div>
        <div class="stat-label">Laporan Hari Ini</div>
    </div>
    <div class="stat-card violet">
        <div class="stat-icon">🔔</div>
        <div class="stat-value">{{ $stats['unread_notifications'] }}</div>
        <div class="stat-label">Notifikasi Belum Dibaca</div>
    </div>
</div>

{{-- Bottom Grid: Recent Reports + Quick Actions --}}
<div class="dashboard-grid">

    {{-- 5 Laporan Terbaru --}}
    <div class="card">
        <div class="card-header">
            📄 5 Laporan Amprahan Terbaru
        </div>
        <div class="card-body">
            @if($recentReports->isEmpty())
                <div class="empty-list">Belum ada laporan amprahan.</div>
            @else
                <ul class="report-list">
                    @foreach($recentReports as $report)
                        <li class="report-item">
                            <div>
                                <div class="report-room">
                                    {{ $report->room?->name ?? '—' }}
                                </div>
                                <div class="report-meta">
                                    <span class="shift-badge shift-{{ $report->shift }}">
                                        {{ ucfirst($report->shift) }}
                                    </span>
                                </div>
                            </div>
                            <div class="report-time">
                                {{ $report->created_at->diffForHumans() }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <div class="card-header">
            ⚡ Quick Actions
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="{{ route('amprahans.create') }}" class="qa-btn indigo">
                    <span class="qa-icon">📝</span>
                    <span class="qa-text">Buat Laporan Amprahan Baru</span>
                    <span class="qa-arrow">→</span>
                </a>
                <a href="{{ route('rooms.index') }}" class="qa-btn green">
                    <span class="qa-icon">🏥</span>
                    <span class="qa-text">Kelola Ruangan</span>
                    <span class="qa-arrow">→</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="qa-btn amber">
                    <span class="qa-icon">🔔</span>
                    <span class="qa-text">Lihat Notifikasi</span>
                    @if($stats['unread_notifications'] > 0)
                        <span class="qa-badge">{{ $stats['unread_notifications'] }} baru</span>
                    @endif
                    <span class="qa-arrow">→</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
