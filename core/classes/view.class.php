<?php

    declare(strict_types=1);

    class View {

        private $_ = array();

        public function mergeTemplates($class, $fields) : string {
            global $path, $db, $debug;

            $this->_ = $fields;

            // write the output into a buffer
            ob_start();

            $file = $path['templates'] . 'header.template.php';
            if(file_exists($file)) {
                include $file;
            } else {
                throw new FileNotFoundException('File \''.$file.'\' not found');
            }

            if(DEBUG) {
                $debug->printDebugLog();
                $db->printLog();
            }

            $file = $path['templates'] . 'menu.template.php';
            if(file_exists($file)) {
                include $file;
            } else {
                throw new FileNotFoundException('File \''.$file.'\' not found');
            }

            $file = $path['templates'] . 'topbar.template.php';
            if(file_exists($file)) {
                include $file;
            } else {
                throw new FileNotFoundException('File \''.$file.'\' not found');
            }

            $file = $path['templates'] . $class . '.template.php';
            if(file_exists($file)) {
                include $file;
            } else {
                throw new FileNotFoundException('File \''.$file.'\' not found');
            }

            $file = $path['templates'] . 'footer.template.php';
            if(file_exists($file)) {
                include $file;
            } else {
                throw new FileNotFoundException('File \''.$file.'\' not found');
            }

            $output = ob_get_contents();

            ob_end_clean();

            // parse the language
            foreach ($this->_['lang'] as $a => $b) {
                $output = str_replace("{{$a}}", $b, $output);
            }

            return $output;
        }
    }