<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 29/04/2021
 * Time: 3:41 PM
 */

/**
 * In this example we create a rule for order billing post/zip codes.
 * This will be a simple true/false string match
 */
use AutomateWoo\RuleQuickFilters\Clauses\SetClause;

class Validar_usuari_en_notas_susbs extends AutomateWoo\Rules\Rule {

    /** @var string - can be string|number|object|select */
    public $type = 'string';

    /** @var string - (required) choose which data item this rule will apply to */
    public $data_item = 'subscription';

    /**
     * Init
     */
    function init() {

        // the title for your rule
        $this->title = __( 'Subscription - Validar Nota', 'automatewoo' );

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
        $args = array(
            'order_id' => $data_item->get_id(),
        );

        $notes = wc_get_order_notes( $args );

        if($notes[0]->added_by != $expected_value){
            return true;
        }

        return false;
    }

}

return new Validar_usuari_en_notas_susbs();