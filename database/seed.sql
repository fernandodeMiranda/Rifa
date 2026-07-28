-- =====================================================================
-- Seed inicial — usuário administrador padrão
-- Senha: "admin123" (troque imediatamente após o primeiro acesso)
-- Hash gerado com password_hash('admin123', PASSWORD_BCRYPT)
-- =====================================================================

INSERT INTO usuarios (nome, email, telefone, senha_hash, tipo, status)
VALUES (
    'Administrador',
    'admin@rifa.local',
    '(00) 00000-0000',
    '$2y$10$f9Jx.uW7.8UC5wHaNuoZk.SPp6yJwBgbwRe1Tnot3qPFkMermElnW',
    'administrador',
    'ativo'
);
