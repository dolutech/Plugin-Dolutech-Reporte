# Dolutech IP Reporte

**Versão:** 0.0.1  
**Requer no mínimo:** WordPress 6.5  
**Testado até:** WordPress 6.5  
**Requer PHP:** 7.4  
**Licença:** GPL-2.0+  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.txt  

## Descrição

**Dolutech IP Reporte** é um plugin para WordPress que facilita o envio de reportes em massa de endereços IP para a [AbuseIPDB](https://www.abuseipdb.com/). Ele permite que administradores de sites WordPress relatem múltiplos IPs envolvidos em atividades maliciosas, como ataques DDoS, brute-force e spam, de forma eficiente e segura diretamente pelo painel de administração do WordPress.

O plugin integra-se com a API do AbuseIPDB para simplificar o processo de reporte e oferece uma interface intuitiva para que você insira os IPs, escolha os motivos de reporte e adicione comentários opcionais.

## Funcionalidades

- **Reporte de múltiplos IPs**: Adicione uma lista de IPs e envie todos de uma vez.
- **Seleção de motivos**: Escolha entre diversas categorias de abuso, como ataques DDoS, brute-force, spam, entre outros.
- **Integração com API**: Conecte-se à API do AbuseIPDB com uma chave de API.
- **Comentários adicionais**: Inclua comentários personalizados para cada reporte.
- **Feedback em tempo real**: Receba notificações sobre o sucesso ou falha no envio de cada reporte.
- **Segurança**: O plugin sanitiza todas as entradas e protege contra injeções de código.
- **Limpeza de campos**: Os campos de entrada são automaticamente limpos após o envio dos reportes.

## Instalação

### Via painel do WordPress

1. Baixe o arquivo ZIP do plugin deste repositório.
2. No painel do WordPress, vá para **Plugins > Adicionar Novo** e clique em **Enviar Plugin**.
3. Escolha o arquivo ZIP baixado e clique em **Instalar Agora**.
4. Após a instalação, clique em **Ativar**.

### Manualmente

1. Clone este repositório ou baixe o arquivo ZIP:
    ```bash
    git clone https://github.com/seu-usuario/dolutech-ip-reporte.git
    ```
2. Faça o upload do diretório `dolutech-ip-reporte` para a pasta `/wp-content/plugins/` no seu servidor WordPress.
3. Ative o plugin no painel de administração do WordPress em **Plugins**.

## Uso

### Configuração da API

1. Após a ativação do plugin, acesse **Dolutech IP Reporte > Configuração**.
2. Insira a sua chave de API fornecida pela [AbuseIPDB](https://www.abuseipdb.com) e clique em **Salvar Alterações**.

### Envio de Reportes

1. Acesse **Dolutech IP Reporte > Reporte**.
2. Insira uma lista de IPs, um por linha, que você deseja reportar.
3. Selecione os motivos do reporte, como ataques DDoS, brute-force, spam, etc.
4. (Opcional) Adicione um comentário sobre o reporte.
5. Clique em **Enviar Reporte**.
6. Você receberá um feedback sobre o sucesso ou falha dos reportes.

## Contribuindo

Se você quiser contribuir com o desenvolvimento do **Dolutech IP Reporte**, siga as etapas abaixo:

1. Faça um fork deste repositório.
2. Crie um branch para a sua feature ou correção de bug:
    ```bash
    git checkout -b minha-feature
    ```
3. Faça os commits necessários:
    ```bash
    git commit -m "Descrição clara do que foi alterado"
    ```
4. Envie suas alterações para o branch original:
    ```bash
    git push origin minha-feature
    ```
5. Abra um Pull Request detalhando suas alterações.

## Segurança

- O plugin realiza a sanitização de todos os dados inseridos.
- Utiliza `wp_nonce_field` para proteger contra ataques CSRF.
- Validações são feitas para garantir que os IPs sejam válidos.
- Cabeçalhos HTTP seguros são usados para enviar os dados (`Content-Type: application/json`).

## Requisitos

- **WordPress:** 6.5 ou superior
- **PHP:** 7.4 ou superior
- **Chave de API** da [AbuseIPDB](https://www.abuseipdb.com)

## Licença

Este plugin é licenciado sob a licença GPL-2.0+.

## Contato

Desenvolvido por **Lucas Catão de Moraes**. Para mais informações, acesse [Dolutech](https://dolutech.com).
