<?php

namespace lwMembersearch\Service\Autoloader;

class lwMembersearchAutoloader
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }
    
    private function loader($className) 
    {
        if (strstr($className, 'LWddd')) {
            $config = \lw_registry::getInstance()->getEntry('config');
            $path = $this->config['plugin_path']['lw'].'lw_ddd';
            $filename = str_replace('LWddd', $path, $className);
        }
        else {
            $path = dirname(__FILE__).'/../..';
            $filename = str_replace('lwMembersearch', $path.'/lw_membersearch', $className);
        }
        $filename = str_replace('\\', '/', $filename).'.php';

        if (is_file($filename)) {
            include_once($filename);
        }
    }
}
