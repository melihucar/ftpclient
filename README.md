FTP Client for PHP
=========================
[![Build Status](https://api.travis-ci.org/melihucar/ftpclient.png?branch=master)](https://travis-ci.org/melihucar/ftpclient)
[![Total Downloads](https://poser.pugx.org/melihucar/ftpclient/downloads.png)](https://packagist.org/packages/melihucar/ftpclient)
[![Latest Stable Version](https://poser.pugx.org/melihucar/ftpclient/v/stable.png)](https://packagist.org/packages/melihucar/ftpclient)

[![Support via Gittip](https://rawgithub.com/twolfson/gittip-badge/0.1.0/dist/gittip.png)](https://www.gittip.com/melihucar)

*By Melih Ucar.
(http://www.melihucar.net/)*

*licensed under the MIT licenses*

# USAGE
**Connection to FTP Host :**

```php
$ftp = new FTPClient();
$ftp->connect($host, $ssl, $port, $timeout);
$ftp->login($username, $password);
```

### Using Passive Mode :###

```php
// This uses passive mode
$ftp->passive();

// If you want to disable using passive mode then
$ftp->passive(false);
```


### Using Binary Mode :###

```php
// This uses binary mode, required for downloading binaries like zip
$ftp->binary(true);

// standard is false
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
// returns directory list as array
$ftp->listDirectory();
```

###Getting Directory List :###

```php
// executes the FTP LIST command, and returns the result as an array
$ftp->rawlistDirectory($parameters, $recursive);
```

###Deleting File :###

```php
$ftp->delete($filename);
```

###Returns file size (bytes) :###

```php
$ftp->size($filename);
```

###Returns last modified time :###

```php
// returns unix timestamp
$ftp->modifiedTime($filename);

// returns formated
$ftp->modifiedTime($filename, $format);
```

###Renaming files or folders :###

```php
$ftp->rename($current, $new);
```

###Downloading a file :###
```php
// Downloads a file from remote host
$ftp->get($localFile, $remoteFile);

// Downloads file to an open file
$ftp->fget($handle, $remoteFile);
```

###Uploading a file :###
```php
// Uploading local file to remote host
$ftp->put($remoteFile, $localFile);

// Uploading from an open file
$ftp->fput($remoteFile, $handle);
```

###Getting server options :###
```php
$ftp->getOption(FTPClient::TIMEOUT_SEC);
```

###Setting server options :###
```php
$ftp->setOption(FTPClient::TIMEOUT_SEC, 30);
```

###Allocating space for uploading file :###
```php
$ftp->allocate($filesize);
```

###Changing file and directory permissions :###
```php
$ftp->chmod($mode, $filename);
```

###Running custom command on remote server :###
```php
$ftp->exec($command);
```

###Error Handling :###
Class throws exception if opetarion fails. So simply use try-catch blocks.
```php
try {
    $ftp = new FTPClient();
    $ftp->connect($host, $ssl, $port, $timeout);
    $ftp->loginlogin($username, $password);
} catch (Exception $e) {
    // we got the error!
}
```

# Contributing
Did you find a bug or do you know a better way to do it? Simply just fork and fix it. Then send a pull request.
