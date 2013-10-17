<?php
/**
 * File containing the ezcDbOracleOption class
 *
 * @package Database
 * @version //autogentag//
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */
/**
 * Class containing the options for MS SQL Server connections
 *
 * @property int $quoteIdentifier
 *           Mode of quoting identifiers.
 *
 * @package Database
 * @version //autogentag//
 */
class ezcDbOracleOptions extends ezcBaseOptions
{
    /**
     * Constant represents mode of identifiers quoting that
     * always quotes all identifiers (default).
     *
     * @access public
     */
    const QUOTES_COMPLIANT = 0;

    /**
     * Constant represents mode of identifiers quoting that
     * quotes only identifiers which need it according to SQL92 spec
     *
     * @access public
     */
    const QUOTES_GUESS     = 1;


    /**
     * Constant represents mode of identifiers quoting that does nothing.
     * Can be used for optimization.
     *
     * @access public
     */
    const QUOTES_UNTOUCHED = 2;


    /**
     * Creates an ezcDbMssqlOptions object with default option values.
     *
     * @param array $options
     */
    public function __construct( array $options = array() )
    {
        $this->quoteIdentifier = self::QUOTES_UNTOUCHED;

        parent::__construct( $options );
    }

    /**
     * Set an option value
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @throws ezcBasePropertyNotFoundException
     *          If a property is not defined in this class
     * @throws ezcBaseValueException
     *          If a property is out of range
     * @ignore
     */
    public function __set( $propertyName, $propertyValue )
    {
        switch ( $propertyName )
        {
            case 'quoteIdentifier':
                if ( !is_numeric( $propertyValue )  ||
                     ( ( $propertyValue != self::QUOTES_COMPLIANT ) &&
                       ( $propertyValue != self::QUOTES_GUESS ) &&
                       ( $propertyValue != self::QUOTES_UNTOUCHED )
                     )
                   )
                {
                    throw new ezcBaseValueException( $propertyName, $propertyValue,
                        'one of ezcDbOracleOptions::QUOTES_COMPLIANT, QUOTES_GUESS, QUOTES_UNTOUCHED constants' );
                }

                $this->quoteIdentifier = (int) $propertyValue;
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $propertyName );
                break;
        }
    }
}

?>
