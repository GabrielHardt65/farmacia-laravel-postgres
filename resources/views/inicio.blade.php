@extends('layouts.home')

@section('title', 'Início - Sistema Farmácia')

@section('content')
<div class="farmacia-home">
    <section class="farmacia-hero">
        <div class="farmacia-hero-copy">
            <span class="farmacia-hero-label">SISTEMA DA FARMÁCIA</span>
            <h1>Olá, {{ Auth::user()?->nome ?? 'Funcionário' }}!</h1>
            <p>Bem-vindo ao sistema de gestão da farmácia.<br>Selecione uma opção para começar.</p>
        </div>
        <div class="farmacia-hero-art" aria-hidden="true">
            <span class="farmacia-hero-cross">✚</span>
            <div class="farmacia-shelf">
                <div class="farmacia-shelf-row"><i class="medicine-box tall"></i><i class="medicine-box"></i><i class="medicine-bottle"></i><i class="medicine-box short"></i><i class="medicine-box tall"></i></div>
                <div class="farmacia-shelf-row"><i class="medicine-bottle"></i><i class="medicine-box tall"></i><i class="medicine-box short"></i><i class="medicine-bottle"></i><i class="medicine-box"></i></div>
            </div>
        </div>
    </section>

    <section class="farmacia-management" aria-labelledby="farmacia-management-title">
        <h2 id="farmacia-management-title">Gestão da Farmácia</h2>
        <div class="farmacia-shortcuts">
            <a href="{{ route('vendas.create') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.6 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Nova Venda</strong><small>Registre vendas e atendimentos</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
            <a href="{{ route('receitas.index') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3.5h6M9 9h6M9 13h6M9 17h4"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Receitas</strong><small>Consulte e gerencie receitas</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
            <a href="{{ route('produtos.index') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10.5 4.5a5 5 0 0 1 7.1 7.1l-6 6a5 5 0 0 1-7.1-7.1z"/><path d="m8 8 8 8"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Produtos</strong><small>Controle o estoque de medicamentos</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
            <a href="{{ route('clientes.index') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2zM17 5a3 3 0 0 1 0 6M18 14a5 5 0 0 1 3 5v1"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Clientes</strong><small>Cadastre e consulte clientes</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
            <a href="{{ route('funcionarios.index') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="3"/><path d="M5 21v-2a7 7 0 0 1 14 0v2zM12 13v6M9 16h6"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Funcionários</strong><small>Gerencie a equipe da farmácia</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
            <a href="{{ route('relatorios.index') }}" class="farmacia-shortcut">
                <span class="farmacia-shortcut-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 3v18h18"/><rect x="6" y="12" width="3" height="6" rx=".5"/><rect x="11" y="8" width="3" height="10" rx=".5"/><rect x="16" y="5" width="3" height="13" rx=".5"/></svg></span>
                <span class="farmacia-shortcut-text"><strong>Relatórios</strong><small>Acompanhe os resultados</small></span><span class="farmacia-shortcut-arrow">›</span>
            </a>
        </div>
    </section>
</div>

