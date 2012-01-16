# MT-Mapper

Naoto Nishio 2011-11-07

MT-Mapper interacts with locConnect for SOLAS.

## Future development
MT-Mapper will have two parts: MTBroker_Select and MTBroker_Connect . In future MTBroker_Select will send back to 
locConnect a nominated service descriptor considered as an optimal service for the provided workflow requirements. 
Then locConnect call MTBroker_Connect to call the target MT. 

On 2011/Nov only MTBroker_Connect has been developed connecting Babelfish MT provided by Yahoo!. MTBroker_Select is under development. 

## Requirements 

### Environment
* PHP
* PEAR (specifically the HTTP_Request2 module)
* JavaScript
* XLIFF 1.2

### Installing PEAR on Debian-based Linux
    sudo apt-get install php-pear
    sudo pear install HTTP_Request2

## How it works
* Call index.html
  * Standalone component
  * It provides the user interface for you to process XLIFF file for 
    translation as a standalone component as well as automatically 
    and continuously calling locConnect looking for job posts.
    As a standalone component it reads upload.php, it allows you to 
    download or display on the web browser.
* Interact with locConnect
  * It reads apipmui.php

The rest of the files are used for processing XLIFF files and for translation process.
