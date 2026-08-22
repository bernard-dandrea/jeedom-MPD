<?php

// Last Modified : 2026/08/22 18:46:20

/*
 * Copyright (C) 2026 Bernard Dandrea
 * SPDX-License-Identifier: GPL-3.0-or-later
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    ajax::init();

  
    if (init('action') == 'test_connexion') {

        $eqLogic = MPD::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new \Exception(__('MPD eqLogic non trouvé : ', __FILE__) . init('id'));
        }
        
        $MPD = $eqLogic->test_connexion();
        ajax::success($MPD);

    }

    if (init('action') == 'generer_commandes') {

        $eqLogic = MPD::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new \Exception(__('MPD eqLogic non trouvé : ', __FILE__) . init('id'));
        }
        
        $MPD = $eqLogic->generer_commandes();
        ajax::success($MPD);

    }

  if (init('action') == 'bt_set_layout') {

        $eqLogic = MPD::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new \Exception(__('MPD eqLogic non trouvé : ', __FILE__) . init('id'));
        }
        
        $MPD = $eqLogic->bt_set_layout();
        ajax::success($MPD);

    }


    throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}