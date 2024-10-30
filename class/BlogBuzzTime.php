<?php

class BlogBuzzTime
{

    private $wpdb;
    private $parameters = array();
    private $option_name = 'blogBuzzTime-options';

    /**
     * @var BlogBuzzTime
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $path;

    private function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;

        add_action('admin_menu', 'blogBuzzTime_adminActions');
        add_action('wp_footer', 'addBlogBuzzTimeJs', 21);
        add_action('admin_init', array($this, 'admin_init'));

        $this->parameters = get_option($this->option_name);
    }

    public function validate($input)
    {
        return $input;
    }

    public function admin_init()
    {
        register_setting('blogbuzztime_options', $this->option_name, array($this, 'validate'));
    }

    /**
     * @return BlogBuzzTime
     */
    public static function getInstance($url = false, $path = false)
    {
        if (is_null(self::$_instance))
        {
            if ($url && $path)
                self::$_instance = new BlogBuzzTime($url, $path);
            else
                throw new Exception('BlogBuzzTime - Please provide url and path');
        }

        return self::$_instance;
    }

    public function install()
    {

    }

    /**
     * @return BlogBuzzTime
     */
    public function uninstall()
    {
        remove_action('wp_footer', 'addBlogBuzzTimeJs');
        delete_option($this->option_name);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function adminActions()
    {
        return $this;
    }

    private function getDbManager()
    {
        if ($this->wpdb)
            return $this->wpdb;

        global $wpdb;
        $this->wpdb = $wpdb;
        return $this->wpdb;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $this->validate($parameters);
        update_option($this->option_name, $this->parameters);
        return $this;
    }

    public function getHashKey()
    {
        $parameters = $this->getParameters();
        if(isset($parameters['hashkey']) && $parameters['hashkey'])
            return $parameters['hashkey'];

        $parameters['hashkey'] = sha1($_SERVER['SERVER_NAME'] . '-' . $_SERVER['REMOTE_ADDR'] . '-bbt:)');
        $this->setParameters($parameters);
        return $parameters['hashkey'];
    }

    public function getParameter($key){
        $p = $this->getParameters();
        return (isset($p[$key])?$p[$key]:false);

    }
}
