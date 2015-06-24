<?php namespace Melihucar\FtpClient;

use \Exception;

/**
 * FTP Client for PHP
 * 
 * @package FTPClient
 * @version 1.0
 * 
 * @copyright Melih Ucar
 * @author Melih Ucar
 * @license http://opensource.org/licenses/MIT (The MIT License)
 * 
 * Copyright (c) 2013, Melih Ucar (http://www.melihucar.net/)
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE
 * OR OTHER DEALINGS IN THE SOFTWARE.
 */
class FtpClient
{

    // Const variables
    const ASCII = FTP_ASCII;
    const BINARY = FTP_BINARY;

    const TIMEOUT_SEC = FTP_TIMEOUT_SEC;
    const AUTOSEEK = FTP_AUTOSEEK;

    /**
     * FTP connection
     * @var Resource
     */
    private $connection = null;

    /**
     * passive mode active / to be activated
     *
     * @var bool
     */
    protected $passive = false;

    /**
     * verbose mode
     *
     * @todo allow verbose mode to call ftp functions without "@"
     * @var bool
     */
    protected $verbose = false;

    /**
     *
     * @var bool
     */
    protected $binary = false;

    /**
     * Constructor
     * 
     * Checks if ftp extension is loaded.
     */
    public function __construct()
    {
        if ( !extension_loaded('ftp') ) {
            throw new Exception('FTP extension is not loaded!');            
        }
    }

    /**
     * Opens a FTP connection
     * 
     * @param string $host
     * @param bool $ssl
     * @param int $port
     * @param int $timeout
     * 
     * @return FTPClient
     */
    public function connect($host, $ssl = false, $port = 21, $timeout = 90)
    {
        if ($ssl) {
            $this->connection = @ftp_ssl_connect($host, $port, $timeout);
        } else {
            $this->connection = @ftp_connect($host, $port, $timeout);
        }

        if ($this->connection == null) {
            throw new Exception('Unable to connect');            
        } else {
            return $this;
        }
    }

    /**
     * Logins to FTP Server
     * 
     * @param string $username
     * @param string $password
     * 
     * @return FTPClient
     */
    public function login($username = 'anonymous', $password = '')
    {
        $result = @ftp_login($this->connection, $username, $password);

        if ($result === false) {
            throw new Exception('Login incorrect');
        } else {
            // set passive mode
            if (!is_null($this->passive)) {
                $this->passive($this->passive);
            }

            return $this;
        }
    }

    /**
     * Closes FTP connection
     * 
     * @return void
     */
    public function close()
    {
        $result = @ftp_close($this->connection);

        if ($result === false) {
            throw new Exception('Unable to close connection');
        }
    }

    /**
     * Changes passive mode,,,
     * 
     * @param bool $passive
     * 
     * @return FTPClient,
     */
    public function passive($passive = true)
    {
        $this->passive = $passive;

        if ($this->connection) {
            $result = ftp_pasv($this->connection, $passive);
            if ($result === false) {
                throw new Exception('Unable to change passive mode');
            }
        }

        return $this;
    }

    public function binary($binary)
    {
        $this->binary = $binary;

        return $this;
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->binary ? FTPClient::BINARY : FTPClient::ASCII;
    }

    /**
     * Changes the current directory to the specified one
     * 
     * @return FTPClient
     */
    public function changeDirectory($directory)
    {
        $result = @ftp_chdir($this->connection, $directory);
        
        if ($result === false) {
            throw new Exception('Unable to change directory');
        }

        return $this;
    }

    /**
     * Changes to the parent directory
     * 
     * @return FTPClient
     */
    public function parentDirectory()
    {
        $result = @ftp_cdup($this->connection);
        
        if ($result === false) {
            throw new Exception('Unable to get parent folder');
        }

        return $this;
    }

    /**
     * Returns the current directory name
     *
     * @return string
     */
    public function getDirectory()
    {
        $result = @ftp_pwd($this->connection);
        
        if ($result === false) {
            throw new Exception('Unable to get directory name');
        }

        return $result;
    }

    /**
     * Creates a directory
     *
     * @param string $directory
     *
     * @return FTPClient
     */
    public function createDirectory($directory)
    {
        $result = @ftp_mkdir($this->connection, $directory);
        
        if ($result === false) {
            throw new Exception('Unable to create directory');
        }

        return $this;
    }

    /**
     * Removes a directory
     *
     * @param string $directory
     * 
     * @return FTPClient
     */
    public function removeDirectory($directory)
    {
        $result = @ftp_rmdir($this->connection, $directory);
        
        if ($result === false) {
            throw new Exception('Unable to remove directory');
        }

        return $this;
    }

    /**
     * Returns a list of files in the given directory
     *
     * @param string $directory
     *
     * @return array
     */
    public function listDirectory($directory)
    {
        $result = @ftp_nlist($this->connection, $directory);
        
        if ($result === false) {
            throw new Exception('Unable to list directory');
        }
        
        asort($result);

        return $result;
    }

    /**
     * @param string $parameters
     * @param bool $recursive
     *
     * @return array
     *
     * @throws \Exception
     */
    public function rawlistDirectory($parameters, $recursive = false)
    {
        $result = @ftp_rawlist($this->connection, $parameters, $recursive);

        if ($result === false) {
            throw new Exception('Unable to list directory');
        }

        return $result;
    }

