
<?php
/**
 * Plugin Name: My First Plugin
 * Description: A beginner WordPress plugin that displays a custom greeting.
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create an admin menu.
function mfp_add_admin_menu() {
    add_options_page(
        'My First Plugin',
        'My First Plugin',
        'manage_options',
        'my-first-plugin',
        'mfp_settings_page'
    );
}

add_action( 'admin_menu', 'mfp_add_admin_menu' );

// Display the settings page.
function mfp_settings_page() {

    if ( isset( $_POST['mfp_save'] ) ) {

        check_admin_referer( 'mfp_save_settings' );

        $greeting = sanitize_text_field(
            wp_unslash( $_POST['mfp_greeting'] ?? '' )
        );

        update_option( 'mfp_greeting', $greeting );

        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }

    $greeting = get_option(
        'mfp_greeting',
        'Hello, welcome to my website!'
    );
    ?>

    <div class="wrap">
        <h1>My First Plugin</h1>

        <form method="post">
            <?php wp_nonce_field( 'mfp_save_settings' ); ?>

            <label for="mfp_greeting">
                Enter your greeting:
            </label>

            <input
                type="text"
                id="mfp_greeting"
                name="mfp_greeting"
                value="<?php echo esc_attr( $greeting ); ?>"
                class="regular-text"
            >

            <p>
                <button
                    type="submit"
                    name="mfp_save"
                    value="1"
                    class="button button-primary"
                >
                    Save Greeting
                </button>
            </p>
        </form>

        <p>Use shortcode: <code>[my_greeting]</code></p>
    </div>

    <?php
}

// Register the shortcode.
// function mfp_display_greeting() {

//     $greeting = get_option(
//         'mfp_greeting',
//         'Hello, welcome to my website!'
//     );

//     return '<div class="mfp-greeting">' .
//         esc_html( $greeting ) .
//         '</div>';
// }


function mfp_display_greeting() {

    $greeting = get_option(
        'mfp_greeting',
        'Hello, welcome to my website!'
    );

    ob_start();
    ?>

    <div class="mfp-greeting">

        <h2><?php echo esc_html( $greeting ); ?></h2>

        <input
            type="text"
            id="mfp-user-name"
            placeholder="Enter your name"
        >

        <button type="button" id="mfp-greet-button">
            Say Hello
        </button>

        <p id="mfp-greeting-result"></p>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const button = document.getElementById('mfp-greet-button');
        const input = document.getElementById('mfp-user-name');
        const result = document.getElementById('mfp-greeting-result');

        if (!button || !input || !result) return;

        button.addEventListener('click', function () {

            const name = input.value.trim();

            if (!name) {
                result.textContent = 'Please enter your name!';
                return;
            }

            result.textContent = 'Hello, ' + name + '! Welcome to my website!';

        });

    });
    </script>

    <?php
    return ob_get_clean();
}


add_shortcode( 'my_greeting', 'mfp_display_greeting' );
