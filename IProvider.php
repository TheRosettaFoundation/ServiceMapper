<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author sean
 */
interface IProvider {
    /**
     * return the translated file 
     * @return String
     */
    public function translateFile($fileText,$source,$target);
    /**
     * return the supported target lanugages
     * @return Array
     */
    public function getTargetLanguages();
    /**
     * return the supported source lanugages
     * @return Array
     */
    public function getSourceLanguages();
    
    /**
     * checks if a provider is enabled
     * @return boolean
     */
    public function isEnabled();   
}

?>
