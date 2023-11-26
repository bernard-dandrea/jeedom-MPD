<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class MPD extends eqLogic
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    public function call_mpc($_command)
    {
        $ip = trim($this->getConfiguration('ip'));
        if ($ip !== '') {
            $ip = ' -h ' . $ip;
        }

        $port = trim($this->getConfiguration('port'));
        if ($port !== '') {
            $port = ' -p ' . $port;
        }

        $password = trim($this->getConfiguration('password'));
        if ($password !== "") {
            $password = ' -P ' . $password;
        }

        $request = 'mpc  ' . $ip . $port . $password . ' ' . $_command . ' 2>&1';
        //     $request_shell = new com_shell($request );
        //     $result = $request_shell->exec();
        //$result=shell_exec($request );

        exec($request, $result);

        log::add('MPD', 'debug', 'call_mpc ' . ' request ' . $request . ' result ' . $result[0]) ;

        return $result;

    }
    public function test_connexion()
    {

        log::add('MPD', 'info', __('test_connexion ', __FILE__));

        $request = 'version';
        $result = $this->call_mpc($request);

        if (strpos($result[0], 'version') !== false) {

            log::add('MPD', 'debug', 'Connexion OK : ' . $result[0]);

            event::add(
                'jeedom::alert',
                array(
                    'level' => 'success',
                    'page' => 'MPD',
                    'message' => __('Connexion OK : ' . $result[0], __FILE__),
                )
            );
        } else {
            log::add('MPD', 'debug', 'Connexion KO ' . $result[0]);
            event::add(
                'jeedom::alert',
                array(
                    'level' => 'error',
                    'page' => 'MPD',
                    'message' => __('Connexion KO : ' . $result[0], __FILE__),
                )
            );
        }
    }

    public function generer_commandes()
    {
        log::add('MPD', 'info', __('generer_commandes ', __FILE__));

        $order = time();
        $update_eqlogic = false;


        $logicalID = 'mute';
        $name = 'Mute';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-volume-mute"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '2');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'volume -10';
        $name = 'Volume_DOWN';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-volume-down"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '2');
                $update_eqlogic = true;

            }
        }

        $logicalID = 'volume +10';
        $name = 'Volume_UP';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-volume-up"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '2');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'refresh_all';
        $name = 'Refresh';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="icon jeedomapp-reload"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '2');
                $update_eqlogic = true;
            }
        }


        $logicalID = 'prev';
        $name = 'Prev';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-step-backward"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'seek -5%';
        $name = 'Seek -';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-angle-double-left"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }


        $logicalID = 'play';
        $name = 'Play';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-play"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'pause';
        $name = 'Pause';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-stop"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'seek +5%';
        $name = 'Seek +';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-angle-double-right"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }


        $logicalID = 'next';
        $name = 'Next';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setDisplay('icon', '<i class="fas fa-step-forward"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '1');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'clear';
        $name = 'clear';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(0);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                //    $command->setDisplay('icon', '<i class="icon jeedomapp-dirG"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

            }
        }

        $logicalID = 'crop';
        $name = 'crop';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(0);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                //        $command->setDisplay('icon', '<i class="icon jeedomapp-dirG"></i>');
                $command->setType('action');
                $command->setSubType('other');
                $command->setEqLogic_id($this->getId());
                $command->save();

            }
        }

        $logicalID = 'load';
        $name = 'Playlist';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                //        $command->setDisplay('icon', '<i class="icon jeedomapp-dirG"></i>');
                $command->setType('action');
                $command->setSubType('select');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '3');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'song';
        $name = 'Song';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                //        $command->setDisplay('icon', '<i class="icon jeedomapp-dirG"></i>');
                $command->setType('action');
                $command->setSubType('select');
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '4');
                $update_eqlogic = true;
            }
        }

        $logicalID = 'current';
        $name = 'Current';
        if (is_object(cmd::byEqLogicIdCmdName($this->getId(), $name)) === false) {
            unset($command);
            $command = cmd::byEqLogicIdAndLogicalId($this->getId(), $logicalID);
            if (!is_object($command)) {
                log::add('MPD', 'info', __('generer_commandes ', __FILE__). ' commande ' . $name);
                $command = new MPDCmd();
                $command->setLogicalId($logicalID);
                $command->setIsVisible(1);
                $order++;
                $command->setOrder($order);
                $command->setName($name);
                $command->setType('info');
                $command->setSubType('string');
                $command->setTemplate('dashboard', 'core::multiline');
                $command->setTemplate('mobile', 'core::multiline');
                $command->setDisplay('showNameOndashboard', '0');
                $command->setIsHistorized(0);
                $command->setEqLogic_id($this->getId());
                $command->save();

                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::column', '1');
                $this->setDisplay('layout::dashboard::table::cmd::' . $command->getId() . '::line', '5');
                $update_eqlogic = true;
            }
        }

        if ($update_eqlogic === true) {
            $this->save();
        }


    }
    public function set_layout()
    {
        log::add('MPD', 'info', __('set_layout ', __FILE__));

        $this->setDisplay('layout::dashboard', 'table');
        $this->setDisplay(
            'layout::dashboard::table::parameters',
            array(
                "center" => "1",
                "styletable" => "",
                "styletd" => "",
                "text::td::1::1" => "",
                "style::td::1::1" => "",
                "text::td::2::1" => "",
                "style::td::2::1" => "",
                "text::td::3::1" => "",
                "style::td::3::1" => "",
                "text::td::4::1" => "",
                "style::td::4::1" => "",
                "text::td::5::1" => "",
                "style::td::5::1" => ""
            )
        );

        $this->setDisplay('layout::dashboard::table::nbLine', '5');
        $this->setDisplay('layout::dashboard::table::nbColumn', '1');
        $this->setDisplay('width', '232px');
        $this->setDisplay('height', '200px');
    }

    public function preInsert()
    {
        if ($this->getConfiguration('type', '') == "") {
            $this->setConfiguration('type', 'MPD');
        }
        $this->setIsEnable(1);
        $this->setIsVisible(1);
        $this->setCategory('multimedia', '1');
        $this->set_layout();
    }

    public function preUpdate()
    {
        if ($this->getIsEnable()) {
            //    return $this->getSessionId();
        }
    }

    public function preSave()
    {
        if ($this->getIsEnable()) {
            //    return $this->getSessionId();
        }
    }

    public function preRemove()
    {

        return true;
    }


    public function postInsert()
    {

        $this->generer_commandes();

    }



}

