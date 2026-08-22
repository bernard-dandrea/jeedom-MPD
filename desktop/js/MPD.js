// Last Modified : 2026/08/22 18:42:36

/*
 * Copyright (C) 2026 Bernard Dandrea
 * SPDX-License-Identifier: GPL-3.0-or-later
 * https://www.gnu.org/licenses/gpl-3.0.html
 */

function addCmdToTable(_cmd) {

    if (document.getElementById('table_cmd') === null) return
    if (document.querySelector('#table_cmd thead') === null) {
        let table = '<thead>'
        table += '<tr>'
        table += '<th>Id</th>'
        table += '<th>{{Nom}}</th>'
        table += '<th>{{Type}}</th>'
        table += '<th>{{Commande}}</th>'
        table += '<th>{{Paramètres}}</th>'
        table += '<th>{{Valeur}}</th>'
        table += '<th>{{Action}}</th>'
        table += '</tr>'
        table += '</thead>'
        table += '<tbody>'
        table += '</tbody>'
        document.getElementById('table_cmd').insertAdjacentHTML('beforeend', table)
    }
    if (!isset(_cmd)) {
        _cmd = { configuration: {} }
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {}
    }
    var tr = '<tr>'
    tr += '<td style="min-width:50px;width:70px;">'
    tr += '<span class="cmdAttr" data-l1key="id"></span>'
    tr += '</td>'
    tr += '<td>'

    tr += '<div class="input-group">'
    tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
    tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
    tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
    tr += '</div>'
    if (init(_cmd.type) === 'action' && init(_cmd.logicalId) === 'song') {
        tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
        tr += '<option value="">{{Aucune}}</option>'
        tr += '</select>'
    }
    tr += '</td>'
    tr += '<td>'
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
    tr += '</td>'
    tr += '<td style="min-width:400px"><input class="cmdAttr form-control input-sm" data-l1key="logicalId" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>'
    tr += '</td>'

    tr += '<td>'

    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> '
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> '
    tr += '</td>'
    tr += '<td>'
    tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'
    tr += '</td>'
    tr += '<td>'
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>'
    }
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>'
    tr += '</td>'
    tr += '</tr>'

    const temp = document.createElement('tbody')
    temp.innerHTML = tr
    const newRow = temp.firstElementChild
    document.querySelector('#table_cmd tbody').appendChild(newRow)

    const valueField = newRow.querySelector('.cmdAttr[data-l1key="value"]')
    if (valueField) {
        jeedom.eqLogic.buildSelectCmd({
            id: document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue(),
            filter: { type: 'info' },
            error: function (error) {
                jeedomUtils.showAlert({ message: error.message, level: 'danger' })
            },
            success: function (result) {
                // comme la fonction est executée en asynchrone, il est nécessaire de faire les mises à jour des commandes dans le success
                valueField.insertAdjacentHTML('beforeend', result)
                newRow.setJeeValues(_cmd, '.cmdAttr')
                jeedom.cmd.changeType(newRow, init(_cmd.subType))
            }
        })
    } else {
        // evite de lire les commandes info à chaque fois
        newRow.setJeeValues(_cmd, '.cmdAttr')
        jeedom.cmd.changeType(newRow, init(_cmd.subType))
    }
}

document.querySelector('#bt_TestConnexionMPD').addEventListener('click', function () {

    var eqLogicId = document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue();

    var paramsAJAX = {
        type: "POST",
        url: 'plugins/MPD/core/ajax/MPD.ajax.php',
        data: {
            action: 'test_connexion',
            id: eqLogicId
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error)
        },
        success: function (data) {
            var message = String(data.result || '');

            var level = 'success';
            if (message.startsWith('KO')) {
                level = 'warning';
            }
            if (message.length >= 4) {
                message = message.substring(3);
            }
            jeedomUtils.showAlert({
                message: message,
                level: level
            })
        }
    }
    domUtils.ajax(paramsAJAX);
});


document.querySelector('#bt_Generer_Commandes').addEventListener('click', function () {

    var eqLogicId = document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue();

    var paramsAJAX = {
        type: "POST",
        url: 'plugins/MPD/core/ajax/MPD.ajax.php',
        data: {
            action: 'generer_commandes',
            id: eqLogicId
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error)
        },
        success: function (data) {
            var message = String(data.result || '');

            var level = 'success';
            if (message.startsWith('KO')) {
                level = 'info';
            }
            if (message.length >= 4) {
                message = message.substring(3);
            }
            jeedomUtils.showAlert({
                message: message,
                level: level
            })
            if (level === 'success') {
                setTimeout(function () {
                    location.reload()
                }, 3000)
            }
        }
    }
    domUtils.ajax(paramsAJAX);

});

document.querySelector('#bt_set_layout').addEventListener('click', function () {

    var eqLogicId = document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue();

    var paramsAJAX = {
        type: "POST",
        url: 'plugins/MPD/core/ajax/MPD.ajax.php',
        data: {
            action: 'bt_set_layout',
            id: eqLogicId
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error)
        },
        success: function (data) {
            var message = String(data.result || '');

            var level = 'success';
            if (message.startsWith('KO')) {
                level = 'info';
            }
            if (message.length >= 4) {
                message = message.substring(3);
            }
            jeedomUtils.showAlert({
                message: message,
                level: level
            })
        }
    }
    domUtils.ajax(paramsAJAX);

});


document.querySelector('#bt_create_command').addEventListener('click', function (event) {

    addCmdToTable({ type: 'action' });

    // Modification de la variable globale de suivi de modification
    modifyWithoutSave = true;
});



