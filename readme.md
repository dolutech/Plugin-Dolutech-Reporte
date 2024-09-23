# Dolutech IP Reporte

**Version:** 0.0.1  
**Requires at least:** WordPress 6.5  
**Tested up to:** WordPress 6.5  
**Requires PHP:** 7.4  
**License:** GPL-2.0+  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.txt  

## Descrição

**Dolutech IP Reporte** é um plugin para WordPress que facilita o envio de reportes em massa para a AbuseIPDB. O plugin permite que você reporte múltiplos IPs de forma eficiente, selecionando os motivos e adicionando comentários aos reportes, tudo diretamente do painel do WordPress.

O plugin foi desenvolvido para simplificar o processo de reporte em massa, integrando-se com a API do AbuseIPDB. Além disso, ele oferece uma interface intuitiva para adicionar sua chave de API, configurar os motivos de reporte e enviar logs de abuso com segurança.

## Funcionalidades

- Reporte de múltiplos IPs ao mesmo tempo.
- Integração com a API do AbuseIPDB.
- Escolha entre múltiplos motivos de reporte, incluindo ataques DDoS, brute-force, spam, entre outros.
- Adicione um comentário aos seus reportes.
- Feedback em tempo real sobre os IPs reportados com sucesso ou falhas.
- Configuração fácil da chave de API diretamente pelo painel de administração.
- Limpeza automática dos campos após o envio dos reportes.
- Totalmente sanitizado e seguro contra injeções de código.

## Instalação

1. Baixe o arquivo ZIP do plugin ou clone o repositório.
2. Acesse o painel de administração do seu WordPress.
3. Vá para **Plugins > Adicionar Novo** e faça o upload do arquivo ZIP.
4. Ative o plugin.
5. Acesse a página **Dolutech IP Reporte > Configuração** para adicionar sua chave de API do AbuseIPDB.
6. Vá para **Dolutech IP Reporte > Reporte** para começar a enviar seus reportes.

## Uso

### Configuração da API

1. Após ativar o plugin, vá até **Dolutech IP Reporte > Configuração**.
2. Insira a sua chave de API da AbuseIPDB e clique em **Salvar Alterações**.

### Envio de Reportes

1. Vá para **Dolutech IP Reporte > Reporte**.
2. Insira a lista de IPs que você deseja reportar (um IP por linha).
3. Selecione os motivos do reporte.
4. (Opcional) Adicione um comentário para fornecer mais detalhes sobre o reporte.
5. Clique em **Enviar Reporte**.
6. O plugin fornecerá feedback sobre o sucesso ou falha dos reportes enviados.

## Segurança

Este plugin foi desenvolvido com segurança em mente:

- Sanitização de todos os dados de entrada.
- Proteção contra ataques de CSRF utilizando `wp_nonce_field`.
- Validação de todos os IPs antes de enviar para a API.
- Uso de cabeçalhos HTTP seguros (`Content-Type: application/json`).
- Compatível com PHP 7.4 ou superior.

## Licença

Este plugin é distribuído sob a licença GPL-2.0+.
