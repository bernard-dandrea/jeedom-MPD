/* This file is part of Jeedom.
*

// Last Modified : 2026/08/16 11:32:04

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


function addCmdToTable(_cmd) {

    if (document.getElementById('table_cmd') == null) return
    if (document.querySelector('#table_cmd thead') == null) {
        table = '<thead>'
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
        var _cmd = { configuration: {} }
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

    tr += '</td>'
    tr += '<td>'
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
    tr += '</td>'
    tr += '<td style="min-width:400px"><input class="cmdAttr form-control input-sm" data-l1key="logicalId" value="0" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>'
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

    let newRow = document.createElement('tr')
    newRow.innerHTML = tr
    newRow.addClass('cmd')
    newRow.setAttribute('data-cmd_id', init(_cmd.id))
    document.getElementById('table_cmd').querySelector('tbody').appendChild(newRow)

    jeedom.eqLogic.buildSelectCmd({
        id: document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue(),
        filter: { type: 'info' },
        error: function (error) {
            jeedomUtils.showAlert({ message: error.message, level: 'danger' })
        },
        success: function (result) {
            newRow.querySelector('.cmdAttr[data-l1key="value"]')?.insertAdjacentHTML('beforeend', result)
            newRow.setJeeValues(_cmd, '.cmdAttr')
            jeedom.cmd.changeType(newRow, init(_cmd.subType))
        }
    })
}

function printEqLogic(_eqLogic) {

    $MPDtype = _eqLogic.configuration.type;
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
            var message = data.result;

            var level = 'success';
            if (message.substr(0, 2) === 'KO') {
                level = 'warning';
            }
            if (message.length >= 4) {
                message = message.substr(3);
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
            var message = data.result;

            var level = 'success';
            if (message.substr(0, 2) === 'KO') {
                level = 'info';
            }
            if (message.length >= 4) {
                message = message.substr(3);
            }
            jeedomUtils.showAlert({
                message: message,
                level: level
            })
            if (level == 'success') {
                setTimeout(function () {
                    location.reload()
                }, 3000)
            }
        }
    }
    domUtils.ajax(paramsAJAX);

});

document.querySelector('#bt_create_command').addEventListener('click', function (event) {

    addCmdToTable({ type: 'action' });

    // Modification de la variable globale de suivi de modification
    modifyWithoutSave = true;
});



