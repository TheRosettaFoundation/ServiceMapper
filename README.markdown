# MT-Mapper

Naoto Nishio 2011-11-07

MT-Broker will interact with locConnect for SOLAS will have two parts: MTBroker_Select and MTBroker_Connect . In future MTBroker_Select sends back to locConnect a nominated service descriptor considered as an optimal service for the provided workflow requirements. Then locConnect call MTBroker_Connect to call the target MT. 

On 2011/Nov only MTBroker_Connect has been developed connecting Babelfish MT provided by Yahoo!. MTBroker_Select is under development. 

## Requirements 
1.1 Environment
php, 
PEAR (HTTP_Request2),
javascript,
XLIFF1.2

### Installing PEAR on Debian-based Linux
sudo apt-get install php-pear
sudo pear install HTTP_Request2

## Contents
1.2 Files
demolangs.ini
languages.ini
apipmui.php
index.html
MTBroker_Connect.class.php
Process_XLIFF.class.php
upload.php

## How it works
2.Call index.html
2.1 Standalone component
It provides the user interface for you to process XLIFF file for translation as a standalone component as well as automatically and continuously calling locConnect looking for job posts.
As a standalone components it reads upload.php, it allows you to download or display on the web browser.

2.2 Interact with locConnect
It reads apipmui.php

The rest of the files are used for process read and write XLIFF file and for translation process.
