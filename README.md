# JsonReader                                                                
                                                                         
A simple JsonReader for CakePHP 2 configuration.

##Installation

1. Simply place this plugin in your plugin dir (`root/plugins` or `root/app/Plugin`).
2. In `app/Config/bootstrap.php`:  

        App::uses('JsonReader', 'JsonReader.Config');
        Configure::config('jsonReader', new JsonReader); // optionally add a path: new JsonReader($path)
    Then:  

        Configure::load('config_filename_with_no_extension', 'jsonReader');
3. You can now access configuration information via `Configure::read('variable')`

## Limitations

Due to how Configure works, and how JSON is represented when decoded by PHP, you will need to make the root of your JSON file an object:

    {
        "foo": "bar"
    }

This allows you to do `Configure::read('foo')` 

### Possible workaround of the limitation

I have plans to perhaps add a configuration option to apply a key to a JSON config file such that you could use the following JSON:

    Config/config.json  

    [
        {"foo": 0},
        {"bar": 1}
    ]

And in the bootstrap:

    Configure::config('jsonReader', new JsonReader(null, 'Raboof')
    Configure::load('config', 'jsonReader);
    $x = Configure::read('Raboof');
    // x => array(
    // array('foo' => 0),
    // array('bar' => 1)
    // )

But that's for future consideration if there's a use case for it.
