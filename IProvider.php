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
    
    const XMLNS_MTC = 'urn:oasis:names:tc:xliff:matches:2.0';
    const XMLNS_ITS = 'http://www.w3.org/2005/11/its';
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
