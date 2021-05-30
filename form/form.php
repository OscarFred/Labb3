<?php
/**
 * Plugin Name: Form
 * Description: A plugin create a form and show sent in data on admin dashboard.
 * Version: 1.0
 * Author: Oscar Fredriksson
 */

if ( ! defined('ABSPATH')) {
    die;
}


if ( class_exists('Form')) {
    $Form = new Form();
}


class Form {
    public $ajax_url;
    public function __construct() {
        $this->ajax_url= admin_url('admin-ajax.php');
        add_shortcode('form', array($this, 'form_page'));
        add_action('wp_footer', array($this, 'form_add_javascript'));
        add_action('wp_ajax_send_form', array($this, 'insert_form'));
        add_action('init', array($this,'kontakt'));
    }
    public function form_page() {
        // <form action=\"$this->ajax_url\" method=\"post\">
        $content = "
        <label for=\"namn\">Namn</label>
        <input id=\"namn\" type=\"text\" name=\"namn\">
        
        <label for=\"epost\">E-post</label>
        <input id=\"epost\" type=\"text\" name=\"epost\">

        <label for=\"meddelande\">Meddelande</label>
        <textarea id=\"meddelande\" name=\"meddelande\"></textarea>
        <input type=\"submit\" name=\"form_submit\" id=\"form_submit\" onclick=\"submit_form('$this->ajax_url')\" value=\"Skicka\">";
        return $content;
        // </form>";
    }
    public function form_add_javascript() {
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            function submit_form(ajaxUrl) {
                let formData =  {
                    namn: $("#namn").val(),
                    epost: $("#epost").val(),
                    meddelande: $("#meddelande").val()
                }
                jQuery.ajax({url: ajaxUrl, data: {action: "send_form", form: formData}, success: function(response){
                        console.log(response)
                        location.reload()
                    }
                })
            }
        </script>
        <?php
    }
    public function insert_form() {
        $postContent = "<p>Epost: " . $_REQUEST['form']['epost'] . "</p><br> <p>Meddelande:" . $_REQUEST['form']['meddelande'] . "</p>";
        wp_insert_post(['post_title' => $_REQUEST['form']['namn'], 'post_content' => $postContent, 'post_type' => 'kontakt']);

    }
    public function kontakt() {
        register_post_type('kontakt', ['labels' => ['name' => 'Kontaktformulär poster', 'singular_name' => 'Kontaktformulär post'], 'public' => true, 'show_in_rest' => true]);
    }
}
    