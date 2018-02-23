<?php

    declare(strict_types = 1);

    defined('INSIDE') OR exit('No direct script access allowed');

    class DefenseData {

        private $rocket_launcher;

        private $light_laser;

        private $heavy_laser;

        private $ion_cannon;

        private $gauss_cannon;

        private $plasma_turret;

        private $small_shield_dome;

        private $large_shield_dome;

        private $anti_ballistic_missile;

        private $interplanetary_missile;

        public function __construct(
            int $drocket_launcher, int $dlight_laser, int $dheavy_laser, int $dion_cannon, int $dgauss_cannon,
            int $dplasma_turret, int $dsmall_shield_dome,
            int $dlarge_shield_dome, int $danti_ballistic_missile, int $dinterplanetary_missile
        ) {

            $this->rocket_launcher = $drocket_launcher;
            $this->light_laser = $dlight_laser;
            $this->heavy_laser = $dheavy_laser;
            $this->ion_cannon = $dion_cannon;
            $this->gauss_cannon = $dgauss_cannon;
            $this->plasma_turret = $dplasma_turret;
            $this->small_shield_dome = $dsmall_shield_dome;
            $this->large_shield_dome = $dlarge_shield_dome;
            $this->anti_ballistic_missile = $danti_ballistic_missile;
            $this->interplanetary_missile = $dinterplanetary_missile;
        }

        public function printDefense() : void {
            echo '<pre>';
            print_r($this);
            echo '</pre>';
        }

        /**
         * @return int
         */
        public function getRocketLauncher() : int {

            return $this->rocket_launcher;
        }

        /**
         * @param $rocket_launcher
         */
        public function setRocketLauncher($rocket_launcher) : void {

            $this->rocket_launcher = $rocket_launcher;
        }

        /**
         * @return int
         */
        public function getLightLaser() : int {

            return $this->light_laser;
        }

        /**
         * @param $light_laser
         */
        public function setLightLaser($light_laser) : void {

            $this->light_laser = $light_laser;
        }

        /**
         * @return int
         */
        public function getHeavyLaser() : int {

            return $this->heavy_laser;
        }

        /**
         * @param $heavy_laser
         */
        public function setHeavyLaser($heavy_laser) : void {

            $this->heavy_laser = $heavy_laser;
        }

        /**
         * @return int
         */
        public function getIonCannon() : int {

            return $this->ion_cannon;
        }

        /**
         * @param $ion_cannon
         */
        public function setIonCannon($ion_cannon) : void {

            $this->ion_cannon = $ion_cannon;
        }

        /**
         * @return int
         */
        public function getGaussCannon() : int {

            return $this->gauss_cannon;
        }

        /**
         * @param $gauss_cannon
         */
        public function setGaussCannon($gauss_cannon) : void {

            $this->gauss_cannon = $gauss_cannon;
        }

        /**
         * @return int
         */
        public function getPlasmaTurret() : int {

            return $this->plasma_turret;
        }

        /**
         * @param $plasma_turret
         */
        public function setPlasmaTurret($plasma_turret) : void {

            $this->plasma_turret = $plasma_turret;
        }

        /**
         * @return int
         */
        public function getSmallShieldDome() : int {

            return $this->small_shield_dome;
        }

        /**
         * @param $small_shield_dome
         */
        public function setSmallShieldDome($small_shield_dome) : void {

            $this->small_shield_dome = $small_shield_dome;
        }

        /**
         * @return int
         */
        public function getLargeShieldDome() : int {

            return $this->large_shield_dome;
        }

        /**
         * @param $large_shield_dome
         */
        public function setLargeShieldDome($large_shield_dome) : void {

            $this->large_shield_dome = $large_shield_dome;
        }

        /**
         * @return int
         */
        public function getAntiBallisticMissile() : int {

            return $this->anti_ballistic_missile;
        }

        /**
         * @param $anti_ballistic_missile
         */
        public function setAntiBallisticMissile($anti_ballistic_missile) : void {

            $this->anti_ballistic_missile = $anti_ballistic_missile;
        }

        /**
         * @return int
         */
        public function getInterplanetaryMissile() : int {

            return $this->interplanetary_missile;
        }

        /**
         * @param $interplanetary_missile
         */
        public function setInterplanetaryMissile($interplanetary_missile) : void {

            $this->interplanetary_missile = $interplanetary_missile;
        }

    }
