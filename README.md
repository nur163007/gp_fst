# GP Foreign Shipment Tool (FST)

A tool to process GP foreign procurement

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

```
PHP version: 5.6
```

### Installing

A step by step series of examples that tell you how to get a development env running


### # 1 . Clone the repository into your xampp/htdocs OR Ampps/www folder
```
git clone https://github.com/aqa-technology/fstV3.git fstV3
```
### # 2. Enter your fstV3 folder
```
cd fstV3
```
Make sure you have [Composer](https://getcomposer.org/download) installed in your machine.

### # 3. Create necessary directories
Then execute the below command.
```
php mkdir.php
```
### # 4. Establish DB connection in below file
```
fstV3/application/lib/dal.php
$password	= "Your_DB_Pass";
$db_name	= "Your_DB_Name";
```
If everything done as described you can check application at your local machine in
```
http://localhost/fstV3
```


