<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 25-Jul-19
 * Time: 1:20 PM
 */

/**
 * CSRF TOKEN APPLICATION
 * Added By: Hasan Masud
 * @Alpha = Empty token.
 * @Beta = Invalid/Doctored Token
 * */

/**
 * generate CSRF token
 *
 * @author  Aqa Technology
 * @param   string $formName
 * @return  string
 */
function generateToken( $formName )
{
    $secretKey = 'imfhs154aergg2#';
    if ( !session_id() && !headers_sent() ) {
        session_start();
    }
    $sessionId = session_id();

    return sha1( $formName.$sessionId.$secretKey );

}
/**
 * check CSRF token
 *
 * @author  Aqa Technology
 * @param   string $token
 * @param   string $formName
 * @return  boolean
 */
function checkToken( $token, $formName )
{
    return $token === generateToken( $formName );
}


/*if ( !empty( $_POST['csrf_token'] ) ) {

    if( checkToken( $_POST['csrf_token'], 'protectedForm' ) ) {
        // valid form, continue
    }

}*/ // end if


/**
 * Insert/Update Request validation
 * @param string $csrf_token
 * @param string $form_name
 * @return integer if request is valid else string(Error message)
 * Added by: Hasan Masud
 * Added on: 06-08-2019
 ***********************/
function validateRequest($csrf_token, $form_name){
    if (!empty($csrf_token)) {
        if (checkToken($csrf_token, $form_name)) {
            return 1;
        } else {
            $err_res["message"] = 'Error Code: Beta.';
            return $err_res;
        }
    } else {
        $err_res["message"] = 'Error Code: Alpha';
        return $err_res;
    }

}
