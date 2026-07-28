-- =====================================================================
-- Sistema de Rifa Eletrônica — Schema SQL
-- Motor: MySQL 8+ / MariaDB 10.4+ (InnoDB, utf8mb4)
-- =====================================================================
-- RN01 - Um número pode assumir apenas um estado: Livre; Reservado; Pago;
--        Cancelado ou Premiado.
-- RN02 - Uma reserva expira automaticamente após 30 minutos.
-- RN03 - Após o envio do comprovante o número fica aguardando aprovação.
-- RN04 - Somente administrador ou organizador podem aprovar pagamentos.
-- RN05 - Após aprovação o número deve passar para o estado de "Pago".
-- RN06 - Somente números pagos participam do sorteio.
-- RN07 - Uma rifa encerrada não pode sofrer alterações.
-- RN08 - Após o sorteio não é permitido alterar o vencedor.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- usuarios
-- Participantes, organizadores e administradores na mesma tabela,
-- diferenciados pela coluna `tipo`.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome                  VARCHAR(150)    NOT NULL,
    email                 VARCHAR(150)    NOT NULL,
    telefone              VARCHAR(20)     NOT NULL,
    senha_hash            VARCHAR(255)    NOT NULL,
    tipo                  ENUM('participante', 'organizador', 'administrador') NOT NULL DEFAULT 'participante',
    status                ENUM('ativo', 'inativo', 'bloqueado') NOT NULL DEFAULT 'ativo',
    email_verificado_em   DATETIME        NULL,
    criado_em             TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email),
    KEY idx_usuarios_tipo (tipo)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- rifas
