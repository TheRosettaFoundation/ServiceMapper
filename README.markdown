# MT-Mapper
This research is focused on the specification of service descriptors that facilitate optimal mapping from abstract workflows to concrete service providers in order to semi-automate service selection in the localisation process. The descriptors will support the documentation of functional requirements and architectural use cases necessary for optimal mapping, and a broker component has been developed to support the objectives outlined here.

MT-Mapper interacts with [locConnect] (https://github.com/TheRosettaFoundation/LocConnect) for SOLAS.

**Coded by:**
* Naoto Nishio 2011-11-07

## License notice
This software is licensed under the terms of the GNU LESSER GENERAL PUBLIC LICENSE Version 3, 29 June 2007 For full terms see License.txt or http://www.gnu.org/licenses/lgpl-3.0.txt

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

### Setup

* Copy config.template.ini to config.ini.
    cp config.template.ini config.ini
* Enter your settings in config.ini

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

## Live demo 
* Live demo: http://demo.solas.uni.me/mapper/

## References
* http://www2.ul.ie/pdf/461983197.pdf
* http://link.springer.com/chapter/10.1007%2F978-3-642-24106-2_48#page-1


## Acknowledgement
This research is supported by the Science Foundation Ireland (Grant 12/CE/I2267) as part of Centre for Next Generation Localisation (CNGL) www.cngl.ie at the Localisation Research Centre, Department of Computer Science and Information Systems, University of Limerick, Limerick, Ireland. It was also supported, in part, by "FP7-ICT-2011-7 - Language technologies" Project "MultilingualWeb-LT (LT-Web) - Language Technology in the Web" (287815 - CSA).
