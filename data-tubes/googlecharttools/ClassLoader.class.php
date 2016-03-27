<?php

/*
 * Copyright 2012 Patrick Strobel.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * By including this file, all classes in this API will be loaded/included automatically
 * when they are "called" by the new-statement.
 * 
 * @author Patrick Strobel
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link http://code.google.com/p/googlecharttools-php
 * @version $Id: ClassLoader.class.php 28 2012-06-16 14:14:01Z patrick.strobel.swt@gmail.com $
 * @package root
 */

namespace googlecharttools;

ClassLoader::register();

/**
 * The class loader is used to automatically load classes that are requested
 * by the new-statement.
 *
 * The class' name is used as file name, with the extension
 * ".class.php" added, whereas the namespace is used as the file's directoy-path.
 *
 * @package root
 */
class ClassLoader {

    private static $baseDir = __DIR__;

    /**
     * Registers the class loader.
     */
    public static function register() {
        spl_autoload_register(__CLASS__ . "::load");
    }

    /**
     * Loads a class file.
     *
     * @param string $class
     *              The class' name.
     * @return boolean
     *              True, if file was loaded successfully.
     */
    public static function load($class) {

        $classpath = explode("\\", $class);
        // Cancel if class has another namespace as classloader
        if (count($classpath) <= 1 || $classpath[0] != __NAMESPACE__) {
            return false;
        }

        // Use namesapces (without the parent namespace) and filename as path
        $path = self::$baseDir . DIRECTORY_SEPARATOR .
                implode(DIRECTORY_SEPARATOR, array_slice($classpath, 1)) . ".class.php";


        // echo "load: ".$class . " path: $path <br>";
        // Cancel if file does not exist
        if (!file_exists($path)) {
            echo "<b>Cannot load class:</b> " . $path . "<br />\n";
            return false;
        }
        require_once($path);
    }

}

?>
