<?php

namespace CPBW_PRO\App\Utilities;

class DynamicBadge {
    
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
            if ($percent == 0) {
                return false;
            }
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

    /**
     * Get regular price of a product
     * 
     * @param $product WC_Product
     * @return float
     */
    public static function regular_price( $product ) {
        $value = (float) $product->get_price();
        return $value;
    }

    /**
     * Get sale price of a product
     * 
     * @param $product WC_Product
     * @return float
     */
    public static function sale_price( $product ) {
        $value = (float) $product->get_sale_price();
        return $value;
    }
}