
# 🔴 Feira Tech MCM

### Tecnologia, inovação e sustentabilidade na ETEC Maria Cristina Medeiros

**Uma plataforma web real, desenvolvida para apoiar a organização e a
participação na Feira Tecnológica da escola.**

![Status](https://img.shields.io/badge/status-projeto%20conclu%C3%ADdo-9f1d35)
![Frontend](https://img.shields.io/badge/frontend-HTML%20%7C%20CSS%20%7C%20JavaScript-e34f26)
![Backend](https://img.shields.io/badge/backend-PHP-777bb4)
![Database](https://img.shields.io/badge/database-MySQL-4479a1)
![Deploy](https://img.shields.io/badge/deploy-Hostinger-673de6)
:::

------------------------------------------------------------------------

## 📌 Sobre o projeto

O **Feira Tech MCM** é uma aplicação web criada para a **Feira
Tecnológica da ETEC Maria Cristina Medeiros**. Seu propósito foi reunir,
em um único ambiente, os projetos desenvolvidos pelos estudantes e
oferecer recursos para a comunidade escolar interagir com os trabalhos
apresentados.

Este foi um **projeto real, publicado em produção e utilizado pela
escola** durante o período de realização do evento. A aplicação foi
hospedada na **Hostinger**, com integração entre a interface, uma API
própria e um banco de dados.

O sistema foi planejado para atender a uma necessidade específica e
temporária da instituição. Por isso, o domínio e a hospedagem não foram
mantidos após o período de uso. Este repositório preserva o projeto como
registro do desenvolvimento e das soluções implementadas.

## ✨ Funcionalidades

-   **Catálogo de projetos:** visualização e consulta dos trabalhos
    cadastrados.
-   **Cadastro e gerenciamento:** criação, edição e exclusão de
    projetos, respeitando as permissões.
-   **Equipes de projeto:** acesso por chave e senha e possibilidade de
    participar de um projeto como integrante.
-   **Autenticação de usuários:** cadastro, login e recuperação de
    acesso.
-   **Área do aluno:** acesso ao perfil e aos projetos relacionados ao
    usuário.
-   **Votação e comentários:** interação dos visitantes com os projetos.
-   **ODS:** associação dos trabalhos aos Objetivos de Desenvolvimento
    Sustentável.
-   **Cronograma e notícias:** divulgação de informações importantes
    sobre a feira.
-   **Notificações:** avisos dentro da plataforma.
-   **Painel administrativo:** ferramentas restritas para gerenciamento
    do sistema.

## 🧰 Tecnologias utilizadas

### Front-end

  -----------------------------------------------------------------------
  Tecnologia                          Como foi utilizada
  ----------------------------------- -----------------------------------
  **HTML5**                           Estrutura inicial da aplicação e
                                      carregamento dos recursos da
                                      interface.

  **CSS3**                            Estilização, layout responsivo,
                                      paleta visual, componentes,
                                      animações e estados de interação.

  **JavaScript**                      Construção dinâmica das telas,
                                      navegação entre seções, validações,
                                      eventos da interface e comunicação
                                      com a API usando requisições HTTP.

  **Google Fonts**                    Tipografia da interface com as
                                      famílias Nunito e Inter.

  **LocalStorage**                    Armazenamento local de rascunhos de
                                      formulário no navegador.
  -----------------------------------------------------------------------

### Back-end e dados

  -----------------------------------------------------------------------
  Tecnologia                          Como foi utilizada
  ----------------------------------- -----------------------------------
  **PHP**                             Implementação dos endpoints da API,
                                      autenticação e regras de negócio do
                                      sistema.

  **MySQL**                           Persistência de usuários, projetos,
                                      comentários, votos e demais
                                      informações da plataforma.

  **PDO**                             Conexão entre PHP e MySQL e
                                      execução de consultas ao banco de
                                      dados.

  **JSON**                            Formato utilizado para trocar dados
                                      entre o front-end JavaScript e os
                                      endpoints da API.

  **Hostinger**                       Hospedagem utilizada para publicar
                                      a aplicação em produção durante o
                                      evento.
  -----------------------------------------------------------------------

## 🏗️ Como a aplicação funciona

A interface é carregada pelo navegador a partir de `index.html`,
enquanto o `script.js` monta os componentes e gerencia a navegação e as
interações. Quando uma funcionalidade precisa consultar ou alterar dados
persistentes, o JavaScript envia requisições para os endpoints em PHP,
localizados na pasta `api/`.

A API processa as solicitações e utiliza o MySQL para consultar ou
salvar os dados. As respostas são retornadas em JSON para que a
interface possa atualizar as informações exibidas.

``` text
Navegador
   │
   ▼
HTML + CSS + JavaScript
   │  requisições HTTP / respostas JSON
   ▼
API em PHP
   │
   ▼
Banco de dados MySQL
```

## 📁 Estrutura principal

``` text
FeiraBackup-main/
├── index.html             # Ponto de entrada da aplicação
├── style.css              # Estilos e identidade visual
├── script.js              # Interface, navegação e integração com a API
├── database.sql           # Estrutura inicial do banco de dados
├── api/
│   ├── auth/              # Autenticação e contas de usuário
│   ├── config/            # Configuração de conexão com o banco
│   └── projects/          # Operações relacionadas aos projetos
├── admin/                 # Painel administrativo
└── odses/                 # Ícones dos Objetivos de Desenvolvimento Sustentável
```

## ▶️ Executar localmente

Para executar uma cópia do projeto, é necessário um ambiente com **PHP e
MySQL**, por exemplo, XAMPP.

1.  Coloque a pasta do projeto no diretório de sites do ambiente local.
2.  Crie um banco de dados MySQL e importe o arquivo `database.sql` pelo
    phpMyAdmin.
3.  Ajuste as configurações de conexão em `api/config/database.php` e
    nos arquivos de configuração do painel administrativo.
4.  Inicie o Apache e o MySQL.
5.  Acesse o projeto pelo endereço local disponibilizado pelo Apache.

> **Atenção:** o repositório é uma cópia do projeto. Antes de colocá-lo
> novamente em produção, revise as credenciais, permissões,
> configurações de segurança e demais parâmetros do servidor. Não
> publique senhas ou credenciais reais.

## 🎓 O que este projeto demonstra

O desenvolvimento do Feira Tech MCM envolveu mais do que a criação de
páginas: exigiu integração entre front-end e back-end, modelagem e
persistência de dados, autenticação, controle de permissões e
implementação de funcionalidades para usuários com diferentes
necessidades.

Entre os principais aprendizados demonstrados estão:

-   Desenvolvimento de interfaces com HTML, CSS e JavaScript.
-   Integração de front-end com uma API própria.
-   Criação de endpoints em PHP e troca de dados via JSON.
-   Operações CRUD conectadas a um banco de dados MySQL.
-   Organização de funcionalidades em módulos.
-   Preparação e publicação de uma aplicação web em hospedagem real.

## 📍 Status e disponibilidade

O projeto foi utilizado pela escola durante o período para o qual foi
desenvolvido. Como a necessidade era temporária, a hospedagem e o
domínio não foram mantidos após o evento. Assim, o repositório funciona
como **portfólio e registro técnico de uma aplicação que chegou a ser
utilizada em produção**.

------------------------------------------------------------------------

::: {align="center"}
**Feira Tech MCM** · Projeto desenvolvido para a ETEC Maria Cristina
Medeiros
:::