class MPDCmd extends cmd
{

    public function execute($_options = null)
    {
        $eqLogic = $this->getEqLogic();
        $LogicalID = $this->getLogicalID();
        log::add('MPD', 'debug', 'execute ' . $this->getName() . ' LogicalID ' . $LogicalID);
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new \Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' , __FILE__) . $this->getHumanName());
        }

        switch ($this->getSubType()) {
            case "select":
                $value = $_options['select'];
                break;
            case "slider":
                $value = $_options['slider'];
                break;
            case "message":
                $value = $_options['message'];
                break;
            case "other":
                $value = '';
                break;
            default:
                log::add('MPD', 'info', 'Type d action non défini : ' . $this->getSubType());
                die;
                break;
        }



        if (strtolower(substr($LogicalID, 0, 9)) === 'playsong ') {
            $value = trim(substr($LogicalID, 8));
            $LogicalID = 'song';
            log::add('MPD', 'debug', 'playsong ' . $LogicalID . ' ' . $value);
        }

        switch ($LogicalID) {
            case 'refresh_all':
                log::add('MPD', 'debug', 'execute ' . $this->getName() . ' logicalID ' . $LogicalID);
                unset($command);
                $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'current');
                if (is_object($command)) {
                    $result = $eqLogic->call_mpc('current');
                    $command->event($result[0]);
                }

