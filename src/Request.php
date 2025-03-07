<?php
namespace Hermawan\DataTables;

use \Config\Services;

class Request
{

	 /**
     * Get DataTable Request
     *  
     * @param  string $requestName
     * @return string|array
     */


    public static function get($requestName = NULL)
    {
    	$request = Services::request();
        if($requestName !== NULL)
    	   return $request->getGetPost($requestName);
        
        return (Object) ((strtolower($request->getMethod()) == 'get') ? $request->getGet() : $request->getPost());

    }


}