<?php
/*
Plugin Name: Dolutech IP Reporte
Description: Plugin para realizar reportes em massa de IPs para a AbuseIPDB e solicitar listas negras personalizadas.
Version: 0.0.2
Author: Lucas Catão de Moraes
Author URI: https://dolutech.com
Requires PHP: 7.4
Requires at least: 6.5
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if (!defined('ABSPATH')) {
    exit; // Evita acesso direto
}

class Dolutech_IP_Reporte {

    private $api_key_option = 'dolutech_ip_reporte_api_key';
    private $site_url = 'https://dolutech.com'; // Seu site
    const API_VERSION = '0.0.2';

    public function __construct() {
        // Hooks para adicionar páginas ao menu do admin
        add_action('admin_menu', array($this, 'add_admin_pages'));

        // Hooks para registrar configurações
        add_action('admin_init', array($this, 'register_settings'));

        // Hooks para processar o formulário de reporte via admin_post
        add_action('admin_post_dolutech_ip_reporte_submit', array($this, 'handle_form_submission'));

        // Hooks para processar o formulário de blacklist via admin_post
        add_action('admin_post_dolutech_ip_reporte_blacklist_submit', array($this, 'handle_blacklist_request'));
    }

    // Adiciona páginas ao menu administrativo
    public function add_admin_pages() {
        add_menu_page(
            'Reporte de IPs',
            'Dolutech IP Reporte',
            'manage_options',
            'dolutech-ip-reporte',
            array($this, 'report_page'),
            'dashicons-shield-alt',
            6
        );

        add_submenu_page(
            'dolutech-ip-reporte',
            'Reporte',
            'Reporte',
            'manage_options',
            'dolutech-ip-reporte',
            array($this, 'report_page')
        );

        add_submenu_page(
            'dolutech-ip-reporte',
            'Solicitar Blacklist',
            'Solicitar Blacklist',
            'manage_options',
            'dolutech-ip-reporte-blacklist',
            array($this, 'blacklist_page')
        );

        add_submenu_page(
            'dolutech-ip-reporte',
            'Configuração',
            'Configuração',
            'manage_options',
            'dolutech-ip-reporte-config',
            array($this, 'config_page')
        );
    }

    // Registra configurações
    public function register_settings() {
        register_setting('dolutech_ip_reporte_settings_group', $this->api_key_option, array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ));
    }

    // Página de Reporte
    public function report_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Captura mensagens de sucesso ou erro via query parameters
        $success = isset($_GET['success']) ? intval($_GET['success']) : 0;
        $failures = isset($_GET['failures']) ? sanitize_text_field($_GET['failures']) : '';

        if ($success > 0) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($success) . ' IP(s) reportado(s) com sucesso.</p></div>';
        }

        if (!empty($failures)) {
            $failures = json_decode(urldecode($failures), true);
            if (is_array($failures)) {
                foreach ($failures as $ip => $error) {
                    echo '<div class="notice notice-error is-dismissible"><p>Falha ao reportar ' . esc_html($ip) . ': ' . esc_html($error) . '</p></div>';
                }
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>Falha ao processar os reportes.</p></div>';
            }
        }

        // Lista de motivos
        $motivos_list = $this->get_motivos();

        ?>

        <div class="wrap">
            <h1>Reporte de IPs</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                // Campos ocultos para o admin_post
                wp_nonce_field('dolutech_ip_reporte_action', 'dolutech_ip_reporte_nonce');
                ?>
                <input type="hidden" name="action" value="dolutech_ip_reporte_submit">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="ips">Lista de IPs</label></th>
                        <td>
                            <textarea id="ips" name="ips" rows="10" cols="50" class="large-text" placeholder="Insira um IP por linha" required><?php echo isset($_GET['ips']) ? esc_textarea($_GET['ips']) : ''; ?></textarea>
                            <p class="description">Insira um IP por linha para realizar o reporte em massa.</p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="motivos">Motivo do Reporte</label></th>
                        <td>
                            <?php foreach ($motivos_list as $motivo): ?>
                                <label>
                                    <input type="checkbox" name="motivos[]" value="<?php echo esc_attr($motivo['ID']); ?>" <?php echo (isset($_GET['motivos']) && in_array($motivo['ID'], explode(',', $_GET['motivos']))) ? 'checked' : ''; ?> />
                                    <?php echo esc_html($motivo['Title']); ?>
                                </label><br/>
                            <?php endforeach; ?>
                            <p class="description">Selecione pelo menos um motivo para o reporte.</p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="comentario">Comentário</label></th>
                        <td>
                            <textarea id="comentario" name="comentario" rows="5" cols="50" class="large-text" placeholder="Adicione um comentário opcional para o reporte."><?php echo isset($_GET['comentario']) ? esc_textarea($_GET['comentario']) : ''; ?></textarea>
                            <p class="description">Adicione um comentário opcional para o reporte.</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Enviar Reporte', 'primary', 'dolutech_ip_reporte_submit'); ?>
            </form>
        </div>

        <?php
    }

    // Página de Solicitar Blacklist
    public function blacklist_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Captura mensagens de sucesso ou erro via query parameters
        $error = isset($_GET['error']) ? sanitize_text_field($_GET['error']) : '';
        $generated_at = isset($_GET['generated_at']) ? sanitize_text_field($_GET['generated_at']) : '';

        if (!empty($error)) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error) . '</p></div>';
        }

        if (!empty($generated_at)) {
            echo '<div class="notice notice-success is-dismissible"><p>Lista negra gerada em: ' . esc_html($generated_at) . '. O download foi iniciado automaticamente.</p></div>';
        }

        ?>

        <div class="wrap">
            <h1>Solicitar Blacklist</h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php
                // Campos ocultos para o admin_post
                wp_nonce_field('dolutech_ip_reporte_blacklist_action', 'dolutech_ip_reporte_blacklist_nonce');
                ?>
                <input type="hidden" name="action" value="dolutech_ip_reporte_blacklist_submit">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="confidenceMinimum">Confiança Mínima (%)</label></th>
                        <td>
                            <input type="number" id="confidenceMinimum" name="confidenceMinimum" value="90" min="1" max="100" required />
                            <p class="description">Escolha a pontuação de confiança mínima para os IPs na lista negra (1-100).</p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="limit">Número de IPs</label></th>
                        <td>
                            <input type="number" id="limit" name="limit" value="10000" min="1" max="10000" required />
                            <p class="description">Escolha o número de IPs para incluir na lista negra (1-10.000).</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button('Gerar Lista Negra', 'primary', 'dolutech_ip_reporte_blacklist_submit'); ?>
            </form>
        </div>

        <?php
    }

    // Página de Configuração
    public function config_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        ?>

        <div class="wrap">
            <h1>Configuração - Dolutech IP Reporte</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('dolutech_ip_reporte_settings_group');
                do_settings_sections('dolutech_ip_reporte_settings_group');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="dolutech_ip_reporte_api_key">Chave de API da AbuseIPDB</label></th>
                        <td>
                            <input type="text" id="dolutech_ip_reporte_api_key" name="<?php echo esc_attr($this->api_key_option); ?>" value="<?php echo esc_attr(get_option($this->api_key_option)); ?>" class="regular-text" required />
                            <p class="description">Insira a sua chave de API da <a href="https://www.abuseipdb.com" target="_blank">AbuseIPDB</a>.</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    // Obtém a lista de motivos
    private function get_motivos() {
        return array(
            array('ID' => 1, 'Title' => 'DNS Compromise', 'Description' => 'Altering DNS records resulting in improper redirection.'),
            array('ID' => 2, 'Title' => 'DNS Poisoning', 'Description' => 'Falsifying domain server cache (cache poisoning).'),
            array('ID' => 3, 'Title' => 'Fraud Orders', 'Description' => 'Fraudulent orders.'),
            array('ID' => 4, 'Title' => 'DDoS Attack', 'Description' => 'Participating in distributed denial-of-service (usually part of botnet).'),
            array('ID' => 5, 'Title' => 'FTP Brute-Force', 'Description' => ''),
            array('ID' => 6, 'Title' => 'Ping of Death', 'Description' => 'Oversized IP packet.'),
            array('ID' => 7, 'Title' => 'Phishing', 'Description' => 'Phishing websites and/or email.'),
            array('ID' => 8, 'Title' => 'Fraud VoIP', 'Description' => ''),
            array('ID' => 9, 'Title' => 'Open Proxy', 'Description' => 'Open proxy, open relay, or Tor exit node.'),
            array('ID' => 10, 'Title' => 'Web Spam', 'Description' => 'Comment/forum spam, HTTP referer spam, or other CMS spam.'),
            array('ID' => 11, 'Title' => 'Email Spam', 'Description' => 'Spam email content, infected attachments, and phishing emails. Note: Limit comments to only relevant information (instead of log dumps) and be sure to remove PII if you want to remain anonymous.'),
            array('ID' => 12, 'Title' => 'Blog Spam', 'Description' => 'CMS blog comment spam.'),
            array('ID' => 13, 'Title' => 'VPN IP', 'Description' => 'Conjunctive category.'),
            array('ID' => 14, 'Title' => 'Port Scan', 'Description' => 'Scanning for open ports and vulnerable services.'),
            array('ID' => 15, 'Title' => 'Hacking', 'Description' => ''),
            array('ID' => 16, 'Title' => 'SQL Injection', 'Description' => 'Attempts at SQL injection.'),
            array('ID' => 17, 'Title' => 'Spoofing', 'Description' => 'Email sender spoofing.'),
            array('ID' => 18, 'Title' => 'Brute-Force', 'Description' => 'Credential brute-force attacks on webpage logins and services like SSH, FTP, SIP, SMTP, RDP, etc. This category is separate from DDoS attacks.'),
            array('ID' => 19, 'Title' => 'Bad Web Bot', 'Description' => 'Webpage scraping (for email addresses, content, etc) and crawlers that do not honor robots.txt. Excessive requests and user agent spoofing can also be reported here.'),
            array('ID' => 20, 'Title' => 'Exploited Host', 'Description' => 'Host is likely infected with malware and being used for other attacks or to host malicious content. The host owner may not be aware of the compromise. This category is often used in combination with other attack categories.'),
            array('ID' => 21, 'Title' => 'Web App Attack', 'Description' => 'Attempts to probe for or exploit installed web applications such as a CMS like WordPress/Drupal, e-commerce solutions, forum software, phpMyAdmin and various other software plugins/solutions.'),
            array('ID' => 22, 'Title' => 'SSH', 'Description' => 'Secure Shell (SSH) abuse. Use this category in combination with more specific categories.'),
            array('ID' => 23, 'Title' => 'IoT Targeted', 'Description' => 'Abuse was targeted at an "Internet of Things" type device. Include information about what type of device was targeted in the comments.')
        );
    }

    // Envia o reporte para a AbuseIPDB
    private function send_report($ip, $motivos, $comentario, $api_key) {
        $endpoint = 'https://api.abuseipdb.com/api/v2/report';

        $data = array(
            'ip' => $ip, // Campo correto conforme a mensagem de erro
            'categories' => implode(',', $motivos),
            'comment' => $comentario
        );

        $args = array(
            'body' => json_encode($data),
            'headers' => array(
                'Key' => $api_key,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dolutech-IP-Reporte/' . self::API_VERSION . ' ' . $this->site_url
            ),
            'timeout' => 60
        );

        $response = wp_remote_post($endpoint, $args);

        if (is_wp_error($response)) {
            // Opcional: Registrar no log de depuração do WordPress
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Dolutech IP Reporte - Erro na requisição: ' . $response->get_error_message());
            }
            return array('success' => false, 'message' => $response->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);

        if ($code === 200 && isset($result['data'])) {
            return array('success' => true, 'message' => 'Reportado com sucesso.');
        } else {
            // Verifica se há erros na resposta
            if (isset($result['errors']) && is_array($result['errors'])) {
                $error_messages = array_map(function($error) {
                    return isset($error['detail']) ? $error['detail'] : (isset($error['message']) ? $error['message'] : 'Erro desconhecido.');
                }, $result['errors']);
                $message = implode('; ', $error_messages);
            } elseif (isset($result['error'])) {
                $message = $result['error'];
            } else {
                $message = 'Erro desconhecido. Resposta da API: ' . esc_html($body);
            }

            // Opcional: Registrar no log de depuração do WordPress
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Dolutech IP Reporte - Erro na resposta da API: ' . $message);
                error_log('Dolutech IP Reporte - Código HTTP: ' . $code);
                error_log('Dolutech IP Reporte - Corpo da Resposta: ' . $body);
            }

            return array('success' => false, 'message' => $message);
        }
    }

    // Envia a solicitação de blacklist para a AbuseIPDB e fornece o download do arquivo .txt
    public function handle_blacklist_request() {
        if (!current_user_can('manage_options')) {
            wp_die('Acesso negado.');
        }

        // Verifica nonce para segurança
        if (!isset($_POST['dolutech_ip_reporte_blacklist_nonce']) || !wp_verify_nonce($_POST['dolutech_ip_reporte_blacklist_nonce'], 'dolutech_ip_reporte_blacklist_action')) {
            wp_die('Erro de verificação de segurança. Tente novamente.');
        }

        // Sanitiza e valida os dados
        $confidence_minimum = isset($_POST['confidenceMinimum']) ? intval($_POST['confidenceMinimum']) : 0;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 0;

        // Valida 'confidenceMinimum'
        if ($confidence_minimum < 1 || $confidence_minimum > 100) {
            wp_redirect(add_query_arg(array('error' => 'conf_min_invalid'), admin_url('admin.php?page=dolutech-ip-reporte-blacklist')));
            exit;
        }

        // Valida 'limit'
        if ($limit < 1 || $limit > 10000) {
            wp_redirect(add_query_arg(array('error' => 'limit_invalid'), admin_url('admin.php?page=dolutech-ip-reporte-blacklist')));
            exit;
        }

        // Obtém a chave de API
        $api_key = get_option($this->api_key_option);
        if (empty($api_key)) {
            wp_redirect(add_query_arg(array('error' => 'sem_api_key'), admin_url('admin.php?page=dolutech-ip-reporte-blacklist')));
            exit;
        }

        // Faz a requisição para a API da AbuseIPDB
        $endpoint = 'https://api.abuseipdb.com/api/v2/blacklist';

        $params = array(
            'confidenceMinimum' => $confidence_minimum,
            'limit' => $limit
        );

        $args = array(
            'headers' => array(
                'Key' => $api_key,
                'Accept' => 'text/plain',
                'User-Agent' => 'Dolutech-IP-Reporte/' . self::API_VERSION . ' ' . $this->site_url
            ),
            'body' => $params,
            'timeout' => 60
        );

        $response = wp_remote_get(add_query_arg($params, $endpoint), $args);

        if (is_wp_error($response)) {
            // Opcional: Registrar no log de depuração do WordPress
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Dolutech IP Reporte - Erro na requisição da blacklist: ' . $response->get_error_message());
            }
            wp_redirect(add_query_arg(array('error' => 'api_request_failed'), admin_url('admin.php?page=dolutech-ip-reporte-blacklist')));
            exit;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code === 200) {
            // Define os headers para download do arquivo .txt
            header('Content-Description: File Transfer');
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename=blacklist_' . time() . '.txt');
            header('Content-Length: ' . strlen($body));
            header('Pragma: public');

            // Envia o conteúdo do arquivo
            echo $body;
            exit;
        } else {
            // Tenta decodificar a resposta como JSON para capturar mensagens de erro
            $result = json_decode($body, true);
            if (isset($result['errors']) && is_array($result['errors'])) {
                $error_messages = array_map(function($error) {
                    return isset($error['detail']) ? $error['detail'] : (isset($error['message']) ? $error['message'] : 'Erro desconhecido.');
                }, $result['errors']);
                $message = implode('; ', $error_messages);
            } else {
                $message = 'Erro desconhecido. Código HTTP: ' . $code;
            }

            // Opcional: Registrar no log de depuração do WordPress
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Dolutech IP Reporte - Erro na resposta da API da blacklist: ' . $message);
                error_log('Dolutech IP Reporte - Código HTTP: ' . $code);
                error_log('Dolutech IP Reporte - Corpo da Resposta: ' . $body);
            }

            wp_redirect(add_query_arg(array('error' => urlencode($message)), admin_url('admin.php?page=dolutech-ip-reporte-blacklist')));
            exit;
        }
    }

    // Processa o formulário de reporte via admin_post
    public function handle_form_submission() {
        if (!current_user_can('manage_options')) {
            wp_die('Acesso negado.');
        }

        // Verifica nonce para segurança
        if (!isset($_POST['dolutech_ip_reporte_nonce']) || !wp_verify_nonce($_POST['dolutech_ip_reporte_nonce'], 'dolutech_ip_reporte_action')) {
            wp_die('Erro de verificação de segurança. Tente novamente.');
        }

        // Sanitiza e valida os dados
        $ips_input = isset($_POST['ips']) ? sanitize_textarea_field($_POST['ips']) : '';
        $motivos = isset($_POST['motivos']) ? array_map('intval', $_POST['motivos']) : array();
        $comentario = isset($_POST['comentario']) ? sanitize_textarea_field($_POST['comentario']) : '';

        // Valida IPs
        $ips = array_filter(array_map('trim', explode("\n", $ips_input)));
        $ips = array_unique($ips);
        $invalid_ips = array();

        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $invalid_ips[] = $ip;
            }
        }

        if (!empty($invalid_ips)) {
            wp_redirect(add_query_arg(array(
                'error' => 'ips_invalidos',
                'invalid_ips' => urlencode(json_encode($invalid_ips)),
                'motivos' => urlencode(implode(',', $motivos)),
                'comentario' => urlencode($comentario)
            ), admin_url('admin.php?page=dolutech-ip-reporte')));
            exit;
        }

        // Verifica se pelo menos um motivo foi selecionado
        if (empty($motivos)) {
            wp_redirect(add_query_arg(array(
                'error' => 'sem_motivos',
                'ips' => urlencode($ips_input),
                'comentario' => urlencode($comentario)
            ), admin_url('admin.php?page=dolutech-ip-reporte')));
            exit;
        }

        // Obtém a chave de API
        $api_key = get_option($this->api_key_option);
        if (empty($api_key)) {
            wp_redirect(add_query_arg(array(
                'error' => 'sem_api_key',
                'ips' => urlencode($ips_input),
                'motivos' => urlencode(implode(',', $motivos)),
                'comentario' => urlencode($comentario)
            ), admin_url('admin.php?page=dolutech-ip-reporte')));
            exit;
        }

        // Inicia o processamento dos reportes
        $success = 0;
        $failures = array();

        foreach ($ips as $ip) {
            $response = $this->send_report($ip, $motivos, $comentario, $api_key);
            if ($response['success']) {
                $success++;
            } else {
                $failures[$ip] = $response['message'];
            }
        }

        // Prepara os argumentos para redirecionamento
        $redirect_args = array();

        if ($success > 0) {
            $redirect_args['success'] = $success;
        }

        if (!empty($failures)) {
            $redirect_args['failures'] = urlencode(json_encode($failures));
        }

        // Redireciona de volta para a página de reporte com os resultados
        wp_redirect(add_query_arg($redirect_args, admin_url('admin.php?page=dolutech-ip-reporte')));
        exit;
    }
}

new Dolutech_IP_Reporte();