<style>
    .dashboard-content:has(.farmacia-home) { background: #fff9fc; padding: 24px 28px !important; }
    .farmacia-home { width: 100%; max-width: 1400px; margin: 0 auto; }
    .farmacia-hero { position: relative; display: flex; align-items: center; justify-content: space-between; min-height: 190px; padding: 28px 42px; border-radius: 20px; overflow: hidden; background: linear-gradient(110deg, #242630 0%, #30323e 56%, #424451 100%) !important; color: #fff; box-shadow: 0 10px 24px rgba(36,38,48,.13); }
    .farmacia-hero-copy { position: relative; z-index: 2; max-width: 700px; }
    .farmacia-hero-label { display: inline-block; padding: 7px 14px; border-radius: 30px; border: 1px solid rgba(255,151,195,.45); background: #f8dce9; color: #a92161 !important; font-size: 11px; font-weight: 800; letter-spacing: 1.4px; margin-bottom: 14px; }
    .farmacia-hero h1 { color: #fff !important; font-size: clamp(26px,2.8vw,37px); font-weight: 800; line-height: 1.2; margin: 0 0 9px; }
    .farmacia-hero p { margin: 0; color: #f7edf3 !important; font-size: 14px; line-height: 1.65; }
    .farmacia-hero-art { position: relative; width: 270px; height: 155px; flex: 0 0 270px; margin-right: 18px; }
    .farmacia-hero-cross { position: absolute; right: -45px; top: -65px; font-size: 190px; color: rgba(255,255,255,.08); }
    .farmacia-shelf { position: absolute; inset: 12px 15px 0; display: flex; flex-direction: column; justify-content: flex-end; gap: 8px; transform: rotate(-3deg); filter: drop-shadow(0 8px 8px rgba(0,0,0,.22)); }
    .farmacia-shelf-row { height: 65px; border-bottom: 8px solid #fff0f6; border-radius: 3px; display: flex; align-items: flex-end; justify-content: space-evenly; padding: 0 10px; }
    .medicine-box { display: block; width: 33px; height: 44px; background: linear-gradient(90deg,#fff 0 76%,#f7c2d8 76%); border: 2px solid #f5e1e9; border-radius: 3px; box-shadow: inset 0 13px #e8a6c1; }
    .medicine-box.tall { height: 55px; box-shadow: inset 0 17px #d45688; }
    .medicine-box.short { height: 35px; box-shadow: inset 0 10px #a7d9d6; }
    .medicine-bottle { display: block; width: 28px; height: 37px; border-radius: 6px 6px 4px 4px; background: linear-gradient(#fff8fc 0 45%,#e5a0be 45% 65%,#fff8fc 65%); position: relative; }
    .medicine-bottle:before { content: ''; position: absolute; width: 18px; height: 8px; background: #f8e3ed; border-radius: 3px 3px 0 0; left: 5px; top: -8px; }
    .farmacia-management { margin-top: 26px; }
    .farmacia-management h2 { margin: 0 0 17px; padding-left: 14px; border-left: 5px solid #df4788; color: #292934; font-size: 22px; font-weight: 800; }
    .farmacia-shortcuts { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 16px; }
    .farmacia-shortcut { min-width: 0; min-height: 108px; display: flex; align-items: center; gap: 15px; padding: 18px; border: 1px solid #f6e8ef; border-radius: 17px; background: #fff; color: inherit; text-decoration: none; box-shadow: 0 5px 16px rgba(90,45,65,.045); transition: transform .2s,box-shadow .2s; }
    .farmacia-shortcut:hover { transform: translateY(-3px); box-shadow: 0 10px 23px rgba(153,49,95,.12); }
    .farmacia-shortcut-icon { flex: 0 0 53px; height: 53px; display: grid; place-items: center; border-radius: 13px; background: #fdeaf2; color: #d83e80; }
    .farmacia-shortcut-icon svg { width: 27px; height: 27px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .farmacia-shortcut-text { display: flex; flex-direction: column; gap: 5px; min-width: 0; }
    .farmacia-shortcut-text strong { color: #282934; font-size: 16px; font-weight: 800; }
    .farmacia-shortcut-text small { color: #81818e; font-size: 12px; line-height: 1.45; }
    .farmacia-shortcut-arrow { margin-left: auto; color: #db4b8b; font-size: 30px; }
    @media(max-width:1050px) { .farmacia-shortcuts { grid-template-columns: repeat(2,minmax(0,1fr)); } }
    @media(max-width:700px) { .dashboard-content:has(.farmacia-home) { padding: 15px !important; } .farmacia-hero { min-height: 185px; padding: 24px; } .farmacia-hero-art { display: none; } .farmacia-shortcuts { grid-template-columns: 1fr; gap: 12px; } .farmacia-shortcut { min-height: 94px; } }
</style>
@endsection
