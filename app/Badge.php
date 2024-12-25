<?php

namespace CPBW_PRO\App;

class Badge {

    /**
     * Badge constructor.
     */
    public function __construct() {
        add_filter('cpbw_pro_badge', [$this, 'apply_dynamic_badge'], 10, 3);
    }

    /**
     * Apply dynamic badges to the product
     * 
     * @param $badge string
     * @param $badge_config array
     * @param $product WC_Product
     * @return string
     */
    public function apply_dynamic_badge( $badge, $badge_config, $product ) {

        error_log(print_r('DynamicBadge::apply_dynamic_badge', true));

        $badge_style = $badge_config['badge_style'];
        $pattern = '/\{\{(.*?)\}\}/';
    
        if (preg_match_all($pattern, $badge_style, $matches)) {
            $dynamic_badges_placeholders = $matches[1];
        }
    
        /**
         * Loop through the dynamic badges placeholders and replace them with the actual values
         * 
         * @param $dynamic_badges_placeholders array
         * @param $badge_style string
         */
        foreach ($dynamic_badges_placeholders as $placeholder) {
            switch ($placeholder) {
                case 'discount_percentage':
                    $value = self::precent_calculation($product);
                    if ($value == 0) {
                        return false;
                    }
                    $badge_style = str_replace('{{discount_percentage}}', $value, $badge_style);
                    break;
    
                case 'discount_value':
                    $value = self::discount_value_calculation($product);
                    if ($value == 0) {
                        return false;
                    }
                    $badge_style = str_replace('{{discount_value}}', $value, $badge_style);
                    break;
    
                case 'regular_price':
                    $value = (float) $product->get_price();
                    if ($value == 0) {
                        return false;
                    }
                    $badge_style = str_replace('{{regular_price}}', $value, $badge_style);
                    break;
    
                case 'sale_price':
                    $value = (float) $product->get_sale_price();
                    if ($value == 0) {
                        return false;
                    }
                    $badge_style = str_replace('{{sale_price}}', $value, $badge_style);
                    break;
    
                default:
                    break;
            }
        }
    
        return $badge_style;
    }    

    /**
     * Calculate the percentage discount of a product
     * 
     * @param $product WC_Product
     * @return float
     */
    public static function precent_calculation( $product ) {
        // Get and cast prices to float to ensure they are numeric
        $price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();   
    
        // Check if sale price is valid to prevent division by zero
        if ($price > 0 && $sale_price > 0) {
            $percent = round((($price - $sale_price) / $price) * 100);
            return (float) $percent . '%';
        }
    
        return false;
    }

    /**
     * Calculate the value of the discount of a product
     * 
     * @param $product WC_Product
     * @return float
     */
    public static function discount_value_calculation( $product ) {
        // Get and cast prices to float to ensure they are numeric
        $price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();
    
        // Check if sale price is valid to prevent unexpected results
        if ($price > 0 && $sale_price > 0) {
            $discount = $price - $sale_price;
            return (float) $discount;
        }
    
        return false;
    }
    
}