<?php
/**
 * Plugin Name: Is seven letters long
 * Description: A plugin to check if a string is 7 characters.
 * Version: 1.0
 * Author: Oscar Fredriksson
 */

if ( ! defined('ABSPATH')) {
    die;
}


if ( class_exists('IsSevenLetters')) {
    $IsSevenLetters = new IsSevenLetters();
}

class IsSevenLetters {
    public function __construct() {
        add_action('admin_menu', array($this, 'my_menu_pages'));
    }
    public function my_menu_pages(){
        add_menu_page('Is seven letters long', 'Is seven letters long', 'manage_options', 'seven-letters', array($this, 'seven_letters_page'));
    }
    public function seven_letters_page() {
        do_action( 'before_is_seven_letters' );
        if (isset($_POST['check'])) {
            $this->is_seven_letters_long($_POST['string']);
            
        }
        
        echo '<form action="" method="post">
        String: <input type="text" name="string"><br>
        <input type="submit" name="check" value="check">
        </form>';
    }
    public function is_seven_letters_long($input) {
        if (strlen($input) === 7) {
            echo 'true';
            return true;
        } else if (strlen($input) > 7 || strlen($input) < 7) {
            echo 'false';
            return false;
        }
    }
}
    