<?php
/**
 * JsonReader File
 * 
 * PHP 5
 * 
 * @uses ConfigReaderInterface
 * @package JsonReader
 * @copyright Nobody. Do as you will with this thing. Rip it, pretend you made it, sell it
 * @author Paul Connolley (connrs) <paul.connolley@gmail.com> 
 * @license WTFPL {@link http://sam.zoy.org/wtfpl/}
 */

/**
 * Json Reader allows Configure to load configuration values from
 * JSON files
 */
class JsonReader implements ConfigReaderInterface {
/**
 * The file extension used when loading configuration files eg: 'json'/'js'.
 * 
 * @var string
 * @access public
 */
    public $ext = '.json';

/**
 * The path this reader finds files on. 
 * 
 * @var bool
 * @access protected
 */
    protected $_path = null;

/**
 * Constructor for JSON Config file reading
 * 
 * @param string $path The path to read JSON Files from.
 */
    public function __construct($path = null) {
        if (!$path) {
            $path = APP . 'Config' . DS;
        }
        $this->_path = $path;
    }

/**
 * Read a JSON configuration file and return its contents.
 * 
 * @param string $key. The identifier to read from. If the key has a . it will be treated
 *  as a plugin prefix.
 * @return array parsed configuration values
 * @throws ConfigurationException when files don't exist or when files contain '..' as this could lead to abusive reads.
 */
    public function read($key) {
        if (strpos($key, '..') !== false) {
            throw new ConfigureException(__d('cake_dev', 'Cannot load configuration files with ../ in them.'));
        }
        if (substr($key, -5) === '.json') {
            $key = substr($key, 0, -5);
        }
        list($plugin, $key) = pluginSplit($key);

        if ($plugin) {
            $file = App::pluginPath($plugin) . 'Config' . DS . $key;
        } else {
            $file = $this->_path . $key;
        }
        $file .= $this->ext;
        if (!is_file($file)) {
            if (!is_file(substr($file, 0, -5))) {
                throw new ConfigureException(__d('cake_dev', 'Could not load configuration files: %s or %s', $file, substr($file, 0, -5)));
            }
        }
        $contents = file_get_contents($file);
        if (empty($contents)) {
            return array();
        }
        $json = json_decode($contents, true);
        if (empty($json)) {
            if ($json === null) {
                throw new ConfigureException(__d('cake_dev', 'There was a problem decoding JSON in file: %s', $file));
            } else {
                return array();
            }
        }
        return $json;
    }
}
