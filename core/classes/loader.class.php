<?php

    declare(strict_types=1);

    defined('INSIDE') OR exit('No direct script access allowed');

    // autoload the classes
    spl_autoload_register(function ($name) {
        //if(file_exists(lcfirst($name) . '.class.php')) {
            require_once lcfirst($name) . '.class.php';
        //}
    });

    class Loader {
        private $user = null;
        private $planet = null;
        private $galaxy = null;
        private $building = null;
        private $defense = null;
        private $tech = null;
        private $fleet = null;
        private $units = null;

        public function printData() : void {
            echo '<pre>';
            print_r($this);
            echo '</pre>';
        }

        function __construct($userID) {
            global $database, $db;

            $query = 'SELECT 
                        user.userID AS user_userID, 
                        user.username AS user_username,
                        user.email AS user_email,
                        user.onlinetime AS user_onlinetime,
                        user.currentplanet AS user_currentplanet,
                        planet.planetID AS planet_planetID,
                        planet.name AS planet_name,
                        planet.galaxy AS planet_galaxy,
                        planet.system AS planet_system,
                        planet.planet AS planet_planet,
                        planet.last_update AS planet_last_update,
                        planet.planet_type AS planet_type,
                        planet.image AS planet_image,
                        planet.diameter AS planet_diameter,
                        planet.fields_current AS planet_fields_current,
                        planet.fields_max AS planet_fields_max,
                        planet.temp_min AS planet_temp_min,
                        planet.temp_max AS planet_temp_max,
                        planet.metal AS planet_metal,
                        planet.crystal AS planet_crystal,
                        planet.deuterium AS planet_deuterium,
                        planet.energy_used AS planet_energy_used,
                        planet.energy_max AS planet_energy_max,
                        planet.metal_mine_percent AS planet_metal_mine_percent,
                        planet.crystal_mine_percent AS planet_crystal_mine_percent,
                        planet.deuterium_synthesizer_percent AS planet_deuterium_synthesizer_percent,
                        planet.solar_plant_percent AS planet_solar_plant_percent,
                        planet.fusion_reactor_percent AS planet_fusion_reactor_percent,
                        planet.solar_satellite_percent AS planet_solar_satellite_percent,
                        planet.b_building_id AS planet_b_building_id,
                        planet.b_building_endtime AS planet_b_building_endtime,
                        planet.b_tech_id AS planet_b_tech_id,
                        planet.b_tech_endtime AS planet_b_tech_endtime,
                        planet.b_hangar_start_time AS planet_b_hangar_start_time,
                        planet.b_hangar_id AS planet_b_hangar_id,
                        planet.b_hangar_plus AS planet_b_hangar_plus,
                        planet.destroyed AS planet_destroyed,
                        galaxy.debris_metal AS galaxy_debris_metal,
                        galaxy.debris_crystal AS galaxy_debris_crystal,
                        building.metal_mine AS building_metal_mine,
                        building.crystal_mine AS building_crystal_mine,
                        building.deuterium_synthesizer AS building_deuterium_synthesizer,
                        building.solar_plant AS building_solar_plant,
                        building.fusion_reactor AS building_fusion_reactor,
                        building.robotic_factory AS building_robotic_factory,
                        building.nanite_factory AS building_nanite_factory,
                        building.shipyard AS building_shipyard,
                        building.metal_storage AS building_metal_storage,
                        building.crystal_storage AS building_crystal_storage,
                        building.deuterium_storage AS building_deuterium_storage,
                        building.research_lab AS building_research_lab,
                        building.terraformer AS building_terraformer,
                        building.alliance_depot AS building_alliance_depot,
                        building.missile_silo AS building_missile_silo,
                        defense.rocket_launcher AS defense_rocket_launcher,
                        defense.light_laser AS defense_light_laser,
                        defense.heavy_laser AS defense_heavy_laser,
                        defense.ion_cannon AS defense_ion_cannon,
                        defense.gauss_cannon AS defense_gauss_cannon,
                        defense.plasma_turret AS defense_plasma_turret,
                        defense.small_shield_dome AS defense_small_shield_dome,
                        defense.large_shield_dome AS defense_large_shield_dome,
                        defense.anti_ballistic_missile AS defense_anti_ballistic_missile,
                        defense.interplanetary_missile AS defense_interplanetary_missile,
                        tech.espionage_tech AS tech_espionage_tech,
                        tech.computer_tech AS tech_computer_tech,
                        tech.weapon_tech AS tech_weapon_tech,
                        tech.armour_tech AS tech_armour_tech,
                        tech.shielding_tech AS tech_shielding_tech,
                        tech.energy_tech AS tech_energy_tech,
                        tech.hyperspace_tech AS tech_hyperspace_tech,
                        tech.combustion_drive_tech AS tech_combustion_tech,
                        tech.impulse_drive_tech AS tech_impulse_drive_tech,
                        tech.hyperspace_drive_tech AS tech_hyperspace_drive_tech,
                        tech.laser_tech AS tech_laser_tech,
                        tech.ion_tech AS tech_ion_tech,
                        tech.plasma_tech AS tech_plasma_tech,
                        tech.intergalactic_research_tech AS tech_intergalactic_research_tech,
                        tech.graviton_tech AS tech_graviton_tech,
                        fleet.small_cargo_ship AS fleet_small_cargo_ship,
                        fleet.large_cargo_ship AS fleet_large_cargo_ship,
                        fleet.light_fighter AS fleet_light_fighter,
                        fleet.heavy_fighter AS fleet_heavy_fighter,
                        fleet.cruiser AS fleet_cruiser,
                        fleet.battleship AS fleet_battleship,
                        fleet.colony_ship AS fleet_colony_ship,
                        fleet.recycler AS fleet_recycler,
                        fleet.espionage_probe AS fleet_espionage_probe,
                        fleet.bomber AS fleet_bomber,
                        fleet.solar_satellite AS fleet_solar_satellite,
                        fleet.destroyer AS fleet_destroyer,
                        fleet.battlecruiser AS fleet_battlecruiser,
                        fleet.deathstar AS fleet_deathstar
                        FROM '.$database['prefix'].'users AS user 
                        LEFT JOIN '.$database['prefix'].'planets AS planet ON user.userID = planet.ownerID  
                        LEFT JOIN '.$database['prefix'].'galaxy AS galaxy ON planet.planetID = galaxy.planetID
                        LEFT JOIN '.$database['prefix'].'buildings AS building ON planet.planetID = building.planetID
                        LEFT JOIN '.$database['prefix'].'defenses AS defense ON planet.planetID = defense.planetID
                        LEFT JOIN '.$database['prefix'].'techs AS tech ON user.userID = tech.userID
                        LEFT JOIN '.$database['prefix'].'fleet AS fleet ON planet.planetID = fleet.planetID
                        WHERE user.userID = :userID;';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':userID',$userID);
            
            $stmt->execute();
            
            $planetList = [];

            while($data = $stmt->fetch()) {
                
                $p =  new Planet(intval($data->planet_planetID), intval($userID), $data->planet_name, intval($data->planet_galaxy), intval($data->planet_system),
                                    intval($data->planet_planet), intval($data->planet_last_update), intval($data->planet_type), $data->planet_image, intval($data->planet_diameter), 
                                    intval($data->planet_fields_current), intval($data->planet_fields_max), intval($data->planet_temp_min), intval($data->planet_temp_max), floatval($data->planet_metal),
                                    floatval($data->planet_crystal), floatval($data->planet_deuterium), intval($data->planet_energy_used), intval($data->planet_energy_max), intval($data->planet_metal_mine_percent),
                                    intval($data->planet_crystal_mine_percent), intval($data->planet_deuterium_synthesizer_percent), intval($data->planet_solar_plant_percent), intval($data->planet_fusion_reactor_percent), 
                                    intval($data->planet_solar_satellite_percent), intval($data->planet_b_building_id), intval($data->planet_b_building_endtime), intval($data->planet_b_tech_id), 
                                    intval($data->planet_b_tech_endtime), intval($data->planet_b_hangar_start_time), (isset($data->planet_b_hangar_id) ? $data->planet_b_hangar_id : ""), intval($data->planet_b_hangar_plus), intval($data->planet_destroyed));
                
                // current planet
                if($data->user_currentplanet == $data->planet_planetID) {
                    $this->user = new User(intval($userID), $data->user_username, $data->user_email, intval($data->user_onlinetime), intval($data->user_currentplanet));
                    
                    $this->building = new Building(intval($data->building_metal_mine), intval($data->building_crystal_mine), intval($data->building_deuterium_synthesizer), intval($data->building_solar_plant), 
                                                    intval($data->building_fusion_reactor), intval($data->building_robotic_factory), intval($data->building_nanite_factory), intval($data->building_shipyard), 
                                                    intval($data->building_metal_storage), intval($data->building_crystal_storage), intval($data->building_deuterium_storage), intval($data->building_research_lab), 
                                                    intval($data->building_terraformer), intval($data->building_alliance_depot), intval($data->building_missile_silo));
                    
                    //$this->galaxy = new Galaxy();
                    
                    $this->defense = new Defense(intval($data->defense_rocket_launcher), intval($data->defense_light_laser), intval($data->defense_heavy_laser), intval($data->defense_ion_cannon), 
                                                intval($data->defense_gauss_cannon), intval($data->defense_plasma_turret), intval($data->defense_small_shield_dome), intval($data->defense_large_shield_dome), 
                                                intval($data->defense_anti_ballistic_missile), intval($data->defense_interplanetary_missile));
                                            
                    $this->galaxy = new Galaxy(intval($data->galaxy_debris_metal), intval($data->galaxy_debris_crystal));

                    $this->fleet = new Fleet(intval($data->fleet_small_cargo_ship), intval($data->fleet_large_cargo_ship), intval($data->fleet_light_fighter), intval($data->fleet_heavy_fighter), 
                                            intval($data->fleet_cruiser), intval($data->fleet_battleship), intval($data->fleet_colony_ship), intval($data->fleet_recycler), intval($data->fleet_espionage_probe), 
                                            intval($data->fleet_bomber), intval($data->fleet_solar_satellite), intval($data->fleet_destroyer), intval($data->fleet_battlecruiser), intval($data->fleet_deathstar));
                    
                    $this->tech = new Tech(intval($data->tech_espionage_tech), intval($data->tech_computer_tech), intval($data->tech_weapon_tech), intval($data->tech_armour_tech), intval($data->tech_shielding_tech), 
                                        intval($data->tech_energy_tech), intval($data->tech_hyperspace_tech), intval($data->tech_combustion_drive_tech), intval($data->tech_impulse_drive_tech), 
                                        intval($data->tech_hyperspace_drive_tech), intval($data->tech_laser_tech), intval($data->tech_ion_tech), intval($data->tech_plasma_tech), intval($data->tech_intergalactic_research_tech), 
                                        intval($data->tech_graviton_tech));
                                        
                    $this->units = new Units();
                    
                    $this->planet = $p;
                }
                
                
                
                
                
                array_push($planetList, $p);
                
            }
            
            
            $this->user->setPlanetList($planetList);
        }

        /**
         * @return null|User
         */
        public function getUser() : User{
            return $this->user;
        }

        /**
         * @return null|User
         */
        public function getPlanet() : Planet {
            return $this->planet;
        }

        /**
         * @return Fleet|null
         */
        public function getGalaxy() : Galaxy {
            return $this->galaxy;
        }

        /**
         * @return Building|null
         */
        public function getBuilding() : Building{
            return $this->building;
        }

        /**
         * @return Defense|null
         */
        public function getDefense() : Defense {
            return $this->defense;
        }

        /**
         * @return null|Tech
         */
        public function getTech() : Tech {
            return $this->tech;
        }

        /**
         * @return Fleet|null
         */
        public function getFleet() : Fleet {
            return $this->fleet;
        }

        /**
         * @return Units|null
         */
        public function getUnits() : Units {
            return $this->units;
        }
    }