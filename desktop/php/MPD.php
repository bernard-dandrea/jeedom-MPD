<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
// Déclaration des variables obligatoires
$plugin = plugin::byId('MPD');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
  <!-- Page d'accueil du plugin -->
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <!-- Boutons de gestion du plugin -->
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Mes MPDs}}</legend>
    <?php
    if (count($eqLogics) == 0) {
      echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement MPD trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
    } else {
      // Champ de recherche
      echo '<div class="input-group" style="margin:5px;">';
      echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
      echo '<div class="input-group-btn">';
      echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
      echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
      echo '</div>';
      echo '</div>';
      // Liste des équipements du plugin
      echo '<div class="eqLogicThumbnailContainer">';
      foreach ($eqLogics as $eqLogic) {

        $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
        echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';

        $file = 'plugins/MPD/plugin_info/' . $eqLogic->getConfiguration('icon') . '.png';
        if (file_exists(__DIR__ . '/../../../../' . $file)) {
          echo '<img src="' . $file . '" height="105" width="95">';
        } else {
          echo '<img src="' . $plugin->getPathImgIcon() . '">';
        }
        echo '<br>';
        echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
        echo '<span class="hiddenAsCard displayTableRight hidden">';
        echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
        echo '</span>';
        echo '</div>';
      }
      echo '</div>';
    }
    ?>
  </div> <!-- /.eqLogicThumbnailDisplay -->


  <!-- Page de présentation de l'équipement -->
  <div class="col-xs-12 eqLogic" style="display: none;">
    <!-- barre de gestion de l'équipement -->
    <div class="input-group pull-right" style="display:inline-flex;">
      <span class="input-group-btn">
        <!-- Les balises <a></a> sont volontairement fermées à la ligne suivante pour éviter les espaces entre les boutons. Ne pas modifier -->
        <a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i
            class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
        </a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i>
          {{Sauvegarder}}
        </a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i
            class="fas fa-minus-circle"></i> {{Supprimer}}
        </a>
      </span>
    </div>
    <!-- Onglets -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab"
          data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i
            class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i
            class="fas fa-list"></i> {{Commandes}}</a></li>
    </ul>
    <div class="tab-content">
      <!-- Onglet de configuration de l'équipement -->
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <!-- Partie gauche de l'onglet "Equipements" -->
        <!-- Paramètres généraux et spécifiques de l'équipement -->
        <form class="form-horizontal">
          <fieldset>

            <div class="col-lg-8">
              <legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
              <div class="form-group">
                <label class="col-sm-4 control-label">{{Nom du MPD}}</label>
                <div class="col-sm-6">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'MPD}}">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">{{Objet parent}}</label>
                <div class="col-sm-6">
                  <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                    $options = '';
                    foreach ((jeeObject::buildTree(null, false)) as $object) {
                      $options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                    }
                    echo $options;
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">{{Catégorie}}</label>
                <div class="col-sm-6">
                  <?php
                  foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    echo '<label class="checkbox-inline">';
                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
                    echo '</label>';
                  }
                  ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label">{{Options}}</label>
                <div class="col-sm-6">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable"
                      checked>{{Activer}}</label>
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible"
                      checked>{{Visible}}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-4">
                  <a class="btn btn-default " id="bt_TestConnexionMPD" '><i class="fa fa-cogs"> {{Tester la connexion au MPD}}</i></a>
                  <a class="btn btn-default " id="bt_Generer_Commandes" '><i class="fa fa-cogs"> {{Générer les
                      commandes}}</i></a>
                </div>
              </div>

              <div class="form-group ">
                <label class="col-sm-4 control-label">{{Adresse IP}}</label>
                <div class="col-sm-6">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"">
                </div>
              </div>
              <div class=" form-group ">
                <label class=" col-sm-4 control-label">{{Port}}</label>
                  <div class="col-sm-6">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"">
                </div>
              </div>
                <div class=" form-group ">
                  <label class=" col-sm-4 control-label">{{Mot de passe}}</label>
                    <div class="col-sm-6">
                      <input type="password" class="eqLogicAttr form-control" data-l1key="configuration"
                        data-l2key="password" />
                    </div>
                  </div>
          </fieldset>
        </form>
      </div>

      <!-- /.tabpanel #eqlogictab-->
      <!-- Onglet des commandes de l equipement-->
      <div role="tabpanel" class="tab-pane" id="commandtab">
        <div class="input-group pull-right" style="display:inline-flex;margin-top:5px;">
          <span class="input-group-btn">
            <a class="btn btn-info btn-xs roundedLeft " id="bt_create_command" title=' {{Créer une commande}}'><i
                class="fas fa-plus-circle"> {{Créer une commande}}</i></a>
          </span>
        </div>
        <br><br>
        <div class="table-responsive">
          <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
                <th style="min-width:200px;width:350px;">{{Nom}}</th>
                <th>{{Commande}}</th>
                <th>{{Type}}</th>
                <th style="min-width:260px;">{{Options}}</th>
                <th>{{Valeur}}
                </th>
                <th style="min-width:80px;width:200px;">{{Actions}}</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div><!-- /.tabpanel #commandtab-->

    </div><!-- /.tab-content -->
  </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php
include_file('desktop', 'MPD', 'js', 'MPD');
include_file('core', 'plugin.template', 'js');
?>