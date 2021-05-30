<?php
/**
 * Plugin Name: OF Weather
 * Description: A plugin to display weather for a selected area.
 * Version: 1.0
 * Author: Oscar Fredriksson
 */

if ( ! defined('ABSPATH')) {
    die;
}

if ( class_exists('ofWeather')) {
    $ofWeather = new ofWeather();
}

class ofWeather {
    public $selected_page;
    public $result;
    public $weather;


    public function __construct() {
        add_action('wp_head', array($this, 'which_page'));
    }
    public function which_page() {       
        $this->selected_page = get_field('of_weather', 'options');
        switch ($this->selected_page) {
            case 'specific_product':
                if ( is_product() && wc_get_product()->get_id() === get_field('specific_product', 'options') ) {
                    add_action('woocommerce_before_single_product', array($this, 'weather'));
                }
                break;
            case 'cart':
                add_action('woocommerce_before_cart', array($this, 'weather'));
                break;
            case 'shop':
                add_action('woocommerce_before_shop_loop', array($this, 'weather'));
                break;
            case 'checkout':
                add_action('woocommerce_before_checkout_form', array($this, 'weather'));
                break;
            case 'product':
                add_action('woocommerce_before_single_product', array($this, 'weather'));
                break;
        }
    
    }
    public function weather () {
        if (get_transient('Weather') === false) {
            $this->result = wp_remote_get('http://api.openweathermap.org/data/2.5/weather?q=Gothenburg&units=metric&appid=455dd990cb94b9cccbd792dafe888831&lang=sv');
            set_transient('Weather', $this->result, HOUR_IN_SECONDS);
            }
            $weather = json_decode(get_transient('Weather')['body'], true);
            echo "Grader: {$weather['main']['temp']} C";
            echo '<br>';
            echo "Beskrivning: {$weather['weather'][0]['description']}";
            echo '<br>';
            echo "Vind hastighet: {$weather['wind']['speed']} m/s";
            echo '<br>';
            
            
        }
    }

add_action('acf/init', 'of_weather_init');
function of_weather_init() {
    if( function_exists('acf_add_options_page') ) {
        $option_page = acf_add_options_page(array(
            'page_title'    => __('OF Weather options'),
            'menu_title'    => __('OF Weather options'),
            'menu_slug'     => 'of_weather_options',
            'capability'    => 'edit_posts',
            'redirect'      => false
        ));
    }
}