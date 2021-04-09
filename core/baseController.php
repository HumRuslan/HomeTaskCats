<?php
namespace core;

class BaseController
{
    protected $layots;

    public function render($views, array $params = [])
    {
        $className = lcfirst(str_replace('Controller', '', substr(get_class($this), strpos(get_class($this), '\\')+1)));
        $viewPath = "./views/$className/$views.php";
        if ($this->layots) {
            require_once('./views/layots/header.php');
        }
        if (file_exists($viewPath)) {
            extract($params);
            require_once($viewPath);
        } else {
            echo "view $viewPath not found";
            die();
        }
        if ($this->layots) {
            require_once('./views/layots/footer.php');
        }
        
    }

    protected function passwordHasher($password)
    {
        return sha1(SALT . $password . SALT);
    }

    public function redirect($path)
    {
        // session_write_close();
        // exit();
        Header("Location: $path");
        
    }
}