    /**
     * Deletes a file on the FTP server
     *
     * @param string $path
     * 
     * @return FTPClient
     */
    public function delete($path)
    {
        $result = @ftp_delete($this->connection, $path);
        
        if ($result === false) {
            throw new Exception('Unable to get parent folder');
        }

        return $this;
    }

    /**
     * Returns the size of the given file.
     * Return -1 on error
     *
     * @param string $remoteFile
     *
     * @return int
     * 
     */
    public function size($remoteFile)
    {
        $size = @ftp_size($this->connection, $remoteFile);

        if ($size === -1) {
            throw new Exception('Unable to get file size');
        }

        return $size;
    }

    /**
     * Returns the last modified time of the given file.
     * Return -1 on error
     *
     * @param string $remoteFile
     *
     * @return int
     * 
     */
    public function modifiedTime($remoteFile, $format = null)
    {
        $time = ftp_mdtm($this->connection, $remoteFile);

        if ( $time !== -1 && $format !== null ) {
            return date($format, $time);
        } else {
            return $time;
        }
    }

    /**
     * Renames a file or a directory on the FTP server
     *
     * @param string $currentName
     * @param string $newName
     *
     * @return bool
     */
    public function rename($currentName, $newName)
    {
        $result = @ftp_rename($this->connection, $currentName, $newName);

        return $result;
    }

    /**
     * Downloads a file from the FTP server
     *
     * @param string $localFile
     * @param string $remoteFile
     * @param int $mode
     * @param int $resumepos
     * 
     * @return FTPClient
     */
    public function get($localFile, $remoteFile, $resumePosision = 0)
    {
        $mode = $this->getMode();
        $result = @ftp_get($this->connection, $localFile, $remoteFile, $mode, $resumePosision);

        if ($result === false)
        {
            throw new Exception(sprintf('Unable to get or save file "%s" from %s', $localFile, $remoteFile));
        }

        return $this;
    }

    /**
     * Uploads from an open file to the FTP server
     *
     * @param string $remoteFile
     * @param string $localFile
     * @param int $mode
     * @param int $startPosision
     * 
     * @return FTPClient
     */
    public function put($remoteFile, $localFile, $startPosision = 0)
    {
        $mode = $this->getMode();
        $result = @ftp_put($this->connection, $remoteFile, $localFile, $mode, $startPosision);
        
        if ($result === false) {
            throw new Exception('Unable to put file');
        }

        return $this;
    }

    /**
     * Downloads a file from the FTP server and saves to an open file
     *
     * @param resource $handle
     * @param string $remoteFile
     * @param int $mode
     * @param int $resumepos
     * 
     * @return FTPClient
     */
    public function fget($handle, $remoteFile, $resumePosision = 0)
    {
        $mode = $this->getMode();
        $result = @ftp_fget($this->connection, $handle, $remoteFile, $mode, $resumePosision);
        
        if ($result === false) {
            throw new Exception('Unable to get file');
        }

        return $this;
    }

    /**
     * Uploads from an open file to the FTP server
     *
     * @param string $remoteFile
     * @param resource $handle
     * @param int $mode
     * @param int $startPosision
     * 
     * @return FTPClient
     */
    public function fput($remoteFile, $handle, $startPosision = 0)
    {
        $mode = $this->getMode();
        $result = @ftp_fput($this->connection, $remoteFile, $handle, $mode, $startPosision);
        
        if ($result === false) {
            throw new Exception('Unable to put file');
        }

        return $this;
    }

    /**
     * Retrieves various runtime behaviours of the current FTP stream
     * TIMEOUT_SEC | AUTOSEEK
     *
     * @param mixed $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        switch ($option) {
            case FTPClient::TIMEOUT_SEC:
            case FTPClient::AUTOSEEK:
                $result = @ftp_get_option($this->connection, $option);

                return $result;
                break;
            
            default:
                throw new Exception('Unsupported option');
                break;
        }
    }

    /**
     * Set miscellaneous runtime FTP options
     * TIMEOUT_SEC | AUTOSEEK
     *
     * @param mixed $option
     * @param mixed $value
     *
     * @return mixed
     */
    public function setOption($option, $value)
    {
        switch ($option) {
            case FTPClient::TIMEOUT_SEC:
                if ($value <= 0) {
                    throw new Exception('Timeout value must be greater than zero');
                }
                break;

            case FTPClient::AUTOSEEK:
                if (!is_bool($value)) {
                    throw new Exception('Autoseek value must be boolean');
                }
                break;
            
            default:
                throw new Exception('Unsupported option');
                break;
        }

        return @ftp_set_option($this->connection, $option, $value);
    }

    /**
     * Allocates space for a file to be uploaded
     * 
     * @param int $filesize
     * 
     * @return FTPClient
     */
    public function allocate($filesize)
    {
        $result = @ftp_alloc($this->connection, $filesize);
        
        if ($result === false) {
            throw new Exception('Unable to allocate');
        }

        return $this;
    }

    /**
     * Set permissions on a file via FTP
     *
     * @param int $mode
     * @param string $filename
     * 
     * @return FTPClient
     */
    public function chmod($mode, $filename)
    {
        $result = @ftp_chmod($this->connection, $mode, $filename);
        
        if ($result === false) {
            throw new Exception('Unable to change permissions');
        }

        return $this;
    }

    /**
     * Requests execution of a command on the FTP server
     *
     * @param string $command
     * 
     * @return FTPClient
     */
    public function exec($command)
    {
        $result = @ftp_exec($this->connection, $command);
        
        if ($result === false) {
            throw new Exception('Unable to exec command');
        }

        return $this;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }
}
