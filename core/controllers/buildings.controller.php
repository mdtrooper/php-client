<?php

    declare(strict_types=1);

    defined('INSIDE') OR exit('No direct script access allowed');

    require $path['interfaces'].'controller.interface.php';

    class C_Building implements I_Controller {

        private $get = null;
        private $post = null;
        private $lang = null;


        function __construct($get, $post) {
            global $data, $debug, $path;

            try {
                $this->get = $get;
                $this->post = $post;

                if (!empty($this->get)) {
                    self::handleGET();
                }

                if (!empty($post)) {
                    self::handlePOST();
                }
                
                require_once($path['classes']."topbar.class.php");

            } catch(Exception $e) {
                if(DEBUG) {
                    $debug->addLog(self::class, __FUNCTION__, __LINE__, get_class($e), $e->getMessage());
                } else {
                    $debug->saveError(self::class, __FUNCTION__, __LINE__, get_class($e), $e->getMessage());
                }
            }
        }

        function handleGET() : void {
            global $data;
            if(!empty($this->get['cp'])) {
                $data->getUser()->setCurrentPlanet(intval($this->get['cp']));
            }

            if(isset($this->get['build'])) {

                $id = intval(filter_input(INPUT_GET, 'build', FILTER_VALIDATE_INT));

                // if the passed value was of type integer, the $id should be set and not null
                if(isset($id) && $id != null) {
                    if ($id > 0) {
                        $this->build($id);
                    } else {
                        throw new InvalidArgumentException("buildID must not be zero or negative");
                    }
                } else {
                    throw new InvalidArgumentException("buildID must be set and of type integer");
                }
            }

            if(isset($this->get['cancel']) && $data->getPlanet()->getBBuildingID() > 0) {

                $id = intval(filter_input(INPUT_GET, 'cancel', FILTER_VALIDATE_INT));

                // if the passed value was of type integer, the $id should be set and not null
                if(isset($id) && $id != null) {
                    if ($id > 0) {
                        
                        if($data->getPlanet()->getBBuildingID() == $id) {
                            $this->cancel($id);
                        } else {
                            throw new InvalidArgumentException("cancelID does not match currently building id");
                        }
                    } else {
                        throw new InvalidArgumentException("cancelID must not be zero or negative");
                    }
                } else {
                    throw new InvalidArgumentException("cancelID must be set and of type integer");
                }

            }
        }

        function handlePOST() : void {

        }

        function build($buildID) : void {
            global $data, $debug;

            try {
                if($buildID < 1 || $buildID > 99 || !array_key_exists($buildID, $data->getUnits()->getBuildings())) {
                    throw new InvalidArgumentException("ID out of range");
                }


                //build it only, if there is not already a building in the queue
                if ($data->getPlanet()->getBBuildingId() == 0) {

                    $units = new Units();

                    $pricelist = $units->getPriceList($buildID);

                    $methodArr = explode('_', $units->getUnit($buildID));

                    $method = 'get';

                    foreach ($methodArr as $a => $b) {
                        $method .= ucfirst($b);
                    }
                    $level = call_user_func_array(array($data->getBuilding(), $method), array());

                    $metal = $pricelist['metal'];
                    $crystal = $pricelist['crystal'];
                    $deuterium = $pricelist['deuterium'];

                    // calculate the total costs up to this level
                    for ($i = 0; $i < $level; $i++) {
                        $metal *= $pricelist['factor'];
                        $crystal *= $pricelist['factor'];
                        $deuterium *= $pricelist['factor'];
                    }


                    if ($data->getPlanet()->getMetal() >= $metal &&
                        $data->getPlanet()->getCrystal() >= $crystal &&
                        $data->getPlanet()->getDeuterium() >= $deuterium) {

                        $n_metal = $data->getPlanet()->getMetal() - $metal;
                        $n_crystal = $data->getPlanet()->getCrystal() - $crystal;
                        $n_deuterium = $data->getPlanet()->getDeuterium() - $deuterium;

                        $toLvl = $level + 1;

                        M_Buildings::build($data->getPlanet()->getPlanetId(), $buildID, $toLvl, $n_metal, $n_crystal,
                            $n_deuterium);
                        header("Refresh:0");
                    }
                }

            } catch (Exception $e) {
                if (DEBUG) {
                    $debug->addLog(self::class, __FUNCTION__, __LINE__, get_class($e), $e->getMessage());
                } else {
                    $debug->saveError(self::class, __FUNCTION__, __LINE__, get_class($e), $e->getMessage());
                }
            }

        }

        function cancel($buildID) : void {
            global $data;

            if($data->getPlanet()->getBBuildingId() == $buildID && $data->getPlanet()->getBBuildingEndtime() > time()) {
                $units = new Units();

                $pricelist = $units->getPriceList($buildID);

                $methodArr = explode('_', $units->getUnit($buildID));

                $method = 'get';

                foreach($methodArr as $a => $b) {
                    $method .= ucfirst($b);
                }
                $level = call_user_func_array(array($data->getBuilding(), $method), array());

                $metal = $pricelist['metal'];
                $crystal = $pricelist['crystal'];
                $deuterium = $pricelist['deuterium'];

                // calculate the total costs up to this level
                for($i = 0; $i < $level; $i++) {
                    $metal *= $pricelist['factor'];
                    $crystal *= $pricelist['factor'];
                    $deuterium *= $pricelist['factor'];
                }

                M_Buildings::cancel($data->getPlanet()->getPlanetId(), $metal, $crystal, $deuterium);
            }

            header("Refresh:0");

        }

        function display() : void {
            global $config, $data;

            // load view
            $view = new V_Buildings();

            $v_lang = M_Buildings::loadLanguage();

            // load the individual rows for each building
            $this->lang['building_list'] = $view->loadBuildingRows($data->getBuilding(), $data->getUnits()->getBuildings(), $data->getPlanet());

            if(is_array($this->lang) && is_array($v_lang)) {
                $this->lang = array_merge($this->lang, $v_lang);
            } else {
                if(!isset($this->lang) && empty($this->lang) && isset($v_lang) && !empty($v_lang)) {
                    $this->lang = $v_lang;
                }
            }



            $view->assign('lang', $this->lang);
            $view->assign('title',$config['game_name']);
            $view->assign('skinpath',$config['skinpath']);
            $view->assign('copyright',$config['copyright']);
            $view->assign('language',$config['language']);

            if(!empty($this->get['mode'])) {
                die($view->loadTemplate($this->get['mode']));
            } else {
                die($view->loadTemplate());
            }
        }
    }