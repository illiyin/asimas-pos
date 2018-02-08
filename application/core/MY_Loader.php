<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

	// function controller( $sController ) {
 //        global $RTR;

 //        // Parse the sController string ex: demo/index
 //        // $aControllerData = explode( '/', $sController );

 //        // $sMethod = !empty( $aControllerData[1] )
 //        //     ? $aControllerData[1]
 //        //     : 'index'
 //        // ;
 //        // $sController = !empty( $aControllerData[0] )
 //        //     ? $aControllerData[0]
 //        //     : $RTR->default_controller
 //        // ;

 //        // $sClass = ucfirst( $sController );
 //        $aControllerData = explode("/", $sController);
 //        $sModule = !empty($aControllerData[0])?$aControllerData[0]:$RTR->default_controller;
 //        $sClass = !empty($aControllerData[1])?$aControllerData[1]:"index";

 //        $sPath = APPPATH . 'modules/';

 //        if ( !file_exists( $sPath . $sModule . "/controllers/" . $sClass . '.php' )) {

 //            set_status_header( 503 );
 //            exit( 'Unable to locate the specified class: '. ucfirst( $sPath . $sModule . "/controllers/" . $sClass ).'.php' );
 //            // exit( $sPath . $sController . '.php' );
 //        }
 //        return $this->file( $sPath . $sModule . "/controllers/" . $sClass . '.php' );
 //        // $sClass = new $sClass;

 //        // if ( !method_exists( $sClass, $sMethod ) ) {
 //        //     set_status_header( 503 );
 //        //     exit( 'There is no Method: ' . $sMethod . ' in Class: '. ucfirst( $sController ).'.php' );
 //        // }
 //        // $aArguments = func_get_args();
 //        // return call_user_func_array( array( $sClass ), array_slice( $aArguments, 0));
 //    }
}