# Sistema de Rifa Eletrônica

Sistema simples de rifa online: PHP puro em camadas (sem framework),
HTML + Tailwind CSS no front-end, MySQL/MariaDB no banco de dados.

## Arquitetura

```
Controller -> Service -> Repository -> Model
                              |
                          Database (PDO)
```

- **Controllers**: recebem a requisição HTTP, chamam o Service e renderizam a View.
- **Services**: regras de negócio (RN01–RN08).
- **Repositories**: acesso a dados via PDO (SQL puro, sem ORM).
- **Models**: DTOs que representam as tabelas do banco.
- **Views**: templates PHP + Tailwind, sem motor de template externo.

## Perfis de usuário

- **Participante**: cadastro, escolha de números, envio de comprovante, acompanhamento.
- **Organizador / Administrador**: criação de rifas, aprovação de pagamentos, sorteio, relatórios.

## Regras de negócio implementadas

| Regra | Descrição |
|-------|-----------|
| RN01 | Número possui apenas 1 de 5 estados (livre, reservado, pago, cancelado, premiado) |
| RN02 | Reserva expira após 30 min — `scripts/expirar_reservas.php` (cron) |
| RN03 | Envio de comprovante → reserva fica "aguardando_aprovacao" |
| RN04 | Apenas administrador/organizador aprovam pagamentos |
| RN05 | Aprovação → número passa a "pago" |
| RN06 | Somente números pagos entram no sorteio |
| RN07 | Rifa encerrada não pode ser alterada |
| RN08 | Sorteio não pode ser refeito nem alterado (UNIQUE `sorteios.rifa_id`) |

## Setup

1. Crie o banco e importe o schema:

   ```bash
   mysql -u root -p -e "CREATE DATABASE rifa CHARACTER SET utf8mb4"
   mysql -u root -p rifa < database/schema.sql
   mysql -u root -p rifa < database/seed.sql
   ```

2. Copie `.env.example` para `.env` e ajuste as credenciais do banco.

3. Instale e gere o CSS do Tailwind:

   ```bash
   npm install
   npm run build   # ou "npm run dev" para watch mode
   ```

4. Suba um servidor local apontando para `public/`:

   ```bash
   php -S localhost:8000 -t public
   ```

5. Configure o cron de expiração de reservas (RN02):

   ```
   */5 * * * * php /caminho/para/rifa/scripts/expirar_reservas.php
   ```

## Estrutura de pastas

```
rifa/
├── public/            # document root (index.php, assets, uploads)
├── app/
│   ├── Config/        # config.php, database.php
│   ├── Core/          # Router, Database (PDO), Session, Autoloader, Env
│   ├── Controllers/    # Participante/ e Admin/
│   ├── Services/       # regras de negócio
│   ├── Repositories/   # acesso a dados (PDO)
│   ├── Models/         # DTOs das tabelas
│   ├── Middlewares/     # Auth, Admin, Organizador
│   ├── Helpers/         # Validator, Upload, Flash
│   └── Views/            # layouts/, participante/, admin/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── scripts/
│   └── expirar_reservas.php   # job cron (RN02)
└── storage/logs/
```

## Login padrão (seed)

- E-mail: `admin@rifa.local`
- Senha: `admin123` (troque após o primeiro acesso)
