<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 30/05/2023
 * Time: 1:31 PM
 */

/**
 * In this example we create a rule for order billing post/zip codes.
 * This will be a simple true/false string match
 */
use AutomateWoo\RuleQuickFilters\Clauses\SetClause;

class My_AW_Rule_Validar_Suscripcion extends AutomateWoo\Rules\Rule {

    /** @var string - can be string|number|object|select */
    public $type = 'string';

    /** @var string - (required) choose which data item this rule will apply to */
    public $data_item = 'subscription';

    /**
     * Init
     */
    function init() {
        // the title for your rule
        $this->title = __( 'Subscription - Excluir ID de la Suscripción', 'automatewoo' );
        $this->description = __( "Campo para excluir las suscripciones de la ejecución del flujo", 'automatewoo');
        // grouping in the admin list
        $this->group = __( 'Personalizado', 'automatewoo' );

        // compare type is the middle select field in the rule list
        // you can define any options here but this is a basic true/false example
        $this->compare_types = [
            'is' => __( 'is', 'automatewoo' ),
            'is_not' => __( 'is not', 'automatewoo' )
        ];
    }


    /**
     * Validates the rule based on options set by a workflow
     * The $data_item passed will already be validated
     * @param $data_item
     * @param $compare
     * @param $expected_value
     * @return bool
     */
    function validate( $data_item, $compare, $expected_value ) {
        // because $this->data_item == 'order' $data_item wil be a WC_Order object
        $order = $data_item;

        $actual_value = $order->get_ID();

        /*if("190.70.133.51" == $_SERVER['REMOTE_ADDR']){
            echo '<pre>';
            print_r($actual_value == $expected_value);
            echo '</pre>';

        }
        die;*/
        // we set 2 compare types
        switch ( $compare ) {
            case 'is':
                return $actual_value == $expected_value;
                break;
            case 'is_not':
                return $actual_value != $expected_value;
                break;
        }

        return false;
    }

}

return new My_AW_Rule_Validar_Suscripcion();