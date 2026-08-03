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

// code repris de core/core/js/plugin.template.js/addCmdToTableDefault version 4.5.2
// Modifications avec commentaire // BD

function addCmdToTable(_cmd) {

    if (document.getElementById('table_cmd') == null) return
    if (document.querySelector('#table_cmd thead') == null) {
        table = '<thead>'
        table += '<tr>'
        table += '<th>Id</th>'  // BD
        table += '<th>{{Nom}}</th>'
        table += '<th>{{Type}}</th>'
        table += '<th>{{Commande}}</th>'
        // BD     table += '<th>{{Options}}</th>'
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
    /*   BD code remplacé par les définitions suivantes
    tr += '<div class="row">'
    tr += '<div class="col-sm-6">'
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>'
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>'
    tr += '</div>'
    tr += '<div class="col-sm-6">'
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">'
    tr += '</div>'
    tr += '</div>'
    tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="{{La valeur de la commande vaut par défaut la commande}}">'
    tr += '<option value="">Aucune</option>'
    tr += '</select>'
    */
    tr += '<div class="input-group">'
    tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
    tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
    tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
    tr += '</div>'
    /*
    tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
    tr += '<option value="">{{Aucune}}</option>'
    tr += '</select>'
    */
    // BD fin des modifs
    tr += '</td>'
    tr += '<td>'
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
    tr += '</td>'
    tr += '<td style="min-width:400px"><input class="cmdAttr form-control input-sm" data-l1key="logicalId" value="0" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>'
    tr += '</td>'
    // Bd tr += '<td>'
    // Bd tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateValue" placeholder="{{Valeur retour d\'état}}" style="width:48%;display:inline-block;">'
    // Bd tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="returnStateTime" placeholder="{{Durée avant retour d\'état (min)}}" style="width:48%;display:inline-block;margin-left:2px;">'
    // Bd tr += '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="updateCmdId" style="display : none;" title="{{Commande d\'information à mettre à jour}}">'
    // BD tr += '<option value="">Aucune</option>'
    // Bd tr += '</select>'
    // Bd tr += '</td>'
    tr += '<td>'
    // BD tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">'
    // BD tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">'
    // BD tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="{{Unité}}" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">'
    // BD tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="listValue" placeholder="{{Liste de valeur|texte séparé par ;}}" title="{{Liste}}">'
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> '
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> '
    // Bd  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> '
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

    var eqLogicId = document.querySelector('.eqLogicAttr[data-l1key="id"]').value;

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

    const eqLogicId = document.querySelector('.eqLogicAttr[data-l1key="id"]').value;

    var paramsAJAX = {
        url: 'plugins/MPD/core/ajax/MPD.ajax.php',
        data: {
            action: 'generer_commandes',
            id: eqLogicId
        },
        type: "POST",
        dataType: 'json',
        success: function (data) {
            //  location.reload();

        },
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        }
    };
    domUtils.ajax(paramsAJAX);

});

document.querySelector('#bt_create_command').addEventListener('click', function (event) {

    addCmdToTable({ type: 'action' });

    // Modification de la variable globale de suivi de modification
    modifyWithoutSave = true;
});



