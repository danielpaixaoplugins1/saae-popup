<?php
/*
Plugin Name: SAAE Pop UP
Description: Pop UP para exibir banner.
Version: 1.01
Author: SAAE Cacoal
*/

// Hook para carregar o CSS e JavaScript
add_action('wp_enqueue_scripts', 'saae_enqueue_scripts');
function saae_enqueue_scripts() {
    wp_enqueue_style('saae-styles', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('saae-scripts', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), null, true);
}

// Hook para o footer para exibir o popup
add_action('wp_footer', 'saae_display_popup');
function saae_display_popup() {
    $options = get_option('saae_pop_up_options');
    $banner_url = $options['banner'];
    if (!empty($banner_url)) {
        ?>
        <div id="saae-popup">
            <div id="saae-popup-content">
                <span id="saae-popup-close-heart">&hearts;</span>
                <span id="saae-popup-close">&times;</span>
                <img src="<?php echo esc_url($banner_url); ?>" alt="Banner">
            </div>
        </div>
        <?php
    }
}

// Hook para adicionar uma página de configurações no admin
add_action('admin_menu', 'saae_admin_menu');
function saae_admin_menu() {
    add_menu_page('SAAE Pop Up Configurações', 'SAAE Pop Up', 'manage_options', 'saae-pop-up', 'saae_pop_up_options_page');
}

// Função para exibir o formulário de opções na página de configurações
function saae_pop_up_options_page() {
    ?>
    <div class="wrap">
        <h2>SAAE Pop Up Configurações</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('saae_pop_up_options');
            do_settings_sections('saae_pop_up');
            submit_button('Salvar Configurações');
            ?>
        </form>
    </div>
    <?php
}

// Registrar e definir configurações
add_action('admin_init', 'saae_pop_up_admin_init');
function saae_pop_up_admin_init() {
    register_setting('saae_pop_up_options', 'saae_pop_up_options', 'saae_pop_up_options_validate');
    add_settings_section('saae_pop_up_main', 'Configurações Principais', 'saae_pop_up_section_text', 'saae_pop_up');
    add_settings_field('saae_pop_up_banner', 'URL do Banner', 'saae_pop_up_setting_banner', 'saae_pop_up', 'saae_pop_up_main');
}

function saae_pop_up_section_text() {
    echo '<p>Insira a URL do banner que você deseja exibir no pop-up.</p>';
}

function saae_pop_up_setting_banner() {
    $options = get_option('saae_pop_up_options');
    echo "<input id='saae_pop_up_banner' name='saae_pop_up_options[banner]' size='60' type='text' value='{$options['banner']}' />";
}

// Validar entradas
function saae_pop_up_options_validate($input) {
    $newinput['banner'] = trim($input['banner']);
    if(!preg_match('/^(http:\/\/|https:\/\/)/i', $newinput['banner'])) {
        $newinput['banner'] = '';
    }
    return $newinput;
}

// CSS para o estilo do pop-up
function saae_custom_css() {
    ?>
    <style>
        #saae-popup {
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            max-width: 970px;
            max-height: 550px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            display: none;
        }

        #saae-popup-content {
            position: relative;
        }

        #saae-popup-close-heart {
            position: absolute;
            top: -10px;
            right: -40px;
            cursor: pointer;
            font-size: 24px;
            color: red;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            line-height: 1;
        }

        #saae-popup-close {
            position: absolute;
            top: -10px;
            right: -10px;
            cursor: pointer;
            font-size: 24px;
            color: red;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            line-height: 1;
        }

        /* Estilos responsivos para telas menores */
        @media screen and (max-width: 767px) {
            #saae-popup {
                max-width: 100%;
                max-height: 100%;
            }
        }

        /* Estilos para dispositivos desktop */
        @media screen and (min-width: 768px) {
            #saae-popup {
                width: 970px;
                height: 550px;
            }
        }
    </style>
    <?php
}

add_action('wp_head', 'saae_custom_css');
