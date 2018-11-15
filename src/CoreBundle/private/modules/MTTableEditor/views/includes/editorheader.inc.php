<?php

use ChameleonSystem\CoreBundle\ServiceLocator;

if ($data['aPermission']['showlist'] && '1' != $data['only_one_record_tbl']) {
    ?>
    <script type="text/javascript">
        function switchRecord(id) {
            if (id != '') {
                url = '<?=PATH_CMS_CONTROLLER; ?>?pagedef=tableeditor&id=' + id + '&tableid=<?=urlencode($data['tableid']); ?>&sRestriction=<?php if (!empty($data['sRestriction'])) {
        echo urlencode($data['sRestriction']);
    } ?>&sRestrictionField=<?php if (!empty($data['sRestrictionField'])) {
        echo urlencode($data['sRestrictionField']);
    } ?>&popLastURL=1';
                document.location.href = url;
            }
        }
    </script>
    <?php
}
$oController = TGlobal::GetController();
?>
<nav class="navbar navbar-light bg-light pl-2 pr-2 pt-1">
    <span class="navbar-brand"><?php
        $length = 50;
        $sRecordName = strip_tags($sRecordName);
        if (mb_strlen($sRecordName) > $length) {
            $sRecordName = mb_substr($sRecordName, 0, $length);
            $lastSpacePos = mb_strrpos($sRecordName, ' ');
            $sRecordName = mb_substr($sRecordName, 0, $lastSpacePos);
            $sRecordName .= '...';
        }

        echo $sRecordName; ?></span>
    <form class="form-inline">
    <?php
    $idsPopoverText = '<div class="callout mt-0 mb-1"><strong class="text-muted">Auto-Increment ID:</strong><br><strong class="h6">'.$data['cmsident'].'</strong></div>
<div class="callout mt-0 mb-1"><strong class="text-muted">ID:</strong><br><strong class="h6">'.$data['id'].'</strong></div>
    ';
    ?>
        <button class="btn btn-outline-success mr-2" type="button" role="button" data-toggle="popover" data-placement="bottom"
          data-content="<?= TGlobal::OutHTML($idsPopoverText); ?>" data-original-title="IDs">
       IDs
    </button>
        <?php if ($data['aPermission']['showlist'] && '1' != $data['only_one_record_tbl']) {
        ?>
            <div id="quicklookuplistBG" class="right-inner-addon"><i class="glyphicon glyphicon-search"></i>
                <input id="quicklookuplist" class="form-control ac_input" type="search"
                       aria-label="Search" name="quicklookuplist" value="" autocomplete="off"
                       placeholder="<?= TGlobal::OutHTML(TGlobal::Translate('chameleon_system_core.list.search_term')); ?>"/>
            </div>
        <?php
        $sRestrictionField = '';
        $sRestriction = '';
        if (!empty($data['sRestrictionField'])) {
            $sRestrictionField = urlencode($data['sRestrictionField']);
        }
        if (!empty($data['sRestriction'])) {
            $sRestriction = urlencode($data['sRestriction']);
        }

        /**
         * @var \ChameleonSystem\CoreBundle\Util\UrlUtil $urlUtil
         */
        $urlUtil = ServiceLocator::get('chameleon_system_core.util.url');
        $sAjaxURL = $urlUtil->getArrayAsUrl([
            'id' => TGlobal::OutJS($data['tableid']),
            'pagedef' => 'tablemanager',
            '_rmhist' => 'false',
            'sOutputMode' => 'Ajax',
            'module_fnc[contentmodule]' => 'ExecuteAjaxCall',
            '_fnc' => 'GetAutoCompleteAjaxList',
            'sRestrictionField' => $sRestrictionField,
            'sRestriction' => $sRestriction,
            'recordID' => $data['id'],
        ], PATH_CMS_CONTROLLER); ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#quicklookuplist").autocomplete(
                        {
                            source: "<?= TGlobal::OutJS($sAjaxURL); ?>",
                            minLength: 1,
                            select: function (event, ui) {
                                switchRecord(ui.item.value);
                            },
                            open: function (event, ui) {
                                $('.ui-autocomplete:last').css('width', 'auto').css('min-width', '155px').css('z-index', '100');
                            }
                        }
                    );
                });
            </script>
            <?php
    }
        ?>
    </form>
</nav>

<?php
if ($oCmsLock) {
            ?>
    <table class="table table-borderless table-sm" id="tableEditorHeader">
        <tr>
            <th><?= TGlobal::OutHTML(TGlobal::Translate('chameleon_system_core.cms_module_table_editor.header_lock')); ?></th>
        </tr>
        <tr><?php
            $oLockUser = $oCmsLock->GetFieldCmsUser(); /** @var $oLockUser TdbCmsUser */ ?>
            <td class="<?= 'user'.$oLockUser->id; ?>" style="cursor:pointer;">
                <?php
                $sData = $oLockUser->GetUserIcon(false).'<div class="name"><strong>'.TGlobal::Translate('chameleon_system_core.record_lock.lock_owner_name').': </strong>'.TGlobal::OutJS($oLockUser->GetName()).'</div>';
            if (!empty($oLockUser->fieldEmail)) {
                $sData .= '<div class="email"><strong>'.TGlobal::Translate('chameleon_system_core.record_lock.lock_owner_mail').': </strong>'.TGlobal::OutJS($oLockUser->fieldEmail).'</div>';
            }
            if (!empty($oLockUser->fieldTel)) {
                $sData .= '<div class="tel"><strong>'.TGlobal::Translate('chameleon_system_core.record_lock.lock_owner_phone').': </strong>'.TGlobal::OutJS($oLockUser->fieldTel).'</div>';
            }

            $oController->AddHTMLHeaderLine('
                <script type="text/javascript">
                  $(document).ready(function() {
                    $(".user'.$oLockUser->id.'").wTooltip({
                      content: \''.$sData.'\',
                      offsetY: 15,
                      offsetX: -8,
                      className: "lockUserinfo chameleonTooltip",
                      style: false
                    });
                  });
                </script>
              ');

            echo '<div>'.$oCmsLock->GetDateField('time_stamp').'</div>';
            echo '<div>'.$oLockUser->GetName().'</div>'; ?>
            </td>
        </tr>
    </table>
    <?php
        }
?>
