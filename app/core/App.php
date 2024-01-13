<?php

class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct(){
        $url = $this->parseURL();
        
        // Controller
        if ($url == NULL) {
            $url = [$this->controller];
        }

        // Import file kelas controller
        $controllerFile = '../app/controllers/' . ucfirst($url[0]) . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $url[0];
            unset($url[0]);
        }
        else {
            // Tambahkan penanganan jika file kelas controller tidak ditemukan
            die('Kesalahan: Controller tidak ditemukan');
        }

        // Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
            else {
                // Tambahkan penanganan jika metode tidak ditemukan dalam kelas controller
                die('Kesalahan: Metode tidak ditemukan');
            }
        }

        // Params
        $this->params = array_values($url);
        
        // Jalankan controller, method, dan kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}

?>
