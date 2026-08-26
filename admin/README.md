Painel Administrativo — instruções rápidas

Arquivos:
- config.php: configurar DSN, usuário e senha do banco (dev local XAMPP por padrão).
- auth.php: funções de autenticação e CSRF.
- login.php: tela de login do admin.
- dashboard.php: painel administrativo (exige login e role=admin).
- create_admin_cli.php: script CLI para criar conta admin (executar via php create_admin_cli.php). Após uso, REMOVER ou restringir este arquivo.
- styles.css: estilos do painel.

Como criar admin (recomendado - CLI, mais seguro):
1. No servidor local, abra terminal/PowerShell na pasta admin.
2. Rode: php create_admin_cli.php
3. Informe email e senha fortes. O script criará o registro com password_hash.
4. Apague create_admin_cli.php ou mova para local seguro.

Dicas de segurança:
- Use HTTPS em produção.
- Não exponha arquivos de administração publicamente sem autenticação e firewall.
- Troque a senha root do MySQL em produção; use um usuário com privilégios limitados.
- Considere 2FA para contas admin.
