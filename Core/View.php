<?php

namespace GoMoney;

defined('CORE_PATH') OR exit('No direct script access allowed');

class View
{
    /**
     * View constructor.
     * @param string $file_name
     * @param array $arguments
     */
    public function __construct(string $file_name, array $arguments = [])
    {
        $this->output($file_name, $arguments);
    }

    /**
     * @param $file_name
     * @param $arguments
     */
    private function output($file_name, $arguments)
    {
        ob_start();
        if (file_exists(CORE_PATH . VIEW_PATH . $file_name . '.php')) {
            if (!empty($arguments)) extract($arguments, EXTR_PREFIX_SAME, 'g');
            require CORE_PATH . VIEW_PATH . $file_name . '.php';
        } else {
            echo print_r($arguments);
        }

        $content = ob_get_contents();

        ob_end_clean();
        echo <<<EOT
$content
EOT;
        exit(0);
    }
}