                $request = 'lsplaylists';
                unset($command);
                $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'load');
                if (is_object($command)) {
                    $result = $eqLogic->call_mpc($request);
                    $list_value = array();
                    for ($i = 0; $i < count($result); $i++) {
                        array_push($list_value, $result[$i] . '|' . $result[$i]);
                    }
                    $command->setConfiguration('listValue', join(";", $list_value));
                    $command->save();
                }

                $request = 'playlist -f %file%';
                unset($command);
                $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'song');
                if (is_object($command)) {
                    $result = $eqLogic->call_mpc($request);
                    $list_value = array();
                    for ($i = 0; $i < count($result); $i++) {
                        array_push($list_value, $result[$i] . '|' . $result[$i]);
                    }
                    $command->setConfiguration('listValue', join(";", $list_value));
                    $command->save();
                }
                
                $eqLogic->refreshWidget();
                return true;

            case 'mute':

                // get current volume
                $request = 'volume';
                $result = $eqLogic->call_mpc($request);

                if (substr($result[0], 0, 7) !== 'volume:') {
                    return false;
                }
                $volume = trim(str_replace('volume: ', '', $result[0]));
                if ($volume === '0%') { // sort du mute
                    $volume = $eqLogic->getConfiguration('mute_volume', '');
                    if ($volume === '') {
                        return true;
                    }
                    $volume = str_replace('%', '', $volume);
                    $request = 'volume ' . $volume;
                    $result = $eqLogic->call_mpc($request);
                    $eqLogic->setConfiguration('mute_volume', '');
                    $eqLogic->save();
                } else { // entre en mute
                    $eqLogic->setConfiguration('mute_volume', $volume);
                    $eqLogic->save();
                    $request = 'volume 0';
                    $result = $eqLogic->call_mpc($request);
                }
                $result = $eqLogic->call_mpc($request);
                return true;

            case 'load':

                $result = $eqLogic->call_mpc('clear');
                $request = 'load ' . $value;
                $result = $eqLogic->call_mpc($request);
                unset($command);
                $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'songs');
                if (is_object($command)) {
                    $command->execCmd();
                }
                $result = $eqLogic->call_mpc('play 1');
                unset($command);
                $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'refresh_all');
                if (is_object($command)) {
                    $command->execCmd();
                }
                return true;

            case 'song':

                $request = 'current -f %file%';
                $result = $eqLogic->call_mpc($request);
                if ($result[0] == $value) {
                    log::add('MPD', 'debug', 'song ' . $value . ' déjà en cours');
                    return true;
                }


                $request = 'playlist -f %file%';
                $result = $eqLogic->call_mpc($request);
                
                for ($i = 0; $i < count($result); $i++) {
                    if ($result[$i] == $value) {

                        log::add('MPD', 'debug', 'song ' . $value . ' trouvé dans la queue de MPD');
                        $result = $eqLogic->call_mpc('play ' . ($i + 1));
                        unset($command);
                        $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'refresh_all');
                        if (is_object($command)) {
                            $command->execCmd();
                        }
                        return true;
                        
                    }
                }

                log::add('MPD', 'debug', 'song ' . $value . ' non trouvé dans la queue de MPD');
                return false;

            default:

                $request = str_replace('%1', $value, $LogicalID);

                log::add('MPD', 'debug', 'execute ' . $this->getName() . ' request ' . $request);
                $result = $eqLogic->call_mpc($request);
                if ($LogicalID === 'prev' || $LogicalID === 'next') {
                    unset($command);
                    $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'refresh_all');
                    if (is_object($command)) {
                        $command->execCmd();
                    }
                }

                if ($LogicalID === 'crop' || $LogicalID === 'clear') {
                    unset($command);
                    $command = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'songs');
                    if (is_object($command)) {
                        $command->execCmd();
                    }
                }

                if (substr($LogicalID, 0, 6) === 'volume') {
                    $eqLogic->setConfiguration('mute_volume', '');
                }

                return true;
        }

        return true;

    }


    public function dontRemoveCmd()
    {
        $eqLogic = $this->getEqLogic();
        if (is_object($eqLogic)) {
            if ($eqLogic->getConfiguration('type', '') == 'MPD') {
                /*
                if ($this->getLogicalId() == 'updatetime') {
                    return true;
                }
                */
            }
            return false;
        }
    }
}