-- Cadastrada e administrada pelo organizador.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS rifas (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organizador_id        BIGINT UNSIGNED NOT NULL,
    titulo                VARCHAR(150)    NOT NULL,
    descricao             TEXT            NULL,
    imagem_capa           VARCHAR(255)    NULL,
    preco_numero          DECIMAL(10, 2)  NOT NULL,
    numero_inicial        INT UNSIGNED    NOT NULL DEFAULT 0,
    numero_final          INT UNSIGNED    NOT NULL,
    quantidade_numeros    INT UNSIGNED    GENERATED ALWAYS AS (numero_final - numero_inicial + 1) STORED,
    data_sorteio          DATETIME        NOT NULL,
    status                ENUM('rascunho', 'publicada', 'encerrada', 'cancelada') NOT NULL DEFAULT 'rascunho',
    publicada_em          DATETIME        NULL,
    encerrada_em          DATETIME        NULL,
    criado_em             TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_rifas_organizador FOREIGN KEY (organizador_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_rifas_faixa CHECK (numero_final >= numero_inicial),
    KEY idx_rifas_organizador (organizador_id),
    KEY idx_rifas_status (status)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- reservas
-- Agrupa a seleção de números feita por um participante em uma rifa.
-- RN02: expira_em controla o timeout de 30 minutos enquanto `status = ativa`.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservas (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rifa_id               BIGINT UNSIGNED NOT NULL,
    participante_id       BIGINT UNSIGNED NOT NULL,
    status                ENUM('ativa', 'aguardando_aprovacao', 'confirmada', 'expirada', 'cancelada', 'rejeitada') NOT NULL DEFAULT 'ativa',
    quantidade_numeros    SMALLINT UNSIGNED NOT NULL,
    valor_total           DECIMAL(10, 2)  NOT NULL,
    criado_em             TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expira_em             DATETIME        NOT NULL,
    confirmado_em         DATETIME        NULL,
    cancelado_em          DATETIME        NULL,
    CONSTRAINT fk_reservas_rifa FOREIGN KEY (rifa_id) REFERENCES rifas (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reservas_participante FOREIGN KEY (participante_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    KEY idx_reservas_rifa (rifa_id),
    KEY idx_reservas_participante (participante_id),
    KEY idx_reservas_status_expira (status, expira_em)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- numeros_rifa
-- RN01: status restrito aos 5 estados definidos pela regra de negócio.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS numeros_rifa (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rifa_id               BIGINT UNSIGNED NOT NULL,
    numero                INT UNSIGNED    NOT NULL,
    status                ENUM('livre', 'reservado', 'pago', 'cancelado', 'premiado') NOT NULL DEFAULT 'livre',
    participante_id       BIGINT UNSIGNED NULL,
    reserva_id            BIGINT UNSIGNED NULL,
    reservado_em          DATETIME        NULL,
    pago_em               DATETIME        NULL,
    CONSTRAINT fk_numeros_rifa_rifa FOREIGN KEY (rifa_id) REFERENCES rifas (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_numeros_rifa_participante FOREIGN KEY (participante_id) REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_numeros_rifa_reserva FOREIGN KEY (reserva_id) REFERENCES reservas (id) ON DELETE SET NULL ON UPDATE CASCADE,
    UNIQUE KEY uq_numeros_rifa_numero (rifa_id, numero),
    KEY idx_numeros_rifa_status (rifa_id, status),
    KEY idx_numeros_rifa_reserva (reserva_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- comprovantes
-- RN03: envio do comprovante coloca a reserva em "aguardando_aprovacao".
-- RN04/RN05: aprovação (por admin/organizador) confirma o pagamento.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS comprovantes (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reserva_id            BIGINT UNSIGNED NOT NULL,
    participante_id       BIGINT UNSIGNED NOT NULL,
    arquivo_path          VARCHAR(255)    NOT NULL,
    arquivo_nome_original VARCHAR(255)    NULL,
    valor_informado       DECIMAL(10, 2)  NULL,
    status                ENUM('pendente', 'aprovado', 'rejeitado') NOT NULL DEFAULT 'pendente',
    observacao            TEXT            NULL,
    enviado_em            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    analisado_em          DATETIME        NULL,
    analisado_por         BIGINT UNSIGNED NULL,
    CONSTRAINT fk_comprovantes_reserva FOREIGN KEY (reserva_id) REFERENCES reservas (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comprovantes_participante FOREIGN KEY (participante_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_comprovantes_analisado_por FOREIGN KEY (analisado_por) REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY idx_comprovantes_status (status),
    KEY idx_comprovantes_reserva (reserva_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- sorteios
-- RN06: apenas números pagos concorrem (garantido na camada de serviço).
-- RN08: um sorteio por rifa (UNIQUE), sem alteração posterior do vencedor.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sorteios (
    id                      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rifa_id                 BIGINT UNSIGNED NOT NULL,
    numero_vencedor_id      BIGINT UNSIGNED NOT NULL,
    participante_vencedor_id BIGINT UNSIGNED NOT NULL,
    metodo                  VARCHAR(50)     NOT NULL DEFAULT 'aleatorio_sistema',
    semente                 VARCHAR(255)    NULL,
    realizado_por           BIGINT UNSIGNED NOT NULL,
    realizado_em            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sorteios_rifa FOREIGN KEY (rifa_id) REFERENCES rifas (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_sorteios_numero FOREIGN KEY (numero_vencedor_id) REFERENCES numeros_rifa (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_sorteios_vencedor FOREIGN KEY (participante_vencedor_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_sorteios_realizado_por FOREIGN KEY (realizado_por) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY uq_sorteios_rifa (rifa_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- logs_auditoria
-- Rastreabilidade de ações sensíveis (aprovações, sorteio, encerramento),
-- dá suporte à verificação das regras RN07 e RN08.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS logs_auditoria (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id            BIGINT UNSIGNED NULL,
    acao                  VARCHAR(100)    NOT NULL,
    entidade               VARCHAR(50)     NOT NULL,
    entidade_id            BIGINT UNSIGNED NULL,
    dados_anteriores       JSON            NULL,
    dados_novos            JSON            NULL,
    criado_em              TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_auditoria_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
    KEY idx_logs_auditoria_entidade (entidade, entidade_id),
    KEY idx_logs_auditoria_usuario (usuario_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
