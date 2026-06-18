# ConectaSUS — Cadastro de Pacientes

Sistema desenvolvido para o processo seletivo da ConectaSUS. A ideia é bem direta: uma API em Laravel para gerenciar pacientes e endereços, e um frontend em Vue.js 2 pra usar no dia a dia.

No backend usei Laravel 12 com Sanctum pra autenticação via token Bearer. No frontend, Vue 2 com Vuex, VeeValidate, máscaras de input e integração com a API do ViaCEP. Tudo roda com Docker — nginx na porta 8080 servindo a SPA e repassando as chamadas da API pro PHP-FPM.

---

## Como rodar (Docker)

Você só precisa ter o Docker instalado.

### 1. Clonar o repositório

```bash
git clone https://github.com/IgorOliveira195/Sistema-de-Cadastro-de-Pacientes.git
cd Sistema-de-Cadastro-de-Pacientes
```

### 2. Criar o `.env` a partir do `.env.example` (obrigatório)

O arquivo `backend/.env` **não vai pro Git** — cada máquina precisa criar o seu. Sem ele o `docker compose up` vai falhar.

Dentro da pasta do projeto, copie o exemplo:

**Linux / Mac / Git Bash:**
```bash
cp backend/.env.example backend/.env
```

**Windows (PowerShell ou CMD):**
```powershell
copy backend\.env.example backend\.env
```

O `.env.example` já vem configurado pro Docker (`DB_HOST=db`, credenciais do banco, `APP_URL`, `FRONTEND_URL`, etc.). Na prática você só duplica o arquivo e renomeia pra `.env`. Se mudar usuário ou senha do banco ali, precisa ajustar também no `docker-compose.yml` (serviço `db`).

### 3. Subir os containers

```bash
docker compose up -d --build
```

### 4. Criar tabelas e popular o banco

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### 5. Gerar a chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

### 6. Build do frontend

O nginx serve os arquivos de `frontend/dist/`, então precisa compilar:

```bash
docker compose exec frontend npm run build
```

Resumo dos comandos na ordem:

```bash
docker compose up -d --build
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan key:generate
docker compose exec frontend npm run build
```

Abre http://localhost:8080 e entra com:

- **E-mail:** admin@conectasus.com
- **Senha:** password

A API fica em http://localhost:8080/api

### Outros comandos que usei 

```bash
docker compose down                                          # parar tudo
docker compose exec app php artisan test                     # testes
docker compose exec app composer install                     # deps PHP
docker compose exec frontend npm run build                   # rebuild da SPA
```

---

## O que tem no sistema

Tem login/logout, um dashboard com os totais, e CRUD completo de endereços e pacientes. Nas listagens dá pra buscar, filtrar, ordenar e paginar. Nos formulários tem máscara de CPF, telefone, CEP, e o endereço preenche sozinho pelo ViaCEP.

Implementei as regras de negócio do PDF (RN-01 a RN-09). A mais visível na interface é a RN-03: não deixa excluir um endereço que ainda tem paciente vinculado.

---

## Testes

```bash
docker compose exec app php artisan test
```

---

## Estrutura do projeto

```
backend/          → API Laravel
frontend/         → SPA Vue.js
nginx/            → config do proxy reverso
docker-compose.yml
```

Os containers são: `app` (PHP-FPM), `nginx` (porta 8080), `db` (MySQL 8) e `frontend` (Node, pra build da SPA).

