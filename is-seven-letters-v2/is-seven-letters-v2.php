<?php
/**
 * Plugin Name: Is seven letters long tester
 * Description: A plugin to test the plugin is seven letters long.
 * Version: 1.0
 * Author: Oscar Fredriksson
 */

if ( ! defined('ABSPATH')) {
    die;
}


if ( class_exists('IsSevenLettersTest')) {
    $IsSevenLettersTest = new IsSevenLettersTest();
}

class IsSevenLettersTest extends IsSevenLetters {
    public function __construct() {
        add_action('before_is_seven_letters', array($this, 'seven_letters_page'));
    }
    public function seven_letters_page() {
        if (isset($_POST['test_function'])) {
            $this->is_seven_letters_test($_POST['test_string'], ($_POST['test_bool'] === 'true' ? true : false));
        }
        echo '
            <br>
            <h1>Test is seven letters long function</h1>

            <form action="" method="post">
            Test string: <input type="text" name="test_string"><br>
            Expected result: <select name="test_bool">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
            <br>
            <input type="submit" name="test_function" value="test_function">
            </form>
            <br>
            <br>';
    }
    public function is_seven_letters_test(string $input_string, bool $input_expected_value) {
        if ($this->is_seven_letters_long($input_string) === $input_expected_value) {
            echo '<p>Lyckat test! :)<p>';
        } else if ($this->is_seven_letters_long($input_string) != $input_expected_value) {
            echo '<p style="color: red;">Test misslyckades! :(</p>';
        }

    }
}