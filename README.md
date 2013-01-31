FTP Client for PHP
=========================

*By Melih Ucar.
(http://www.melihucar.net/)*

*licensed under the MIT licenses*

# USAGE
**Connection to FTP Host :**

```php
$ftp = new FTPClient();
$ftp->connect($host, $ssl, $port, $timeout);
$ftp->loginlogin($username, $password);
```

### Using Passive Mode :###

```php
// This uses passive mode
$ftp->passive();

// If you want to disable using passive mode then
$ftp->passive(false);
```

###Changing Directory :###

```php
// You can use fullpath to change dir
$ftp->changeDirectory('/root_dir/sub_dir');

// or you can use method chaining
$ftp->changeDirectory('/root_dir')->changeDirectory('sub_dir');
```

###Change To Parent Directory :###

```php
$ftp->changeDirectory('/root_dir/sub_dir');

$ftp->parentDirectory(); // now we are in /root_dir
```

###Getting Current Directory :###

```php
// will return current path as string
$ftp->getDirectory();
```

###Creating Directory :###

```php
// creates a directory in current path
$ftp->createDirectory($directoryName);
```

###Removing Directory :###

```php
$ftp->removeDirectory($directoryName);
```

###Getting Directory List :###

```php
// return directory list as array
$ftp->listDirectory();
```
