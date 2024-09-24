# Dolutech IP Reporte

![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.5%2B-blue)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)
![License](https://img.shields.io/badge/License-GPL%20v2%2B-green)

**Versão:** 0.0.2  
**Requer no mínimo:** WordPress 6.5  
**Requer PHP:** 7.4  
**Licença:** GPL-2.0+  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.txt  

## Descrição

**Dolutech IP Reporte** é um plugin WordPress que permite reportar múltiplos endereços IP para a [AbuseIPDB](https://www.abuseipdb.com/) e solicitar listas negras personalizadas com base no score de confiança de abuso.

O plugin permite que administradores de sites WordPress possam reportar atividades maliciosas, como ataques DDoS, brute-force, e spam, diretamente do painel de administração. Além disso, você pode gerar e baixar listas negras com os IPs mais reportados na AbuseIPDB, baseados em critérios de confiança.

## Funcionalidades

- **Reporte de múltiplos IPs**: Reporte facilmente vários IPs de uma só vez.
- **Solicitação de Blacklist**: Geração de listas negras baseadas em critérios de confiança e limite de IPs.
- **Download de Blacklist**: Baixe listas negras diretamente no formato `.txt`.
- **Segurança integrada**: Proteção contra injeção de código e ataques CSRF.

## Instalação

### Via painel do WordPress

1. Faça o download do plugin como um arquivo ZIP.
2. No painel de administração do WordPress, vá para **Plugins > Adicionar Novo** e clique em **Enviar Plugin**.
3. Envie o arquivo ZIP baixado e clique em **Instalar Agora**.
4. Após a instalação, clique em **Ativar**.

### Manualmente

1. Clone este repositório ou faça o download do arquivo ZIP:
    ```bash
    git clone https://github.com/seu-usuario/dolutech-ip-reporte.git
    ```
2. Faça o upload da pasta `dolutech-ip-reporte` para o diretório `/wp-content/plugins/` do seu servidor WordPress.
3. No painel de administração do WordPress, vá para **Plugins > Plugins Instalados** e ative o plugin.

## Uso

### Configuração da API

1. Acesse **Dolutech IP Reporte > Configuração**.
2. Insira sua chave de API da [AbuseIPDB](https://www.abuseipdb.com) e clique em **Salvar Alterações**.

### Envio de Reportes

1. Acesse **Dolutech IP Reporte > Reporte**.
2. Insira uma lista de IPs, um por linha.
3. Selecione os motivos para o reporte, como ataques DDoS, brute-force, spam, entre outros.
4. Adicione um comentário opcional.
5. Clique em **Enviar Reporte**.
6. Você receberá um feedback sobre o sucesso ou falha do reporte.

### Solicitação de Blacklist

1. Acesse **Dolutech IP Reporte > Solicitar Blacklist**.
2. Escolha o nível mínimo de confiança para os IPs na lista (entre 1% e 100%).
3. Escolha o número de IPs para incluir na lista (entre 1 e 10.000).
4. Clique em **Gerar Lista Negra** e o arquivo será baixado automaticamente.

## Contribuindo

Sinta-se à vontade para contribuir com o desenvolvimento deste plugin. Siga os passos abaixo:

1. Faça um fork deste repositório.
2. Crie um branch para sua feature ou correção de bug:
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
5. Abra um **Pull Request** detalhando suas alterações.

## Segurança

- O plugin sanitiza todas as entradas de dados.
- Utiliza `wp_nonce_field` para proteger contra ataques CSRF.
- As entradas de IP são validadas para garantir que sejam válidas e apropriadas.
- Utiliza cabeçalhos HTTP seguros para comunicação com a API da AbuseIPDB.

## Requisitos

- **WordPress:** 6.5 ou superior
- **PHP:** 7.4 ou superior
- **Chave de API** da [AbuseIPDB](https://www.abuseipdb.com)

## Licença

Este plugin está licenciado sob a [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.txt).

## Contato

Desenvolvido por **Lucas Catão de Moraes**. Para mais informações, visite [Dolutech](https://dolutech.com).
