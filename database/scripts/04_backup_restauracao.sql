-- =====================================================
-- BACKUP E RESTAURACAOO - Banco da farmacia (Neon / PostgreSQL)
-- Schema: samaraferreira_bd
-- Os comandos abaixo rodam no PROMPT (terminal), nao no SQL Editor.
-- =====================================================

-- 1) BACKUP em arquivo texto (estrutura + dados)
-- pg_dump "STRING_DE_CONEXAO" --no-owner --no-privileges -f backup_completo.sql

-- 2) BACKUP compactado (formato custom, restauravel com pg_restore)
-- pg_dump "STRING_DE_CONEXAO" --no-owner --no-privileges -Fc -f backup_farmacia.dump

-- 3) BACKUP separado (usado neste projeto)
-- pg_dump "STRING_DE_CONEXAO" --schema-only --no-owner --no-privileges -f 01_estrutura.sql
-- pg_dump "STRING_DE_CONEXAO" --data-only --no-owner --no-privileges --column-inserts -f 02_dados.sql

-- 4) RESTAURACAO em um banco de teste
-- pg_restore --no-owner --no-privileges -d "STRING_DO_BANCO_DE_TESTE" backup_farmacia.dump
-- ou, para o arquivo texto:
-- psql "STRING_DO_BANCO_DE_TESTE" -f backup_completo.sql

-- 5) BACKUP E RESTAURACAO PELO NEON (executado em 26/10/2026)
--    a) Branches > Create branch: "teste-restauracao" (copia da production)
--    b) Contagem inicial de avaliacoes: 150
--    c) Desastre simulado na branch de teste:
--         DELETE FROM samaraferreira_bd.avaliacoes;   -- 150 linhas apagadas
--    d) Backup & Restore > Restore from history, origem production, 26/10/2026 15:00
--       O Neon guardou a versao anterior como branch "teste-restauracao_old_..."
--    e) Verificacao apos o restore:
SELECT COUNT(*) AS avaliacoes FROM samaraferreira_bd.avaliacoes;  -- voltou a 150

-- 6) ATUALIZAR VIEW MATERIALIZADA apos restaurar ou carregar dados
REFRESH MATERIALIZED VIEW samaraferreira_bd.vw_samaraferreira_faturamento_por_funcionario;