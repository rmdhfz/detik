<?php
/**
 * @author      Jesse Boyer <contact@jream.com>
 * @copyright   Copyright (C), 2011-12 Jesse Boyer
 * @license     GNU General Public License 3 (http://www.gnu.org/licenses/)
 *              Refer to the LICENSE file distributed within the package.
 *
 * @link        http://jream.com
 *
 * @internal    Inspired by Klein @ https://github.com/chriso/klein.php
 */

class Route
{
    private static $_listUri = array();
    private static $_listCall = array();
    private static $_trim = '/\^$';
    static public function add($uri, $function)
    {
        $uri = trim($uri, self::$_trim);
        self::$_listUri[] = $uri;
        self::$_listCall[] = $function;
    }
    static public function submit()
    {   
        $uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';
        $uri = trim($uri, self::$_trim);
        $replacementValues = array();
        foreach (self::$_listUri as $listKey => $listUri)
        {
            if (preg_match("#^$listUri$#", $uri))
            {
                $realUri = explode('/', $uri);
                $fakeUri = explode('/', $listUri);
                foreach ($fakeUri as $key => $value) 
                {
                    if ($value == '.+') 
                    {
                        $replacementValues[] = $realUri[$key];
                    }
                }
                call_user_func_array(self::$_listCall[$listKey], $replacementValues);
            }
        }
    }
}