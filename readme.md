# Dolutech IP Reporte

**Versão:** 0.0.2  
**Requer no mínimo:** WordPress 6.5  
**Testado até:** WordPress 6.5  
**Requer PHP:** 7.4  
**Licença:** GPL-2.0+  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.txt  

## Descrição

**Dolutech IP Reporte** é um plugin para WordPress que facilita o envio de reportes em massa de endereços IP para a [AbuseIPDB](https://www.abuseipdb.com/) e permite a solicitação de listas negras (blacklists) personalizadas com base no nível de confiança (abuse confidence score).

Com este plugin, administradores podem reportar múltiplos IPs envolvidos em atividades maliciosas, como ataques DDoS, brute-force e spam, diretamente pelo painel de administração do WordPress. Além disso, é possível gerar e baixar listas negras de IPs diretamente da AbuseIPDB.

## Funcionalidades

- **Reporte de múltiplos IPs**: Adicione uma lista de IPs e envie todos de uma vez para a AbuseIPDB.
- **Seleção de motivos**: Escolha entre diversas categorias de abuso, como ataques DDoS, brute-force, spam, entre outros.
- **Geração de listas negras**: Solicite listas negras personalizadas, definindo o nível mínimo de confiança (1-100%) e o número máximo de IPs (1-10.000).
- **Download de listas negras**: Faça o download da lista negra gerada em formato `.txt`.
- **Configuração de API**: Integração fácil com a API da AbuseIPDB, configurada diretamente pelo painel de administração.
- **Segurança**: O plugin sanitiza todas as entradas e protege contra injeções de código e ataques CSRF.

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

1. Após ativar o plugin, acesse **Dolutech IP Reporte > Configuração**.
2. Insira a sua chave de API fornecida pela [AbuseIPDB](https://www.abuseipdb.com) e clique em **Salvar Alterações**.

### Envio de Reportes

1. Acesse **Dolutech IP Reporte > Reporte**.
2. Insira uma lista de IPs, um por linha, que você deseja reportar.
3. Selecione os motivos do reporte, como ataques DDoS, brute-force, spam, etc.
4. (Opcional) Adicione um comentário sobre o reporte.
5. Clique em **Enviar Reporte**.
6. Você receberá um feedback sobre o sucesso ou falha dos reportes.

### Solicitar Blacklist

1. Acesse **Dolutech IP Reporte > Solicitar Blacklist**.
2. Escolha o nível mínimo de confiança para os IPs na lista (entre 1% e 100%).
3. Escolha o número máximo de IPs que deseja incluir na lista (entre 1 e 10.000).
4. Clique em **Gerar Lista Negra**.
5. O download da lista será iniciado automaticamente em formato `.txt`.

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

- O plugin sanitiza todas as entradas de dados.
- Utiliza `wp_nonce_field` para proteger contra ataques CSRF.
- Validações são feitas para garantir que os IPs sejam válidos e que os parâmetros de solicitação estejam dentro dos limites corretos.
- Utiliza cabeçalhos HTTP seguros (`Content-Type: application/json` e `text/plain`).

## Requisitos

- **WordPress:** 6.5 ou superior
- **PHP:** 7.4 ou superior
- **Chave de API** da [AbuseIPDB](https://www.abuseipdb.com)

## Licença

Este plugin é licenciado sob a licença GPL-2.0+.

## Contato

Desenvolvido por **Lucas Catão de Moraes**. Para mais informações, acesse [Dolutech](https://dolutech.com).
